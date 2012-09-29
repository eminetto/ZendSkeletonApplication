<?php
namespace Core\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Core\Db\TableGateway;

abstract class Service implements ServiceManagerAwareInterface
{
	/**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        //return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Retrieve TableGateway
     * 
     * @param  string $table
     * @return TableGateway
     */
	protected function getTable($table)
    {
        $sm = $this->getServiceManager();
        $dbAdapter = $sm->get('DbAdapter');
        $tableGateway = new TableGateway($dbAdapter);
        $object = new $table;
        $tableGateway->initialize($object->getTableName(), $object );
        return $tableGateway;
    }

    /**
     * Retrieve Service
     * 
     * @return Service
     */
    protected function getService($service)
    {
        return $this->getServiceManager()->get($service);
    }
}