(function(){  

$('#show_returned_loans').on('mouseover', function(){
       $(this).css('cursor', 'pointer'); 
    });
    
    $('#show_returned_debts').on('click', function(){
       $('.debts').fadeToggle();  
    });
    
    $('#show_returned_loans').on('click', function(){
       $('.loans').fadeToggle();  
    });
  
}) ();