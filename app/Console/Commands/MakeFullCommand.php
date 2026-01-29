<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFullCommand extends Command
{
    protected $signature = 'make:full {name} {--ignore-model}';
    protected $description = 'Create model, migration, controller, request, resource, and collection';

    public function handle()
    {
        $name = $this->argument('name');
        $ignoreModel = $this->option('ignore-model');


        if(!$ignoreModel){
            $this->callSilent('make:model', [
                'name' => $name,
                '--migration' => true,
            ]);

        }
        else{
            $this->info("⏭️ Skipped model and migration creation.");
        }

       
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

        $this->info("✅ Collection created.");

        $this->createService($name);

        $this->info("🎉 All files for '{$name}' generated successfully!");
    }

    public function createController($name){
        $className = ucfirst($name);
        $snake_class_name = Str::snake($name);


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

    public function createService($name){
        $className = ucfirst($name);
        $snake_class_name = Str::snake($name);

        $serviceName = "{$className}Service";
        $serviceDir = app_path('Http/Services');
        $servicePath = "{$serviceDir}/{$serviceName}.php";

        if (!File::exists($serviceDir)) {
            File::makeDirectory($serviceDir, 0755, true);
        }

        if (File::exists($servicePath)) {
            $this->warn("⚠️ Service {$serviceName} already exists.");
            return;
        }

        $stub = File::get(base_path('stubs/service.stub')); 

        $stub = str_replace('{{ class_name }}', $serviceName, $stub);
        $stub = str_replace('{{ snake_class_name }}', $snake_class_name, $stub);

        File::put($servicePath, $stub);

        $this->info("✅ Service {$serviceName} created successfully!");
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
