<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsStockAvailable extends Model
{
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
}
