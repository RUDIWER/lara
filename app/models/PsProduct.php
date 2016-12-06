<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsProduct extends Model
{
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';
    public $timestamps = false;

    public function PsProductLang()
   {
       return $this->hasMany('App\Models\PsProductLang');
   }
}
