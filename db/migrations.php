<?php
/**
 * @var \Ads\Application $application
 * @var \Ads\Core\Domain\DBAL\DB $db
 */
$application = require dirname(__DIR__) . '/config/bootstrap.php';
$db = $application->getInstance(\Ads\Core\Domain\DBAL\DB::class);
$pdo = $db->getPDO();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'name' => 'production_db',
            'connection' => $pdo
        ]
    ],
    'version_order' => 'creation'
];
