<?php

// module/Skel/conﬁg/module.config.php:
return array(
    'controllers' => array( //add module controllers
        'invokables' => array(
            'Skel\Controller\Index' => 'Skel\Controller\IndexController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'skel' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/skel',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Skel\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'module'        => 'skel'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array( //permite mandar dados pela url 
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
    //the module can have a specific layout
    'module_layout' => array(
        'Skel' => 'layout/layout_skel.phtml'
    ),
    'view_manager' => array( 
        'template_path_stack' => array(
            'skel' => __DIR__ . '/../view',
        ),
    ),
    'db' => array( //module can have a specific db configuration
        'driver' => 'PDO_SQLite',
        'dsn' => 'sqlite:' . __DIR__ .'/../data/skel.db',
        'driver_options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    )
);