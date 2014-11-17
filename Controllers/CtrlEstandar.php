<?php

	class CtrlEstandar{

		function isLogged(){
			if( isset($_SESSION['user']) )
				return true;
			return false;
		}

		function isAdmin(){
			if( isset($_SESSION['type']) && $_SESSION['type'] == '1' )
				return true;
			return false;
		}

		function isUser(){
			if( isset($_SESSION['type']) && $_SESSION['type'] == '0' )
				return true;
			return false;
		}


		function logout(){
			session_unset();
			session_destroy();		
			setcookie(session_name(), '', time()-3600);
		}

		function login($usuario, $pass){
			$model =new UserMdl();
			$result = $model->login($usuario, md5($pass));
			if ($result == null){
				return false;
			}
			$_SESSION['user'] = $result['idUsuario'];
			$_SESSION['type'] = $result['rol'];
			$_SESSION['username'] = $usuario;
			return true;
		}

		function getUserName(){
			return $_SESSION['username'];
		}

		function loguear($usuario){
			$_SESSION['user'] = $usuario['idUsuario'];
			$_SESSION['type'] = $usuario['rol'];
			$_SESSION['username'] = $usuario['usuario'];
		}
	}
?>