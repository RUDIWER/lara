<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsAddress extends Model
{
    protected $table = 'ps_address';
    protected $primaryKey = 'id_address';
    public $timestamps = false;

    public function country(){
      return $this->belongsTo('App\Models\PsCountryLang','id_country','id_country');
    }


}
