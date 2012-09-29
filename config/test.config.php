<?php

return array(
    'db' => array(
        'driver' => 'PDO_SQLite',
        'dsn' => 'sqlite::memory:',
        'driver_options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    )

);