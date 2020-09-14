<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authClient', function (Blueprint $table) {
            $table->bigIncrements('authClientid');
            $table->char('authClientSignature', 100)->comment('alphanumeric')->nullable();
            $table->char('authClientKey', 100)->comment('alphanumeric')->nullable();
            $table->integer('authClientListFirewallId')->comment('refr from list firewall')->nullable();
            $table->enum('authClientAccess', ['0', '1', '2'])->comment('0 not set, 1 have access, 2 s revoke')->default('0');
            $table->string('authClientPassword')->comment('hash')->nullable();
            $table->dateTime('authClientExpiredDate')->nullable();
            $table->timestampTz('authClientCreatedDate')->useCurrent();
            $table->timestampTz('authClientUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('authClientDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authClient');
    }
}
