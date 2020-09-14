<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->bigIncrements('warehouseId');
            $table->integer('warehouseKeeperPhoneNumber')->comment('23987482')->nullable();
            $table->integer('warehousePhoneNumber')->comment('23987482')->nullable();
            $table->bigInteger('warehouseAddressId')->nullable()->comment('refer to address id');
            $table->char('warehouseKeeperName', 100)->comment('malih')->nullable();
            $table->char('warehouseKeeperNIK', 100)->comment('87236723546')->nullable();
            $table->char('warehouseKeeperStorageId', 100)->comment('refer to storage')->nullable();
            $table->string('warehouseNote')->comment('note for warehouse')->nullable();
            $table->timestampTz('warehouseCreatedDate')->useCurrent();
            $table->timestampTz('warehouseUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('warehouseDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse');
    }
}
