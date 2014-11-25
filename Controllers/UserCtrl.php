<?php

/**
	*Oswaldo Marinez Fonseca
Controlador Usuarios cambiar todos por instrucciones de usuarios
*/

require('Controllers/CtrlEstandar.php');

class UserCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/UserMdl.php');
		require('Models/User.php');
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
				if($this->isAdmin())
					$this->create();
				else
					require('Views/error.html');
				break;
			case 'delete':
				if($this->isAdmin())
					$this->delete();
				else
					require('Views/error.html');
				break;
			case 'details':
				if($this->isAdmin())
					$this->details();
				else
					require('Views/error.html');
				break;
			case 'edit':
				if($this->isAdmin())
					$this->edit();
				else
					require('Views/error.html');
				break;
			case 'show_all':
				if($this->isAdmin())
					$this->show_all();
				else
					require('Views/error.html');
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
			case 'profile':
				if($this->isLogged())
					$this->profile();
				else
					header('Location:  index.php');
				break;
			case 'validate':
				if($this->isAdmin())
					$this->validate();
				else
					header('Location:  index.php');
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function validate(){
		if($this->model->searchUser($_POST['name']))
			echo json_encode("El usuario ya existe");
		else if ($this->model->searchEmail($_POST['email']))
			echo json_encode("El email ya existe");
		else
			echo json_encode(true);
	}
	private function index(){
		//views for different user
		if($this->isAdmin())
			$section = file_get_contents('Views/index.html');
		else if ($this->isUser())
			$section = file_get_contents('Views/indexEmployee.html');
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
				require_once('include/Message.php');
				Message("danger", "La contraseña o el nombre del usuario no son correctos");
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
				$this->index();
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
					require('config.ini');
					require_once('Models/User.php');

					$mail = new Mail();

					//The token is created for recovering the password
					$token = sha1(rand(0,999).rand(999,9999).rand(1,300));

					$this->model->actionUser((int)$result['id_user'], $token, "recoverPass");

					$enlace = $SERVER . "index.php?ctrl=usuario&act=recoverPass&token=".$token;

					$contentMail = file_get_contents('Views/Mailer/password_reset_instructions.html');

					$dicc = array('{nombre}' => $result['user_name'] , 
								  '{enlace}' => $enlace,
								  '{server}' => $SERVER. "index.php"
						);
					$contentMail = strtr($contentMail, $dicc);

					if($mail->correo($result['user_email'], "Olvido su Password?", $contentMail)){
						require_once('include/Message.php');
						Message("success", "Las instrucciones para reestablecer su contraseña le han sido enviadas. Por favor cheque su correo electrónico");
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


	private function show_all(){
		//get all users to display
		$users =$this->model->get_all();
		$section = file_get_contents('Views/User/show_all.html');;
		$info = "";
		if($users)
			foreach ($users as $user) {
				$info .= "<tr>
			         		<td> $user[user_name]</td>
			         		<td> $user[user_email] </td>
			         		<td> $user[rol] </td>
			         		<td>
			         			<a href='index.php?ctrl=user&act=details&id=$user[id_user]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=user&act=edit&id=$user[id_user]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=user&act=delete&id=$user[id_user]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/User/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateNameUser($_POST['name']);
			$password = md5(validatePass($_POST['password']));
			$password_confirm = md5(validatePass($_POST['password_confirm']));
			$email = validateEmail($_POST['email']);
			$rol = validateName($_POST['rol']);
			$join = isset($_POST['join']) ? $_POST['join'] : NULL;

			$option = $rol == 'admin' ? 'id_employee' : ($rol == 'employee' ? 'id_employee' : ($rol == 'client' ? 'id_client' : NULL));

			$user = new User($name, $password, $email, $rol);
			$result =$this->model->create($user, $option, $join);
			if($result){
				$this->show_message("success", "El usuario creo exitosamente");
			}
			else{
				$this->show_message("danger", "No se creo, no puede haber duplicados en el nombre o el correo");
			}
		}
	}

	private function delete(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_user = $_GET['id'];
			$user =$this->model->get($id_user);
			if($user){
				$this->model->delete($id_user);
				$this->show_message("success", "El usuario se elimino exitosamente");
			}
			else{
				echo 'no existe el usuario';
			}
		}
	}

	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_user= $_GET['id'];
			$user =$this->model->get($id_user);
			if($user){

				$section = file_get_contents('Views/User/details.html');

			    $dicc = array('{nombre}' => $user['user_name']
			    			 ,'{password}' => '****'
			    			 ,'{email}' => $user['user_email']
			    			 ,'{rol}' => $user['rol']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese usuario';
			}
		}

	}

	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_user= $_GET['id'];
			$user =$this->model->get($id_user);
			if($user){

				$section = file_get_contents('Views/User/edit.html');

				//$rolName = $user['rol'] == 'admin' ? 'Administrador' : ($user['rol'] == 'employee' ? 'Empleado' : 'Cliente');

			    $dicc = array('{id}' => $user['id_user']
			    			 ,'{nombre}' => $user['user_name']
			    			 ,'{password}' => $user['password']
			    			 ,'{email}' => $user['user_email']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese usuario para editarlo';
			}
		}
		else{
			$id_user= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateNameUser($_POST['name']);
			$password = md5(validatePass($_POST['password']));
			$password_confirm = md5(validatePass($_POST['password_confirm']));
			$email = validateEmail($_POST['email']);
			$rol = validateName($_POST['rol']);
			$join = isset($_POST['join']) ? $_POST['join'] : NULL;

			$option = $rol == 'admin' ? 'id_employee' : ($rol == 'employee' ? 'id_employee' : ($rol == 'client' ? 'id_client' : NULL));

			$user = new User($name, $password, $email, $rol);

			$result =$this->model->edit($user, $id_user, $option, $join);
			if($result){
				$this->show_message("success", "El usuario se edito correctamente");
			}
			else{
				$this->show_message("danger", "No se edito no puede haber duplicados en el nombre o el correo");
			}
		}
	}


	private function profile(){
		$id_user= $this->getIdUser();
		$user =$this->model->get($id_user);
		if($user){

			$section = file_get_contents('Views/User/profile.html');


		    $dicc = array('{nombre}' => $user['user_name']
		    			 ,'{password}' => '*****'
		    			 ,'{email}' => $user['user_email']
		    			 ,'{rol}' => $user['rol']
		    	);
		    $section = strtr($section, $dicc);
			$this->template($section);
		}
		else{
			echo 'no hay Perfil';
		}
	}

	private function template($section){
		//views for different user
		if($this->isAdmin())
			$header = file_get_contents('Views/header.html');
		else if ($this->isUser())
			$header = file_get_contents('Views/headerEmployee.html');
		$footer = file_get_contents('Views/footer.html');
		$dicc = array('{user}' => $this->getUserName());
	    $header = strtr($header, $dicc);
	    
		echo $header . $section . $footer;
	}

	private function show_message($tipo, $message){
		require_once('include/Message.php');
		$this->show_all();
		Message($tipo, $message);
	}
	
}

?>