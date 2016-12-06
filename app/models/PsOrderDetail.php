<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsOrderDetail extends Model
{
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order_detail';
    public $timestamps = false;

    public function product()
    {
        return $this->hasMany('App\Models\CzProduct','product_id','id_product');
    }

}
