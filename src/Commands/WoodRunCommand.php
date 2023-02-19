<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\SubjectAbstract;
use Bfg\Wood\ClassFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class WoodRunCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:run";

    /**
     * @var string
     */
    protected $description = "Run wood generate";

    /**
     * @throws BindingResolutionException
     */
    public function handle(): int
    {
        Comcode::on('apply', [$this, 'apply']);
        Comcode::on('parsed', [$this, 'parsed']);

        app(ClassFactory::class)
            ->generate()
            ->save(
                fn (ClassSubject $subject)
                => $this->info("Saved: " . str_replace(base_path(), '', $subject->fileSubject->file)),
                fn (string $path)
                => $this->error("Deleted: " . $path),
            );

        $this->info('Finished!');

        return 0;
    }

    public function apply(SubjectAbstract $subject)
    {
        $this->info("Apply changes for: " . $subject->fileSubject->file);
    }

    public function parsed(SubjectAbstract $subject)
    {
        $this->comment("Parsed file: " . str_replace(base_path(), '', $subject->fileSubject->file));
    }
}
