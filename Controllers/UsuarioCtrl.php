<?php

/**
	*Oswaldo Marinez Fonseca
Controlador Generico cambiar todos por instrucciones de alta y baja
*/

require('Controllers/CtrlEstandar.php');

class UsuarioCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require('Models/UsuarioMdl.php');
		$this->model =new UsuarioMdl();
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
			case 'recoverPass':
				$this->recoverPass();
				break;
			case 'changePass':
				if($this->isLogged())
					$this->changePass();
				else
					header('Location:  index.php');//
				break;
		    default:
		    	header('Location:  index.php');
		}
	}
	
	private function create(){
		include('Controllers/validacionesCtrl.php');

	}
	private function delete(){

	}
	private function details(){

	}
	private function edit(){

	}

	private function recoverPass(){
		if(isset($_GET['token'])){
			$token = $_GET['token'];
			echo "token";
			//vista de recuperar contraseña 
			//buscar y eliminar el token en la base de datos
			$result = $this->model->changePass($token);
			var_dump($result);
			$this->loguear($result);
			if($this->isLogged()){
				echo 'se logeo correcto';
				require_once('Views/changePass.html');

			}
			else{
				echo 'no se logeo';
			}

			//vista de recuperar contraseña 
		}
		else{
			//vista de error
			echo 'Agregar vista de error';
		}
	}

	private function changePass(){
		if(empty($_POST)){
			require_once('Views/changePass.html');
		}
		else{
			echo 'validar las contraseñas y hacer un update de contraseña en la base de datos para el usuario';
			//validar las contraseñas y hacer un update de contraseña en la base de datos para el usuario
			//ir a la menu principal y mostrar el mensaje que se cambio
		}
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
