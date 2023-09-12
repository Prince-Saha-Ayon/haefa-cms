@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    {{--        pagination style--}}

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


    {{--pagination style ends--}}

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
        border: 10px solid rgba(122,122,122,.15);
    }

    .dataItem p{
        font-weight: 400;
        font-size: 15px;
    }
    .dataItem span{
        font-weight: 600;
        font-size: 15px;
    }

    @media (max-width: 767px){
        #prescription, .logoText, address p, .header p{
            font-size: 12px !important;
        }
        .header h4{
            font-size: 18px !important;
        }
        .patientageLeftSide {
            width: 100% !important;
            min-height: auto !important;
            border: 0 !important;
        }
        .itemMerge{
            flex-direction: column;
        }
        .patientageLeftSide h5{
            font-size: 18px !important;
        }
        .userImg {
            width: 140px !important;
            height: 140px !important;
            border-width: 5px;
        }
        .patientageRightSide .dataItem p,
        .patientageRightSide .dataItem span,
        .patientageLeftSide p{
            margin-bottom: 0;
            font-size: 14px;
        }
        .patientageRightSide .dataItem h5{
            font-size: 16px !important;
            margin-bottom: 5px !important;
        }
        .patientageRightSide{
            padding: 10px 10px !important;
        }
        .patientageRightSide .dataItem{
            margin-top: 15px !important;
        }

    }
</style>
@endpush

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

                    <form id="form-filter" method="POST" action="" >
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="daterange">Date Range</label>
                                <input type="text" class="form-control daterangepicker-start" id="daterange" name="daterange" >
                            </div>

                            <div class="form-group col-md-3">
                                <label for="name">Branches</label>

                                <select class="selectpicker" data-live-search="true" name="hc_id" id="hc_id">
                                    <option value="">Select Branch</option> <!-- Empty option added -->
                                    @foreach($branches as $branch)
                                    <option value="{{$branch->barcode_prefix}}">{{$branch->HealthCenterName}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>
                            <div class="form-group col-md-4 pt-24">

                                <button type="button"  class="btn btn-primary btn-sm float-right mr-2" id="search"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12 col-xxl-4 chart-style" id="date_wise" ></div>
                    </div>

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

<script src="js/highcharts.js"></script>
<script src="js/series-label.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>

 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script>

// disease rate by date range start

$('#search').click(function() {
    var daterange = $('#daterange').val();
    var hc_id = $('#hc_id').val();
    const parts = daterange.split(" - ");
// Get the first date.
    const fdate = parts[0];
// Get the second date.
    const ldate = parts[1];

    $.ajax({
        type: "GET",
        url: "{{ url('ajax-disease-rate-date-range') }}",
        data: { hc_id: hc_id, fdate: fdate, ldate: ldate },
        beforeSend: function(){
            $('#warning-searching').removeClass('invisible');
        },
        complete: function(){
            $('#warning-searching').addClass('invisible');
        },
        success: function(response) {
            //console.log(response.data);
            // Extract the data array from the response
            var data = response.data.data;

            // Define an array to store colors for each data point
            var colors = [];

            // Define custom colors based on the count (y-value)
            for (var i = 0; i < data.length; i++) {
                var count = data[i][1];
                var color;

                if (count >= 0 && count <= 5) {
                    color = 'blue';
                } else if (count >= 6 && count <= 10) {
                    color = 'yellow';
                } else if (count >= 11 && count <= 15) {
                    color = 'red';
                } else {
                    color = 'black';
                }

                colors.push(color); // Add the color to the array
            }

            var chart = Highcharts.chart('date_wise', {
                title: {
                    text: response.healthcenter + ' Disease',
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    title: {
                        text: 'Disease',

                    },
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                yAxis: [{
                    title: {
                        text: 'Patients',
                        enabled: true
                    }
                }],
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            format: '{y}',
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold'
                            }
                        },
                        colorByPoint: true, // Use colorByPoint to specify colors for each data point
                    },
                },
                series: [{
                    name: 'Patients',
                    type: 'column',
                    data: data,
                    showInLegend: false,
                    colors: colors, // Use the custom colors array
                }],
                exporting: {
                    filename: 'branch_wise_disease_report', // Specify your custom file name here
                },
            });
        },
        error: function(xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });




});


// disease rate by date range end

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        console.log("Selected Date Range: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

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
            // 'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            // Add more custom ranges here...
        }
    }, cb);

    cb(start, end);
    $('.daterangepicker').mouseleave(function() {
        $(this).hide();
    });
    $('.daterangepicker-start').click(function() {
        $('.daterangepicker').show();
    });


    var table;
    $(document).ready(function () {
        $('#dataTable').DataTable({
            pagingType: 'full_numbers',
            dom: 'Bfrtip',
            orderCellsTop: true,
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export to Excel',

                },
            ],
        });



    });
    $('#btn-filter').on('click', function (event) {
        $('#warning-searching').removeClass('invisible');
    });

    $(function () {

        $('#starting_age, #ending_age').on('input', function () {
            if ($('#starting_age').val() != '' && $('#ending_age').val() != '') {
                $('#btn-filter').removeClass('d-none');
            } else {
                $('#btn-filter').addClass('d-none');
            }
        });
    });

</script>
@endpush
