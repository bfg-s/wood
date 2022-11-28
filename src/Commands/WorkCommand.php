<?php

namespace Bfg\Wood\Commands;

use Bfg\Wood\Commands\Watchers\AppWatcher;
use Bfg\Wood\Commands\Watchers\FileSystemWatcher;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use ElementaryFramework\FireFS\FireFS;
use Illuminate\Console\Command;

class WorkCommand extends Command
{
    protected $signature = "work {cmd?*} {--event=} {--files=*}";

    protected bool $_started = true;

    protected static array $map = [
        'modified:/database/wood.json' => [
            'wood:compile'
        ],
        'renamed:*' => [
            []
        ]
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cmd = $this->argument('cmd');
        if ($cmd) {
            $this->hasCmd($cmd);
            return 0;
        }
        $fs = new FireFS();
        $appWatchers = new AppWatcher($this);
        $appWatcherProcess = (new FileSystemWatcher($fs))
            ->setListener($appWatchers)
            ->setPath("./app")
            ->build();
        $databaseWatcherProcess = (new FileSystemWatcher($fs))
            ->setListener($appWatchers)
            ->setPath("./database")
            ->build();

        while (
            $this->_started
        ) {
            $appWatcherProcess->process();
            $databaseWatcherProcess->process();
            usleep(500);
        }

        return 0;
    }

    protected function hasCmd(array $cmd)
    {
        $event = $this->option('event');
        $files = $this->option('files');
        if (array_key_exists($cmd[0], Artisan::all())) {
            Artisan::call(implode(' ', $cmd), [], $this->output);
        } else if (
            is_callable($cmd)
        ) {
            call_user_func($cmd, $event, $files, $this);
        } else if (
            is_callable($cmd[0])
        ) {
            call_user_func($cmd[0], $event, $files, $this);
        } else {
            $this->exec($cmd);
        }
    }

    public function exists(
        string $event, string $file
    ): ?array {
        $file = str_replace(base_path(), '', $file);
        foreach (static::$map as $pattern => $command) {
            if (Str::is($pattern, "$event:$file")) {
                return array_map(
                    fn (string $cmd) => str_replace([
                        '{file}', '{basePath}', '{fullPath}', '{event}'
                    ], [
                        $file, base_path(), base_path($file), $event
                    ], $cmd),
                    (array) $command
                );
            }
        }
        return null;
    }

    public function stop()
    {
        $this->_started = false;
    }

    public function exec(array $command)
    {
        $first = $command[0] ?? null;
        if ($first) {
            if ($first === 'php') {
                $command[0] = (new PhpExecutableFinder)->find();
            }

            $stream = fopen('php://temporary', 'w+');

            $process = new Process($command);
            $process->setInput($stream);

            $process->run(function ($type, $buffer) {
                $buffer = trim($buffer, "\n");
                if (Process::ERR === $type) {
                    $this->error($buffer);
                } else {
                    $this->line($buffer);
                }
            });
        }
    }
}
