<?php

namespace Bfg\Wood\Commands;

use Exception;
use Workerman\Worker;

class HookWorker extends Worker
{
    /**
     * Run all worker instances.
     *
     * @return void
     * @throws Exception
     */
    public static function runAll()
    {
        static::checkSapiEnv();
        static::init();
        static::daemonize();
        static::initWorkers();
        static::installSignal();
        static::saveMasterPid();
        static::displayUI();
        static::forkWorkers();
        static::resetStd();
        static::monitorWorkers();
    }
}
