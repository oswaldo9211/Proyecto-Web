<?php
	$response = new stdClass();
	$response->mensaje = "";

	$file = $_FILES["file"]["name"];
	$file_tmp = $_FILES["file"]["tmp_name"];
	if ($file == "") {
		$response->estado = false;
   		$response->mensaje = "Error No se ha especificado ningún fichero";
	}
	else{
		$destination = "./" . $file;
		if (move_uploaded_file($file_tmp, $destination)) {
			$response->estado = true;
			$response->mensaje = "si se pudo subir";
		}
		else {
			$response->estado = false;
	   		$response->mensaje = "El archivo no se pudo subir, inténtalo mas tarde";
		}

		if (($gestor = fopen($destination, "r")) !== FALSE) {
			$response->mensaje = "TRUETRUE";
		}

		unlink($destination);
	  	
	}
  	echo json_encode($response);

?>