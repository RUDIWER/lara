@extends('layouts.app')

<div id="productApp">
@section('content')
<div id="isNew" data-field-id="{{$isNew}}" ></div>

@if ($isNew == 0)
    <form action="/producten/save/{{ $product->id_cz_product }}" name="productForm" id="productForm" method="post">
@else
    <form action="/producten/save/0" name="productForm" id="productForm" method="post">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
@if ($isNew == 0)
    <input type="hidden" name="id_cz_product" id="id_cz_product" value="{{ $product->id_cz_product }}">
@endif
<input type="hidden" name="shipping_amount_cz_be_ex_btw" id="shipping_amount_cz_be_ex_btw" data-field-id="{{ $param->shipping_amount_cz_be_ex_btw }}" v-model.number="shipping_amount_cz_be_ex_btw">
<input type="hidden" name="shipping_cost_cz_be_in_param" id="shipping_cost_cz_be_in_param" data-field-id="{{ $param->shipping_cost_cz_be_ex_btw }}" v-model.number="shipping_cost_cz_be_in_param">
<input type="hidden" name="shipping_cost_cz_nl_in_param" id="shipping_cost_cz_nl_in_param" data-field-id="{{ $param->shipping_cost_cz_nl_ex_btw }}" v-model.number="shipping_cost_cz_nl_in_param">
<input type="hidden" name="min_order_amount_free_shipping" id="min_order_amount_free_shipping" data-field-id="{{ $param->min_order_amount_free_shipping }}" v-model.number="min_order_amount_free_shipping">

<div id="isNew" data-field-id="{{$isNew}}" ></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if ($isNew == 0)
                    <h4 class="panel-heading">Product {{ $product->id_product }} wijzigen {{ $isNew }}
                @else
                    <h4 class="panel-heading">Product Toevoegen {{ $isNew }}
                @endif
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('producten') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
                       <input type="submit" class='btn btn-default btn-sm' value="Opslaan">
                    </div>
                </h4>
                <div class="panel-body panel-body-form">
                    <div class="form-group">
            			<div class="col-xs-12">
            				<div class="row">
            					<div class="col-xs-2 pull-left">
                                    <label class="control-label">Product ID</label><br>
                                    @if ($isNew == 0 && $product->id_product > 0)
			                            <input type="number" class="form-control input-sm" name="id_product" id="id_product" value="{{ $product->id_product }}" placeholder="Product id" readonly tabindex="-1">
                                    @else
                                        <input type="number" class="form-control input-sm" placeholder="Nog niet toegekend..." readonly tabindex="-1">
                                    @endif
                            	</div>
                                <div class="col-xs-2">
            						<label class="control-label">Referentie</label>
                                    <input type="text" class="form-control input-sm input-required" required autofocus name="reference" id="reference" value="{{ $product->reference }}" placeholder="Referentie">
            					</div>
            					<div class="col-xs-2">
            						<label class="control-label">Lot-nr</label>
                                    <input type="text" class="form-control input-sm" name="lot_nr" id="lot_nr" value="{{ $product->lot_nr }}" placeholder="Lot nr.">
            					</div>
            					<div class="col-xs-2">
            						<label class="control-label">EAN-13</label>
                                    <input type="text" class="form-control input-sm" name="ean13" id="ean13" value="{{ $product->ean13 }}" placeholder="ean code...">
            					</div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Creatie Datum</label>
                                    @if ($isNew == 0)
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ $product->date_add }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @else
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ date('Y-m-d H:i:s') }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @endif
                                </div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Laatste wijziging</label>
                                    <input type="text" class="form-control input-sm" name="date_upd" id="date_upd" value="{{ $product->date_upd }}" placeholder="Laatste wijziging" readonly tabindex="-1">
                				</div>
            			    </div> <!-- row -->
                        </div> <!-- colxs-12-->
                        <div class="h-line"></div>
                        <div class="form-group  col-xs-6  pull-left">
                            <div class="row">
            					<div class="col-xs-10">
            						<label class="control-label">Omschrijving</label>
                                    <input type="text" class="form-control input-sm input-required" name="name" id="name" value="{{ $product->name }}" placeholder="Omschrijving">
            					</div>
                			</div> <!-- row -->
                            <div class="row">
                                <div class="col-xs-6" >
                                    <label class="control-label">Stand. Categorie</label>
                                    <select class="form-control selectpicker show-tick show-menu-arrow" data-live-search="true" data-style="btn-default btn-sm btn-required" title="Product categorie..." name="id_category_default" id="id_category_default" required >
                                        @foreach ($categories as $category)
                                            @if($category->id_category == $product->id_category_default)
                                                <option value="{{ $category->id_category }}" selected="selected">{{ $category->name }} / {{ $category->id_category }}</option>
                                            @else
                                                <option value="{{ $category->id_category }}">{{ $category->name }} / {{ $category->id_category }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
            					<div class="col-xs-4">
            						<label class="control-label">BOL.com Fix Kost</label><br>
                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="bol_group_cost_fix" id="bol_group_cost_fix" data-field-id="{{ $product->bol_group_cost_fix }}" v-model.number.lazy="bol_group_cost_fix">
            					</div> <!-- col -->
                                <div class="col-xs-4">
            						<label class="control-label">BOL.com % Kost</label><br>
                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="bol_group_cost_procent" id="bol_group_cost_procent" data-field-id="{{ $product->bol_group_cost_procent }}" v-model.number.lazy="bol_group_cost_procent">
            					</div> <!-- col -->
                			</div> <!-- row -->
                        </div> <!-- form-group -->
                        <div class="form-group  col-xs-6  pull-right">
                            <div class="row">
                                <div class="col-xs-6 pull-right" >
                                    <label class="control-label">Leverancier</label>
                                    <select class="form-control selectpicker show-tick show-menu-arrow" data-live-search="true" data-style="btn-default btn-sm btn-required" title="Kies een Leverancier..." name="id_supplier" id="id_supplier" required >
                                        @foreach ($suppliers as $supplier)
                                            @if($supplier->id_supplier == $product->id_supplier)
                                                <option value="{{ $supplier->id_supplier }}" selected="selected">{{ $supplier->name }}</option>
                                            @else
                                                <option value="{{ $supplier->id_supplier }}">{{ $supplier->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 pull-right" >
                                    <label class="control-label">Ref. Leverancier</label>
                                    <input type="text" class="form-control input-sm" name="product_supplier_reference" id="product_supplier_reference" value="{{ $product->product_supplier_reference }}" placeholder="Ref. Leverancier">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 pull-right" >
                                    <div class="checkbox pull-right">
                                        <label>Actief in webshop?</label>  <input type="checkbox" id="toggle_presta" data-toggle="toggle" data-on="JA" data-off="NEEN" data-offstyle="danger" data-size="mini">
                                    </div>
                                    <input type="hidden" id="active" name="active" value="{{ $product->active }}">
                                    {{ $product->active }}

                                    <div class="checkbox pull-right">
                                        <label>Actief op Bol.NL?</label>  <input type="checkbox" id="toggle_bol_nl" data-toggle="toggle" data-on="JA" data-off="NEEN" data-offstyle="danger" data-size="mini">
                                    </div>
                                    <input type="hidden" id="active_bol_nl" name="active_bol_nl" value="{{ $product->active_bol_nl }}">
                                    {{ $product->active_bol_nl }}



                                </div>
                            </div>

                        </div> <!-- form-group -->
            			<div class="form-group col-xs-12">
            				<ul class="nav nav-tabs">
            					<li class="active"><a data-toggle="tab" href="#tab1">Prijzen</a></li>
            					<li><a data-toggle="tab" href="#tab2">Algemeen</a></li>
            					<li><a data-toggle="tab" href="#tab3">Voorraad</a></li>
            					<li><a data-toggle="tab" href="#tab4">Prestashop</a></li>
                                <li><a data-toggle="tab" href="#tab5">Afbeelding</a></li>

            				</ul>
                            <div class="tab-content">
                                <div id="tab1" class="tab-pane fade in active">					<!--    TAB 1 :   PRIJZEN -->
                                    <div class="row">
                                        <div class="form-group  col-xs-3  pull-left"> 					<!--    GROUP 1  :   INKOOPRIJZEN -->
                                            <h4>Inkoopprijzen</h4>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">IKP. Lev. Netto</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" name="ikp_supplier" id="ikp_supplier" data-field-id="{{ $product->ikp_supplier }}" v-model.number.lazy="ikp_supplier">
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Kosten Factor</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" name="cost_factor" id="cost_factor" data-field-id="{{ $product->cost_factor }}" v-model.number.lazy="cost_factor">
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Inkoopprijs All In CZ</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="ikp_ex_cz" id="ikp_ex_cz" data-field-id="{{ $product->ikp_ex_cz }}" v-model.number.lazy="ikp_ex_cz"  tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                        </div> <!-- form group xs-3 -->
                                        <div class="form-group  col-xs-6 pull-right">    <!--    GROUP 1  :   GROOTHANDELSPRIJZEN -->
                                            <h4>Groothandel / Dropshipping</h4>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label class="control-label">Marge dropshipping</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="margin_factor_dropshipping" id="margin_factor_dropshipping" data-field-id="{{ $product->margin_factor_dropshipping }}" v-model.number.lazy="margin_factor_dropshipping">
                                                </div>
                                                <div class="col-xs-6">
                                                    <label class="control-label">VKP. ex. Dropshipping</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_ex_dropshipping" id="vkp_ex_dropshipping" data-field-id="{{ $product->vkp_ex_dropshipping }}" v-model.number.lazy="vkp_ex_dropshipping">
                                                </div>
                                            </div> <!-- ROW -->
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label class="control-label">Marge Groothandel</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" name="margin_factor_wholesale" id="margin_factor_wholesale" data-field-id="{{ $product->margin_factor_wholesale }}" v-model.number.lazy="margin_factor_wholesale">
                                                </div>
                                                <div class="col-xs-6">
                                                    <label class="control-label">VKP. ex. Groothandel</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_ex_wholesale" id="vkp_ex_wholesale" data-field-id="{{ $product->vkp_ex_wholesale }}" v-model.number.lazy="vkp_ex_wholesale">
                                                </div>
                                            </div> <!-- ROW -->
                                        </div> <!-- form group -->
                                    </div> <!-- row -->
                                    <div class="h-line"></div>
                                    <div class="row">
                                        <div class="form-group col-xs-3 pull-left">       <!--    GROUP 1  :   COOL-ZAWADI VERKOOPPRIJZEN -->
                                            <h4>VKP Cool-Zawadi</h4>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Marge Ex. Verzending</label>
                                                    <input type="number" step="0.0001" class="form-control input-sm input-required" required name="margin_factor_cz" id="margin_factor_cz" data-field-id="{{ $product->margin_factor_cz  }}" v-model.number.lazy="margin_factor_cz">
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Marge Belgie</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="margin_factor_be_cz" id="margin_factor_be_cz" data-field-id="{{ $product->margin_factor_be_cz }}" v-model.number.lazy="margin_factor_be_cz" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Marge Nederland</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="margin_factor_nl_cz" id="margin_factor_nl_cz" data-field-id="{{ $product->margin_factor_nl_cz }}" v-model.number.lazy="margin_factor_nl_cz" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. ex. BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_cz_ex_vat" id="vkp_cz_ex_vat" data-field-id="{{ $product->vkp_cz_ex_vat }}" v-model.number.lazy="vkp_cz_ex_vat" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. incl BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_cz_in_vat" id="vkp_cz_in_vat" data-field-id="{{ $product->vkp_cz_in_vat }}" v-model.number.lazy="vkp_cz_in_vat">
                                                    <div v-if="vkp_cz_in_vat < min_order_amount_free_shipping">
                                                         + @{{ shipping_amount_cz_be_ex_btw }} ex. BTW verzending
                                                    </div>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Verzending Ex. BTW Belgie</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" id="shipping_cost_cz_be_ex_btw" data-field-id="{{ $param->shipping_cost_cz_be_ex_btw }}" v-model.number="shipping_cost_cz_be_ex_btw" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Verzending Ex. BTW Nederland</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" id="shipping_cost_cz_nl_ex_btw" data-field-id="{{ $param->shipping_cost_cz_nl_ex_btw }}" v-model.number="shipping_cost_cz_nl_ex_btw" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Winst Bedrag Belgie</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" name="netto_profit_amount_be" id="netto_profit_amount_be" data-field-id="{{ $product->netto_profit_amount_be }}" v-model.number="netto_profit_amount_be"  tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Winst Bedrag Nederland</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" name="netto_profit_amount_nl" id="netto_profit_amount_nl" data-field-id="{{ $product->netto_profit_amount_nl }}" v-model.number="netto_profit_amount_nl" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                        </div> <!-- form group -->

                                        <div class="form-group col-xs-3" >       <!--    GROUP 2  :  BOL - BELGIE VERKOOPPRIJZEN -->
                                            <h4>VKP Bol.com BE</h4>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Marge</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="margin_factor_bol_be" id="margin_factor_bol_be" data-field-id="{{ $product->margin_factor_bol_be }}" v-model.number="margin_factor_bol_be" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. ex. BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="vkp_bol_be_ex_vat" id="vkp_bol_be_ex_vat" data-field-id="{{ $product->vkp_bol_be_ex_vat }}" v-model.number="vkp_bol_be_ex_vat" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. incl BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_bol_be_in_vat" id="vkp_bol_be_in_vat" data-field-id="{{ $product->vkp_bol_be_in_vat }}" v-model.number.lazy="vkp_bol_be_in_vat" required>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Verzending Bol BE (ex. BTW)</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="shipping_cost_bol_be" id="shipping_cost_bol_be" data-field-id="{{ $param->shipping_cost_bol_be_ex_btw }}" v-model.number="shipping_cost_bol_be" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Kosten Bol BE (ex. BTW)</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="bol_be_cost" id="bol_be_cost" data-field-id="{{ $product->bol_be_cost }}" v-model.number="bol_be_cost"  tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Winst Bedrag</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="netto_profit_amount_bol_be" id="netto_profit_amount_bol_be" data-field-id="{{ $product->netto_profit_amount_bol_be }}" v-model.number="netto_profit_amount_bol_be"  tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                        </div> <!-- form group -->
                                        <div class="form-group col-xs-3">       <!--    GROUP 2  :  BOL - NEDERLAND VERKOOPPRIJZEN -->
                                            <h4>VKP Bol.com NL</h4>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Marge</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="margin_factor_bol_nl" id="margin_factor_bol_nl" data-field-id="{{ $product->margin_factor_bol_nl }}" v-model.number="margin_factor_bol_nl" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. ex. BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="vkp_bol_nl_ex_vat" id="vkp_bol_nl_ex_vat" data-field-id="{{ $product->vkp_bol_nl_ex_vat }}" v-model.number="vkp_bol_nl_ex_vat" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">VKP. incl BTW</label>
                                                    <input type="number" step="0.01" class="form-control input-sm input-required" required name="vkp_bol_nl_in_vat" id="vkp_bol_nl_in_vat" data-field-id="{{ $product->vkp_bol_nl_in_vat  }}" v-model.number.lazy="vkp_bol_nl_in_vat" required>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Verzending Bol NL</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="shipping_cost_bol_nl" id="shipping_cost_bol_nl" data-field-id="{{ $param->shipping_cost_bol_nl_ex_btw }}" v-model.number="shipping_cost_bol_nl" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Kosten Bol NL</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="bol_nl_cost" id="bol_nl_cost" data-field-id="{{ $product->bol_nl_cost }}" v-model.number="bol_nl_cost" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="control-label">Winst Bedrag</label>
                                                    <input type="number" step="0.01" class="form-control input-sm" name="netto_profit_amount_bol_nl" id="netto_profit_amount_bol_nl" data-field-id="{{ $product->netto_profit_amount_bol_nl }}" v-model.number="netto_profit_amount_bol_nl" tabindex="-1" readonly>
                                                </div> <!-- class -->
                                            </div> <!-- row -->
                                        </div> <!-- form group -->
                                    </div> <!-- ROW -->
                                </div> <!-- tab1 -->
                                <div id="tab2" class="tab-pane fade">               <!-- TAB 2 : ALGEMEEN -->
            						<div class="row">
            							<div class="form-group  col-xs-12">
            								<h3>Algemene instellingen</h3>
            								<div class="row">
            									<div class="col-xs-2">
            										<label class="control-label">BTW %</label>
                                                    @if ($isNew == 0)
                                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="vat_procent" id="vat_procent" data-field-id="{{ $product->vat_procent }}" v-model="vat_procent">
                                                    @else
                                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="vat_procent" id="vat_procent" data-field-id="{{ $param->stand_vat_procent }}" v-model="vat_procent">
                                                    @endif
                                                </div> <!-- class -->
            								</div> <!-- row -->
            							</div> <!-- form group -->
            						</div> <!-- row -->
            					</div> <!-- tab 2 algmeen -->
            					<div id="tab3" class="tab-pane fade">               <!-- TAB 3 : VOORRAAD -->
            						<div class="row">
                                        <div class="form-group  col-xs-3">
                                            <label class="control-label">Voorraad (NIET MANUEEL WIJZIGEN AUB !!!)</label>
                                            @if ($isNew == 0)
                                                <input type="number" step="1" class="form-control input-sm" name="quantity_in_stock" id="quantity_in_stock" value="{{ $product->quantity_in_stock }}" tabindex="-1" readonly>
                                            @else
                                                <input type="number" step="1" class="form-control input-sm" name="quantity_in_stock" id="quantity_in_stock" value="{{ $product->quantity_in_stock }}">
                                            @endif
                                        </div> <!-- class -->
            						</div> <!-- row -->
                                    <div class="row">
                                        <div class="form-group  col-xs-3">
                                            <label class="control-label">Te faktureren</label>
                                            <input type="number" step="1" class="form-control input-sm" name="quantity_to_invoice" id="quantity_to_invoice" value="{{ $product->quantity_to_invoice }}">
                                        </div> <!-- class -->
                                    </div> <!-- row -->

            					</div> <!-- tab 3 voorraad -->

                                <div id="tab4" class="tab-pane fade in">					<!--    TAB 4 : PRESTASHOP -->
                                    <div class="row">
                                        <br>
                                        <div class="form-group  col-xs-12">
                                            <label class="control-label">Omschrijving op de Webshop (NL)</label>
                                            <textarea class="form-control" id="descr_short_nl" name="descr_short_nl">{{ $product->descr_short_nl }}</textarea>
                                        </div>   <!-- form-group -->
                                    </div>   <!-- row -->
                                    <div class="row">
                                        <br>
                                        <div class="form-group  col-xs-6">
                                            <label class="control-label">Vriendelijke URL</label>
                                            <input type="text" class="form-control input-sm" name="link_rewrite_nl" id="link_rewrite_nl" value="{{ $product->link_rewrite_nl }}" placeholder="link rewrite...">
                                        </div>   <!-- form-group -->
                                    </div>   <!-- row -->
                                    <div class="row">
                                        <div class="form-group  col-xs-6">
                                            <label class="control-label">Meta beschrijving</label>
                                            <input type="text" class="form-control input-sm" name="meta_descr_nl" id="meta_descr_nl" value="{{ $product->meta_descr_nl }}" placeholder="Meta beschrijving...">
                                        </div>   <!-- form-group -->
                                    </div>   <!-- row -->
                                    <div class="row">
                                        <div class="form-group  col-xs-6">
                                            <label class="control-label">Meta titel</label>
                                            <input type="text" class="form-control input-sm" name="meta_title_nl" id="meta_title_nl" value="{{ $product->meta_title_nl }}" placeholder="Meta titel...">
                                        </div>   <!-- form-group -->
                                    </div>   <!-- row -->
                                </div> <!-- Tab 1 -->
            					<div id="tab5" class="tab-pane fade in">					<!--    TAB 5 :  AFBEELDING  -->
                                    <div class="row">
                                        @if($imagePath == "")
                                            <center><img src="http://www.cool-zawadi.com/img/no_image.png" alt="product Image" id="image"></center>
                                        @else
                                            <center>Path naar afbeelding : {{ $imagePath }}</center>
                                            <center><img src="{{ $imagePath }}" alt="product Image" id="image"></center>
                                        @endif
                                    </div>
                            	</div>	<!-- tab 4 Afbeelding ---->
            				</div> <!-- tab content -->
                        </div>
            		</div> <!-- form group -->
                </div>
            </div>
        </div>
    </div>

@include('partials.footer')
</div> <!-- container -->
</form>
</div> <!-- Vue productApp -->

<script src="/js/vue.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">

var isNew = $('#isNew').data("field-id");

// Fire change event when opening form to trigger vue.js computed properties
$(document).ready(function() {
    var element = document.getElementById('ikp_supplier');
    var event = new Event('change');
    element.dispatchEvent(event);
    var activePrestaSwitch = $('#active').val();
    if(activePrestaSwitch !=0){
        $('#toggle_presta').bootstrapToggle('on');
    }else{
        $('#toggle_presta').bootstrapToggle('off')
    }
    var activeBolNlSwitch = $('#active_bol_nl').val();
    if(activeBolNlSwitch !=0){
        $('#toggle_bol_nl').bootstrapToggle('on');
    }else{
        $('#toggle_bol_nl').bootstrapToggle('off')
    }
});

// Init TInymce on textarea fields
tinymce.init({
    selector:'textarea',
    menubar: false,
    height: 200,
    theme:'modern',
    toolbar: [
        'bold italic underline strikethrough alignleft aligncenter alignright alignjustify',
        'cut copy paste bullist numlist outdent indent blockquote undo redo removeformat subscript superscript',
        'styleselect formatselect fontselect fontsizeselect',
    ],

 });
// Switch prestashop
$(function() {
    $('#toggle_presta').change(function() {
        if($(this).prop('checked')){
            $("#active").val("1");
        }else{
            $("#active").val("0");
        }
    })
 })

// Switch Bol_NL
$(function() {
    $('#toggle_bol_nl').change(function() {
        if($(this).prop('checked'))
        {
            $("#active_bol_nl").val("1");
        }
        else
        {
            $("#active_bol_nl").val("0");
        }
    })
 })




 // link rewrite
$(function(){
 $('#name').focusout(function() {
     if (isNew){
         var link_name = document.getElementById('name').value;
         var link_rewrite = (link_name.split(' ').join('-')).toLowerCase();
         $('#link_rewrite_nl').val(link_rewrite);
         $('#meta_descr_nl').val(link_name);
         $('#meta_title_nl').val(link_name);
    }
})
})





var productApp = new Vue({
  el: '#productApp',

  data: {
      vat_procent: $('#vat_procent').data("field-id"),
      ikp_supplier: $('#ikp_supplier').data("field-id"),
      cost_factor: $('#cost_factor').data("field-id"),
      ikp_ex_cz: $('#ikp_ex_cz').data("field-id"),
      margin_factor_dropshipping: $('#margin_factor_dropshipping').data("field-id"),
      vkp_ex_dropshipping: $('#vkp_ex_dropshipping').data("field-id"),
      margin_factor_wholesale: $('#margin_factor_wholesale').data("field-id"),
      vkp_ex_wholesale: $('#vkp_ex_wholesale').data("field-id"),
      margin_factor_cz: $('#margin_factor_cz').data("field-id"),
      margin_factor_be_cz: $('#margin_factor_be_cz').data("field-id"),
      margin_factor_nl_cz: $('#margin_factor_nl_cz').data("field-id"),
      vkp_cz_ex_vat: Math.round(($('#vkp_cz_ex_vat').data("field-id"))*100)/100,
      vkp_cz_in_vat: $('#vkp_cz_in_vat').data("field-id"),
      shipping_cost_cz_be_ex_btw: $('#shipping_cost_cz_be_ex_btw').data("field-id"),
      shipping_cost_cz_nl_ex_btw: $('#shipping_cost_cz_nl_ex_btw').data("field-id"),
      shipping_cost_cz_be_in_param: $('#shipping_cost_cz_be_in_param').data("field-id"),
      shipping_cost_cz_nl_in_param: $('#shipping_cost_cz_nl_in_param').data("field-id"),
      shipping_amount_cz_be_ex_btw: $('#shipping_amount_cz_be_ex_btw').data("field-id"),
      netto_profit_amount_be: $('#netto_profit_amount_be').data("field-id"),
      netto_profit_amount_nl: $('#netto_profit_amount_nl').data("field-id"),
      min_order_amount_free_shipping: $('#min_order_amount_free_shipping').data("field-id"),
      margin_factor_bol_be: $('#margin_factor_bol_be').data("field-id"),
      vkp_bol_be_ex_vat: $('#vkp_bol_be_ex_vat').data("field-id"),
      vkp_bol_be_in_vat: Math.round(($('#vkp_bol_be_in_vat').data("field-id"))*100)/100,
      shipping_cost_bol_be: $('#shipping_cost_bol_be').data("field-id"),
      bol_be_cost: $('#bol_be_cost').data("field-id"),
      netto_profit_amount_bol_be: $('#netto_profit_amount_bol_be').data("field-id"),
      margin_factor_bol_nl: $('#margin_factor_bol_nl').data("field-id"),
      vkp_bol_nl_ex_vat: $('#vkp_bol_nl_ex_vat').data("field-id"),
      vkp_bol_nl_in_vat: Math.round(($('#vkp_bol_nl_in_vat').data("field-id"))*100)/100,
      shipping_cost_bol_nl: $('#shipping_cost_bol_nl').data("field-id"),
      bol_nl_cost: $('#bol_nl_cost').data("field-id"),
      netto_profit_amount_bol_nl: $('#netto_profit_amount_bol_nl').data("field-id"),
      bol_group_cost_procent: $('#bol_group_cost_procent').data("field-id"),
      bol_group_cost_fix: $('#bol_group_cost_fix').data("field-id")
  },
  computed: {
    ikp_ex_cz: function() {
            return Math.round((this.ikp_supplier * this.cost_factor)*100)/100
    },
    vkp_ex_dropshipping: {
        get: function () {
          return Math.round((this.ikp_ex_cz * this.margin_factor_dropshipping)*100)/100
        },
        set: function (newValue) {
            this.margin_factor_dropshipping = Math.round((newValue / this.ikp_ex_cz)*100)/100
        }
    },
    vkp_ex_wholesale: {
        get: function () {
          return Math.round((this.ikp_ex_cz * this.margin_factor_wholesale)*100)/100
        },
        set: function (newValue) {
            this.margin_factor_wholesale = Math.round((newValue / this.ikp_ex_cz)*100)/100
        }
    },
    margin_factor_cz: {
        get: function() {
            newValue =  Math.round((this.vkp_cz_ex_vat / this.ikp_ex_cz)*10000)/10000
            return newValue
        },
        set: function(newValue) {
            this.vkp_cz_ex_vat = Math.round((this.ikp_ex_cz * newValue)*100)/100
        }
    },
    margin_factor_be_cz: function(){
        newValue = Math.round(((this.vkp_cz_ex_vat - this.shipping_cost_cz_be_ex_btw) / this.ikp_ex_cz)*100)/100
        if( newValue > 1){
            $('#margin_factor_be_cz').css("background-color","#90EE90");
        }else{
            $('#margin_factor_be_cz').css("background-color","#FFB6C1");

        }
        return newValue
    },
    margin_factor_nl_cz: function(){
        newValue = Math.round(((this.vkp_cz_ex_vat - this.shipping_cost_cz_nl_ex_btw) / this.ikp_ex_cz)*100)/100
        if( newValue > 1){
            $('#margin_factor_nl_cz').css("background-color","#90EE90");
        }else{
            $('#margin_factor_nl_cz').css("background-color","#FFB6C1");
        }
        return newValue
    },
    vkp_cz_in_vat: {
        get: function() {
            return Math.round((((Number(this.vkp_cz_ex_vat)/100)* Number(this.vat_procent))+Number(this.vkp_cz_ex_vat))*100)/100
        },
        set: function(newValue) {
            this.vkp_cz_ex_vat=Math.round((newValue / ((this.vat_procent/100)+1))*100)/100
        }
    },
    shipping_cost_cz_be_ex_btw: function(){
        if(this.vkp_cz_in_vat < this.min_order_amount_free_shipping){
            return (Math.round((this.shipping_cost_cz_be_in_param - this.shipping_amount_cz_be_ex_btw)*100)/100)
        }else{
            return (Math.round((this.shipping_cost_cz_be_in_param)*100)/100)
        }
    },
    shipping_cost_cz_nl_ex_btw: function(){
        if(this.vkp_cz_in_vat < this.min_order_amount_free_shipping){
            return (Math.round((this.shipping_cost_cz_nl_in_param - this.shipping_amount_cz_be_ex_btw)*100)/100)
        }else{
            return (Math.round((this.shipping_cost_cz_nl_in_param)*100)/100)
        }
    },
    netto_profit_amount_be: function(){
        newValue = (Math.round((this.vkp_cz_ex_vat- this.shipping_cost_cz_be_ex_btw-this.ikp_ex_cz)*100)/100)
        if( newValue > 0){
            $('#netto_profit_amount_be').css("background-color","#90EE90");
        }else{
            $('#netto_profit_amount_be').css("background-color","#FFB6C1");
        }
        return newValue
    },
    netto_profit_amount_nl: function(){
        newValue = (Math.round((this.vkp_cz_ex_vat- this.shipping_cost_cz_nl_ex_btw-this.ikp_ex_cz)*100)/100)
        if( newValue > 0){
            $('#netto_profit_amount_nl').css("background-color","#90EE90");
        }else{
            $('#netto_profit_amount_nl').css("background-color","#FFB6C1");
        }
        return newValue
    },
    vkp_bol_be_ex_vat: function(){
        return (Math.round((this.vkp_bol_be_in_vat / ((this.vat_procent/100)+1))*100)/100)
    },
    bol_be_cost: function(){

        fix_cost_ex_btw = (this.bol_group_cost_fix / ((this.vat_procent/100)+1))
        newValue = (Math.round(((this.bol_group_cost_procent / 100 * this.vkp_bol_be_ex_vat)+ fix_cost_ex_btw)*100)/100)
        if((!this.bol_group_cost_fix ||  !this.bol_group_cost_procent) && (this.vkp_bol_be_in_vat > 0)){
            toastr.error('U dient De Bol kosten nog in te vullen !!!!')
            $('#bol_be_cost').css("background-color","#FFB6C1");
        }else{
            $('#bol_be_cost').css("background-color","#90EE90");

        }

        return newValue
    },
    netto_profit_amount_bol_be: function(){
        newValue =  (Math.round((this.vkp_bol_be_ex_vat - this.ikp_ex_cz - this.bol_be_cost - this.shipping_cost_bol_be)*100)/100)
        if( newValue > 0){
            $('#netto_profit_amount_bol_be').css("background-color","#90EE90");
        }else{
            $('#netto_profit_amount_bol_be').css("background-color","#FFB6C1");
        }
        return newValue
    },
    margin_factor_bol_be: function() {
        newValue = (Math.round((this.vkp_bol_be_ex_vat / (this.ikp_ex_cz + this.bol_be_cost + (Number(this.shipping_cost_bol_be))))*100)/100)
        if( newValue > 1){
            $('#margin_factor_bol_be').css("background-color","#90EE90");
        }else{
            $('#margin_factor_bol_be').css("background-color","#FFB6C1");
        }
        return newValue
    },
    vkp_bol_nl_ex_vat: function(){
        return (Math.round((this.vkp_bol_nl_in_vat / ((this.vat_procent/100)+1))*100)/100)
    },
    bol_nl_cost: function(){
        fix_cost_ex_btw = (this.bol_group_cost_fix / ((this.vat_procent/100)+1))
        newValue = (Math.round(((this.bol_group_cost_procent / 100 * this.vkp_bol_nl_ex_vat)+ fix_cost_ex_btw)*100)/100)
        return newValue
    },
    netto_profit_amount_bol_nl: function(){
        newValue =  (Math.round((this.vkp_bol_nl_ex_vat - this.ikp_ex_cz - this.bol_nl_cost - this.shipping_cost_bol_nl)*100)/100)
        if( newValue > 0){
            $('#netto_profit_amount_bol_nl').css("background-color","#90EE90");
        }else{
            $('#netto_profit_amount_bol_nl').css("background-color","#FFB6C1");
        }
        return newValue
    },
    margin_factor_bol_nl: function() {
        newValue = (Math.round((this.vkp_bol_nl_ex_vat / (this.ikp_ex_cz + this.bol_nl_cost + (Number(this.shipping_cost_bol_nl))))*100)/100)
        if( newValue > 1){
            $('#margin_factor_bol_nl').css("background-color","#90EE90");
        }else{
            $('#margin_factor_bol_nl').css("background-color","#FFB6C1");
        }
        return newValue
    }
  }
})


</script>
@endsection
