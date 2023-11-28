<script>

  var fbgNumeric = {!! json_encode($fbgNumeric ?? '') !!};



        var fbg = {!! json_encode($fbg ?? '') !!};

        var medicationData = {!! json_encode($medicationData) !!};
        var distinctDates = {!! json_encode($DistinctDate) !!};

// Convert date strings to the "DD-Mon-YYYY" format
var formattedDates = distinctDates.map(function(dateString) {
    var dateParts = dateString.split('-');
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthIndex = parseInt(dateParts[1]) - 1;
    return dateParts[2]  + '-' + months[monthIndex] + '-' + dateParts[0];
});

        if(fbgNumeric != ''){
            $('#highcharts').removeClass('d-none');


        }


        Highcharts.chart('container_glucose', {
            chart: {
                type: 'line'
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Control of DM over a period of time'
            },
            xAxis: {
                  title: {
            text: '',
             style: {
                fontSize: '14px', // Adjust the font size as needed
                fontWeight: 'bold',
                color:'black'
        },
        },

        categories: formattedDates,

                // categories: ['Jan 2003', 'Feb 2003', 'Mar', 'Apr', 'May', 'Jun',
                //     'Jul'
                // ],

                accessibility: {
                    description: 'Date'
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold' 
                    }
                }
            },
    yAxis: {
        title: {
            text: 'FBG (mmol/L)',
             style: {
                fontSize: '14px',
                fontWeight: 'bold',
                color:'black'
            },
        },
        labels: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold'
            }
        },
        lineColor: '#000', // Set the color of the Y-axis line
        lineWidth: 1,
        min: 0, // Set the minimum value of the Y-axis
        tickInterval: 2, // Set the interval between tick marks, adjust as needed
        gridLineColor: '#ffffff',
        legend: {
        itemStyle: {
            fontSize: '12px', // Set the font size for the legend name
            fontWeight: 'bold'
            // Optionally, set font weight
        },
    },
    plotLines: [{
            value: 7, // The FBG normal value
            color: 'yellow', // Line color
            dashStyle: 'Solid', // Line style (optional)
            width: 2, // Line width (optional)
            label: {
                text: 'Diabetes', // Label text
                align: 'center',
                // Label position adjustment
            },
            zIndex: 2 // Set the zIndex to a higher value than the other series
            },
            {
            value: 6, // The FBG normal value
            color: 'green', // Line color
            dashStyle: 'Solid', // Line style (optional)
            width: 2, // Line width (optional)
            label: {
                text: 'Normal', // Label text
                align: 'center',
                // Label position adjustment
            },
            zIndex: 2 // Set the zIndex to a higher value than the other series
            },
            {
            value: 4, // The FBG normal value
            color: 'red', // Line color
            dashStyle: 'Solid', // Line style (optional)
            width: 2, // Line width (optional)
            label: {
                text: 'Hypoglycemia', // Label text
                align: 'center',
                // Label position adjustment
            },
            zIndex: 2 // Set the zIndex to a higher value than the other series
            }
        ],
        
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
                            var fbgData = fbg[index];

                            var label2 = fbgData;

                            if (this.series.index ===1) {
                                return label2;
                            }

                        },
                        style: {
                            fontSize: '16px'
                        }
                    }
                }
            },
            series: [
                
                {name: 'FBG (mmol/L)',
                    marker: {
                        symbol: 'square'
                    },
                    data: <?php echo $fbgNumeric ?? '0' ; ?>,
                    zIndex: 1
                }

            ],
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


</script>