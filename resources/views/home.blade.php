@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    U bent correct ingelogd, Welkom !<br>
                    <br>
                    <b>Enkele interessante links :<b>
                    <br>
                     <a href="http://www.cool-zawadi.com/admin294dxt04v/index.php?controller=AdminLogin&token=460e913907b56bebb24c10b615140ccd" target="_blank">BackOffice Prestashop</a>
                     <br>
                     <a href="https://www.bol.com/sdd/dashboard/dashboard.html" target="_blank">Bol.com Backoffice</a>    
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>
@endsection
