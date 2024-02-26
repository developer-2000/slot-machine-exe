<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Download;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('название файла');
            $table->string('path')->comment('адрес файла');
            $table->string('version')->nullable()->comment('версия приложения - если это приложение');
            $table->string('mime')->nullable()->comment('тип файла');
            $table->integer('size')->unsigned()->default(0)->comment('тип файла');

            $table->timestamps();
        });

        // тестовая версия проверка версии обновления приложения и скачка архива
        Download::create([
            'title' => 'title file',
            'path' => '/uploads/version/app.zip',
            'version' => '0.1',
            'mime' => 'application/zip',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('downloads');
    }
}
