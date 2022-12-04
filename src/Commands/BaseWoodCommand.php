<?php

namespace Bfg\Wood\Commands;

use Bfg\Wood\ClassGetter;
use Bfg\Wood\Models\Php;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class BaseWoodCommand extends Command
{

    /**
     * @var Builder
     */
    protected Builder $connection;

    /**
     * @var string|null
     */
    protected ?string $dbFile = null;

    public function __construct()
    {
        parent::__construct();

        $this->dbFile = config('wood.connection.database');

        if (! is_file($this->dbFile)) {

            file_put_contents($this->dbFile, '');
        }

        $this->connection = Schema::connection('wood');
    }

    /**
     * @param  bool  $force
     * @return void
     */
    protected function freshPhpTable(bool $force = false): void
    {
        if (!$this->connection->hasTable('php')) {

            $this->connection->create('php', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['class', 'anonymous', 'interface', 'trait']);
                $table->string('file');
                $table->bigInteger('inode');
                $table->string('name');
                $table->morphs('topic');
                $table->integer('processed')->default(0);
                $table->integer('max_property')->default(0);
                $table->integer('max_method')->default(0);
            });

            $this->info('PHP table, created!');
        }

        if (!$this->connection->hasTable('php_subjects')) {

            $this->connection->create('php_subjects', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['method', 'property']);
                $table->foreignId('php_id')->constrained('php');
                $table->string('name');
                $table->integer('processed')->default(0);
            });

            $this->info('PHP subject table, created!');
        }
    }

    /**
     * @return Collection
     */
    protected function getWorkFiles(): Collection
    {
        return collect(Finder::create()
            ->files()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->in(base_path())
            ->filter(fn (SplFileInfo $i) => str_ends_with($i->getFilename(), '.php'))
            ->filter(fn (SplFileInfo $i) => ! str_starts_with($i->getFilename(), '_'))
            ->exclude('vendor'))
            ->map(fn (SplFileInfo $i) => [
                'filename' => $i->getPathname(),
                'inode' => $i->getInode(),
                'class' => (new ClassGetter())->getClassFullNameFromFile($i->getPathname()),
            ])->filter(fn (array $d) => $d['class'] && $d['inode']);
    }
}
