$(document).ready(function(){
	$.ajax({
		url:'index.php?ctrl=client&act=get_all',
		dataType: 'json',
		success: function(json){
			var select = document.getElementById("client");
			for(i in json){
				texto = document.createTextNode(json[i].client_name);
				option = document.createElement('option');
				option.setAttribute('value',json[i].id_client);
				option.appendChild(texto);
				select.appendChild(option);
			}
		}
	});

	$.ajax({
		url:'index.php?ctrl=brand&act=get_all',
		dataType: 'json',
		success: function(json){
			var select = document.getElementById("brand");
			for(i in json){
				var texto = document.createTextNode(json[i].brand);
				var option = document.createElement('option');
				option.setAttribute('value',json[i].id_brand);
				option.appendChild(texto);
				select.appendChild(option);
			}
		}
	});

	$("#brand").change(function(){
		var select = document.getElementById("model");
		$('#model').html('');
		var texto = document.createTextNode('Selecciona una opcion');
		var option = document.createElement('option');
		option.appendChild(texto);
		option.setAttribute('value',"");
		select.appendChild(option);
        $.ajax({
        	type: 'POST',
			url:'index.php?ctrl=model&act=models',
			dataType: 'json',
            data:{
            	brand : $('#brand').val()
            },
			success: function(json){
				console.log(json);
				for(i in json){
					texto = document.createTextNode(json[i].model);
					option = document.createElement('option');
					option.setAttribute('value',json[i].id_model);
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
			url:'index.php?ctrl=vehicle&act=validate',
            data:{
            	VIN : $('#VIN').val()
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