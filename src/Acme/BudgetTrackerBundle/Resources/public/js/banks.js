$(document).ready(function(){
    
    $('button').on('click', function(){
//       var new_sum = Math.round($('#sum').val() * Math.pow((1 + $('#sum').val()/100), $('#period').val())).toFixed(2);
//       var profit = Math.round(new_sum - $('#sum').val()).toFixed(2);
        
        if(!$('#sum').val() || !$('#interest').val() || !$('#period').val()){
            $('.products').hide();
            $('#error').show();         
        } else {           
            var sum = parseFloat($('#sum').val());
            var interest = parseFloat($('#interest').val());
            var period = parseFloat($('#period').val());

           var profit = parseFloat(((sum*interest/100/12)*period).toFixed(2));
           var profit_tax = parseFloat((profit - profit*10/100).toFixed(2));  
           var new_sum = parseFloat((sum + profit_tax).toFixed(2));       

           $('#profit').html(profit);
           $('#profit_tax').html(profit_tax);
           $('#months').html(period);
           $('#new_sum').html(new_sum);

           $('.products').show();
           $('#error').hide();
        }
    });   
});
