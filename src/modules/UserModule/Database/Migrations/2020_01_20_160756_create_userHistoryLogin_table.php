<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHistoryLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userHistoryLogin', function (Blueprint $table) {
            $table->bigIncrements('userHistoryLoginId');
            $table->bigInteger('userHistoryLoginUserId')->comment('refer from table user')->nullable();
            $table->string('userHistoryLoginUserToken')->comment('reffer from table user')->nullable();
            $table->string('userHistoryLoginClientId')->comment('whe login use third party')->nullable();
            $table->char('userHistoryOTPCode', 10)->comment('format is aplhanumeric capital 6 digit')->nullable();
            $table->ipAddress('userHistoryLoginIpAddress')->comment('getIpAddress User when login')->nullable();
            $table->string('userHistoryLoginPlatform')->nullable();
            $table->enum('userHistoryLoginScope', ['0', '1', '2'])->comment('0 is revoke, 1 is frontoffice, 2 is backoffice')->default('1');
            $table->timestampTz('userHistoryLoginExpiredDate')->nullable();
            $table->dateTime('userHistoryLoginCreatedDate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userHistoryLogin');
    }
}
