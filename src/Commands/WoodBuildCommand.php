<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\SubjectAbstract;
use Bfg\Wood\ArrayFactory;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Exceptions\InvalidValueByRegexp;
use Bfg\Wood\Exceptions\ParseHasPossibleVariants;
use Bfg\Wood\Exceptions\UndefinedDataForParameter;
use Illuminate\Contracts\Container\BindingResolutionException;

class WoodBuildCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:build";

    /**
     * @var string
     */
    protected $description = "Run wood generate. Alias for wood:run";

    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            app(ArrayFactory::class)
                ->parse()
                ->compare()
                ->detect()
                ->compare()
                ->save();

            $this->info('Parse is finished!');
        } catch (
            ParseHasPossibleVariants|InvalidValueByRegexp|UndefinedDataForParameter $exception
        ) {
            $this->error($exception->getMessage());
        }

        $this->call('wood:run');

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
