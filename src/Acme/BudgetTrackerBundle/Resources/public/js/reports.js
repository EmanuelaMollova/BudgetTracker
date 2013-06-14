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
    
//        $('#none').on('click', function (e) {
//        e.preventDefault();
//        $("input[type='checkbox']").removeAttr('checked');
//    });
//    
//    $('#all').on('click', function (e) {
//        e.preventDefault();
//        $("input[type='checkbox']").attr('checked', 'checked');
//    });
    

   
}) ();