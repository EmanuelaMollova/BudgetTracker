$(document).ready(function(){
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
