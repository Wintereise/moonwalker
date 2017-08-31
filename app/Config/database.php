<?php return array (
    'cli' =>
        array (
            'bootstrap' => 'vendor/autoload.php',
        ),
    'schema' =>
        array (
            'auto_id' => 1,
            'paths' =>
                array (
                    0 => 'app/Models',
                ),
        ),
    'databases' =>
        array (
            'master' =>
                array (
                    'driver' => 'mysql',
                    'database' => 'moonwalker',
                    'host' => 'localhost',
                    'user' => 'root',
                    'pass' => NULL,
                    'password' => NULL,
                    'query_options' =>
                        array (
                        ),
                    'connection_options' =>
                        array (
                            1002 => 'SET NAMES utf8',
                        ),
                    'dsn' => 'mysql:host=localhost;dbname=moonwalker',
                ),
        ),
);