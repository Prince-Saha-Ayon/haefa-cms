@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
    .bootstrap-select{
        width: 100% !important;
    }
    .dropdown-menu.inner.show{
    min-width: 10px !important;
   }
    .highcharts-data-table table {
    border: 1px solid #ccc;
}

.highcharts-data-table td,
.highcharts-data-table th {
    border: 1px solid #ccc;
    padding: 5px;
    font-family: Arial, Monospace;
    font-size: 15px;
}

caption {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
    color: #040505;
    text-align: left;
    caption-side: top;
    font-weight: bold
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

                                <select class="selectpicker w-100" data-live-search="true" name="hc_id" id="hc_id">
                                    <option value="">Select Branch</option> <!-- Empty option added -->
                                    @foreach($branches as $branch)
                                    <option value="{{$branch->barcode_prefix}}">{{$branch->healthCenter->HealthCenterName}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>
                            <div class="form-group col-md-4 pt-24">

                                 <button type="button" id="refresh" class="btn btn-danger btn-sm float-right mr-2 refresh">
                                <i class="fas fa-sync-alt"></i></button>

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
        url: "{{ url('hypertension-report') }}",
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
        let responseData=response.data;
         var labels = responseData.map(entry => {
            let date = new Date(entry.IllCreateDate);
            return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        });
        var controlledData = responseData.map(entry => parseInt(entry.Controlled));
        var uncontrolledData = responseData.map(entry => parseInt(entry.Uncontrolled));
           

            // Define an array to store colors for each data point
            var colors = ['rgba(0, 128, 0, 1)', 'rgba(197,90,17, 1)'];

        var chart = Highcharts.chart('date_wise', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Number of Patients Diagnosed with HTN',
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: 'Date ',
            style: {
                fontSize: '16px'
            },
        },
        categories: labels,
        accessibility: {
            description: 'Months of the year'
        },
        labels: {
            style: {
                fontSize: '16px'
            }
        },
    },
    yAxis: [{
        title: {
            text: 'Patients',
            enabled: true,
            style: {
                fontSize: '14px'
            },
        },
        labels: {
            style: {
                fontSize: '16px'
            }
        },
         lineColor: '#000', // Set the color of the Y-axis line
        lineWidth: 1, 
    }],
    legend: {
        align: 'right',
        x: 0,
        verticalAlign: 'top',
        y: 30,
        floating: true,
        backgroundColor: 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false,
        itemStyle: {
            fontSize: '18px',
            fontWeight: 'normal'
        }
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}',
        style: {
            fontSize: '18px'
        }
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '18px',
                    fontWeight: 'normal'
                }
            },
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '16px'
            }
        }
    },
    series: [{
        name: 'Controlled',
        type: 'column',
        data: controlledData,
        color: colors[0],
    }, {
        name: 'Uncontrolled',
        type: 'column',
        data: uncontrolledData,
        color: colors[1],
    }],
    exporting: {
        buttons: {
            contextButton: {
                menuItems: [
                "printChart",
                "separator",
                "downloadPNG",
                "downloadJPEG",
                "downloadPDF",
                "downloadSVG",
                "separator",
                "downloadCSV",
                "downloadXLS",
                "viewData",
                "openInCloud"
                
                ]
            }
        }
    }
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

    $('#refresh').click(function(){
    $('#daterange').val('');
  

    $('.selectpicker').selectpicker('val', '');
    $('#date_wise').html('');
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
