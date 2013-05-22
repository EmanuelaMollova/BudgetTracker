(function(){
    
    $('#all').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").attr('checked', 'checked');
    });
    
    $('#none').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").removeAttr('checked');
    });
    
}) ();