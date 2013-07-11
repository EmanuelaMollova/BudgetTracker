$(document).ready(function(){
    $.fn.fadeSlideToggle = function(speed, fn) {
        return $(this).animate({
            'height': 'toggle',
            'opacity': 'toggle'
        }, speed || 300, fn);
    };
    
    $('.badge').on('click', function(e){
        console.log('work');
        e.preventDefault();
        $('#notifications').fadeSlideToggle(); 
    })   
});