<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userDetail', function (Blueprint $table) {
            $table->bigIncrements('userDetailId');
            $table->bigInteger('userDetailUserId')->comment('refer from table user');
            $table->integer('userDetailListCountryId')->comment('refer from list nationality')->nullable();
            $table->integer('userDetailUserBankAccountId')->comment('refer from table bankAccount')->nullable();
            $table->integer('userDetailEWalletId')->comment('refer from table Ewallet')->nullable();
            $table->integer('userDetailIdCardAddressId')->comment('refer from table address')->nullable();
            $table->integer('userDetailDomicileAddressId')->comment('refer from table address')->nullable();
            $table->integer('userDetailBillingAddressId')->comment('refer from table address')->nullable();
            $table->integer('userDetailShipAddressId')->comment('refer from table address')->nullable();
            $table->integer('userDetailUserSocialMediaId')->comment('refer from table socialMedia')->nullable();
            $table->integer('userDetailSupportId')->comment('refer from table Ewallet')->nullable();
            $table->integer('userDetailMaritalStatusId')->comment('refer from table GlobalParam')->nullable();
            $table->integer('userDetailOccupationId')->comment('refer from table GlobalParam')->nullable();
            $table->integer('userDetailLastEducationId')->comment('refer from table GlobalParam')->nullable();
            $table->integer('userDetailReligionId')->comment('refer from table GlobalParam')->nullable();
            $table->string('userDetailPhotoStorageUrl')->comment('refer from table Ewallet')->nullable();
            $table->string('userDetailDocumentStorageUrl')->comment('refer from table GlobalParam')->nullable();
            $table->string('userDetailDocument2StorageUrl')->comment('refer from table GlobalParam')->nullable();
            $table->string('userDetailIdCardStorageUrl')->comment('refer from table GlobalParam')->nullable();
            $table->integer('userDetailIdCardNumber')->nullable();
            $table->date('userDetailBirthDay')->comment('yyyy-mm-dd');
            $table->char('userDetailWebUrl', 100)->comment('bayusyaits.com');
            $table->char('userDetailParentReferalCode', 20)->comment('alphanumeric IUI981');
            $table->char('userDetailGetCodeFrom', 20)->comment('alphanumeric IUI981');
            $table->string('userDetailNotes')->comment('notes from admin')->nullable();
            $table->char('userDetailKnowFrom', 40)->comment('facebook/google/web/friends/another')->nullable();
            $table->dateTime('userDetailIdCardExpiredDate')->comment('if lifetime date is 3000-12-31')->nullable();
            $table->dateTime('userDetailNotesDate')->nullable();
            $table->timestampTz('userDetailCreatedDate')->useCurrent();
            $table->timestampTz('userDetailUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('userDetailDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userDetail');
    }
}
