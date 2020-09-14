<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage', function (Blueprint $table) {
            $table->bigIncrements('storageId');
            $table->enum('storageIsParent',['1', '0'])->comment('1 (true) or 0 (false)')->nullable();
            $table->bigInteger('storageParentId')->nullable();
            $table->bigInteger('storageUserId')->comment('relate with table user, for update and add storage data from bo user');
            $table->binary('storageData')->comment('/www/htdocs/inc')->nullable();
            $table->char('storageSlug', 100)->nullable();
            $table->json('storageValue')->comment('description/ alt/ caption')->nullable();
            $table->char('storageFileName', 100)->nullable();
            $table->string('storageFileUrl')->nullable();
            $table->char('storageScope', 100)->comment('image, video, audio, doc, animation')->nullable();
            $table->enum('storageFileExtension', ['bmp', 'gif', 'jpg', 'mp3', 'wav', 'doc', 'docx', 'rtf', 'txt', 'xls', 'xlsx', 'xlr', 'csv', 'pdf', 'svg', 'jpeg', 'png'])->comment('jpg/ png/ pdf');
            $table->smallInteger('storageFilePermissions')->comment('755 Everybody can read, write to, or execute | 700 Only you can read, write to, or execute | 744 Only you can read, write to, or execute but Everybody can read | 444 You can only read, as everyone else')->default('755');
            $table->timestampTz('storageCreatedDate')->useCurrent();
            $table->timestampTz('storageUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('storageDeletedDate')->nullable();
        });
        DB::statement('ALTER TABLE `storage` CHANGE COLUMN `storageData` `storageDataBlob` MEDIUMBLOB NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema:: dropIfExists('storage');
    }
}
