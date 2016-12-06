<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsProductLang extends Model
{
    protected $table = 'ps_product_lang';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
}
