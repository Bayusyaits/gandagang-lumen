<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;

class CreateGlobalParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globalParam', function (Blueprint $table) {
            $table->increments('globalParamId');
            $table->bigInteger('globalParamUserId')->comment('id from table user')->nullable();
            $table->string('globalParamStorageUrl')->nullable();
            $table->char('globalParamSlug', 100)->nullable();
            $table->enum('globalParamIsParent', ['1', '0'])->comment('1 (true) or 0 (false)')->nullable();
            $table->integer('globalParamParentId')->nullable();
            $table->json('globalParamValue')->comment('{"id": {"description": "description","title": "penjelasan"}, "en": {"description": "description","title": "penjelasan"}}')->default(new Expression('(JSON_ARRAY())'));
            $table->char('globalParamScope', 50)->comment('category product, market place, posting blog, feature, payment, know from')->nullable();
            $table->timestampTz('globalParamCreatedDate')->useCurrent();
            $table->timestampTz('globalParamUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('globalParamDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('globalParam');
    }
}
