@extends('layouts.app')

@push('stylesheet')
<link rel="stylesheet" href="css/chart.min.css" crossorigin="anonymous">
@endpush
@push('script')
    <script src="js/highcharts.js" crossorigin="anonymous"></script>
    <script src="js/chart.min.js" crossorigin="anonymous"></script>
    <script src="js/series-label.js" crossorigin="anonymous"></script>
    <script src="js/exporting.js" crossorigin="anonymous"></script>
    <script src="js/export-data.js" crossorigin="anonymous"></script>
    <script src="js/accessibility.js" crossorigin="anonymous"></script>
@endpush
@section('content')
<div class="dt-content">
    <h2 class="p-3 border text-black dt-card font-weight-bold rounded" style="font-size: 14px;">Branch Name: {{$branch_name??''}}</h2>
    <div class="row pt-5">
     {{-- Disease & branch wise patient count--}}

        <div class="col-xl-3 col-lg-3 col-sm-4">
            <div class="info_Item bg1 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-address-book" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $DM_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-4">
            <div class="info_Item bg2 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                <i class="fa fa-flask" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $DM_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-4">
            <div class="info_Item bg3 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                <i class="fa fa-deaf" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $DM_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-4">
            <div class="info_Item bg4 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $DM_count??0 }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- <div class="col-xl-3 col-sm-5 p-3">
            <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
                <h4 class="text-black mt-1 p-2">Total Number of HTN Patients</h4>
                <h5 class="text-black mt-1 p-1">
                    {{ $HTN_count??0 }}
                </h5>
            </div>
        </div>

        <div class="col-xl-3 col-sm-5 p-3">
            <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
                <h4 class="text-black mt-1 p-2">Total Number of ANC/PNC Patients</h4>
                <h5 class="text-black mt-1 p-1">
                    {{ $ANCPNC_count??0 }}
                </h5>
            </div>
        </div>

        <div class="col-xl-3 col-sm-5 p-3">
            <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
                <h4 class="text-black mt-1 p-2">Total Number of Pregnancy Induced Hypertension Patients</h4>
                <h5 class="text-black mt-1 p-1">
                    {{ $PregnancyInducedHypertensionCount??0 }}
                </h5>
            </div>
        </div>

        <div class="col-xl-3 col-sm-5 p-3">
            <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
                <h4 class="text-black mt-1 p-2">Total Number of Gestational DM Patients</h4>
                <h5 class="text-black mt-1 p-1">
                    {{ $GestationalDMCount??0 }}
                </h5>
            </div>
        </div>

        {{--        Referred helthcenter name with patient count--}}
        <div class="col-xl-3 col-sm-5 p-3">
            <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
                <h4 class="text-black mt-1 p-2">Total Number of Referral Case</h4>
                <h5 class="text-black mt-1 p-1">
                    {{ $referred_case_count_heltcenter??0 }}
                </h5>
            </div>
        </div> -->

    </div>

    {{-- Database Sync Button --}}

    <!-- Start :: Bar Chart-->
        <div class="row py-5">

          <div class="col-md-12">
            <!-- Patient wise today's top 10 disease Start -->
            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4 style="margin:0px;">Today's top 10 disease </h4>
                <div class="ml-auto"> <!-- This div pushes the button to the right -->
                    <a href="{{ route('toptendiseases') }}" class="btn btn-primary">Details</a>
                </div>
                </div>

            </div>

            <!-- Card -->
            <div class="dt-card">
                <!-- Card Body -->
                <div class="dt-card__body">

                    <div class="row">
                        <div class="col-md-12">
                            <figure class="highcharts-figure">
                                <div id="container_diseases"></div>
                            </figure>
                        </div>
                    </div>

                </div>
                <!-- /card body -->
            </div>
            <!-- /card -->
            <!-- Patient wise today's top 10 disease End -->

            <!-- heart rate graph -->
            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4>Today's All disease </h4>
                <div class="ml-auto"> <!-- This div pushes the button to the right -->
                    <a href="{{ route('diseaseRateDateRange') }}" class="btn btn-primary">Details</a>
                </div>
                </div>
            </div>

            <!-- Card -->
            <div class="dt-card">
                <!-- Card Body -->
                <div class="dt-card__body">
                    <div class="row">
                        <div class="col-md-12">
                            <figure class="highcharts-figure">
                                <div id="container_alldiseases"></div>
                            </figure>
                        </div>
                    </div>
                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->



          
          </div>
        </div>
        <!-- End :: Bar Chart-->

  </div>
@endsection


@push('script')
<script>
$(document).ready(function(){
// Top ten disease
var chartData = {!! $illnesses['diseases'] !!};

Highcharts.chart('container_diseases', {
    chart: {
        type: 'column'
    },
    title: {
        text: `Today's Top 10 Disease`
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: 'Diseases'
        },
        categories: chartData.map(function(item) {
            return item.IllnessCode;
        }),
        labels: {
            style: {
                fontSize: '9px',
                fontWeight: 'bold'
            }
        },
    },
    yAxis: {
        title: {
            text: 'Patients'
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        },
    },
    plotOptions: {
        column: {
            colorByPoint: true,
            dataLabels: {
                enabled: true, // Display data labels on top of bars
                format: '{y}', // Display the y-value (patient count)
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            }
        }
    },
    series: [{
        name: 'Patients',
        data: chartData.map(function(item) {
            return parseFloat(item.Patients);
        })
    }]
});

// All disease
var chartDataAll = {!! $all_illnesses !!};

Highcharts.chart('container_alldiseases', {
    chart: {
        type: 'column'
    },
    title: {
        text: `Today's All Disease`
    },
    credits: {
        enabled: false
    },
    xAxis: {
        title: {
            text: 'Diseases'
        },
        categories: chartDataAll.map(function(allitem) {
            return allitem.IllnessCode;
        }),
        labels: {
            style: {
                fontSize: '9px',
                fontWeight: 'bold'
            }
        },
    },
    yAxis: {
        title: {
            text: 'Patients'
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        },
    },
    plotOptions: {
        column: {
            colorByPoint: true,
            dataLabels: {
                enabled: true, // Display data labels on top of bars
                format: '{y}', // Display the y-value (patient count)
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            }
        }
    },
    series: [{
        name: 'Patients',
        data: chartDataAll.map(function(allitem) {
            return parseFloat(allitem.Patients);
        })
    }]
});
})
</script>
@endpush
