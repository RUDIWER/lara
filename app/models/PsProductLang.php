<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsProductLang extends Model
{
    protected $table = 'ps_product_lang';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
    protected $fillable=[
        'id_product',
        'id_shop',
        'id_lang',
        'description',
        'description_short',
        'link_rewrite',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'name',
        'available_now',
        'available_later'
    ];
}

