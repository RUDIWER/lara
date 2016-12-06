<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PsSupplier extends Model
{
    protected $table = 'ps_supplier';
    protected $primaryKey = 'id_supplier';
    public $timestamps = false;
    protected $fillable=[
        'id_supplier',
        'name',
        'active',
        'website',
        'email_1',
        'email_1_descr',
        'email_2',
        'email_2_descr',
        'tel_1',
        'tel_1_descr',
        'tel_2',
        'tel_2_descr',
        'tel_3',
        'tel_3_descr',
        'contactperson_1',
        'contactperson_2',
        'vat_number',
        'bank_account',
        'memo'
    ];
}
