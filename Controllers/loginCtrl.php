<?php
require('Controllers/CtrlEstandar.php');

class loginCtrl extends CtrlEstandar{
	private $model;

	function __construct(){
		require('Models/loginMdl.php');
		$this->model =new loginMdl();
	}

	function run()
	{	
		if($this->isLogged())
			header('Location:  index.php?menuprincipal');

		$act = isset($_GET['act'])?$_GET['act'] : '';
		switch($act){
			case 'login':
				$this->loguearte();
				break;
			case 'logout':
				$this->logout();
				header('Location:  index.php');
				break;
			case 'recoverPass':
				//Validate User and permissions
				$this->recoverPass();
				break;
			case 'token':
				$token = sha1(rand(0,999).rand(999,9999).rand(1,300));
				echo $token;
			break;
		    default:
				$this->loguearte();
		}
	}


	function loguearte(){
		if(empty($_POST)){
			require_once('Views/login.html');
		}
		else{
			if($this->login($_POST['username'], $_POST['password']))
				header('Location:  index.php?menuprincipal');
			else{
				require_once('Views/login.html');
				require_once('Views/Message.php');
				Messsage("menssage","error","La contrase\u00f1a o el nombre del usuario no son correctos");
			}
		}
	}

	function recoverPass(){
		if(empty($_POST)){
			require_once('Views/recuperarPassword.html');
		}
		else{
			$result = $this->model->searchEmail($_POST['email']);
			if($result){
				require_once('Views/login.html');
				require_once('Mailer/Mail.php');
				require_once('Views/Message.php');
				require_once('Models/Usuario.php');

				$mail = new Mail();
				var_dump($result);

				//The token is created for recovering the password
				$token = sha1(rand(0,999).rand(999,9999).rand(1,300));

				$this->model->actionUser((int)$result['idUsuario'], $token, "recoverPass");

				$enlace = "index.php?ctrl=usuario&act=recoverPass&token=".$token;

				$contentMail = file_get_contents('Views/Mailer/password_reset_instructions.html');
				$dicc = array('{nombre}' => $result['usuario'] , 
							  '{enlace}' => $enlace 
					);
				$contentMail = strtr($contentMail, $dicc);

				if($mail->correo($result['email'], "Olvido su Password?", $contentMail)){
					Messsage("menssageGeneral", "exito", "Las instrucciones para reestablecer su contraseña le han sido enviadas Por favor cheque su correo electrónico");
				}

			}
			else{
				require_once('Views/recuperarPassword.html');
				require_once('Views/Message.php');
				Messsage("menssage", "error", "No fue encontrado ningún usuario con esa cuenta de correo electrónico");
			}
		}
	}
}
	
?>