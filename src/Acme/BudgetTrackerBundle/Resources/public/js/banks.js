$(document).ready(function(){
    $('.results').hide();
    
    $('button').on('click', function(){
       //var new_sum = Math.round($('#sum').val() * Math.pow((1 + $('#bank_interest').val()/100), $('#period').val())).toFixed(2);
       var new_sum = Math.round($('#sum').val() * Math.pow((1 + $('#bank_interest').val()/100), $('#period').val())).toFixed(2);
       var profit = Math.round(new_sum - $('#sum').val()).toFixed(2);
       
       $('#profit').html(profit);
       
       $('#new_sum').html(new_sum);
       
       $('.results').show();
    });   
});
