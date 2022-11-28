<?php

namespace Bfg\Wood\Commands\Watchers;

use ElementaryFramework\FireFS\FireFS;
use ElementaryFramework\FireFS\Events\FileSystemEntityModifiedEvent;
use ElementaryFramework\FireFS\Events\FileSystemEntityDeletedEvent;
use ElementaryFramework\FireFS\Events\FileSystemEntityCreatedEvent;
use ElementaryFramework\FireFS\Exceptions\FileSystemWatcherException;
use ElementaryFramework\FireFS\Listener\IFileSystemListener;

class FileSystemWatcher
{
    /**
     * The file system listener associated
     * to this watcher.
     *
     * @var WatcherAbstract
     */
    public $_listener;

    /**
     * The file system manager.
     *
     * @var FireFS
     */
    public $_fs;

    /**
     * The path to watch.
     *
     * @var string
     */
    public $_path = "./";

    /**
     * Define if we are watching changes
     * recursively.
     *
     * @var bool
     */
    public $_recursive = true;

    /**
     * The regex pattern of files and folders to watch.
     *
     * @var array
     */
    public $_patternInclude = array();

    /**
     * The regex pattern of files and folders
     * to excludes from watching.
     *
     * @var array
     */
    public $_patternExclude = array(
        "/^.+[\/\\\\]node_modules[\/\\\\]?.*$/",
        "/^.+[\/\\\\]\.git[\/\\\\]?.*$/",
        "/^.+[\/\\\\]vendor[\/\\\\]?.*$/"
    );

    /**
     * The number of milliseconds to wait before
     * watch for changes.
     *
     * @var integer
     */
    public $_watchInterval = 1000000;

    /**
     * Defines if the watcher is started and running.
     *
     * @var bool
     */
    public $_started = true;

    /**
     * Defines if the watcher is built.
     *
     * @var bool
     */
    public $_built = false;

    /**
     * Stores the list of files in the watched
     * folder.
     *
     * @var array
     */
    public $_filesCache = array();

    /**
     * Stores last modification times of watched files.
     *
     * @var array
     */
    public $_lastModTimeCache = array();

    /**
     * Defines if we are currently watching a
     * directory or not.
     *
     * @var bool
     */
    public $_watchingDirectory;

    /**
     * Creates a new instance of FileSystemWatcher.
     *
     * @param FireFS $fs The file system manager instance to use by the watcher.
     */
    public function __construct(FireFS &$fs)
    {
        $this->_fs = $fs;
    }

    /**
     * Sets the listener to use on watched files.
     *
     * @param IFileSystemListener $listener The listener instance.
     *
     * @return self
     */
    public function setListener(IFileSystemListener $listener): self
    {
        $this->_listener = $listener;

        return $this;
    }

    /**
     * Sets the path to the file/directory to watch.
     *
     * @param string $path The path.
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->_path = $this->_fs->toInternalPath($path);

        return $this;
    }

    /**
     * Defines if the watcher must watch for files recursively
     * (Works only if the watched entity is a directory).
     *
     * @param boolean $recursive
     *
     * @return self
     */
    public function setRecursive(bool $recursive): self
    {
        $this->_recursive = $recursive;

        return $this;
    }

    /**
     * Adds a new regex pattern for files to watch.
     *
     * @param string $pattern The regex pattern to add.
     *
     * @return self
     */
    public function addPattern(string $pattern): self
    {
        $this->_patternInclude[] = $pattern;

        return $this;
    }

    /**
     * Adds a new regex pattern for files to exclude from watcher.
     *
     * @param string $pattern The regex pattern to add.
     *
     * @return self
     */
    public function addExcludePattern(string $pattern): self
    {
        $this->_patternExclude[] = $pattern;

        return $this;
    }

    /**
     * Set the array of regex patterns matching files to watch.
     *
     * @param array $patterns The array of regex patterns.
     *
     * @return self
     */
    public function setPatterns(array $patterns) : self
    {
        $this->_patternInclude = $patterns;

        return $this;
    }

    /**
     * Set the array of regex patterns matching files to exclude from watcher.
     *
     * @param array $patterns The array of regex patterns.
     *
     * @return self
     */
    public function setExcludePatterns(array $patterns)
    {
        $this->_patternExclude = $patterns;

        return $this;
    }

    /**
     * Sets the watch interval.
     *
     * @param integer $interval The interval in milliseconds.
     *
     * @return self
     */
    public function setWatchInterval(int $interval): self
    {
        $this->_watchInterval = $interval * 1000;

        return $this;
    }

    public function build(): self
    {
        if (!$this->_built) {
            $this->_watchingDirectory = false;

            $this->_lastModTimeCache = array();
            $this->_filesCache = array();

            if ($this->_fs->isDir($this->_path)) {
                $this->_filesCache = $this->_fs->readDir($this->_path, $this->_recursive);
                $this->_watchingDirectory = true;
            } else if ($this->_fs->exists($this->_path)) {
                $this->_addForWatch($this->_path);
            }

            $this->_cacheLastModTimes();

            $this->_built = true;
        }

        return $this;
    }

    /**
     * Start the file system watcher.
     *
     * @return void
     */
    public function start()
    {
        if (!$this->_built) {
            throw new FileSystemWatcherException("You must build the watcher before start it.");
        }

        if ($this->_started) return;

        $this->_started = true;

        while ($this->_started) {
            $this->process();
            usleep($this->_watchInterval);
        }
    }

    /**
     * Process a watch
     *
     * @return void
     */
    public function process()
    {
        $oldWD = $this->_fs->workingDir();
        $this->_fs->setWorkingDir($this->_fs->dirname($this->_path));
        clearstatcache(true);
        $this->_detectChanges();
        $this->_cacheLastModTimes();
        $this->_fs->setWorkingDir($oldWD);

        $time = time()-1;

        if (
            isset($this->_listener->just_deleted[$time])
        ) {
            $this->_listener->onDeleted($this->_listener->just_deleted[$time]);
            unset($this->_listener->just_deleted[$time]);
        }
    }

    /**
     * Stop the file system watcher.
     *
     * @return void
     * @throws FileSystemWatcherException
     */
    public function stop()
    {
        if (!$this->_built) {
            throw new FileSystemWatcherException("You must build the watcher before stop it.");
        }

        $this->_started = false;
        $this->_cacheLastModTimes();
    }

    /**
     * Restart the file system watcher.
     *
     * @return void
     * @throws FileSystemWatcherException
     */
    public function restart()
    {
        if (!$this->_built) {
            throw new FileSystemWatcherException("You must build the watcher before restart it.");
        }

        $this->stop();
        $this->_built = false;
        $this->build()->start();
    }

    private function _detectChanges()
    {
        if ($this->_watchingDirectory) {
            $this->_watchFolder($this->_path);
        } else {
            $this->_watchFile($this->_path);
        }
    }

    private function _watchFolder(string $_path)
    {
        $directory = $this->_fs->readDir($_path, $this->_recursive);
        $watching = array_merge($this->_filesCache, $directory);

        foreach ($watching as $name => $path) {
            if ($this->_fs->isDir($path)) {
                continue;
            } else {
                $this->_watchFile($path);
            }
        }

        $this->_filesCache = $directory;
    }

    private function _watchFile(string $_path)
    {
        if (count($this->_patternExclude) > 0) {
            foreach ($this->_patternExclude as $pattern) {
                if (preg_match($pattern, $_path, $m)) {
                    return;
                }
            }
        }

        $match = true;

        if (count($this->_patternInclude) > 0) {
            $match = false;
            foreach ($this->_patternInclude as $pattern) {
                if (preg_match($pattern, $_path, $m)) {
                    $match = true;
                    break;
                }
            }
        }

        if ($match) {
            $path = $this->_fs->cleanPath($_path);

            if ($this->_fs->exists($path)) {
                if (array_key_exists($path, $this->_lastModTimeCache)) {
                    if ($this->_lastModTimeCache[$path] < $this->_lmt($path)) {
                        if ($this->_listener->onAny(new FileSystemEntityModifiedEvent($path))) {
                            $this->_listener->onModified(new FileSystemEntityModifiedEvent($path));
                        }
                    }
                } else {
                    $this->_addForWatch($_path);
                    if ($this->_listener->onAny(new FileSystemEntityCreatedEvent($path))) {
                        $this->_listener->onCreated(new FileSystemEntityCreatedEvent($path));
                    }
                }
            } else {
                if (array_key_exists($path, $this->_lastModTimeCache)) {
                    $this->_removeFromWatch($_path);
                    $this->_listener->onAny(new FileSystemEntityDeletedEvent($path));
                }
            }
        }
    }

    private function _cacheLastModTimes()
    {
        foreach ($this->_filesCache as $name => $path) {
            // If the file got deleted during from the listener,
            // or during the process but after scanning for changes.
            if ($this->_fs->exists($path)) {
                $this->_lastModTimeCache[$path] = $this->_lmt($path);
            }
        }
    }

    private function _lmt(string $path): int
    {
        return $this->_fs->lastModTime($path);
    }

    private function _addForWatch(string $path)
    {
        $p =  $this->_fs->cleanPath($path);
        $this->_filesCache[$path] = $p;
        $this->_lastModTimeCache[$p] = $this->_lmt($p);
    }

    private function _removeFromWatch(string $path)
    {
        $p =  $this->_fs->cleanPath($path);
        unset($this->_filesCache[$path]);
        unset($this->_lastModTimeCache[$p]);
    }
}
