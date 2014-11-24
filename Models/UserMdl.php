<?php

	/**
	*/

class UserMdl {
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
				$query = "DELETE FROM AccionUsuario WHERE token='$token'";
				$result = $this ->db_driver-> query($query);
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

	public function changePassword($usuario, $password)
	{
		$password = $this->db_driver->real_escape_string($password);
		$query = "UPDATE Usuario SET  password='$password' WHERE usuario='$usuario'";
		$result = $this ->db_driver-> query($query);

		if($this->db_driver->errno){
			$this->db_driver->close();
			return false;
		}
		if($result){
			return true;
		}
		else
			return false;
	}

	public function get_all(){
		$query = "SELECT * FROM Usuario";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar todos");
			return false;
		}
		if($result->num_rows<=0){
				return false;
		}
		else{
			while ($row = $result->fetch_array()){
				$rows[] = $row;
			}
			return $rows;
		}
	}

	public function get($id_user){

		$query = "SELECT * FROM Usuario  WHERE idUsuario='$id_user'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($user){
		$name = $this->db_driver->real_escape_string($user->name);
		$password = $this->db_driver->real_escape_string($user->password);
		$email = $this->db_driver->real_escape_string($user->email);
		$rol = $this->db_driver->real_escape_string($user->rol);

		$query   =	"INSERT INTO Usuario
					(usuario, password, email, rol)
					VALUES('$name','$password', '$email', '$rol')";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function edit($user, $id_user){

		$name = $this->db_driver->real_escape_string($user->name);
		$password = $this->db_driver->real_escape_string($user->password);
		$email = $this->db_driver->real_escape_string($user->email);
		$rol = $this->db_driver->real_escape_string($user->rol);


		$query   =	"UPDATE Usuario
					set usuario = '$name', password = '$password', email = '$email', rol = '$rol'
					WHERE idUsuario='$id_user'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de editar");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_user){
		$query = "DELETE  FROM Usuario  WHERE idUsuario='$id_user'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

}

?>
