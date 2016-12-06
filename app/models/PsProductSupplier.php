<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsProductSupplier extends Model
{
    protected $table = 'ps_product_supplier';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
}
