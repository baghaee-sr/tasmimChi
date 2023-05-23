<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'controllerMap' => [
        'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
            'migrationPath' => '@app/migrations', // Directory storing the migration classes
            'migrationNamespace' => null, // Full migration namespace
            'useTablePrefix' => true, // Whether the table names generated should consider the $tablePrefix setting of the DB connection
            'onlyShow' => false, // Whether to only display changes instead of generating update migration
            'fixHistory' => false, // Whether to add generated migration to migration history
            'skipMigrations' => [], // List of migrations from the history table that should be skipped during the update process
            'excludeTables' => [], // List of database tables that should be skipped for actions with "*"
            'fileMode' => null, // Permission to be set for newly generated migration files
            'fileOwnership' => null, // User and/or group ownership to be set for newly generated migration files
            'leeway' => 0, // Leeway in seconds to apply to a starting timestamp when generating migration
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
