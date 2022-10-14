<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('faq_categories_translations')) {
            Schema::create('faq_categories_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('faq_categories_id');
                $table->string('name', 120)->nullable();

                $table->primary(['lang_code', 'faq_categories_id'], 'faq_categories_translations_primary');
            });
        }

        if (!Schema::hasTable('faqs_translations')) {
            Schema::create('faqs_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('faqs_id');
                $table->text('question')->nullable();
                $table->text('answer')->nullable();

                $table->primary(['lang_code', 'faqs_id'], 'faqs_translations_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_categories_translations');
        Schema::dropIfExists('faqs_translations');
    }
};
