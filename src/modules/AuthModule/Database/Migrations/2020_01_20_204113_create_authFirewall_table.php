<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthFirewallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authFirewall', function (Blueprint $table) {
            $table->increments('authFirewallId');
            $table->ipAddress('authFirewallIpAddress')->comment('127.0.0.1');
            $table->char('authFirewallDomain', 100)->comment('bayusyaits.com')->nullable();
            $table->enum('authFirewallStatus', ['0', '1'])->comment('0 is blacklist, 1 is whitelist')->default('0');
            $table->timestampTz('authFirewallCreatedDate')->useCurrent();
            $table->timestampTz('authFirewallUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('authFirewallDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authFirewall');
    }
}
