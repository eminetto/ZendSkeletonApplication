<?php

// module/Skel/conﬁg/module.config.php:
return array(
    'controllers' => array( //add module controllers
        'invokables' => array(
            'index' => 'Skel\Controller\IndexController'
        ),
    ),

    'router' => array(
        'routes' => array(

            'skel' => array( //change to the module's name
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/skel[/:controller[/:action[/:id]]]', //change to the module's name
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                        'module' => 'skel', //change to the module's name
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array( //the module can have a specific layout
        // 'template_map' => array(
        //     'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        // ),
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