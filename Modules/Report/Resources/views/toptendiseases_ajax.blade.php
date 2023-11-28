<script>

var chartData = {!! json_encode($illnesses['diseases']) !!};
var branch = {!! json_encode($illnesses['branch']) !!};
var branchName = branch && branch.length > 0 ? branch[0].HealthCenterName : 'All Branch';



    Highcharts.chart('container_diseases', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 10 Diseases in '+ branchName
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
                    fontSize: '20px',
                    fontWeight: 'bold',
                    color:'black'  // Set the desired font size
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
                text: 'No. of patients',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color:'black'  // Set the desired font size
                }
            },
            lineColor: '#000',
            lineWidth: 1,
             labels: {
                style: {
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            },
        },
        tooltip: {
                crosshairs: true,
                shared: false,
             
                style: {
                    fontSize: '13px'
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
            } // Let Highcharts choose colors
            },
            
        },
        legend: {
        itemStyle: {
            fontSize: '13px', // Set the font size for the legend name
            // Optionally, set font weight
        },
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
</script>
