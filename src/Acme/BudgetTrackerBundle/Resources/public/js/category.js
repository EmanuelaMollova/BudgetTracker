$(document).ready(function(){
     $('.edit').editable(function (value, settings) {
            var data = {};
            data[this.id] = value;
            data["_token"] = token;
            console.log(path);
            console.log(data);
            $.post(path, data);
                return(value);
            }, {
                indicator:'Saving...',
                tooltip:'Click to edit',
                cancel:'Cancel',
                submit:'Save'
            });
});