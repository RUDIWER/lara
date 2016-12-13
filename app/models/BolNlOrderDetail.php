<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BolNlOrderDetail extends Model
{
    protected $table = 'bol_nl_order_detail';
    protected $primaryKey = 'id_bol_nl_order_detail';
    public $timestamps = false;
}
