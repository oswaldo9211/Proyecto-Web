$(document).ready(function(){
	$("#rol").change(function(){
		var select = document.getElementById("join");
		$('#join').html('');
		var texto = document.createTextNode('Selecciona una opcion');
		var option = document.createElement('option');
		option.appendChild(texto);
		option.setAttribute('value',"");
		select.appendChild(option);
           if($("#rol").val() == 'admin' || $("#rol").val() == 'employee'){
           		$.ajax({
					url:'index.php?ctrl=employee&act=get_all',
					dataType: 'json',
					success: function(json){
						for(i in json){
							texto = document.createTextNode(json[i].emp_name + " " +json[i].emp_last_name);
							option = document.createElement('option');
							option.setAttribute('value',json[i].id_employee);
							option.appendChild(texto);
							select.appendChild(option);
						}
					}
				});
           }
           else if($("#rol").val() == 'client'){
           		$.ajax({
					url:'index.php?ctrl=client&act=get_all',
					dataType: 'json',
					success: function(json){
						for(i in json){
							texto = document.createTextNode(json[i].client_name);
							option = document.createElement('option');
							option.setAttribute('value',json[i].id_client);
							option.appendChild(texto);
							select.appendChild(option);
						}
					}
				});
           }
    });

	$('#form').submit(function(){
		if(document.getElementById( "alert")){
			var alert = document.getElementById( "alert");
			alert.parentNode.removeChild(alert);
		}
		/*message for repeated passwords*/
		var p1 = document.getElementById("password");
        var p2 = document.getElementById("password_confirm");
        var message = document.getElementById("message");
  		if (p1.value != p2.value) {
            var div = document.createElement('div');
            div.setAttribute('class', 'alert alert-danger alert-dismissable');
            div.setAttribute('id', 'alert');
            var button = document.createElement('button');
            button.setAttribute('type','button');
            button.setAttribute('class','close');
            button.setAttribute('data-dismiss','alert');
            var textoboton = document.createTextNode('x');
            button.appendChild(textoboton);
            var texto = document.createTextNode('Las contraseña deben de ser iguales');
            div.appendChild(button);
            div.appendChild(texto);
            message.appendChild(div);
        	return false;
        } 
        else{
        	var error = false;
        	$.ajax({
				type: 'POST',
				url:'index.php?ctrl=user&act=validate',
	            data:{
	            	name : $('#name').val()
	            	,email : $('#email').val()
	            },
				dataType: 'json',
				async: false,
				success: function(json){
					if(json != true){
						var div = document.createElement('div');
			            div.setAttribute('class', 'alert alert-danger alert-dismissable');
			            div.setAttribute('id', 'alert');
			            var button = document.createElement('button');
			            button.setAttribute('type','button');
			            button.setAttribute('class','close');
			            button.setAttribute('data-dismiss','alert');
			            var textoboton = document.createTextNode('x');
			            button.appendChild(textoboton);
			            var texto = document.createTextNode(json);
			            div.appendChild(button);
			            div.appendChild(texto);
			            message.appendChild(div);
			        	error = true;
					}
				}
			});
			return !error;
        }
     });
});