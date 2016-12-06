<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsCategoryProduct extends Model
{
    protected $table = 'ps_category_product';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
}
