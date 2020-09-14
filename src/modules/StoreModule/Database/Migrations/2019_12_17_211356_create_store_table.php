<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->bigIncrements('storeId');
            $table->integer('storePartnerId')->comment('refer to partner')->nullable();
            $table->integer('storeOwnerPhoneNumber')->comment('23987482')->nullable();
            $table->integer('storePhoneNumber')->comment('23987482')->nullable();
            $table->bigInteger('storeAddressId')->nullable()->comment('refer to address id');
            $table->char('storeOwnerName', 100)->comment('malih')->nullable();
            $table->char('storeOwnerNIK', 100)->comment('87236723546')->nullable();
            $table->char('storeOwnerStorageId', 100)->comment('refer to storage')->nullable();
            $table->string('storeNote')->comment('note for store')->nullable();
            $table->timestampTz('storeCreatedDate')->useCurrent();
            $table->timestampTz('storeUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('storeDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
}
