<?php

namespace Mohammedkh21\MvcGenerator\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MvcGeneratorService
{
    public $name = '';
    public $attributes = [];


    public function route()
    {
        $routeContents = "Route::resource('" . strtolower(Str::plural($this->name)) . "', \App\Http\Controllers\\" . str_replace('/','\\',$this->name) . "Controller::class);\n";
        file_put_contents(base_path('routes/web.php'), $routeContents, FILE_APPEND);
    }

    public function controller()
    {
        Artisan::call('make:controller', [
            'name' => $this->name . 'Controller',
            '--resource' => true,
        ]);
    }

    public function seeder()
    {
        Artisan::call('make:seeder', ['name' => $this->name . 'Seeder']);
    }

    public function migration()
    {
        $attributes = $this->attributes;
        $name = explode('/', $this->name);
        $name = Str::snake(end($name)) . 's';
        $migrationName = 'create_' . $name . '_table';
        $migrationFilename = database_path("migrations/" . date('Y_m_d_His') . "_$migrationName.php");

        $migrationTemplate = "
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('$name', function (Blueprint \$table) {
            \$table->id();
        ";

        $attributes = array_diff($attributes , ['id','created_at','updated_at']);

        foreach ($attributes as $attribute) {
            if (substr($attribute, -3) == '_id') {
                $table = substr($attribute, 0, -3) . 's';
                $migrationTemplate .= "\$table->foreignId('$attribute')->constrained(table: '$table',indexName: '$table-$name'); ";

            } else {
                $migrationTemplate .= "\$table->string('$attribute'); ";

            }
        }
        $migrationTemplate .= "
            \$table->timestamps();
                });
            }

            public function down()
            {
                Schema::dropIfExists('$name');
            }
        };
        ";
        File::put($migrationFilename, $migrationTemplate);
    }

    public function model(){
        $name = explode('/', $this->name);
        $name = ucfirst( end($name) );
        $attributes = $this->attributes;
        $fillable = collect($attributes)->map(function ($name){
            return '"'.$name.'"';
        });
        $fillable = implode(',',$fillable->toArray());
        $modelTemplate = "
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $name extends Model
{
    protected \$fillable = [$fillable];
    ";
        foreach ($attributes as $attribute) {
            if (substr($attribute, -3) == '_id') {
                $table = ucfirst(substr($attribute, 0, -3));
                $modelTemplate .= "
                    public function $table(){
                        return \$this->hasOne($table::class);
                    }
                ";

            }
        }


        $modelTemplate .= "

}
";
        $directory = app_path().'/Models';
        $filename = "$directory/$name.php";
        File::put($filename, $modelTemplate);


    }

}
