<?php

namespace Bfg\Wood\Commands\Watchers;

use ElementaryFramework\FireFS\Events\FileSystemEvent;
use ElementaryFramework\FireFS\Listener\IFileSystemListener;

abstract class WatcherAbstract implements IFileSystemListener
{
    public array $just_deleted = [];

    /**
     * Action called on any event.
     *
     * @param  FileSystemEvent  $event  The raised event.
     *
     * @return boolean true to propagate the event, false otherwise.
     */
    function onAny(FileSystemEvent $event): bool
    {
        $eventType = $event->getEventType();

        if (
            $eventType === FileSystemEvent::EVENT_DELETE
        ) {
            $this->just_deleted[time()] = $event;
        } else if (
            $eventType === FileSystemEvent::EVENT_CREATE
        ) {
            $time = time();
            /** @var FileSystemEvent|null $renamed */
            $renamed = $this->just_deleted[$time] ?? null;
            if (
                $renamed
            ) {
                $this->fireEvent('renamed', $renamed, $event);
                unset($this->just_deleted[$time]);
                return false;
            }
        }

        return true;
    }

    /**
     * Action called when a "create" event occurs on
     * the file system.
     *
     * @param  FileSystemEvent  $event  The raised event.
     *
     * @return void
     */
    function onCreated(FileSystemEvent $event): void
    {
        $this->fireEvent('created', $event);
    }

    /**
     * Action called when a "modify" event occurs on
     * the file system.
     *
     * @param  FileSystemEvent  $event  The raised event.
     *
     * @return void
     */
    function onModified(FileSystemEvent $event): void
    {
        $this->fireEvent('modified', $event);
    }

    /**
     * Action called when a "delete" event occurs on
     * the file system.
     *
     * @param  FileSystemEvent  $event  The raised event.
     *
     * @return void
     */
    function onDeleted(FileSystemEvent $event): void
    {
        $this->fireEvent('deleted', $event);
    }

    protected function fireEvent(
        string $name,
        ...$parameters
    ) {
        if (method_exists($this, 'any')) {
            $this->any(...$parameters);
        }
        if (method_exists($this, $name)) {
            $this->{$name}(...$parameters);
        }
    }
}
