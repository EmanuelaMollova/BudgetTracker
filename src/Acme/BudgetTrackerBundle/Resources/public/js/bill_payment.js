$(document).ready(function(){
    $('#bill_payment_date_when_paid').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    }); 
    
    $('#bill_payment_date_to_pay_again').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });
});


