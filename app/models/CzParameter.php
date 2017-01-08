<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CzParameter extends Model
{
    protected $table = 'cz_parameter';
    protected $primaryKey = 'id_cz_parameter';
    public $timestamps = false;
    protected $fillable=[
        'shipping_cost_cz_be_ex_btw',
        'shipping_cost_cz_nl_ex_btw',
      //  'max_cz_amount_for_shipping_cz',   // Fout ??????
        'min_order_amount_free_shipping',
        'stand_counted_shipping_cost_cz',
        'shipping_cost_bol_be_ex_btw',
        'shipping_cost_bol_nl_ex_btw',
        'fixed_cost_bol_ex_btw',
        'procent_cost_bol_ex_btw',
        'stand_vat_procent',
        'stand_margin_dropshipping',
        'stand_margin_wholesale'
    ];
}
