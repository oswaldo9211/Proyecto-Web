<?php

	class CtrlEstandar{

		function isLogged(){
			if( isset($_SESSION['user']) )
				return true;
			return false;
		}

		function isAdmin(){
			if( isset($_SESSION['type']) && $_SESSION['type'] == 'admin' )
				return true;
			return false;
		}

		function isUser(){
			if( isset($_SESSION['type']) && ($_SESSION['type'] == 'admin' || $_SESSION['type'] == 'employee'))
				return true;
			return false;
		}

		function isClient(){
			if( isset($_SESSION['type']) && ($_SESSION['type'] == 'admin' || $_SESSION['type'] == 'client'))
				return true;
			return false;
		}


		function logout(){
			session_unset();
			session_destroy();		
			setcookie(session_name(), '', time()-3600);
		}

		function login($username, $pass){
			$model =new UserMdl();
			$user = $model->login($username, md5($pass));
			if ($user == null){
				return false;
			}
			$_SESSION['user'] = $user['id_user'];
			$_SESSION['type'] = $user['rol'];
			$_SESSION['username'] = $username;
			return true;
		}

		function getUserName(){
			return $_SESSION['username'];
		}

		function getIdUser(){
			return $_SESSION['user'];
		}

		function loguear($usuario){
			$_SESSION['user'] = $usuario['id_user'];
			$_SESSION['type'] = $usuario['rol'];
			$_SESSION['username'] = $usuario['user_name'];
		}
	}
?>