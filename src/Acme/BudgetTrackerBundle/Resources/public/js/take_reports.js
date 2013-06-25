 $(document).ready(function(){
    $('u').on('mouseover', function(){
       $(this).css('cursor', 'pointer'); 
    });
    
    $('u').on('click', function(ev){
        ev.preventDefault();
        var elem = $(this).text();
        $('.'+elem).fadeToggle();
    });
    
    $('.toggle_expenses').on('click', function(){
       $('.products').fadeToggle();  
    });
 });