@extends('layouts.app')
@section('content')
<div id="custInvoiceApp">
<div id="isNew" data-field-id="{{$isNew}}" ></div>

@if ($isNew == 0)
    <form action="/facturen/save/{{ $invoice->id_cust_invoice }}" name="custInvoiceForm" id="custInvoiceForm" method="post">
@else
    <form action="/facturen/save/0" name="custInvoiceForm" id="custInvoiceForm" method="post">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
@if ($isNew == 0)
    <input type="hidden" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}">
@endif
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
            <!--           <input type="submit" class='btn btn-default btn-sm' value="Opslaan"> -->
                    </div>
                </h4>
                <div class="panel-body panel-body-form">
                    <div class="form-group">
            			<div class="col-xs-12">
                            <div class="row">
                                 <div class="col-xs-2">
            						<label class="control-label">Factuurnr</label>
                                    <input type="number" class="form-control input-sm" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}" readonly tabindex="-1">
            					 </div>
                                 <div class="col-xs-2 pull-right">
            						<label class="control-label">Factuur Datum</label>
                                    <input type="text" class="form-control input-sm" name="invoice_date" id="invoice_date" value="{{ $invoice->invoice_date }}" readonly tabindex="-1">
            				     </div>
                                <div class="col-xs-2 pull-right">
            						<label class="control-label">Order Datum</label>
                                    <input type="text" class="form-control input-sm" name="order_date" id="order_date" value="{{ $invoice->order_date }}" readonly tabindex="-1">
            				     </div>
                            </div> <!-- row -->
                        </div> <!-- class xs-12 -->  
                        <div class="h-line"></div>   
                        <div class="form-group">                        
                            <div class="col-xs-6">                  
                                <div class="row">
                                    <div class="col-xs-2">
                                        <label class="control-label">Klant</label>
                                        <input type="text" class="form-control input-sm input-required" name="id_customer" id="id_customer" value="{{ $invoice->id_customer }}">
                                    </div>
                                    <div class="col-xs-4">
                                        {{ $invoice->customer_first_name}} {{ $invoice->customer_name }}<br>
                                        {{ $invoice->customer_street_nr }}<br> 
                                        {{ $invoice->customer_postal_code}} {{ $invoice->customer_city }} {{ $invoice->customer_country }}<br>
                                        {{ $invoice->customer_vat_number}} <br>
                                    </div>
                                </div> <!--row -->                  
                            </div> <!-- class xs 6 -->
                            <div class="col-xs-2 pull-right">
                                <label class="control-label">CZ Ordernr</label>
                                <input type="number" class="form-control input-sm" name="id_cust_order" id="id_cust_order" value="{{ $invoice->id_cust_order }}" readonly tabindex="-1">
                                <label class="control-label">Bol Ordernr</label>
                                <input type="number" class="form-control input-sm" name="ordernr_bol" id="ordernr_bol" value="{{ $invoice->ordernr_bol }}" readonly tabindex="-1">
                            </div> <!-- class xs 6 -->
                        </div>
                        <div class="h-line"></div>

                    <!-- FACTUUR REGELS !!!!  -->
                        <div class="form-group">
                            <div class="col-xs-12">
                            @foreach($invoiceDetails as $invoiceDetail)
                                <div class="row">
                                    <div class="col-xs-2">
                                        <input type="text" class="form-control input-sm input-required" name="id_product" id="id_product" value="{{ $invoiceDetail->id_product }}">
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control input-sm input-required" name="product_descr" id="product_descr" value="{{ $invoiceDetail->product_descr }}">
                                    </div>
                                    <div class="col-xs-1">
                                        <input type="number" class="form-control input-sm input-required" name="quantity" id="quantity" value="{{ $invoiceDetail->quantity }}">
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="number" class="form-control input-sm input-required" name="product_unit_price_ex_vat" id="product_unit_price_ex_vat" value="{{ round($invoiceDetail->product_unit_price_ex_vat,2) }}">
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="number" class="form-control input-sm input-required" name="product_total_price_ex_vat" id="product_total_price_ex_vat" value="{{ round($invoiceDetail->product_total_price_ex_vat,2) }}">
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="number" class="form-control input-sm input-required" name="product_total_price_incl_vat" id="product_total_price_incl_vat" value="{{ round($invoiceDetail->product_total_price_incl_vat,2) }}">
                                    </div>




                                </div>
                                @endforeach
                            </div>
                        </div>
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
                                    <input type="number" class="form-control input-sm" name="total_products_exl_btw " id="total_products_exl_btw " value="{{ round($invoice->total_products_exl_btw,2) }}" readonly tabindex="-1">
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
                                    <input type="number" class="form-control input-sm" name="total_shipping_cost_exl_btw" id="total_shipping_cost_exl_btw" value="{{ round($invoice->total_shipping_cost_exl_btw,2) }}" readonly tabindex="-1">
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
                                    <input type="number" class="form-control input-sm" name="total_shipping_incl_btw " id="total_shipping_incl_btw" value="{{ round($invoice->total_shipping_incl_btw,2) }}" readonly tabindex="-1">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="col-xs-7">
            						<label class="control-label">Kost. Geschenkverp. Ex. BTW</label>
                                    <input type="number" class="form-control input-sm" name="total_wrapping_exl_btw" id="total_wrapping_exl_btw" value="{{ round($invoice->total_wrapping_cost_ex_btw,2) }}" readonly tabindex="-1">
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
                                    <input type="number" class="form-control input-sm" name="total_wrapping_exl_btw" id="total_wrapping_exl_btw" value="{{ round($invoice->total_wrapping_exl_btw,2) }}" readonly tabindex="-1">
                                    </div>
                                </div>

                                <div class="col-xs-4">
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
                                    <input type="number" class="form-control input-sm" name="total_wrapping_incl_btw" id="total_wrapping_incl_btw" value="{{ round($invoice->total_wrapping_incl_btw,2) }}" readonly tabindex="-1">
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
                                    <input type="number" class="form-control input-sm" name="total_paid" id="total_paid" value="{{ round($invoice->total_paid,2) }}" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div> <!-- form-group-->
                 </div> <!-- panel-body -->
            </div> <!-- panel-default -->
        </div> <!-- class col-md-12 -->
    </div> <!-- row -->
</div> <!-- container-fluid -->
@include('partials.footer')
@endsection