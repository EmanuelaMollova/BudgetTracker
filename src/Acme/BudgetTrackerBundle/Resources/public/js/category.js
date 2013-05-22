(function(){
     $('.edit').editable(function (value, settings) {
            var data = {};
            data[this.id] = value;
            //data["_token"] = "{{form._token.vars.value}}";
            data["_token"] = token;
            $.post(path, data);
            //$.post("{{ path('edit_category', { 'id': category.id}) }}", data);
            //console.log('The value:   ');
            //console.log(value);
            //console.log('The data:   ');
            //console.log(cat.id);
                return(value);
            }, {
                indicator:'Saving...',
                tooltip:'Click to edit',
                cancel:'Cancel',
                submit:'Save'
            });
}) ();