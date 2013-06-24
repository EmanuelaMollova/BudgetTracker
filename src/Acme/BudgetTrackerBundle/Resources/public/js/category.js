$(document).ready(function(){
         
     $('.edit').editable(path, {
         cancel    : 'Cancel',
         submit    : 'Save',
         tooltip   : 'Click to edit'
     });
     
//     $('.validation').on('click','.alert', function(){        
//         console.log('working');    
//         $("input[type=text]").val('').hide();
//     });

});