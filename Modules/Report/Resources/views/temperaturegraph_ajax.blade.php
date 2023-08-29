<script>

var CurrentTemparature1 = {!! json_encode($CurrentTemparature1) !!};


Highcharts.chart('container_temperature', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Temperature Graph'
    },
    credits: {
        enabled: false
    },
    xAxis: {

        categories: {!! json_encode($DistinctDate) !!},

        // categories: ['Jan 2003', 'Feb 2003', 'Mar', 'Apr', 'May', 'Jun',
        //     'Jul'
        // ],

        accessibility: {
            description: 'Months of the year'
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yAxis: {
        title: {
            text: 'Temperature '
        },
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: false
    },

    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            },
            dataLabels: {
                enabled: true,
                formatter: function() {
                    var index = this.point.index;
                    var CurrentTemparature = CurrentTemparature1[index];

                    var label =  CurrentTemparature;
                    return label;

                },
                style: {
                    fontSize: '12px'
                }
            }
        }
    },
    series: [{
            name: 'Temperature',
            marker: {
                symbol: 'square'
            },
            // data: [5.22, 5.7, 8.7, 13.9, 18.2, 21.4, 1.0]
            data: <?php echo $CurrentTemparature1Numeric; ?>
        }
    ]

});
</script>
