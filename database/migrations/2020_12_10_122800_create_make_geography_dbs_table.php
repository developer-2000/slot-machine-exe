<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeGeographyDbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('make_geography_dbs', function (Blueprint $table) {
            $table->id();
            $table->longText('country')->nullable()->comment('все страны на 3 языках');
            $table->longText('regions')->nullable()->comment('области стран на 3 языках');
            $table->longText('cities')->nullable()->comment('города областей на 3 языках');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('make_geography_dbs');
    }
}
