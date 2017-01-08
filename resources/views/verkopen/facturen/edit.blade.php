@extends('layouts.app')
@section('content')
<div id="custInvoiceApp">
    <div id="isNew" data-field-id="{{$isNew}}" ></div>

    @if ($isNew == 0)
        <form action="/verkopen/facturen/save/{{ $invoice->id_cust_invoice }}" name="custInvoiceForm" id="custInvoiceForm" method="post">
    @else
        <form action="/verkopen/facturen/save/0" name="custInvoiceForm" id="custInvoiceForm" method="post">
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if ($isNew == 0)
        <input type="hidden" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}">
    @endif
    <input type="hidden"  id="stand_vat_procent" value="{{ $param->stand_vat_procent }}">
    <input type="hidden"  id="shipping_cost_cz_be_ex_btw" value="{{ $param->shipping_cost_cz_be_ex_btw }}">
    <input type="hidden"  id="shipping_cost_cz_nl_ex_btw" value="{{ $param->shipping_cost_cz_nl_ex_btw }}">
    <input type="hidden"  id="min_order_amount_free_shipping" value="{{ $param->min_order_amount_free_shipping }}">
    <input type="hidden"  id="shipping_amount_cz_be_ex_btw" value="{{ $param->shipping_amount_cz_be_ex_btw }}">
    <input type="hidden"  id="shipping_cost_bol_be_ex_btw" value="{{ $param->shipping_cost_bol_be_ex_btw }}">
    <input type="hidden"  id="shipping_cost_bol_nl_ex_btw" value="{{ $param->shipping_cost_bol_nl_ex_btw }}">


    <div id="isNew" data-field-id="{{$isNew}}" ></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    @if ($isNew == 0)
                        <h4 class="panel-heading">Factuur {{ $invoice->id_cust_invoice }} wijzigen {{ $isNew }}
                    @else
                        <h4 class="panel-heading">Factuur Toevoegen {{ $isNew }}
                    @endif
                        <div class="btn-group btn-titlebar pull-right">
                        <a href="{{ URL::to('/verkopen/facturen/print/'.$invoice->id_cust_invoice)}}" type="button" class='btn btn-default btn-sm'>Print</a>
                        <a href="{{ URL::to('/verkopen/facturen') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
                        <input type="submit" class='btn btn-default btn-sm' value="Opslaan">
                        </div>
                    </h4>
                    <div class="panel-body panel-body-form">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <label class="control-label">Factuurnr</label>
                                        @if ($isNew == 0)
                                            <input type="number" class="form-control input-sm" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}" readonly tabindex="-1">
                                        @else
                                            <input type="text" class="form-control input-sm" name="id_cust_invoice" id="id_cust_invoice" value="Nog niet toegekend" readonly tabindex="-1">
                                        @endif                              
                                    </div>
                                    <div class="col-xs-2 pull-right">
                                        <label class="control-label">Factuur Datum</label>
                                        @if ($isNew == 0)
                                            <input type="text" class="form-control input-sm" name="invoice_date" id="invoice_date" value="{{ $invoice->invoice_date }}" readonly tabindex="-1">
                                        @else
                                            <input type="text" class="form-control input-sm" name="invoice_date" id="invoice_date" value="{{  date('Y-m-d H:i:s') }}" readonly tabindex="-1">
                                        @endif          
                                    </div>
                                    <div class="col-xs-2 pull-right">
                                        <label class="control-label">Order Datum</label>
                                        @if ($isNew == 0)
                                            <input type="text" class="form-control input-sm" name="order_date" id="order_date" value="{{ $invoice->order_date }}" readonly tabindex="-1">
                                        @else
                                            <input type="date" class="form-control input-sm" name="order_date" id="order_date" value="{{ date('Y-m-d H:i:s') }}" placeholder="Order datum...">
                                        @endif                      
                                    </div>
                                </div> <!-- row -->
                            </div> <!-- class xs-12 -->  
                            <div class="h-line"></div>   
                            <div class="form-group">                        
                                <div class="col-xs-6">                  
                                    <div class="row">
                                        <div class="col-xs-4">                                                                 
                                            <label class="control-label">Klant</label>
                                            @if ($isNew == 1)
                                                <select class="form-control selectpicker show-tick show-menu-arrow customerSelector" data-live-search="true" data-style="btn-default btn-sm btn-required" title="klant..." name="id_customer" id="id_customer" required >
                                                    @foreach ($customers as $customer)
                                                        @if($customer->id_customer == $invoice->id_customer)
                                                            <option 
                                                                value="{{ $customer->id_customer }}" selected="selected">{{ $customer->firstname }} {{ $customer->lastname }} / {{ $customer->id_customer }}</option>
                                                        @else
                                                            <option
                                                                value="{{ $customer->id_customer }}">{{ $customer->firstname }} {{ $customer->lastname }} / {{ $customer->id_customer }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control input-sm" name="id_customer" id="id_customer" value="{{ $invoice->customer_first_name }} {{ $invoice->customer_name }} / {{ $invoice->id_customer }}" readonly tabindex="-1">
                                            @endif
                                            
                                        </div>
                                        @if ($isNew == 0)
                                            <div class="col-xs-4">
                                                Factuur Adres :<br>
                                                <b>{{ $invoice->company }}</b><br> 
                                                <b>{{ $invoice->customer_street_nr }}</b><br> 
                                                <b>{{ $invoice->customer_postal_code}} {{ $invoice->customer_city }} {{ $invoice->customer_country }}</b><br>
                                                <b>{{ $invoice->customer_vat_number}} </b><br>
                                            </div>
                                        @endif   
                                    </div> <!--row -->  
                                    <div class="row">
                                        <div class="col-xs-4">                                                                 
                                            <label class="control-label">Type Factuur</label>
                                            <select class="form-control selectpicker show-tick show-menu-arrow invoiceSelector" data-style="btn-default btn-sm btn-required" title="Type..." required >
                                            @if ($invoice->invoice_type == 1)
                                                <option value="1" selected="selected">Manuele Input</option>
                                                <option value="3">Bol-BE</option>
                                                <option value="5">Bol-NL</option>
                                                <option value="4">Via CZ-BE</option>
                                                <option value="6">Via CZ-NL</option>
                                            @elseif($invoice->invoice_type == 3)
                                                <option value="1">Manuele Input</option>
                                                <option value="3" selected="selected">Bol-BE</option>
                                                <option value="5">Bol-NL</option>
                                                <option value="4">Via CZ-BE</option>
                                                <option value="6">Via CZ-NL</option>
                                            @elseif($invoice->invoice_type == 5)
                                                <option value="1">Manuele Input</option>
                                                <option value="3">Bol-BE</option>
                                                <option value="5" selected="selected">Bol-NL</option>
                                                <option value="4">Via CZ-BE</option>
                                                <option value="6">Via CZ-NL</option>
                                            @elseif($invoice->invoice_type == 4)
                                                <option value="1">Manuele Input</option>
                                                <option value="3">Bol-BE</option>
                                                <option value="5">Bol-NL</option>
                                                <option value="4" selected="selected">Via CZ-BE</option>
                                                <option value="6">Via CZ-NL</option>
                                            @elseif($invoice->invoice_type == 6)
                                                <option value="1">Manuele Input</option>
                                                <option value="3">Bol-BE</option>
                                                <option value="5">Bol-NL</option>
                                                <option value="4">Via CZ-BE</option>
                                                <option value="6" selected="selected">Via CZ-NL</option>
                                            @else
                                                <option value="1">Manuele Input</option>
                                                <option value="3">Bol-BE</option>
                                                <option value="5">Bol-NL</option>
                                                <option value="4">Via CZ-BE</option>
                                                <option value="6">Via CZ-NL</option>
                                            @endif
                                            </select>
                                        </div>
                                    </div> <!-- row -->
                
                                </div> <!-- class xs 6 -->
                                <div class="col-xs-2 pull-right">
                                    <label class="control-label">CZ Ordernr</label>
                                    <input type="number" class="form-control input-sm" name="id_cust_order" id="id_cust_order" value="{{ $invoice->id_cust_order }}">
                                    <label class="control-label">Bol Ordernr</label>
                                    <input type="number" class="form-control input-sm" name="ordernr_bol" id="ordernr_bol" value="{{ $invoice->ordernr_bol }}"><br>
                                </div> <!-- class xs 6 -->
                            </div>
                        <!-- Faktuur Regels -->
                            <div class="form-group">
                                <div class="invoiceDetail">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="table" class="table-editable">
                                                <table name="invoiceTable" id="invoiceTable" class="table">
                                                    <tr> 
                                                        <th><button type="button" id="addRow"  name="addRow" class="table-add pull-left">+</button></th>
                                                        <th>Ref. / Omschrijving</th>
                                                        <th>Aantal</th>
                                                        <th>Eenh.Prijs</th>
                                                        <th>Tot. Ex. BTW</th>
                                                        <th>Tot. Incl BTW</th>
                                                        <th>Acties</th>
                                                    </tr>
                                                    @if($invoiceDetails)
                                                
                                                        <?php $rowCount = 1; ?>
                                                        @foreach($invoiceDetails as $invoiceDetail)
                                                            <tr class="original" id="row{{$rowCount}}"> 
                                                                <td class="rowNumber">{{ $rowCount}}</td>
                                                                <td class="col-xs-5">
                                                                    <div class="form-group">
                                                                        <select class="selectpicker productSelector show-tick show-menu-arrow form-control" data-live-search="true" data-style="btn-default btn-sm" name="id_product[]" id="{{$rowCount}}">
                                                                            @foreach ($products as $product)
                                                                                @if($product->id_product == $invoiceDetail->id_product)
                                                                                    <option data-cz-price="{{$product->vkp_cz_ex_vat}}"
                                                                                            data-bolbe-price="{{$product->vkp_bol_be_ex_vat}}"
                                                                                            data-bolnl-price="{{$product->vkp_bol_nl_ex_vat}}"
                                                                                            data-vat="{{$product->vat_procent}}"
                                                                                            data-bol-fix-cost="{{$product->bol_group_cost_fix}}"
                                                                                            data-bol-procent-cost="{{$product->bol_group_cost_procent}}" selected="selected">{{ $product->id_product }} / {{$product->name }}</option>
                                                                                @else
                                                                                    <option data-cz-price="{{$product->vkp_cz_ex_vat}}" 
                                                                                            data-bolbe-price="{{$product->vkp_bol_be_ex_vat}}"
                                                                                            data-bolnl-price="{{$product->vkp_bol_nl_ex_vat}}"
                                                                                            data-vat="{{$product->vat_procent}}"
                                                                                            data-bol-fix-cost="{{$product->bol_group_cost_fix}}"
                                                                                            data-bol-procent-cost="{{$product->bol_group_cost_procent}}">{{ $product->id_product }} / {{ $product->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>

                                                                <td class="input-sm col-xs-1"><input type="number" class="form-control input-sm quantity" name="quantity[]" id="quantity{{$rowCount}}" value="{{ $invoiceDetail->quantity }}"></td>
                                                                <td class="input-sm col-xs-1"><input type="number" step="0.01" class="form-control input-sm unitPrice" name="unitPrice[]" id="unitPrice{{$rowCount}}" value="{{ round($invoiceDetail->product_unit_price_ex_vat,2) }}"></td>
                                                                <td class="input-sm col-xs-2"><input type="number" class="form-control input-sm rowPriceEx" name="rowPriceEx[]" id="rowPriceEx{{$rowCount}}" value="{{ round($invoiceDetail->product_total_price_ex_vat,2) }}" readonly tabindex="-1"></td>
                                                                <td class="input-sm col-xs-2"><input type="number" class="form-control input-sm rowPriceIncl" name="rowPriceIncl[]" id="rowPriceIncl{{$rowCount}}" value="{{ round($invoiceDetail->product_total_price_incl_vat,2) }}" readonly tabindex="-1"></td>

                                                                <input type="hidden" class="ikPrice" name="ikPrice[]" id="ikPrice{{$rowCount}}"></td>
                                                                <input type="hidden" class="rowIkPrice" name="rowIkPrice[]" id="rowIkPrice{{$rowCount}}"></td>
                                                                <input type="hidden" class="bolFixCost" name="bolFixCost[]" id="bolFixCost{{$rowCount}}"></td>
                                                                <input type="hidden" class="bolProcentCost" name="bolProcentCost[]" id="bolProcentCost{{$rowCount}}"></td>
                                                                <input type="hidden" class="rowBolCost" name="rowBolcost[]" id="rowBolCost{{$rowCount}}"></td>

                                                                <td class="input-sm col-xs-1">
                                                                    <button type="button" class="delRow glyphicon glyphicon-remove pull-right"></button>
                                                                </td>
                                                            <tr>
                                                            <?php $rowCount++; ?> 
                                                            <input type="hidden" name="rowCount" id="rowCount" value="{{ $rowCount }}">
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="export-btn" class="btn btn-primary">Export Data</button>
                            <p id="export"></p>


                        <!-- FACTUUR VOET -->
                            <div class="h-line"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">BTW % Verz / Verp.</label>
                                        <input type="number"  step="0.01" class="form-control input-sm" name="total_shipping_btw_procent" id="total_shipping_btw_procent" value="{{ round($invoice->total_shipping_btw_procent,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Totaal IKP ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_ikp_cz_exl_btw" id="total_ikp_cz_exl_btw" value="{{ round($invoice->total_ikp_cz_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Producten ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_products_exl_btw " id="total_products_exl_btw" value="{{ round($invoice->total_products_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Verzending Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_shipping_exl_btw" id="total_shipping_exl_btw" value="{{ round($invoice->total_shipping_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Kost. Verzending Ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm total_shipping_cost_exl_btw" name="total_shipping_cost_exl_btw" id="total_shipping_cost_exl_btw" value="{{ round($invoice->total_shipping_cost_exl_btw,2) }}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Producten Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_products_incl_btw" id="total_products_incl_btw" value="{{ round($invoice->total_products_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Verzending Incl. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm total_shipping_incl_btw" name="total_shipping_incl_btw" id="total_shipping_incl_btw" value="{{ round($invoice->total_shipping_incl_btw,2) }}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Kost. Geschenkverp. Ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm total_wrapping_cost_exl_btw" name="total_wrapping_cost_exl_btw" id="total_wrapping_cost_exl_btw" value="{{ round($invoice->total_wrapping_cost_ex_btw,2) }}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Factuur Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_invoice_exl_btw" id="total_invoice_exl_btw" value="{{ round($invoice->total_invoice_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Geschenkverp. Ex. BTW</label>
                                        <input type="number" class="form-control input-sm total_wrapping_exl_btw" name="total_wrapping_exl_btw" id="total_wrapping_exl_btw" value="{{ round($invoice->total_wrapping_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label" id="label_bol_costs">Kosten BOL</label>
                                        <input type="number" step="0.01" class="form-control input-sm total_costs_bol_exl_btw" name="total_costs_bol_exl_btw" id="total_costs_bol_exl_btw" value="{{ round($invoice->total_costs_bol_exl_btw,2) }}">
                                        </div>
                                    </div>


                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Factuur Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_invoice_incl_btw" id="total_invoice_incl_btw" value="{{ round($invoice->total_invoice_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Geschenkverp. Incl. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm total_wrapping_incl_btw" name="total_wrapping_incl_btw" id="total_wrapping_incl_btw" value="{{ round($invoice->total_wrapping_incl_btw,2) }}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Netto Marge Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="netto_margin_ex_btw" id="netto_margin_ex_btw" value="{{ round($invoice->netto_margin_ex_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Totaal Betaald</label>
                                        <input type="number" step="0.01" class="form-control input-sm" name="total_paid" id="total_paid" value="{{ round($invoice->total_paid,2) }}">
                                        </div>
                                    </div> <!-- col-xs-4 -->
                                </div> <!-- Row -->
                            </div> <!-- form group -->
                        </div> <!-- form-group-->
                    </div> <!-- panel-body -->
                </div> <!-- panel-default -->
            </div> <!-- class col-md-12 -->
        </div> <!-- row -->
    </div> <!-- container-fluid -->
    @include('partials.footer')
</div> <!-- App -->

<script type="text/javascript" charset="utf-8">
function r22(num) {    
    return +(Math.round(num + "e+2")  + "e-2");
};

function getTotalProductsExVat() {
    var totalProductsExVat = 0;
    $('.rowPriceEx').each(function(){
        var rowPriceEx = r22($(this).val());
        totalProductsExVat = r22(totalProductsExVat + rowPriceEx);
    });
    return(totalProductsExVat);
};

function getTotalProductsInVat() {
    var totalProductsInVat = 0;
    $('.rowPriceIncl').each(function(){
        var rowPriceIncl= r22($(this).val());
        totalProductsInVat = r22(totalProductsInVat + rowPriceIncl);
    });
    return(totalProductsInVat);
};

function getTotalIkPrice() {
    var totalIkPrice = 0;
    $('.rowIkPrice').each(function(){
        var rowIkPrice= r22($(this).val());
        totalIkPrice = r22(totalIkPrice + rowIkPrice);
    });
    return(totalIkPrice);
};

function getTotalBolCost() {
    var totalBolCost = 0;
    $('.rowBolCost').each(function(){
        var rowBolCost = r22($(this).val());
        console.log(rowBolCost);
        totalBolCost = r22(totalBolCost + rowBolCost);       
    });
    console.log(totalBolCost);
    return(totalBolCost);
};



$(document).ready(function() {
    var invoiceType = 0
    var standVatProcent = Number($('#stand_vat_procent').val());
    $('#total_shipping_btw_procent').val(standVatProcent);
    $("#total_costs_bol_exl_btw").hide();
    $("#label_bol_costs").hide();


    var shippingCostCzBeExVat = Number($('#shipping_cost_cz_be_ex_btw').val());
    var shippingCostCzNlExVat = Number($('#shipping_cost_cz_nl_ex_btw').val());
    var minOrderAmountFreeShipping = Number($('#min_order_amount_free_shipping').val());
    var shippingAmountCzExVat = Number($('#shipping_amount_cz_be_ex_btw').val());
    var shipping_cost_bol_be_ex_btw = Number($('#shipping_cost_bol_be_ex_btw').val());
    var shipping_cost_bol_nl_ex_btw = Number($('#shipping_cost_bol_nl_ex_btw').val());
    var wrappingAmountExVat = Number($('#total_wrapping_exl_btw').val());

    $('#addRow').click(function(){
        var invoiceType = Number($('.invoiceSelector').find(":selected").val());
        if(invoiceType == 0){
            toastr["error"]("Eerst Factuur Type invullen aub !!");
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();
            return false;
        };
        rowNumber = Number(($('.rowNumber').length)+1);
        var row = 
            '<tr id="row'+ rowNumber +'">' + 
                '<td class="rowNumber">'+ rowNumber + '</td>'+
                '<td class="col-xs-5">'+
                    '<div class="form-group">' +
                        '<select class="selectpicker productSelector show-tick show-menu-arrow form-control" data-live-search="true" data-style="btn-default btn-sm" title="product..." name="id_product[]" id="'+ rowNumber +'"">' +
                            '@foreach ($products as $product)' +
                                '<option data-cz-price="{{$product->vkp_cz_ex_vat}}" data-bolbe-price="{{$product->vkp_bol_be_ex_vat}}" data-bolnl-price="{{$product->vkp_bol_nl_ex_vat}}" data-ik-price="{{$product->ikp_ex_cz}}"  data-bol-fix-cost="{{$product->bol_group_cost_fix}}" data-bol-procent-cost="{{$product->bol_group_cost_procent}}" data-vat="{{$product->vat_procent}}">{{ $product->id_product }} / {{$product->name }}</option>' +
                            '@endforeach'+
                        '</select>'+
                    '</div>'+
                '</td>'+
                '<td class="input-sm col-xs-1"><input type="number" class="form-control input-sm quantity" name="quantity[]" id="quantity' + rowNumber + '"></td>' +
                '<td class="input-sm col-xs-1"><input type="number" step="0.01" class="form-control input-sm unitPrice" name="unitPrice[]" id="unitPrice' + rowNumber +'"></td>' +
                '<td class="input-sm col-xs-2"><input type="number" class="form-control input-sm rowPriceEx" name="rowPriceEx[]" id="rowPriceEx' + rowNumber + '" readonly tabindex="-1"></td>' + 
                '<td class="input-sm col-xs-2"><input type="number" class="form-control input-sm rowPriceIncl" name="rpwPriceIncl[]" id="rowPriceIncl' + rowNumber + '" readonly tabindex="-1"></td>' +

                '<input type="hidden" class="ikPrice" name="ikPrice[]" id="ikPrice' + rowNumber + '"></td>' + 
                '<input type="hidden" class="rowIkPrice" name="rowIkPrice[]" id="rowIkPrice' + rowNumber + '"></td>' +
                '<input type="hidden" class="bolFixCost" name="bolFixCost[]" id="bolFixCost' + rowNumber + '"></td>' +
                '<input type="hidden" class="bolProcentCost" name="bolProcentCost[]" id="bolProcentCost' + rowNumber + '"></td>' +
                '<input type="hidden" class="rowBolCost" name="rowBolCost[] id="rowBolCost' + rowNumber + '"></td>' +

                '<td class="input-sm col-xs-1">' +
                    '<button type="button" class="delRow glyphicon glyphicon-remove pull-right"></button>' +
                '</td>' +
            '</tr>' 
        $('#invoiceTable').append(row);
        $("#invoiceTable").find('.selectpicker').last().selectpicker();

        return false;

    });

    $('body').delegate('.delRow','click',function(){
        $(this).parent().parent().remove();

        var rowNumber = 0;
        $('.rowNumber').each(function(){
            rowNumber++;
            $(this).text(rowNumber);
        })
        return false;
    });
    
    $('body').delegate('.productSelector','change',function(){ 
        var invoiceType = Number($('.invoiceSelector').find(":selected").val());
        if(invoiceType == 0){
            toastr["error"]("Eerst Factuur Type invullen aub !!");
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();
        };
        var selectId = $(this).attr('id');
        if(invoiceType == 3)  // Bol BE
        {
            var vkPrice = r22($('#' + selectId).find(":selected").attr('data-bolbe-price')); 
        }
        else if(invoiceType == 5) // BOL NL
        {
            var vkPrice = r22($('#' + selectId).find(":selected").attr('data-bolnl-price')); 
        }
        else   // Via CZ of andere
        {
            var vkPrice = r22($('#' + selectId).find(":selected").attr('data-cz-price')); 
        }  
        var ikPrice = r22($('#' + selectId).find(":selected").attr('data-ik-price'));       
        var bolFixCost = r22($('#' + selectId).find(":selected").attr('data-bol-fix-cost')); 
        var bolProcentCost = r22($('#' + selectId).find(":selected").attr('data-bol-procent-cost'));     
        $("#unitPrice" + selectId).val(vkPrice);
        $("#ikPrice" + selectId).val(ikPrice);
        $("#bolFixCost" + selectId).val(bolFixCost);
        $("#bolProcentCost" + selectId).val(bolProcentCost);
    });

    $('body').delegate('#total_shipping_incl_btw','change',function(){ 
        var invoiceType = Number($('.invoiceSelector').find(":selected").val());
        if(invoiceType == 0){
            toastr["error"]("Eerst Factuur Type invullen aub !!");
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();
        };
        var totalShippingInVat = r22($(this).val());
        var totalShippingExVat = r22(totalShippingInVat / ((standVatProcent/100)+1));
        $('#total_shipping_exl_btw').val( totalShippingExVat);
    });

    $('body').delegate('#total_wrapping_incl_btw','change',function(){ 
        var invoiceType = Number($('.invoiceSelector').find(":selected").val());
        if(invoiceType == 0){
            toastr["error"]("Eerst Factuur Type invullen aub !!");
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();

        };
        var totalWrappingInVat = r22($(this).val());
        var totalWrappingExVat = r22(totalWrappingInVat / ((standVatProcent/100)+1));
        $('#total_wrapping_exl_btw').val( totalWrappingExVat);
    });


    $('body').delegate('.quantity, .unitPrice,.ikPrice, .total_shipping_incl_btw, .total_wrapping_incl_btw, .total_wrapping_cost_exl_btw, .total_shipping_cost_exl_btw, .invoiceSelector', 'change', function(){
        var invoiceType = Number($('.invoiceSelector').find(":selected").val()); 

        if(invoiceType == 3)
        {      // Bol-Be
            $('#total_shipping_cost_exl_btw').val(shipping_cost_bol_be_ex_btw);
            $('#total_shipping_exl_btw').val(0);
            $('#total_shipping_incl_btw').val(0);
            $("#total_costs_bol_exl_btw").show();
            $("#label_bol_costs").show();
        }
        else if(invoiceType == 5)
        {    // BOL-NL
            $('#total_shipping_cost_exl_btw').val(shipping_cost_bol_nl_ex_btw);
            $('#total_shipping_exl_btw').val(0);
            $('#total_shipping_incl_btw').val(0);
            $("#total_costs_bol_exl_btw").show();
            $("#label_bol_costs").show();
        }
        else if(invoiceType == 4)
        {    // CZ - BE
            $('#total_shipping_cost_exl_btw').val(shippingCostCzBeExVat);
            var totalProductsInVat = getTotalProductsInVat();
            if(totalProductsInVat < minOrderAmountFreeShipping){
                var shippingAmountCzInVat = r22((((shippingAmountCzExVat)/100)*standVatProcent) +shippingAmountCzExVat)  
                $('#total_shipping_exl_btw').val(shippingAmountCzExVat);
                $('#total_shipping_incl_btw').val(shippingAmountCzInVat);
            }
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();

        }
        else if(invoiceType == 6)
        {    // CZ - BE
            $('#total_shipping_cost_exl_btw').val(shippingCostCzNlExVat);
            if(totalProductsInVat < minOrderAmountFreeShipping){
                var shippingAmountCzInVat = r22((((shippingAmountCzExVat)/100)*standVatProcent) +shippingAmountCzExVat)  
                $('#total_shipping_exl_btw').val(shippingAmountCzExVat);
                $('#total_shipping_incl_btw').val(shippingAmountCzInVat);
            }
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();

        }
       
        if(invoiceType == 0){
            toastr["error"]("Eerst Factuur Type invullen aub !!");
            $("#total_costs_bol_exl_btw").hide();
            $("#label_bol_costs").hide();
        };
        var tr = $(this).parent().parent();
        var quantity = tr.find('.quantity').val();
        var unitPrice = r22((tr.find('.unitPrice').val()));
        var ikPrice = r22((tr.find('.ikPrice').val()));


        var rowPriceEx = r22(quantity * unitPrice);
        var rowIkPrice = r22(quantity * ikPrice);

        var rowPriceIncl = r22((((quantity * unitPrice) / 100) * standVatProcent) + (quantity * unitPrice));
        tr.find('.rowPriceEx').val(rowPriceEx);
        tr.find('.rowPriceIncl').val(rowPriceIncl);
        tr.find('.rowIkPrice').val(rowIkPrice);


        var totalProductsExVat = getTotalProductsExVat();
        $('#total_products_exl_btw').val(totalProductsExVat);

        var totalProductsInVat = getTotalProductsInVat();
        $('#total_products_incl_btw').val(totalProductsInVat);

        if(invoiceType == 4 || invoiceType == 6)   // Via CZ Presta
        {
            if(totalProductsInVat < minOrderAmountFreeShipping){
                var shippingAmountCzInVat = r22((((shippingAmountCzExVat)/100)*standVatProcent) +shippingAmountCzExVat)  
                $('#total_shipping_exl_btw').val(shippingAmountCzExVat);
                $('#total_shipping_incl_btw').val(shippingAmountCzInVat);

            }else{
                $('#total_shipping_exl_btw').val(0);
                $('#total_shipping_incl_btw').val(0);
            }
        }

        if(invoiceType == 3 || invoiceType == 5)  // Via Bol BE of NL Calculate BOL costs
        {
            var bolFixCost  = (tr.find('.bolFixCost').val()) / ((standVatProcent/100)+1);
            var bolProcentCost  = tr.find('.bolProcentCost').val();
            var rowBolCost = r22((bolProcentCost/100 * rowPriceEx) + bolFixCost);
            console.log(rowBolCost);
            tr.find('.rowBolCost').val(rowBolCost);
            var totalBolCost = getTotalBolCost();
            $('#total_costs_bol_exl_btw').val(totalBolCost);
        }

        var totalShippingExVat = r22(Number($('#total_shipping_exl_btw').val()));
        var totalWrappingExVat = r22(Number($('#total_wrapping_exl_btw').val()));

        var totalShippingInVat = r22(Number($('#total_shipping_incl_btw').val()));
        var totalWrappingInVat = r22(Number($('#total_wrapping_incl_btw').val()));

        var shippingCostExVat = r22(Number($('#total_shipping_cost_exl_btw').val()));
        var wrappingCostExVat = r22(Number($('#total_wrapping_cost_exl_btw').val()));



        var totalInvoiceExVat = r22(totalProductsExVat + totalShippingExVat + totalWrappingExVat);
        $('#total_invoice_exl_btw').val(totalInvoiceExVat);

        var totalInvoiceInVat = r22(totalProductsInVat + totalShippingInVat + totalWrappingInVat);
        $('#total_invoice_incl_btw').val(totalInvoiceInVat);

        var totalIkPrice = getTotalIkPrice();
        $('#total_ikp_cz_exl_btw').val(totalIkPrice);


        if(invoiceType == 3 || invoiceType == 5)  // Via Bol BE of NL Calculate BOL costs
        {
            var margin = r22(totalInvoiceExVat - totalIkPrice - shippingCostExVat - wrappingCostExVat - totalBolCost);
        }
        else
        {
            var margin = r22(totalInvoiceExVat - totalIkPrice - shippingCostExVat - wrappingCostExVat);     
        }
        $('#netto_margin_ex_btw').val(margin);
        if( margin > 0){
            $('#netto_margin_ex_btw').css("background-color","#90EE90");
        }else{
            $('#netto_margin_ex_btw').css("background-color","#FFB6C1");
        }

    });
    
})  // end $document

</script>
@endsection


