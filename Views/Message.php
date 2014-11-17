<?php

function Messsage($etiqueta, $tipo, $menssage){
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
        </script>';
	echo '<link rel="stylesheet" type="text/css" href="assets/stylesheets/message.css" />
				<script>';
	echo '$("#'.$etiqueta.'").after( "<p><p/>");';
	echo '$("#'.$etiqueta.'").addClass("'.$tipo.' mensajes");';
	echo 'var menssage = document.getElementById("'.$etiqueta.'");
				menssage.innerHTML = "'.$menssage.'";';
	echo '$(document).ready(function(){
    	setTimeout(function(){ $(".mensajes").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 3000);  
  	});
	</script>';
}

?>