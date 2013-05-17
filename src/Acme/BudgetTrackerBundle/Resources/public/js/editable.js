(function(){
//    $('table')
//    .on('click', 'span', function(){
//       $(this).attr('contenteditable', 'true').toggleClass('sele').removeClass('high'); 
//    })
//    .on('mouseenter', 'span', function(){
//        if(!$(this).attr('contenteditable')) {
//            $(this).addClass('high');
//        }
//    })
//    .on('mouseleave', 'span', function(){
//        $(this).removeClass('high');
//    })
//    .on('keyup', 'span', function(event){
//        if(event.keyCode === 13){
//            event.preventDefault();
//            console.log(event);
//        }
//    })

var nowTemp = new Date();
var bl = $('#expense_description').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        autoclose: true,
        todayHighlight: true
    });
    
         
}) ();