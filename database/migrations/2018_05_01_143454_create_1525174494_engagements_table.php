<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1525174494EngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('engagements')) {
            Schema::create('engagements', function (Blueprint $table) {
                $table->increments('id');
                $table->string('transaction_id')->nullable();
                $table->integer('bank_id');
                $table->timestampTz('date')->nullable();
                $table->string('transaction_type')->nullable();
                $table->string('currency')->nullable();
                $table->string('amount')->nullable();
                $table->string('fee')->nullable();
                $table->string('net_amount')->nullable();
                $table->string('asset_type')->nullable();
                $table->string('asset_price')->nullable();
                $table->string('asset_amount')->nullable();
                $table->string('status')->nullable();
                $table->longText('notes')->nullable();
                $table->string('name_of_sender')->nullable();
                $table->string('account')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['deleted_at']);
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
        Schema::dropIfExists('engagements');
    }
}