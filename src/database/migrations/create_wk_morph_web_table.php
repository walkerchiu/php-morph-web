<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMorphWebTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.morph-web.webs'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('morph');
            $table->string('type', 15);
            $table->string('serial')->nullable();
            $table->string('target', 10)->default('_blank');
            $table->string('url');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->index(['morph_type', 'morph_id', 'type']);
            $table->index('type');
            $table->index('serial');
            $table->index('url');
            $table->index('is_enabled');
        });
        if (!config('wk-morph-web.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-web.webs_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.morph-web.webs_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-web.webs'));
    }
}
