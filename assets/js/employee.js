$(document).ready(function(){
	$.ajax({
		url:'index.php?ctrl=employee&act=states',
		dataType: 'json',
		success: function(json){
			var select = document.getElementById("state");
			for(i in json){
				var texto = document.createTextNode(json[i].edo_nombre);
				var option = document.createElement('option');
				option.setAttribute('value',json[i].edo_id);
				option.appendChild(texto);
				select.appendChild(option);
			}
		}
	});


	$("#state").change(function(){
		var select = document.getElementById("city");
		$('#city').html('');
		var texto = document.createTextNode('Selecciona una opcion');
		var option = document.createElement('option');
		option.appendChild(texto);
		option.setAttribute('value',"");
		select.appendChild(option);
        $.ajax({
        	type: 'POST',
			url:'index.php?ctrl=employee&act=cities',
			dataType: 'json',
            data:{
            	state : $('#state').val()
            },
			success: function(json){
				console.log(json);
				for(i in json){
					texto = document.createTextNode(json[i].ciu_nombre);
					option = document.createElement('option');
					option.setAttribute('value',json[i].ciu_id);
					option.appendChild(texto);
					select.appendChild(option);
				}
			}
		});
    });

	$('#form').submit(function(){
		if(document.getElementById( "alert")){
			var alert = document.getElementById( "alert");
			alert.parentNode.removeChild(alert);
		}
		/*validate form*/
    	var error = false;
    	$.ajax({
			type: 'POST',
			url:'index.php?ctrl=employee&act=validate',
            data:{
            	RFC : $('#RFC').val()
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
     });
});