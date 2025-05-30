<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('languages')) {
            return;
        }

        Schema::create('languages', function (Blueprint $table) {
            $table->char('code', 5)
                ->primary();

            $table->string('name');
            $table->string('native')
                ->nullable();


            $table->boolean('active')
                ->default(true);

            $table->boolean('default')
                ->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
};
