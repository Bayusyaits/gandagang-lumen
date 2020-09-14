<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productDetail', function (Blueprint $table) {
            $table->bigIncrements('productDetailId');
            $table->char('productDetailSKU', 100)->comment('PK12SS1501')->nullable();
            $table->decimal('productDetailPrice', 8, 2);
            $table->integer('productDetailStock')->comment('realtime uptodate, stock pack')->nullable();
            $table->char('productDetailBrand', 100)->comment('gandagang')->nullable();
            $table->integer('productDetailStoreId')->comment('If retail, Refer to table store id')->nullable();
            $table->integer('productDetailWarehouseId')->comment('Refer to table warehouse id')->nullable();
            $table->integer('productDetailAgentId')->comment('Refer to table agent id')->nullable();
            $table->char('productDetailMadeInListCountryId')->nullable();
            $table->smallInteger('productDetailWeight')->nullable();
            $table->smallInteger('productDetailSize')->nullable();
            $table->smallInteger('productDetailItemInPack')->comment('total item in pack/ cardboard like 12,24, 36')->nullable();
            $table->json('productDetailValue')->comment('{"title: { "en": "title", "id":"judul"}, "description": {"en": "description","id": "penjelasan"}"}')->nullable();
            $table->enum('productDetailUnit', ['ton', 'kwintal', 'kilogram', 'gram', 'pound', 'ons', 'litre', 'miligram'])->comment('available, unvailable, inactive')->nullable();
            $table->decimal('productDetailMinTransaction', 8, 2)->nullable();
            $table->decimal('productDetailMaxTransaction', 8, 2)->useCurrent()->nullable();
            $table->integer('productDetailLocationListDataCityId')->nullable();
            $table->dateTime('productDetailExpiredDate')->comment('productDetail will be expirect to used')->nullable();
            $table->dateTime('productDetailReadyStockDate')->comment('Will be ready date if out of stock')->nullable();
            $table->timestampTz('productDetailCreatedDate')->useCurrent();
            $table->timestampTz('productDetailUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productDetail');
    }
}
