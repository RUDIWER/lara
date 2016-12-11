@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h4 class="panel-heading">Klanten Overzicht
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('klanten/create') }}" type="button" class='btn btn-default btn-sm pull-right'>toevoegen</a>
                    </div>
                </h4>
                <div class="panel-body">
                   <body>
                       <div id="customer_grid" style="width: 99%; height: 480px;"></div>
                   </body>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>

<script type="text/javascript" charset="utf-8">

// Customer Grid
    mygrid = new dhtmlXGridObject('customer_grid');
    mygrid.enableSmartRendering(true); // false to disable Enable to load big datasets faster !!!
    mygrid.setHeader("Id,Familienaam,Voornaam,Email");
    mygrid.attachHeader("#numeric_filter,#text_filter,#text_filter,#text_filter");
    mygrid.enableKeyboardSupport(true);
    mygrid.setColTypes("ro,ro,ro,ro");
    mygrid.setInitWidths("45");
    mygrid.enableLightMouseNavigation(true);
    mygrid.enableStableSorting(true);
    mygrid.init();
    mygrid.setColAlign("right,left,left,left");
    mygrid.enableAlterCss("even","uneven");
    mygrid.setColSorting("int,str,str,str");
    //                mygrid.sortRows(0, "str", "asc"); // sorts grid
    mygrid.setSortImgState(true, 1, "asc"); // sets icon to sort arrow
    mygrid.load("./customer_data",function(){
        mygrid.sortRows(1,"str","asc"); //0 - index of column
    });
    mygrid.attachEvent("onRowDblClicked", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./klanten/edit/" + cellValue;
        window.location.href = path;
    });
    mygrid.attachEvent("onEnter", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./klanten/edit/" + cellValue;
        window.location.href = path;
    });

 //   var dp = new dataProcessor("./customer_data");
 //   dp.init(mygrid);

// Customer Form

</script>

@endsection
