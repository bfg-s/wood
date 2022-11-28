<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

class WoodCompileCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:compile";

    /**
     * @var string
     */
    protected $description = "Run wood compile from file";

    /**
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $file = database_path('wood.json');

        try {
            $data = $this->decode($file);
            //dd($data);

            if (isset($data[0]['name'])) {
                $data[0]['id'] = 1;
            }

            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        } catch (Throwable $t) {
            $this->error($t->getMessage());
        }


        return 0;
    }

    /**
     * @throws Exception
     */
    protected function decode(string $file)
    {
        $result = json_decode(file_get_contents($file), 1);

        $error = match (json_last_error()) {
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
            default => null
        };

        if ($error) {
            throw new Exception($error);
        }

        return $result;
    }
}
