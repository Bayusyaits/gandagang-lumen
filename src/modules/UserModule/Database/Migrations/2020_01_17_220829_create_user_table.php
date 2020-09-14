<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('userId');
            $table->string('userClientId')->comment('id from thirdparty like google/facebook')->nullable();
            $table->char('userClientSecret', 100)->comment('thirdparty')->nullable();
            $table->string('userToken')->comment('for remember token, and value uptodate when user clieck rememberme token')->nullable();
            $table->char('userRegisterdBy', 100)->nullable()->comment('google, apps, facebook');
            $table->char('userEmail', 100);
            $table->enum('userSalutation', ['Mr', 'Mrs', 'Ms'])->nullable();
            $table->char('userFirstName', 20)->comment('is alphabet')->nullable();
            $table->char('userLastName', 20)->comment('is alphabet')->nullable();
            $table->string('userPassword');
            $table->char('userName', 100)->comment('is alphabet')->nullable();
            $table->integer('userMobilePrefix')->comment('refer from list mobile prefix in table list nationality')->nullable();
            $table->char('userPhoneNumber', 12)->comment('is numeric')->nullable();
            $table->enum('userStatus', ['1', '2', '3', '4', '5'])->comment('registered', 'completing data', 'active', 'inactive', 'rejected')->nullable();
            $table->enum('userType', ['1', '2'])->comment('1 is frontoffice 2 is backoffice')->nullable();
            $table->smallInteger('userRole')->comment('Role:
            1. customer
            2. supplier
            3. seller
            4. business analyst
            5. admin
            6. ceo
            7. qualityControl
            8. developer
            9. Shipper
            10. Distributor
            11. Vendor')->nullable();
            $table->char('userVerifyPhoneNumberCode', 10)->comment('format is aplhanumeric capital 6 digit')->nullable();
            $table->char('userVerifyEmailCode', 10)->comment('format is aplhanumeric capital 6 digit')->nullable();
            $table->enum('userOTPStatus', ['0', '1'])->comment('0 is inactive, 1 is active | when login user must input otp')->default('1');
            $table->enum('userVerifyData', ['0', '1'])->comment('0 is verified, 1 is unverified input by admin bo')->default('0');
            $table->enum('userAgreePrivacy', ['0', '1'])->comment('1 is agree')->default('0');
            $table->enum('userAgreeSubscribe', ['0', '1'])->comment('1 is agree')->default('0');            
            $table->string('userPlatform')->nullable();
            $table->ipAddress('userIpAddress')->comment('ip address equivalent column.')->nullable();
            $table->dateTimeTz('userVerifyPhoneNumberDate', 0)->nullable();
            $table->dateTimeTz('userVerifyEmailDate', 0)->nullable();
            $table->dateTimeTz('userVerifyDataDate', 0)->nullable();
            $table->timestampTz('userCreatedDate')->useCurrent();
            $table->timestampTz('userUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('userDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
