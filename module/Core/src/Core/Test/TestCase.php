<?php
namespace Core\Test;

use Zend\Db\Adapter\Adapter;
use Core\Db\TableGateway;
use Zend\Mvc\Application;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Mvc\MvcEvent;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Zend\Mvc\Application
     */
    protected $application;
    
    /**
     * @var Zend\Di\Di
     */
    protected $di;

    public function setup()
    {
        parent::setup();

        $config = include 'config/application.config.php';
        $config['module_listener_options']['config_static_paths'] = array(getcwd() . '/config/test.config.php');

        if (file_exists(__DIR__ . '/config/test.config.php')) {
            $moduleConfig = include __DIR__ . '/config/test.config.php';
            array_unshift($config['module_listener_options']['config_static_paths'], $moduleConfig);
        }
        
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig(
            isset($config['service_manager']) ? $config['service_manager'] : array()
        ));
        $this->serviceManager->setService('ApplicationConfig', $config);
        $this->serviceManager->setFactory('ServiceListener', 'Zend\Mvc\Service\ServiceListenerFactory');
        
        $moduleManager = $this->serviceManager->get('ModuleManager');
        $moduleManager->loadModules();
        $this->routes = array();
        foreach ($moduleManager->getModules() as $m) {
            $moduleConfig = include __DIR__ . '/../../../../' . ucfirst($m) . '/config/module.config.php';
            if (isset($moduleConfig['router'])) {
                foreach($moduleConfig['router']['routes'] as $key => $name) {
                    $this->routes[$key] = $name;
                }
            }
        }
        $this->serviceManager->setAllowOverride(true);

        $this->application = $this->serviceManager->get('Application');
        $this->event  = new MvcEvent();
        $this->event->setTarget($this->application);
        $this->event->setApplication($this->application)
            ->setRequest($this->application->getRequest())
            ->setResponse($this->application->getResponse())
            ->setRouter($this->serviceManager->get('Router'));

        $this->createDatabase();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->dropDatabase();
    }

    /**
     * Retrieve TableGateway
     * 
     * @param  string $table
     * @return TableGateway
     */
    protected function getTable($table)
    {
        $sm = $this->serviceManager;
        $dbAdapter = $sm->get('DbAdapter');
        $tableGateway = new TableGateway($dbAdapter, $table, new $table);
        $tableGateway->initialize();
        
        return $tableGateway;
    }

    /**
     * Retrieve Service
     *
     * @param  string $service
     * @return Service
     */
    protected function getService($service)
    {
        return $this->serviceManager->get($service);
    }

    /**
     * @return void
     */
    public function createDatabase()
    {
        $dbAdapter = $this->getAdapter();

        if ( get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Sqlite' ) {
            //enable foreign keys on sqlite
            $dbAdapter->query('PRAGMA foreign_keys = ON;', Adapter::QUERY_MODE_EXECUTE);
        }

        if ( get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Mysql' ) {
            //enable foreign keys on mysql
            $dbAdapter->query('SET FOREIGN_KEY_CHECKS = 1;', Adapter::QUERY_MODE_EXECUTE);
        }

        $queries = include \Bootstrap::getModulePath() . '/data/test.data.php';
        foreach ($queries as $query) {
            $dbAdapter->query($query['create'], Adapter::QUERY_MODE_EXECUTE);
        }
    }

    /**
     * @return void
     */
    public function dropDatabase()
    {
        $dbAdapter = $this->getAdapter();

        if ( get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Sqlite' ) {
            //disable foreign keys on sqlite
            $dbAdapter->query('PRAGMA foreign_keys = OFF;', Adapter::QUERY_MODE_EXECUTE);
        }
        if ( get_class($dbAdapter->getPlatform()) == 'Zend\Db\Adapter\Platform\Mysql' ) {
            //disable foreign keys on mysql
            $dbAdapter->query('SET FOREIGN_KEY_CHECKS = 0;', Adapter::QUERY_MODE_EXECUTE);
        }

        $queries = include \Bootstrap::getModulePath() . '/data/test.data.php';
        foreach ($queries as $query) {
            $dbAdapter->query($query['drop'], Adapter::QUERY_MODE_EXECUTE);
        }
    }

    /**
     * 
     * @return Zend\Db\Adapter\Adapter
     */
    public function getAdapter() 
    {
        return $this->serviceManager->get('DbAdapter');
    }
}