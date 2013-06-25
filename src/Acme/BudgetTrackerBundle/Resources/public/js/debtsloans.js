$(document).ready(function(){
    
    $('#debtloan_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    }); 

    $('#show_returned_debts').on('click', function(){
       $('.debts').fadeToggle();  
    });
    
    $('#show_returned_loans').on('click', function(){
       $('.loans').fadeToggle();  
    });
  
});