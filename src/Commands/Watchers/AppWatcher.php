<?php

namespace Bfg\Wood\Commands\Watchers;

use Bfg\Wood\Commands\WorkCommand;
use ElementaryFramework\FireFS\Events\FileSystemEvent;

class AppWatcher extends WatcherAbstract
{
    public function __construct(
        public WorkCommand $command
    ) {
    }

    protected function renamed(
        FileSystemEvent $old,
        FileSystemEvent $new,
    ) {
        if (
            $command = $this->command->exists('renamed', $new->getPath())
        ) {
            foreach ($command as $item) {
                $this->command->exec([
                    'php', 'artisan', 'work',
                    ...(array) $item,
                    '--files', $old->getPath(),
                    '--files', $new->getPath(),
                    '--event', 'renamed',
                ]);
            }
        }
    }

    protected function created(
        FileSystemEvent $file,
    ) {
        if (
            $command = $this->command->exists('created', $file->getPath())
        ) {
            foreach ($command as $item) {
                $this->command->exec([
                    'php', 'artisan', 'work',
                    ...(array) $item,
                    '--files', $file->getPath(),
                    '--event', 'created',
                ]);
            }
        }
    }

    protected function modified(
        FileSystemEvent $file,
    ) {
        if (
            $command = $this->command->exists('modified', $file->getPath())
        ) {
            foreach ($command as $item) {
                $this->command->exec([
                    'php', 'artisan', 'work',
                    ...(array) $item,
                    '--files', $file->getPath(),
                    '--event', 'modified',
                ]);
            }
        }
    }

    protected function deleted(
        FileSystemEvent $file,
    ) {
        if (
            $command = $this->command->exists('deleted', $file->getPath())
        ) {
            foreach ($command as $item) {
                $this->command->exec([
                    'php', 'artisan', 'work',
                    ...(array) $item,
                    '--files', $file->getPath(),
                    '--event', 'deleted',
                ]);
            }
        }
    }
}
