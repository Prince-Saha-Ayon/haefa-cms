<script>

var chartData = {!! json_encode($illnesses['diseases']) !!};
var branch = {!! json_encode($illnesses['branch']) !!};
var branchName = branch && branch.length > 0 ? branch[0].HealthCenterName : 'All Branch';



    Highcharts.chart('container_diseases', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 10 Illnesses for-'+ branchName
        },
        credits: {
            enabled: false
        },
       
        xAxis: {
          
            categories: chartData.map(function(item) {
                return item.IllnessCode;
            }),
             labels: {
                style: {
                    fontSize: '14px',
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
                    fontSize: '14px'
                }
            },
        },
        tooltip: {
                crosshairs: true,
                shared: false,
             
                style: {
                    fontSize: '16px'
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
            } // Let Highcharts choose colors
            },
            
        },
        legend: {
        itemStyle: {
            fontSize: '12px', // Set the font size for the legend name
            // Optionally, set font weight
        },
    },
        series: [{
            name: 'Diseases',
            
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
</script>
