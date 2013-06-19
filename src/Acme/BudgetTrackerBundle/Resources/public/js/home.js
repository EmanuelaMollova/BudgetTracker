$(document).ready(function(){
    $('span').on('click', function(ev){
        ev.preventDefault();
       alert("BAAAAAAAAAAAA!"); 
    });
    
    $('u').on('mouseover', function(){
       $(this).css('cursor', 'pointer'); 
    });
    
    $('u').on('click', function(ev){
        ev.preventDefault();
        var elem = $(this).text();
        $('.'+elem).fadeToggle();
    });
    
    $('button').on('click', function(){
       $('.products').fadeToggle();  
    });
});
