$(document).ready(function(){
    
    if(chart == true){
        Highcharts.setOptions({
            colors: ['#759E1A', '#AA3333']
        });

        $('#container').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false
                        },
                        title: {
                            text: ''
                        },
                        tooltip: {
                            formatter: function () {
                                return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 0) + '%</b>';
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    color: '#000000',
                                    connectorColor: '#000000',
                                    formatter: function() {
                                        return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                                    }
                                }
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Total',
                            data: [
                                ['Remaining', remaining], ['Spent', spent]
                            ]
                        }]
                    });
    }

    $('u').on('mouseover', function(){
       $(this).css('cursor', 'pointer'); 
    });
    
    $('u').on('click', function(){
        var elem = $(this).text();
        $('.'+elem).fadeToggle();
    });

    $('button').on('click', function(){
        $('.products').fadeToggle();  
    });
});
