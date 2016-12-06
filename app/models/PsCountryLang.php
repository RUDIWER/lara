<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsCountryLang extends Model
{
    protected $table = 'ps_country_lang';
    protected $primaryKey = 'id_country';
    public $timestamps = false;
}
