<?php
declare(strict_types=1);

namespace Ads\Core\ServiceProvider;


use Ads\Core\Domain\DBAL\ConnectionSettings;
use Ads\Core\Domain\DBAL\DB;
use Pimple\Container;


class DBSeviceProvider implements IServiceProvider
{

    public function boot(Container $container)
    {
        $container[DB::class] = function () {
            $connectionSettings = require CONFIG . '/db.php';
            return new DB($connectionSettings);
        };
    }
}