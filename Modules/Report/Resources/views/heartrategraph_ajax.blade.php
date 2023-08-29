<script>

var HeartRate1 = {!! json_encode($HeartRate1) !!};


Highcharts.chart('container_heart', {
    chart: {
        type: 'spline'
    },
    credits: {
        enabled: false
    },
    title: {
        text: 'Heart Rate Graph'
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
            text: 'Heart Rate '
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
                    var HeartRate = HeartRate1[index];

                    var label =  HeartRate;
                    return label;

                },
                style: {
                    fontSize: '12px'
                }
            }
        }
    },
    series: [{
            name: 'Heart Rate',
            marker: {
                symbol: 'square'
            },
            // data: [5.22, 5.7, 8.7, 13.9, 18.2, 21.4, 1.0]
            data: <?php echo $HeartRate1Numeric; ?>
        }
    ]

});
</script>
