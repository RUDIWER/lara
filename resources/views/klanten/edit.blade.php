
@extends('layouts.app')

@section('content')
@if ($isNew == 0)
    <form action="/klanten/save/{{ $klant->id_customer }}" name="customerForm" id="customerForm" method="post">
@else
    <form action="/klanten/save/0" name="customerForm" id="customerForm" method="post">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div id="isNew" data-field-id="{{$isNew}}" ></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if ($isNew == 0)
                    <h4 class="panel-heading">Klant {{ $klant->id_customer }} wijzigen
                @else
                    <h4 class="panel-heading">Klant Toevoegen
                @endif
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('klanten') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
                       <input type="submit" class='btn btn-default btn-sm' value="Opslaan">
                    </div>
                </h4>
                <div class="panel-body panel-body-form">
                    <div class="form-group">
            			<div class="col-xs-12">
            				<div class="row">
            					<div class="col-xs-2 pull-left">
                                    <label class="control-label">Klant ID</label><br>
                                    @if ($isNew == 0)
			                            <input type="number" class="form-control input-sm" name="id_customer" id="id_customer" value="{{ $klant->id_customer }}" placeholder="Klant id" readonly tabindex="-1">
                                    @else
                                        <input type="number" class="form-control input-sm" placeholder="Nog niet toegekend..." readonly tabindex="-1">
                                    @endif
                            	</div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Creatie Datum</label>
                                    @if ($isNew == 0)
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ $klant->date_add }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @else
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ date('Y-m-d H:i:s') }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @endif
                                </div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Laatste wijziging</label>
                                    <input type="text" class="form-control input-sm" name="date_upd" id="date_upd" value="{{ $klant->date_upd }}" placeholder="Laatste wijziging" readonly tabindex="-1">
                				</div>
            			    </div> <!-- row -->
                        </div> <!-- col-->
            		</div> <!-- form group -->
                    <div class="h-line"></div>
                    <div class="form-group col-xs-6">      <!-- BASIS GEGEVENS KLANTEN linke kolom -->
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label class="control-label">Familienaam</label>
                                    <input type="text" class="form-control input-sm input-required" required autofocus name="lastname" id="lastname" value="{{ $klant->lastname }}" placeholder="Familienaam">
                                </div>
                            </div>
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label class="control-label">Voornaam</label>
                                    <input type="text" class="form-control input-sm input-required" required name="firstname" id="firstname" value="{{ $klant->firstname }}" placeholder="Voornaam">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label class="control-label">Titel</label>
                                    <select class="form-control selectpicker show-tick show-menu-arrow" data-style="btn-default btn-sm btn-required" name="id_gender" id="title" required>
                                        @if ($klant->id_gender == 1)
                                            <option value="1" selected="selected">Dhr.</option>
                                            <option value="2">Mevr.</option>
                                        @elseif ($klant->id_gender == 2)
                                            <option value="1">Dhr.</option>
                                            <option value="2" selected="selected">Mevr.</option>
                                        @else
                                            <option value="1">Dhr.</option>
                                            <option value="2">Mevr.</option>
                                        @endif
                                    </select>

                                </div>
                            </div> <!-- col -->

                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Bedrijfsnaam</label>
                                <input type="text" class="form-control input-sm" name="company" id="company" value="{{ $klant->company }}" placeholder="Bedrijfsnaam">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-8">
                                <label class="control-label">E-mail adres</label>
                                <input type="email" class="form-control input-sm" name="email" id="email" value="{{ $klant->email }}" placeholder="email">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
            				<div class="col-xs-6">
            					<label class="control-label">Website</label>
                                <input type="text" class="form-control input-sm" name="website" id="website" value="{{ $klant->website }}" placeholder="website">
            				</div> <!-- col -->
            			</div> <!-- row -->
                    </div> <!-- form group -->
                    <div class="form-group col-xs-6">      <!-- BASIS GEGEVENS KLANTEN Rechter kolom -->
                        <div class="row">
        					<div class="col-xs-4 pull-right">
        						<label class="control-label">Geboortedatum</label>
                                <input type="date" class="form-control input-sm" name="birthdate" id="birthdate" value="{{ $klant->birthdate }}" placeholder="geboren op...">
        					</div>
        				</div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-4 pull-right">
                                <label class="control-label">Type op Web</label>
                                <select class="selectpicker form-control selectpicker show-tick show-menu-arrow" data-style="btn-default btn-sm btn-required" name="id_default_group" id="clienttype" title="Type klant..." required>
            						@if ($klant->id_default_group == "1")
            							<option value="1" selected="selected">Bezoeker</option>
            							<option value="2">Gast</option>
            							<option value="3">Klant</option>
            							<option value="4">Groothandel</option>
            							<option value="5">Dropshipping</option>
            						@elseif ($klant->id_default_group == "2")
            							<option value="1">Bezoeker</option>
            							<option value="2" selected="selected">Gast</option>
            							<option value="3">Klant</option>
            							<option value="4">Groothandel</option>
            							<option value="5">Dropshipping</option>
                                    @elseif ($klant->id_default_group == "3")
            							<option value="1">Bezoeker</option>
            							<option value="2">Gast</option>
            							<option value="3" selected="selected">Klant</option>
            							<option value="4">Groothandel</option>
            							<option value="5">Dropshipping</option>
                                    @elseif ($klant->id_default_group == "4")
            							<option value="1">Bezoeker</option>
            							<option value="2">Gast</option>
            							<option value="3">Klant</option>
            							<option value="4" selected="selected">Groothandel</option>
            							<option value="5">Dropshipping</option>
            						@elseif ($klant->id_default_group == "5")
            							<option value="1">Bezoeker</option>
            							<option value="2">Gast</option>
            							<option value="3">Klant</option>
            							<option value="4">Groothandel</option>
            							<option value="5" selected="selected">Dropshipping</option>
            						@else
            							<option value="1">Bezoeker</option>
            							<option value="2">Gast</option>
            							<option value="3">Klant</option>
            							<option value="4">Groothandel</option>
            							<option value="5">Dropshipping</option>
            						@endif
            					</select>
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-4 pull-right">
                                <label class="control-label">Oorsprong klant</label><br>
                                <select class="selectpicker form-control selectpicker show-tick show-menu-arrow" data-style="btn-default btn-sm btn-required" name="note" id="note" title="Oorsprong..." required>
            						@if ($klant->note == "Cool-Zawadi")
            							<option selected="selected">Cool-Zawadi</option>
            							<option>Bol.com</option>
            							<option>Amazon</option>
            						@elseif ($klant->note == "Bol.com")
            							<option>Cool-Zawadi</option>
            							<option selected="selected">Bol.com</option>
            							<option>Amazon</option>
            						@elseif ($klant->note == "Amazon")
            							<option>Cool-Zawadi</option>
            							<option>Bol.com</option>
            							<option selected="selected">Amazon</option>
            						@else
            							<option>Cool-Zawadi</option>
            							<option>Bol.com</option>
            							<option>Amazon</option>
            						@endif
            					</select>
                            </div>	<!-- class -->
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-4 pull-right">
    							<label class="control-label">Taal</label><br>
                                <select class="selectpicker form-control selectpicker show-tick show-menu-arrow" required data-style="btn-default btn-sm btn-required" name="id_lang" id="language" title="Taal...">
            						@if ($klant->id_lang == 4)
            							<option value="4" selected="selected">Nederlands</option>
            							<option value="2">Frans</option>
            						@elseif ($klant->id_lang == 2)
            							<option value="4">Nederlands</option>
            							<option value="2" selected="selected">Frans</option>
            						@else
            							<option value="4">Nederlands</option>
            							<option value="2">frans</option>
            						@endif
            					</select>
        					</div>
                        </div> <!-- row -->
                    </div> <!-- form group -->
                    <div class="form-group col-xs-12">      <!-- FORM GROUP TABS - ADDRESSEN -->
                        <ul class="nav nav-tabs">
                            @if ($isNew == 0)
                                <li class="active"><a data-toggle="tab" href="#tab1">Adressen</a></li>
                            @else
                                <li class="active"><a data-toggle="tab" href="#tab1">Adres gegevens</a></li>
                            @endif
                            @if ($isNew == 0)
                                <button type="button" id="add_address" class="btn btn-default btn-sm pull-right">Adres toevoegen</button>
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if ($isNew == 0)
                                <div id="tab1" class="tab-pane fade in active">
                                    <body>
                                        <div id="address_grid" style="width: 100%; height: 130px;"></div>
                                    </body>
                                </div>
                            @else
                                <div id="tab1" class="tab-pane fade in active">
                                    @include('partials.addressForm')
                                    <script>  $('#addressForm').show();  </script>
                                </div>
                            @endif
                        </div> <!-- tab-content -->
                    </div> <!-- form group -->
                </div> <!-- Panel body -->
            </div> <!-- Panel -->
        </div>  <!-- md-12 -->
    </div> <!-- row -->
    @include('partials.footer')
</div> <!-- contaainer -->
</form>

<!-- ADDRESS Form -->
@include('partials.addressForm')

<script type="text/javascript" charset="utf-8">
var lastname = document.getElementById('lastname').value;
var firstname = document.getElementById('firstname').value;

var isNew = $('#isNew').data("field-id");
if (!isNew){
    var customer_id = document.getElementById('id_customer').value;

    // Address Grid + Event handler to change addresss
    mygrid = new dhtmlXGridObject('address_grid');
    mygrid.setHeader("Id,Alias,Bedrijfsnaam,BTW nr.,Adres,Postcode,Gemeente,Landcode,Tel,Mobiel");
    mygrid.enableKeyboardSupport(true);
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
    mygrid.setInitWidths("45");
    mygrid.enableLightMouseNavigation(true);
    mygrid.init();
    mygrid.setColAlign("right,left,left,left,left,left,left,left,left,left");
    mygrid.load("/klant_address_data/" + customer_id,function(){
        mygrid.sortRows(1,"int","asc"); //0 - index of column
    });
    mygrid.attachEvent("onRowDblClicked", function(row,col){
       var id = mygrid.cells(row,0).getValue();
       $.ajax({
           url: "./address/edit/" + id,
           method: 'GET'
       }).done(function(response) {
           // EDIT EXISTING ADDRESS
           // Populate the form fields with the data returned from server
           $('#addressForm')
               .find('[name="id_address"]').val(response.address.id_address).end()
               .find('[name="id_existing_customer"]').val(customer_id).end()
               .find('[name="lastname"]').val(response.address.lastname).end()
               .find('[name="firstname"]').val(response.address.firstname).end()
               .find('[name="alias"]').val(response.address.alias).end()
               .find('[name="vat_number"]').val(response.address.vat_number).end()
               .find('[name="address1"]').val(response.address.address1).end()
               .find('[name="address2"]').val(response.address.address2).end()
               .find('[name="postcode"]').val(response.address.postcode).end()
               .find('[name="city"]').val(response.address.city).end()
               .find('[name="id_country"]').val(response.address.id_country).end()
               .find('[name="phone"]').val(response.address.phone).end()
               .find('[name="phone_mobile"]').val(response.address.phone_mobile).end()
               .find('[name="other"]').val(response.address.other).end();
            // Set country selectpicker correct
            var id_country = response.address.id_country;
            $('select[name="sel_country"]').val(id_country);
            $('.selectpicker').selectpicker('refresh');
           // Show the dialog
           bootbox
               .dialog({
                   size: "large",
                   title: 'Adres wijzigen',
                   message: $('#addressForm'),
                   show: false // We will show it manually later
               })
               .on('shown.bs.modal', function() {
                   $('#addressForm').show();   // Show the addressform
                   $('#delButton').show();

               })
               .on('hide.bs.modal', function(e) {
                   $('#addressForm').trigger("reset");
                   mygrid.clearAll();
                   mygrid.load("/klant_address_data/" + customer_id);
                   $('#addressForm').hide().appendTo('body');
               })
               .modal('show');
       });
   });
}  // EN id isNew

// Add New address with modal addresform click toevoegen
$('#add_address').click( function(e) {
    $('#addressForm')
        .find('[name="id_existing_customer"]').val(customer_id).end()
        .find('[name="lastname"]').val(lastname).end()
        .find('[name="firstname"]').val(firstname).end()

    bootbox
        .dialog({
            size: "large",
            title: 'Adres Toevoegen',
            message: $('#addressForm'),
            show: false // We will show it manually later
        })
        .on('shown.bs.modal', function() {
            $('#addressForm').show();    // Show the addressform
            $('#delButton').hide();
        })
        .on('hide.bs.modal', function(e) {
            $('#addressForm').trigger("reset");
            mygrid.clearAll();
            mygrid.load("/klant_address_data/" + customer_id);
            $('#addressForm').hide().appendTo('body');
        })
        .modal('show');
});

</script>
@endsection
