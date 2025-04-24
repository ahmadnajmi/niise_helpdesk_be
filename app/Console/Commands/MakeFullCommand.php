<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFullCommand extends Command
{
    protected $signature = 'make:full {name}';
    protected $description = 'Create model, migration, controller, request, resource, and collection';

    public function handle()
    {
        $name = $this->argument('name');

        $this->callSilent('make:model', [
            'name' => $name,
            '--migration' => true,
        ]);

        $this->info("✅ Model and migration created.");

        $this->createController($name);

        $this->callSilent('make:request', [
            'name' => "{$name}Request",
        ]);

        $this->info("✅ Request created.");

        $this->callSilent('make:resource', [
            'name' => "{$name}Resources",
        ]);

        $this->info("✅ Resource created.");

        $this->createCollection($name);

        $this->info("🎉 All files for '{$name}' generated successfully!");
    }

    public function createController($name){
        $className = ucfirst($name);
        $snake_class_name = Str::snake('OperatingTime');


        $this->callSilent('make:controller', [
            'name' => "{$name}Controller",
            '--api' => true,
        ]);


        $controllerPath = app_path("Http/Controllers/{$className}Controller.php");

        $content = file_get_contents($controllerPath);
  
        $content = str_replace('{{ class_name }}', $className, $content);
        $content = str_replace('{{ snake_class_name }}', $snake_class_name, $content);


        file_put_contents($controllerPath, $content);
  
        $this->info("✅ Controller {$className}Controller generated successfully!");
    }

    public function createCollection($name){

        $collectionName = "{$name}Collection";
        $resourcePath = app_path("Http/Resources/{$collectionName}.php");
        $collectionPath = app_path("Http/Collection/{$collectionName}.php");

        $this->callSilent('make:resource', [
            'name' => $collectionName,
            '--collection' => true,
        ]);

        if (!File::exists(app_path('Http/Collection'))) {
            File::makeDirectory(app_path('Http/Collection'), 0755, true);
        }

        if (File::exists($resourcePath)) {
            File::move($resourcePath, $collectionPath);
            $content = File::get($collectionPath);
            $content = str_replace('namespace App\Http\Resources;', 'namespace App\Http\Collection;', $content);
            File::put($collectionPath, $content);
            $this->info("✅ Collection created and moved to Http/Collection.");
        } else {
            $this->warn("⚠️ Collection resource file not found.");
        }
    }
}
