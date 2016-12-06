<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsImage extends Model
{
    protected $table = 'ps_image';
    protected $primaryKey = 'id_product';
    public $timestamps = false;
}
