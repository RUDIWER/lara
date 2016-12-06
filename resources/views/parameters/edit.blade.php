
@extends('layouts.app')
@section('content')
<form action="/parameters/save/{{$param->id_cz_parameter}}" name="paramForm" id="paramForm" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="container-fluid">
	<div class="panel panel-default">
    	<h4 class="panel-heading">
    		Instellingen Wijzigen
			<div class="btn-group btn-titlebar pull-right">
				<a href="{{ URL::to('home') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
				<input type="submit" class='btn btn-default btn-sm' value="Opslaan">
			</div>
		</h4>
    	<div class="panel-body" panel-body-form>
            <div class="form-group col-xs-12">
                <br>
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#tab1">Stand Kosten</a></li>
					<li><a data-toggle="tab" href="#tab2">Overige stand. waarden</a></li>
					<li><a data-toggle="tab" href="#tab3">Aantal Records</a></li>
				</ul>
				<div class="tab-content">
					<div id="tab1" class="tab-pane fade in active">
						<div class="row">
							<div class="form-group  col-xs-4  pull-left"> 					<!--    GROUP 1  :   INKOOPRIJZEN -->
								<h4>Kosten CZ</h4>
								<div class="row">			<!--    TAB 1 :   PRIJZEN -->
                					<div class="col-xs-12">
                						<label class="control-label">Verzend Kost CZ Belgie ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="shipping_cost_cz_be_ex_btw" id="shipping_cost_cz_be_ex_btw" value="{{ $param->shipping_cost_cz_be_ex_btw }}">
                					</div>
								</div>
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Verzend Kost CZ NL ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="shipping_cost_cz_nl_ex_btw" id="shipping_cost_cz_nl_ex_btw" value="{{ $param->shipping_cost_cz_nl_ex_btw }}">
                					</div>
								</div>
								<br>
								<div class="row">
									<div class="col-xs-12">
										<label class="control-label">Min bedrag voor gratis verzending CZ (incl.BTW)</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="min_order_amount_free_shipping" id="min_order_amount_free_shipping" value="{{ $param->min_order_amount_free_shipping }}">
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<label class="control-label">Stand. aangerekende verzendkost via CZ (ex. BTW)</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="shipping_amount_cz_be_ex_btw " id="shipping_amount_cz_be_ex_btw " value="{{ $param->shipping_amount_cz_be_ex_btw }}">
									</div>
								</div> <!-- row -->
							</div> <!-- form group -->
							<div class="form-group  col-xs-4"> 					<!--    GROUP 1  :   INKOOPRIJZEN -->
								<h4>Kosten BOL</h4>
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Verzendkost Bol BE ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="shipping_cost_bol_be_ex_btw" id="shipping_cost_bol_be_ex_btw" value="{{ $param->shipping_cost_bol_be_ex_btw }}">
                					</div>
								</div>
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Verzendkost Bol NL ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="shipping_cost_bol_nl_ex_btw" id="shipping_cost_bol_nl_ex_btw" value="{{ $param->shipping_cost_bol_nl_ex_btw }}">
                					</div>
                				</div> <!-- row -->
								<br>
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Stand. Fix Kost per product Bol ex. BTW</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="fixed_cost_bol_ex_btw" id="fixed_cost_bol_ex_btw" value="{{ $param->fixed_cost_bol_ex_btw}}">
                					</div>
                				</div> <!-- row -->
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Stand. % Kost per product Bol</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="procent_cost_bol_ex_btw" id="procent_cost_bol_ex_btw" value="{{ $param->procent_cost_bol_ex_btw}}">
                					</div>
                				</div> <!-- row -->
							</div>
						</div>
                    </div> <!-- tab 1 -->
					<div id="tab2" class="tab-pane fade">
						<div class="row">
							<div class="form-group  col-xs-4  pull-left"> 					<!--    GROUP 1  :   INKOOPRIJZEN -->
								<h4>Overige Standaardwaarden</h4>
								<div class="row">			<!--    TAB 1 :   PRIJZEN -->
                					<div class="col-xs-12">
                						<label class="control-label">Stand. BTW Procent Producten</label>
                                        <input type="number" step="0.01" class="form-control input-sm input-required" required name="stand_vat_procent" id="stand_vat_procent" value="{{ $param->stand_vat_procent }}">
                					</div>
								</div>
								<br>
								<div class="row">
									<div class="col-xs-12">
										<label class="control-label">Stand. Marge voor Dropshipping</label>
										<input type="number" step="0.01" class="form-control input-sm input-required" required name="stand_margin_dropshipping" id="stand_margin_dropshipping" value="{{ $param->stand_margin_dropshipping}}">
									</div>
								</div> <!-- row -->
								<div class="row">
									<div class="col-xs-12">
										<label class="control-label">Stand. Marge voor Groothandel</label>
										<input type="number" step="0.01" class="form-control input-sm input-required" required name="stand_margin_wholesale" id="stand_margin_wholesale" value="{{ $param->stand_margin_wholesale}}">
									</div>
								</div> <!-- row -->
							</div>
						</div>
					</div> <!-- tab 2 -->
					<div id="tab3" class="tab-pane fade">
						<h4>Automatische Nummering - NIET WIJZIGEN ZONDER SUPPORT !!!!!!</h4>
						<div class="row">
							<div class="form-group  col-xs-4  pull-left">
                					<div class="col-xs-12">
                						<label class="control-label">Hoogste VerkoopFactuurnr</label>
                					</div>
								</div>
								<div class="row">
                					<div class="col-xs-12">
                						<label class="control-label">Hoogste VerkoopFactuur regelnr</label>
                					</div>
								</div>
							</div>
						</div>
					</div>
            	</div> <!-- tab content -->
        	</div> <!-- panel body -->
	  </div><!-- panel-heading -->
  </div><!-- panel  primary-->
</form>
@include('partials.footer')

@endsection
