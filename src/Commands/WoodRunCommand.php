<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\SubjectAbstract;
use Bfg\Wood\ArrayFactory;
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

        app(ClassFactory::class)
            ->generate()
            ->save(
                fn (ClassSubject $subject)
                => $this->info("Saved: " . str_replace(base_path(), '', $subject->fileSubject->file)),
                fn (string $path)
                => $this->comment("Deleted: " . $path),
            );

        $this->info('Finished!');

        return 0;
    }

    /**
     * @param  SubjectAbstract  $subject
     * @return void
     */
    public function apply(SubjectAbstract $subject): void
    {
        $this->info("Apply changes for: " . str_replace(base_path(), '', $subject->fileSubject->file));
    }
}
