<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMdataccBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mdatacc_barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('mdata_barcode_prefix');
            $table->string('mdata_barcode_number');
            $table->string('mdata_barcode_prefix_number');
            $table->text('mdata_barcode_generate');
            $table->string('mdata_barcode_status')->default('unused');
            $table->enum('status',['1','2'])->default('1')->comment="1=Active,2=Inactive";
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
        Schema::dropIfExists('mdatacc_barcodes');
    }
}
