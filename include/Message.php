<?php

function Message($tipo, $message){
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
        </script>';
	echo "<script>
			var div = document.createElement('div');
	        div.setAttribute('class', 'alert alert-{$tipo} alert-dismissable');
	        div.setAttribute('id', 'alert');
	        var button = document.createElement('button');
	        button.setAttribute('type','button');
	        button.setAttribute('class','close');
	        button.setAttribute('data-dismiss','alert');
	        var textoboton = document.createTextNode('x');
	        button.appendChild(textoboton);
	        var texto = document.createTextNode('{$message}');
	        div.appendChild(button);
	        div.appendChild(texto);
	        message.appendChild(div);
	    </script>";
}

?>