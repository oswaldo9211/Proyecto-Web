$(document).ready(function(){
	$.ajax({
		url:'index.php?ctrl=location&act=get_all',
		dataType: 'json',
		success: function(json){
			var select = document.getElementById("locations");
			for(i in json){
				var texto = document.createTextNode(json[i].location_name);
				var option = document.createElement('option');
				option.setAttribute('value',json[i].id_location);
				option.appendChild(texto);
				select.appendChild(option);
			}
			console.log(json);
		}
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
			url:'index.php?ctrl=service&act=validate',
            data:{
            	name : $('#name').val()
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