<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->bigInteger('section_id')->nullable();
            $table->bigInteger('page_name');
            $table->integer('category_id')->nullable();
            $table->string('type',50);
            $table->string('position',50);
            $table->text('script')->nullable();
            $table->string('image',100)->nullable();
            $table->string('link',255)->nullable();
            $table->integer('sort_order')->nullable();
            $table->enum('status',['Active', 'Inactive'])->default('Active');
            $table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('end_date')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamp('update_time')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('update_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisement');
    }
}
