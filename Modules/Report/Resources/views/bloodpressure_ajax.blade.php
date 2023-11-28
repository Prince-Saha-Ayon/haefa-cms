<script>
// var BPSystolic1 = {!! json_encode($BPSystolic1) !!};
// var BPDiastolic1 = {!! json_encode($BPDiastolic1) !!};
var BPSystolic2 = {!! json_encode($BPSystolic2) !!};
var BPDiastolic2 = {!! json_encode($BPDiastolic2) !!};
var medicationData = {!! json_encode($medicationData) !!};

var distinctDates = {!! json_encode($DistinctDate) !!};

// Convert date strings to the "DD-Mon-YYYY" format
var formattedDates = distinctDates.map(function(dateString) {
    var dateParts = dateString.split('-');
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthIndex = parseInt(dateParts[1]) - 1;
    return dateParts[2]  + '-' + months[monthIndex] + '-' + dateParts[0];
});

Highcharts.chart('container_bloodp', {
    chart: {
        type: 'line'
    },
    credits: {
        enabled: false
    },
    title: {
        text: 'Control of HTN over a period of time'
    },
    xAxis: {
        categories: formattedDates,
        accessibility: {
            description: 'Months of the year'
        },
        labels: {
            style: {
                fontSize: '16px'
            }
        },
    },
    yAxis: {
        
        title: {
            text: 'mmHg',
            style: {
                fontSize: '14px',
                fontWeight: 'bold' // Adjust the font size as needed
            }
        },
        min: 40, // Set the minimum value of the Y-axis
        max: 200, // Set the maximum value of the Y-axis
        tickInterval: 20, // Set the interval between tick marks, adjust as needed
    
        gridLineColor: '#ffffff',
        labels: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold'
            }
        },
        lineColor: '#000', // Set the color of the Y-axis line
        lineWidth: 1,
        plotLines: [
            {
                value: 130, // The Systolic normal value
                color: 'green', // Line color
                dashStyle: 'ShortDot', // Line style (optional)
                width: 1, // Line width (optional)
                label: {
                    text: 'Normal Systolic BP (130) ', // Label text
                    align: 'Right',
                 style: {
                fontSize: '11px',
                fontWeight: 'bold'
                }

                    
                    // Label position adjustment
                },
                zIndex: 2 // Set the zIndex to a higher value than the other series
            },
            {
                value: 80, // The Diastolic normal value
                color: 'blue', // Line color
                dashStyle: 'ShortDot', // Line style (optional)
                width: 1, // Line width (optional)
                label: {
                    text: 'Normal Diastolic BP (80) ', // Label text
                    align: 'Right',
                 style: {
                fontSize: '11px',
                fontWeight: 'bold'
            }
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
            fontSize: '16px'
        },
        formatter: function () {
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
    legend: {
        itemStyle: {
            fontSize: '12px', // Set the font size for the legend name
            // Optionally, set font weight
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
                formatter: function () {
                    var index = this.point.index;
                    // var bpsData1 = BPSystolic1[index];
                    // var bpdData1 = BPDiastolic1[index];
                    var bpsData2 = BPSystolic2[index];
                    var bpdData2 = BPDiastolic2[index];

                    // var label1 = bpsData1;
                    // var label2 = bpdData1;
                    var label3 = bpsData2;
                    var label4 = bpdData2;

                    if (this.series.index === 2) {
                        return '<span style="font-size: 16px; color: blue;">' + label3 + '</span>'; // Adjust font size as needed
                    } else if (this.series.index === 3) {
                        return '<span style="font-size: 16px; color: purple;">' + label4 + '</span>'; // Adjust font size as needed
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
            name: 'BP Systolic',
            marker: {
                symbol: 'square'
            },
            zIndex: 1,
            data: <?php echo $BPSystolic2Numeric; ?>
        },
        {
            name: 'BP Diastolic',
            marker: {
                symbol: 'square'
            },
            zIndex: 1,
            data: <?php echo $BPDiastolic2Numeric; ?>
        }
    ]
});
</script>
