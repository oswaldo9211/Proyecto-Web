<?php

/**
	*Oswaldo Marinez Fonseca
Controlador Generico cambiar todos por instrucciones de alta y baja
*/

require('Controllers/CtrlEstandar.php');

class GenericoCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		//require('Models/GenericoMdl.php');
		//$this->model =new GenericoMdl();
	}

	function run()
	{
		$act = isset($_GET['act'])?$_GET['act'] : '';
		switch($act){
			case 'create':
				//Validar permisos en cada uno de estas
				//if($this->isAdmin())
				//	$this->create();
				//else
				//	echo "No tienes permisos";
				$this->delete();
				break;
			case 'delete':
				//Validate User and permissions
				$this->delete();
				break;
			case 'details':
				//Validate User and permissions
				$this->details();
				break;
			case 'edit':
				//Validate User and permissions
				$this->edit();
				break;
		    default:
		    	header('Location:  index.php');
		}
	}
	
	private function create(){
		include('Controllers/validacionesCtrl.php');
		//Valide variables 
		/*$vin = $_POST['nombre'];
		$brand = $_POST['correo'];
		$type = $_POST['rfc'];
		if pasa las validaciones crear un objeto
		$profesor= new Profesor($_POST['nombre'],$_POST['aPaterno'],$_POST['aMaterno'], 
													$_POST['correo'], $_POST['codigo']);
		$result= $this->modelo->getProfesor($profesor->codigo);
		//se pudo insertar
		if($this->modelo->altaProfesor($profesor, $result))
			require('views/insertarProfesorView.html');
		else
			echo 'El profesor ya esta registrado';

		//insertar en la base de datos te regresa reultado lo guardas en $result
		$result = $this->model->create($vin, $brand, $type, $model);
		if($result){
			require('Views/Created.html');
		}
		else
			require('Views/Error.html');*/

	}
	private function delete(){

	}
	private function details(){

	}
	private function edit(){

	}




	/**
	* @param string $data
	* @return string $data
	* Valide a string to be 
	*/
	private function validateNumber($data){
	}

	private function validateText($data){
	}
	
}

?>
