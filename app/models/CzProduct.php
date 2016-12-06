<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CzProduct extends Model
{
    protected $table = 'cz_product';
    protected $primaryKey = 'id_cz_product';
    public $timestamps = false;
    protected $fillable=[
        'id_cz_product',
        'id_product',
        'id_supplier',
        'active',
        'active_bol_nl',
        'id_category_default',
        'ean13',
        'reference',
        'product_supplier_reference',
        'name',
        'lot_nr',
        'cost_factor',
        'quantity_in_stock',
        'ikp_supplier',
        'ikp_ex_cz',
        'vat_procent',
        'margin_factor_dropshipping',
        'margin_factor_wholesale',
        'vkp_ex_dropshipping',
        'vkp_ex_wholesale',
        'vkp_cz_ex_vat',
        'vkp_cz_in_vat',
        'margin_factor_cz',
        'margin_factor_be_cz',
        'margin_factor_nl_cz',
        'shipping_cost_cz_be',
        'shipping_cost_cz_nl',
        'netto_profit_amount_be',
        'netto_profit_amount_nl',
        'margin_factor_bol_be',
        'vkp_bol_be_ex_vat',
        'vkp_bol_be_in_vat',
        'shipping_cost_bol_be',
        'bol_be_cost',
        'bol_group_cost_fix',
        'bol_group_cost_procent',
        'netto_profit_amount_bol_be',
        'vkp_bol_nl_ex_vat',
        'vkp_bol_nl_in_vat',
        'margin_factor_bol_nl',
        'shipping_cost_bol_nl',
        'bol_nl_cost',
        'netto_profit_amount_bol_nl',
        'date_add',
        'date_upd',
        'descr_short_nl',
        'link_rewrite_nl',
        'meta_descr_nl',
        'meta_title_nl',
        'tag_1_nl',
        'tag_2_nl',
        'tag_3_nl',
        'tag_4_nl',
        'tag_5_nl',
        'tag_6_nl'
    ];
}
