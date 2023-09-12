@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

#container {
    height: 400px;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}



{
        {
        -- pagination style--
    }
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 5px;
}

.pagination .active {
    font-weight: bold;
    color: #000;
}

.pagination a {
    color: #007bff;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #007bff;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #007bff;
    color: #fff;
}


    {
        {
        --pagination style ends--
    }
}

#prescription .container {
    background-color: #f2f2f2 !important;
}

.header p {
    font-size: 14px;
}

.aside {
    width: 400px;
    border-right: 1px solid #ddd;
    min-height: 600px;
    padding-bottom: 20px;
}

.signatureImage {
    display: inline-block;
    width: 100px;
    object-fit: contain;
    margin-bottom: 5px;
}

.signatureBox {
    position: absolute;
    right: 50px;
    bottom: 30px;
}

.footer {
    padding-top: 20px;
    padding-bottom: 20px;
    border-top: 1px solid #ddd;
}

.footer p {
    font-size: 14px;
}

.apiLogo {
    max-width: 40px;
    transform: translateY(-4px);
    margin-left: 5px;
}

.logoText {
    font-size: 14px;
}

.nextinfo {
    margin-top: 150px;
}

.userImg {
    margin-top: 20px;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 20px;
    border: 10px solid rgba(122, 122, 122, .15);
}

.dataItem p {
    font-weight: 400;
    font-size: 15px;
}

.dataItem span {
    font-weight: 600;
    font-size: 15px;
}

@media (max-width: 767px) {

    #prescription,
    .logoText,
    address p,
    .header p {
        font-size: 12px !important;
    }

    .header h4 {
        font-size: 18px !important;
    }

    .patientageLeftSide {
        width: 100% !important;
        min-height: auto !important;
        border: 0 !important;
    }

    .itemMerge {
        flex-direction: column;
    }

    .patientageLeftSide h5 {
        font-size: 18px !important;
    }

    .userImg {
        width: 140px !important;
        height: 140px !important;
        border-width: 5px;
    }

    .patientageRightSide .dataItem p,
    .patientageRightSide .dataItem span,
    .patientageLeftSide p {
        margin-bottom: 0;
        font-size: 14px;
    }

    .patientageRightSide .dataItem h5 {
        font-size: 16px !important;
        margin-bottom: 5px !important;
    }

    .patientageRightSide {
        padding: 10px 10px !important;
    }

    .patientageRightSide .dataItem {
        margin-top: 15px !important;
    }

}
</style>
@endpush

<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.html5.min.js"></script>


@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
            </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->
                @if (permission('patientage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New patientage','Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter" method="GET" action="{{url('ajax-top-ten-diseases')}}">

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="name">Date Range</label>
                                <input type="text" class="form-control" value="" name="daterange" id="daterange"
                                    placeholder="Select Date">
                            </div>


                            <div class="form-group col-md-3">
                                <label for="name">Select Branch</label>

                                <select class="selectpicker" data-live-search="true" name="hc_id" id="hc_id">
                                    <option value="">Select Branch</option> <!-- Empty option added -->

                                    @foreach($healthcenters as $healthcenter)
                                        <option value="{{$healthcenter->barcode_prefix}}">{{$healthcenter->healthCenter->HealthCenterName}}</option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>

                            <div class="form-group col-md-2 pt-24">

                                <button type="button" id="search" class="btn btn-primary btn-sm float-right mr-2">
                                    <i class="fas fa-search"></i>
                                </button>

                                <button type="button" id="refresh" class="btn btn-primary btn-sm float-right mr-2 refresh">
                                <i class="fas fa-sync-alt"></i></button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="container_diseases"></div>
                                </figure>
                            </div>
                        </div>
                    </form>



                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('script')
 <script src="js/dataTables.buttons.min.js"></script>
 <script src="js/buttons.html5.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/series-label.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>

<script>
 var table;
 $(document).ready(function(){
 var start = moment().subtract(29, 'days');
    var end = moment();

     $('input[name="daterange"]').daterangepicker({
        startDate: start,
        endDate: end,
        showDropdowns: true,
        linkedCalendars: false,
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')],
        'This Year': [moment().startOf('year'), moment().endOf('year')]
        }
    });

     $('.daterangepicker').mouseleave(function() {
        $(this).hide();
    });
      $('input[name="daterange"]').click(function() {
        $('.daterangepicker').show();
    });



 });




$('#refresh').click(function(){
    $('#daterange').val('');


    $('.selectpicker').selectpicker('val', '');
    $('#container_diseases').html('');
});

$('#search').click(function() {
    var daterange = $('#daterange').val();
    var hc_id = $('#hc_id').val();
    console.log(daterange);
    const parts = daterange.split(" - ");

// Get the first date.
    const fdate = parts[0];

// Get the second date.
    const ldate = parts[1];
    console.log(fdate,ldate,hc_id)


    $.ajax({
        url: "{{ url('ajax-top-ten-diseases') }}",
        type: "get",
        data: { starting_date: fdate, ending_date: ldate, hc_id: hc_id },
        dataType: "html",
        beforeSend: function(){
            $('#warning-searching').removeClass('invisible');
        },
        complete: function(){
            $('#warning-searching').addClass('invisible');
        },
        success: function(data) {
            $('#container_diseases').html(data);
        },
        error: function(xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
});


</script>
@endpush
