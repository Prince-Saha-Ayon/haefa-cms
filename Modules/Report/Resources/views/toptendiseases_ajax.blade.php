<script>

var chartData = {!! json_encode($illnesses['diseases']) !!};
var branch = {!! json_encode($illnesses['branch']) !!};
var branchName = branch && branch.length > 0 ? branch[0].HealthCenterName : 'Unknown Branch';



    Highcharts.chart('container_diseases', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 10 Illnesses for'+ branchName
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
            } // Let Highcharts choose colors
            },
            
        },
        series: [{
            name: 'Patients',
            data: chartData.map(function(item) {
               return parseFloat(item.Patients);
            })
        }]
    });
</script>
