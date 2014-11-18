<?php

/**
	*Oswaldo Marinez Fonseca
Controlador Generico cambiar todos por instrucciones de alta y baja
*/

require('Controllers/CtrlEstandar.php');

class UserCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require('Models/UserMdl.php');
		$this->model =new UserMdl();
		
	}

	function run()
	{
		$act = isset($_GET['act'])?$_GET['act'] : 'index';
		switch($act){
			case 'login':
				$this->loguearte();
				break;
			case 'logout':
				$this->logout();
				header('Location:  index.php');
				break;
			case 'index':
			//validate permisos
			if($this->isLogged())
				$this->index();
			else{
				$this->loguearte();
			}
			break;
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
				if($this->isLogged())
					header('Location:  index.php');
				else{
					$this->recoverPass();
				}
				break;
			case 'changePass':
				if($this->isLogged())
					$this->changePass();
				else
					header('Location:  index.php');
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function index(){
		//Si es admin mostrar otra vista
		$section = file_get_contents('Views/index.html');
		$this->template($section);
		
	}
	
	private function loguearte(){
		if(empty($_POST)){
			require_once('Views/login.html');
		}
		else{
			if($this->login($_POST['username'], $_POST['password']))
				header('Location:  index.php');
			else{
				require_once('Views/login.html');
				require_once('Views/Message.php');
				Messsage("menssage","error","La contrase\u00f1a o el nombre del usuario no son correctos");
			}
		}
	}

	private function changePass(){
		if(empty($_POST)){
			$section = file_get_contents('Views/User/changePass.html');
			$this->template($section);
		}
		else{
			$password = $_POST['password'];
			$result= $this->model->changePassword($this->getUserName(), md5($password));
			if($result){
				//Si es admin mostrar otra vista
				$section = file_get_contents('Views/index.html');
				$this->template($section);
			}else{
				require_once('Views/error.html');//error
			}
		}
	}

	private function recoverPass(){
		/*I have the token if I look in the database to change the password*/
		if(isset($_GET['token'])){
			$token = $_GET['token']; 
			//Is sought and the token is deleted in the database
			$result = $this->model->changePass($token, "recoverPass");
			//var_dump($result);
			$this->loguear($result);
			if($this->isLogged()){
				$section = file_get_contents('Views/User/changePass.html');
				$this->template($section);
			}
			else{
				//require_once('Views/error404.html');
			}
		}
		else{
			/*If not token means that the user gave clicking Recuperar Password*/
			if(empty($_POST)){
				require_once('Views/User/recoverPassword.html');
			}
			else{
				$result = $this->model->searchEmail($_POST['email']);
				if($result){
					require_once('Views/login.html');
					require_once('mailer/Mail.php');
					require_once('Views/Message.php');
					require_once('Models/User.php');

					$mail = new Mail();
					var_dump($result);

					//The token is created for recovering the password
					$token = sha1(rand(0,999).rand(999,9999).rand(1,300));

					$this->model->actionUser((int)$result['idUsuario'], $token, "recoverPass");

					$enlace = $SERVER. "index.php?ctrl=usuario&act=recoverPass&token=".$token;

					$contentMail = file_get_contents('Views/Mailer/password_reset_instructions.html');

					$dicc = array('{nombre}' => $result['usuario'] , 
								  '{enlace}' => $enlace 
						);
					$contentMail = strtr($contentMail, $dicc);

					if($mail->correo($result['email'], "Olvido su Password?", $contentMail)){
						Messsage("menssage", "exito", "Las instrucciones para reestablecer su contraseña le han sido enviadas Por favor cheque su correo electrónico");
					}
				}
				else{
					require_once('Views/User/recoverPassword.html');
					require_once('Views/Message.php');
					Messsage("menssage", "error", "No fue encontrado ningún usuario con esa cuenta de correo electrónico");
				}
			}
		}
	}



	private function create(){

	}
	private function delete(){

	}
	private function details(){

	}
	private function edit(){

	}

	private function template($section){
		$header = file_get_contents('Views/header.html');
		$footer = file_get_contents('Views/footer.html');
		$dicc = array('{user}' => $this->getUserName());
	    $header = strtr($header, $dicc);
	    
		echo $header . $section . $footer;
	}
	
}

?>