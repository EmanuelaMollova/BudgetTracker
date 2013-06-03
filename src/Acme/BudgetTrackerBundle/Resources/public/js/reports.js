(function(){  
    $('#select_time').on('change', function(){
        

        
        if($(this).val() === 'days'){
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
        
        } else if ($(this).val() === 'months') {
            $('#month_start_date').datepicker({
                format: 'dd-mm-yyyy',
                startView: 1,
                minViewMode: 1,
                autoclose: true
            });

            $('#month_end_date').datepicker({
                format: 'dd-mm-yyyy',
                startViewMode: 1,
                minViewMode: 1,
                autoclose: true
            });
        } else {
            $('#month_start_date').datepicker({
                format: 'dd-mm-yyyy',
                startView: 2,
                minViewMode: 2,
                autoclose: true
            });

            $('#month_end_date').datepicker({
                format: 'dd-mm-yyyy',
                startViewMode: 2,
                minViewMode: 2,
                autoclose: true
            });
        }
    });
    
    $('#all').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").attr('checked', 'checked');
    });
    
    $('#none').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").removeAttr('checked');
    });
   
}) ();