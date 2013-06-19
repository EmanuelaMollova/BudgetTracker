$(document).ready(function(){
          $('.alert').alert();

     
     $('.edit').editable(path, {
         cancel    : 'Cancel',
         submit    : 'Save',
         tooltip   : 'Click to edit'
     });
     

});

//------------------------------------------------------------------------------

// Unsuccessfull try

//     $('.edit').editable(function (value, settings) {
//            var data = {};
//            data[this.id] = value;
//            data["_token"] = token;
//            console.log(path);
//            console.log(data);
//            $.post(path, data);
//                return(value);
//            }, {
//                indicator:'Saving...',
//                tooltip:'Click to edit',
//                cancel:'Cancel',
//                submit:'Save'
//            });