<?php

namespace Netto\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportCommand extends Command
{
    protected $signature = 'cms:import {filename : Path to CSV file} {model : Database model name}';
    protected $description = 'Import CSV file entries into database models';

    /**
     * @return int|null
     */
    public function handle(): ?int
    {
        $filename = $this->argument('filename');
        if (!(new Filesystem)->exists($filename)) {
            $this->error("File {$filename} was not found!");
            return 1;
        }

        $model = $this->argument('model');
        $className = "App\\Models\\{$model}";
        if (!class_exists($className)) {
            $this->error("Class {$className} was not found!");
            return 1;
        }

        $pointer = fopen($filename, 'rb');
        $i = 0;
        $e = 0;
        $attributes = [];

        while (($read = fgetcsv($pointer, null, ',')) !== false) {
            $i++;

            if ($i == 1) {
                $attributes = $read;
                continue;
            }

            if (empty($attributes)) {
                $this->error("Attribute names were not found!");
                return 1;
            }

            $object = new $className();
            foreach ($read as $key => $value) {
                if (mb_strlen($value) === 0) {
                    $value = null;
                }

                $object->setAttribute($attributes[$key], $value);
            }

            if (!$object->save()) {
                $this->error("Failed to save object!");
                return 1;
            }

            $e++;
        }

        fclose($pointer);

        $this->info("Imported: {$e}");
        return null;
    }
}
