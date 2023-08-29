<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_formats', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_district')->nullable()->comment="Cox's Bazar=CXB";
            $table->string('barcode_upazila')->nullable()->comment="Ukhiya=UK";
            $table->string('barcode_union')->nullable()->comment="Holdiapalong=HD";
            $table->string('barcode_community_clinic')->nullable()->comment="South Haludiya Community Clinic=SH";
            $table->string('barcode_prefix')->unique();
            $table->string('barcode_number');
            $table->enum('status',['1','2'])->default('1')->comment="1=active,2=inactive";
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcode_formats');
    }
}
