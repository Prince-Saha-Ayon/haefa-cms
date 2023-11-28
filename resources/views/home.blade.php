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
    <h2 class="px-5 py-4 mb-3 border text-black dt-card font-weight-bold rounded" style="font-size: 14px;">Branch Name: {{$branch_name ?? ''}}</h2>
    <div class="row g-4 pt-5">
     {{-- Disease & branch wise patient count--}}

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg1 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-syringe" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $DM_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg2 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-heartbeat" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of HTN Patients</p>
                    <h1 class="text-black text-white mb-0">
                    {{ $HTN_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg3 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-flask" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of ANC/PNC Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $ANCPNC_count??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg4 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of Pregnancy Induced Hypertension Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $PregnancyInducedHypertensionCount??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg5 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of Gestational DM Patients</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $GestationalDMCount??0 }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-5">
            <div class="info_Item bg6 d-flex align-items-center">
                <div class="iconBox d-flex align-items-center justify-content-center">
                    <i class="fa fa-bed" aria-hidden="true"></i>
                </div>
                <div class="content p-3">
                    <p class="text-black text-white text-uppercase mb-0">Total Number of Referral Case</p>
                    <h1 class="text-black text-white mb-0">
                        {{ $referred_case_count_heltcenter??0 }}
                    </h1>
                </div>
            </div>
        </div>

    </div>

    {{-- Database Sync Button --}}

    <!-- Start :: Bar Chart-->
        <div class="row py-5">

          <div class="col-md-12">
            <!-- Patient wise today's top 10 disease Start -->
            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4 style="margin:0px;">Today's top 10 disease </h4>
                @if (permission('dashboard-button'))
                <div class="ml-auto"> <!-- This div pushes the button to the right -->
                    <a href="{{ route('toptendiseases') }}" class="btn btn-primary">Details</a>
                </div>
                @endif
                </div>

            </div>

            <!-- Card -->
            <div class="dt-card">
                <!-- Card Body -->
                <div class="dt-card__body">

                    <div class="row">
                        <div class="col-md-12">
                            <figure class="highcharts-figure position-relative">
                            <div class="colorBox d-flex">
                                    <p class="mb-0 d-flex align-items-center"><span></span>0 - 100</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>101 - 150</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>>151</p>
                                </div>
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
                <h4 class="mb-0">Today's All disease</h4>
                @if (permission('dashboard-button'))
                <div class="ml-auto"> <!-- This div pushes the button to the right -->
                    <a href="{{ route('diseaseRateDateRange') }}" class="btn btn-primary">Details</a>
                </div>
                @endif
                </div>
            </div>

            <!-- Card -->
            <div class="dt-card">
                <!-- Card Body -->
                <div class="dt-card__body ">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <figure class="highcharts-figure position-relative">
                                <div class="colorBox d-flex">
                                    <p class="mb-0 d-flex align-items-center"><span></span>0 - 100</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>101 - 150</p>
                                    <p class="mb-0 d-flex align-items-center"><span></span>>151</p>
                                </div>
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
        categories: chartData.map(function(item) {
            return item.IllnessCode;
        }),
        title: {
            text: 'Diseases',
            style: {
                fontSize: '18px',
                fontWeight: 'bold',
                color:'black' // Set the desired font size
            }
        },
        labels: {
            style: {
                fontSize: '13px',
                fontWeight: 'bold'
            }
        },
    },
    yAxis: {
        title: {
            text: 'No. of Patients',
            style: {
                fontSize: '20px',
                fontWeight: 'bold',
                color:'black'  // Set the desired font size
            }
        },
        lineColor: '#000', // Set the color of the Y-axis line
        lineWidth: 1,
        labels: {
            style: {
                fontSize: '13px',
                fontWeight: 'bold'
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
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            }
        }
    },
    series: [{
        name: 'Diseases',
        showInLegend: false,
        data: chartData.map(function(item) {
            let color;
            const patientCount = parseFloat(item.Patients);
            if (patientCount >= 0 && patientCount <= 100) {
                color = 'green'; // Color for the range 0-100
            } else if (patientCount > 100 && patientCount <= 150) {
                color = 'yellow'; // Color for the range 101-150
            } else {
                color = 'red'; // Color for values greater than 150
            }
            return {
                y: patientCount,
                color: color
            };
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
        categories: chartDataAll.map(function(allitem) {
            return allitem.IllnessCode;
        }),
        title: {
            text: 'Diseases',
            style: {
                fontSize: '18px',
                fontWeight: 'bold',
                color:'black' // Set the desired font size
            }
        },
        labels: {
            style: {
                fontSize: '13px',
                fontWeight: 'bold'
            }
        },
    },
    yAxis: {
        title: {
            text: 'No. of Patients',
            style: {
                fontSize: '20px',
                fontWeight: 'bold',
                color:'black'  // Set the desired font size
            }
        },
        lineColor: '#000', // Set the color of the Y-axis line
        lineWidth: 1,
        labels: {
            style: {
                fontSize: '13px'
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
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            }
        }
    },
    series: [{
        name: 'Diseases',
        showInLegend: false,
        data: chartDataAll.map(function(allitem) {
            let color;
            const patientCount = parseFloat(allitem.Patients);
            if (patientCount >= 0 && patientCount <= 100) {
                color = 'green'; // Color for the range 0-100
            } else if (patientCount > 100 && patientCount <= 150) {
                color = 'yellow'; // Color for the range 101-150
            } else {
                color = 'red'; // Color for values greater than 150
            }
            return {
                y: patientCount,
                color: color
            };
        })
    }]
});
})
</script>
@endpush
