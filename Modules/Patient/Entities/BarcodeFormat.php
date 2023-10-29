<?php

namespace Modules\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarcodeFormat extends Model
{
    use HasFactory;

    protected $table = 'barcode_formats';
    public $timestamps = false;
    protected $fillable = ['id', 'barcode_district','barcode_upazila','barcode_union','barcode_community_clinic',
        'barcode_prefix','barcode_number','created_by','updated_by',
        'created_at','updated_at', 'status'];

    protected static function newFactory()
    {
        return \Modules\Patient\Database\factories\BarcodeFormatFactory::new();
    }
}
