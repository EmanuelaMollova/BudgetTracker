(function(){  
    $('#month_start_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });

    $('#month_end_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });
            
//                            $.ajax({
//          url: "",
//          context: document.body,
//          success: function(s,x){
//            $(this).html(s);
//          }
//        });
    
//        $('#none').on('click', function () {
//        $("input[type='checkbox']").removeAttr('checked');
//    });
//    
//    $('#all').on('click', function () {
//        $("input[type='checkbox']").attr('checked', 'checked');
//    });

 
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
    
    $('#chbx').on('click', function(){
        $("input[type='checkbox']").prop("checked", $('#chbx').prop("checked"))
    });
    
   
}) ();