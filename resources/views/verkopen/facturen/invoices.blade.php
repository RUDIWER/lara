@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h4 class="panel-heading">facturen Overzicht
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('verkopen/facturen/create') }}" type="button" class='btn btn-default btn-sm pull-right'>toevoegen</a>
                    </div>
                </h4>
                <div class="panel-body">
                   <body>
                       <div id="invoice_grid" style="width: 99%; height: 480px;"></div>
                   </body>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>

<script type="text/javascript" charset="utf-8">

// invocie Grid
    mygrid = new dhtmlXGridObject('invoice_grid');
    mygrid.enableSmartRendering(true); // false to disable Enable to load big datasets faster !!!
    mygrid.setHeader("Id,Klantnaam,Voornaam,Email,Bedrag");
    mygrid.attachHeader("#numeric_filter,#text_filter,#text_filter,#text_filter,#numeric_filter");
    mygrid.enableKeyboardSupport(true);
    mygrid.setColTypes("ron,ro,ro,ro,ron");
    mygrid.setInitWidths("45");
    mygrid.setNumberFormat("0,000.00",4);
    mygrid.enableLightMouseNavigation(true);
    mygrid.enableStableSorting(true);
    mygrid.init();
    mygrid.setColAlign("right,left,left,left,right");
    mygrid.enableAlterCss("even","uneven");
    mygrid.setColSorting("int,str,str,str,int");
    //                mygrid.sortRows(0, "str", "asc"); // sorts grid
    mygrid.setSortImgState(true, 0, "asc"); // sets icon to sort arrow
    mygrid.load("./invoice_data",function(){
        mygrid.sortRows(0,"num","des"); //0 - index of column
    });
    mygrid.attachEvent("onRowDblClicked", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./facturen/edit/" + cellValue;
        window.location.href = path;
    });
    mygrid.attachEvent("onEnter", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./facturen/edit/" + cellValue;
        window.location.href = path;
    });

//    var dp = new dataProcessor("./invoice_data");
//    dp.init(mygrid);

// Customer Form

</script>

@endsection
