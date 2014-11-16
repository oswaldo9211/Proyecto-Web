<?php

	/**
	*/

class UsuarioMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}

	public function changePass($token)
	{
		$user = $this->db_driver->real_escape_string($token);

		$query = "SELECT * FROM AccionUsuario WHERE token='$token'";
		$result = $this ->db_driver-> query($query);

		if($this->db_driver->errno){
			$this->db_driver->close();
			return false;
		}
		else{
			if($result->num_rows<=0){
				$this->db_driver->close();
				return false;
			}
			else{
				$row = $result->fetch_assoc();
				//$query = "DELETE FROM AccionUsuario WHERE token='$token'";
				//$result = $this ->db_driver-> query($query);
				$row = $this->getUsuario($row['idUsuario']);
				$this->db_driver->close();
				return $row;
			}
		}
	}

	public function getUsuario($idUsuario)
	{
		$idUsuario = $this->db_driver->real_escape_string($idUsuario);
		$query = "SELECT * FROM Usuario WHERE idUsuario='$idUsuario'";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			$this->db_driver->close();
			return null;
		}
		else{
			if($result->num_rows<=0){
				$this->db_driver->close();
				return null;
			}
			else{
				$row = $result->fetch_assoc();
				return $row;
			}
		}
	}

}

?>
