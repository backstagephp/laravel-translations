<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('translations')) {
            return;
        }

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);

            $table->string('group')
                ->nullable()
                ->index();

            $table->text('key');

            $table->longText('text')
                ->nullable();

            $table->longText('source_text')
                ->nullable();

            $table->string('namespace')
                ->default('*')
                ->index();

            $table->timestamp('translated_at')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
};
