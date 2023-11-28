@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
.colorBox{
    left: 0px;
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
                        <div class="col-md-12">
                            <figure class="highcharts-figure position-relative">
                                <div class="colorBox d-flex">
                                    <p class="mb-0 d-flex align-items-center"><span></span>0 - 100</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>101 - 150</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>>151</p>
                                </div>
                                <div id="date_wise"></div>
                            </figure>
                        </div>
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
          
            var branchName = response.healthcenter && response.healthcenter != '' ? response.healthcenter : 'All Branch';

            // Define an array to store colors for each data point
            var colors = [];

            // Define custom colors based on the count (y-value)
            for (var i = 0; i < data.length; i++) {
                var count = data[i][1];
                var color;

                if (count >= 0 && count <= 100) {
                   color = 'green';
                } else if (count >= 100 && count <= 150) {
                    color = '#ffd700';
                }  else {
                    color = 'red';
                }

                colors.push(color); // Add the color to the array
            }

            var chart = Highcharts.chart('date_wise', {
                title: {
                    text: branchName + ' Disease',
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    title: {
                        text: 'Diseases',
                        style: {
                            fontSize: '18px',
                            fontWeight: 'bold',
                            color:'black'  // Set the desired font size
                        }
                    },
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: [{
                    title: {
                        text: 'No. of patients',
                        style: {
                            fontSize: '18px',
                            fontWeight: 'bold',
                            color:'black'  // Set the desired font size
                        }
                    },
                    lineColor: '#000',
                    lineWidth: 1
                }],
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            format: '{y}',
                            style: {
                                fontSize: '13px',
                                fontWeight: 'bold'
                            }
                        },
                        colorByPoint: true, // Use colorByPoint to specify colors for each data point
                    },
                },
                series: [{
                    name: 'Diseases',
                    type: 'column',
                    data: data,
                    showInLegend: false,
                    colors: colors, // Use the custom colors array
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
                            //"downloadCSV",
                            //"downloadXLS",
                            //"viewData",
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
