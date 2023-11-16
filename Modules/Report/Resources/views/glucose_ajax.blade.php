<script>

  var fbgNumeric = {!! json_encode($fbgNumeric ?? '') !!};

        var rbgNumeric = {!! json_encode($rbgNumeric ?? '') !!};
        var hemoglobinNumeric = {!! json_encode($hemoglobinNumeric ?? '') !!};

        var rbg = {!! json_encode($rbg ?? '') !!};

        var fbg = {!! json_encode($fbg ?? '') !!};
        var hemoglobin = {!! json_encode($hemoglobin ?? '') !!};

        var medicationData = {!! json_encode($medicationData) !!};

        if(fbgNumeric != ''){
            $('#highcharts').removeClass('d-none');


        }


        Highcharts.chart('container_glucose', {
            chart: {
                type: 'spline'
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Patient Diabetes Report'
            },
            xAxis: {
                  title: {
            text: 'Date ',
             style: {
                fontSize: '14px' // Adjust the font size as needed
        },
        },

                categories: {!! json_encode($DistinctDate ?? '') !!},

                // categories: ['Jan 2003', 'Feb 2003', 'Mar', 'Apr', 'May', 'Jun',
                //     'Jul'
                // ],

                accessibility: {
                    description: 'Date'
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yAxis: {
        title: {
            text: 'RBG FBG ',
             style: {
                fontSize: '14px' // Adjust the font size as needed
        },
        },
        labels: {
            style: {
                fontSize: '14px'
            }
        },
        legend: {
        itemStyle: {
            fontSize: '12px', // Set the font size for the legend name
            // Optionally, set font weight
        },
    },
    plotLines: [{
      value: 7, // The FBG normal value
      color: 'green', // Line color
      dashStyle: 'ShortDash', // Line style (optional)
      width: 2, // Line width (optional)
      label: {
        text: 'Normal FBG: 7', // Label text
        align: 'center',
         // Label position adjustment
      },
      zIndex: 2 // Set the zIndex to a higher value than the other series
    }],
        
    },
            tooltip: {
                crosshairs: true,
                shared: false,
                style: {
                    fontSize: '16px', // Adjust the font size here
                 },
                formatter: function() {
                var index = this.point.index;
                var medicationDataForDate = medicationData[index];

                var tooltipContent = '<b>Date: ' + this.x + '</b><br>';
                if (medicationDataForDate) {
                    tooltipContent += '<br>Medications:<br>';
                    for (var i = 0; i < medicationDataForDate.length; i++) {
                        tooltipContent += medicationDataForDate[i].DrugCode + '<br>';
                    }
                }

                return tooltipContent;
            },
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
                            var rbgData = rbg[index];
                            var fbgData = fbg[index];
                            var hemoglobinData = hemoglobin[index];


                            var label1 =  rbgData;
                            var label2 = fbgData;
                            var label3 = hemoglobinData;


                            if (this.series.index ===0) {
                                return label1;
                            }else if (this.series.index ===1) {
                                return label2;
                            }else if (this.series.index ===2) {
                                return label3;
                            }

                        },
                        style: {
                            fontSize: '16px'
                        }
                    }
                }
            },
            series: [
                {
                    name: 'RBG (mmol/L)',
                    marker: {
                        symbol: 'square'
                    },
                    // data: [5.22, 5.7, 8.7, 13.9, 18.2, 21.4, 1.0]
                    data: <?php echo $rbgNumeric ?? '0' ; ?>,
                    zIndex: 1
                },
                {name: 'FBG (mmol/L)',
                    marker: {
                        symbol: 'square'
                    },
                    data: <?php echo $fbgNumeric ?? '0' ; ?>,
                    zIndex: 1
                },

                {name: 'Hemoglobin (m/dL)',
                    marker: {
                        symbol: 'square'
                    },
                    data: <?php echo $hemoglobinNumeric ?? '0' ; ?>,
                    zIndex: 1
                },

            ]

        });


</script>