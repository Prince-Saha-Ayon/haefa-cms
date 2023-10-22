@extends('layouts.app')

@push('stylesheet')
<link rel="stylesheet" href="css/chart.min.css">
@endpush

    <script src="js/highcharts.js"></script>
    <script src="js/series-label.js"></script>
    <script src="js/exporting.js"></script>
    <script src="js/export-data.js"></script>
    <script src="js/accessibility.js"></script>

@section('content')
<div class="dt-content">
    <h2 class="p-3 border  bg-info text-white font-weight-bold rounded" style="font-size: 14px;">Branch Name: {{$branch_name}}</h2>
    <div class="row pt-5">

      <div class="col-xl-3 col-sm-5">
        <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
          <h2 class="text-black mt-1 p-3">Total Number of DM Patients</h2>
        </div>
      </div>

      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
          <h2 class="text-black mt-1 p-3">Total Number of HTN Patients</h2>
        </div>
      </div>

      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
          <h2 class="text-black mt-1 p-3">Total Number of ANC/PNC Patients</h2>
        </div>
      </div>

      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height align-items-center pt-5">
          <h2 class="text-black mt-1 p-3">Total Number of ANC/PNC Patients</h2>
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

          <!-- Temperature graph  -->

          <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4>Temperature Graph </h4>
                </div>
            </div>

          <!-- Card -->
          <div class="dt-card">

          <!-- Card Body -->
          <div class="dt-card__body">

              <div class="row">
                  <div class="col-md-12">
                      <figure class="highcharts-figure">
                          <div id="container_temperature"></div>
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
<script src="js/chart.min.js"></script>
<script src="js/highcharts.js"></script>
<script>

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


</script>
@endpush
