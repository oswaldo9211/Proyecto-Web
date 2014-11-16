<?php

	/**
	*/

class loginMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}

	public function login($user, $pass)
	{
		$user = $this->db_driver->real_escape_string($user);
		$pass = $this->db_driver->real_escape_string($pass);

		$query = "SELECT * FROM Usuario WHERE usuario='$user' and password='$pass'";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			$this->db_driver->close();
			return null;
		}
		else{
			$this->db_driver->close();
			if($result->num_rows<=0){
				return null;
			}
			else{
				$row = $result->fetch_assoc();
				return $row;
			}
		}
	}


	public function searchEmail($email)
	{
		$email = $this->db_driver->real_escape_string($email);

		$query = "SELECT * FROM Usuario WHERE email='$email'";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			$this->db_driver->close();
			die("No se pudeo hacer la consulta del login de email");
			return false;
		}
		else{
			if($result->num_rows<=0){
				$this->db_driver->close();
				return false;
			}
			else{
				$row = $result->fetch_assoc();
				return $row;
			}
		}
	}

	public function actionUser($idUsuario, $token, $accion)
	{
		$idUsuario = $this->db_driver->real_escape_string($idUsuario);
		$token = $this->db_driver->real_escape_string($token);
		$accion = $this->db_driver->real_escape_string($accion);

		$query   =	"INSERT INTO AccionUsuario
								(idUsuario,token, accion)
								VALUES('$idUsuario','$token', '$accion')";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno)
			die("No se pudeo hacer la inserccion");
		$this->db_driver->close();
		return true;
	}

}

?>
