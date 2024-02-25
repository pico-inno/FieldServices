<?php

namespace App\Factories;

use Exception;

abstract class fileCreator
{


    public $name;
    public function create(){
        $name= $this->name;
           // Extract directory and filename
        $segments = explode('.', $name);
        $fileName = array_pop($segments) . 'Action.php';
        $directory = implode('/', $segments);

        // Generate the action file path
        $filePath = app_path("Actions/{$directory}/{$fileName}");

        // Check if file already exists
        if (file_exists($filePath)) {
            return throw new Exception('File Already Created');
        }

        // Create the directory if it doesn't exist
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        // Create the action file
        $stub = file_get_contents(__DIR__ . '/stubs/action.stub');
        $stub = str_replace('{{ actionName }}', $fileName, $stub);
        file_put_contents($filePath, $stub);
    }

}
