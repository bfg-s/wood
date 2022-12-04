<?php

namespace Bfg\Wood\Commands;

use Exception;
use Illuminate\Support\Facades\Artisan;

class WoodServerCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:server
    {--host=0.0.0.0 : The host of server}
    {--port=2020 : The port of server}
    ";

    /**
     * @var string
     */
    protected $description = "Run wood server";

    /**
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $host = 'tcp://' . $this->option('host')
            . ':'
            . $this->option('port');
        $tcp_worker = new HookWorker($host);
        $tcp_worker->count = 4;
        $tcp_worker->onConnect = function ($connection) {
            $this->info('Wood client connected!');
        };
        $tcp_worker->onMessage = function ($connection, $data) {
            if (array_key_exists($data, Artisan::all())) {
                $connection->send("loading");
                $this->call($data);
                $connection->send("loaded");
            }
        };
        $tcp_worker->onClose = function ($connection) {
            $this->info('Wood client disconnected!');
        };

        HookWorker::runAll();

        return 0;
    }
}
