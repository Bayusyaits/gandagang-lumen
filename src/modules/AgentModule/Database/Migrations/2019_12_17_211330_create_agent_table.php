<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->bigIncrements('agentId');
            $table->integer('agentPartnerId')->comment('if agent to be partner')->nullable();
            $table->enum('agentType', ['vendor', 'broker', 'agent', 'retailer'])->comment('must be fill');
            $table->bigInteger('agentAddressId')->nullable()->comment('refer to address id');
            $table->integer('agentPhoneNumber')->comment('23987482')->nullable();
            $table->integer('agentWebUrl')->comment('23987482')->nullable();
            $table->integer('agentSalesPhoneNumber')->comment('23987482')->nullable();
            $table->char('agentSalesName', 100)->comment('malih')->nullable();
            $table->char('agentSalesNIK', 100)->comment('87236723546')->nullable();
            $table->char('agentSalesIdCardNumber', 100)->comment('87236723546')->nullable();
            $table->char('agentSalesIdCardStorageId', 100)->comment('refer to storage')->nullable();
            $table->string('agentSalesNote')->comment('note for agent/ sales')->nullable();
            $table->enum('agentSalesStatus', ['active', 'inactive'])->comment('available, unvailable, inactive')->nullable();
            $table->timestampTz('agentCreatedDate')->useCurrent();
            $table->timestampTz('agentUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('agentDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent');
    }
}
