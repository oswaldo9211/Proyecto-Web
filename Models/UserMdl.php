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

		$query = "SELECT * FROM User WHERE user_name='$user' and password='$pass'";

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

		$query = "SELECT * FROM User WHERE user_email='$email'";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			//die("No se pudeo hacer la consulta del login de email");
			return false;
		}
		else{
			if($result->num_rows<=0){
				return false;
			}
			else{
				$row = $result->fetch_assoc();
				return $row;
			}
		}
	}

	public function searchUser($user_name)
	{
		$user_name = $this->db_driver->real_escape_string($user_name);

		$query = "SELECT * FROM User WHERE user_name='$user_name'";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			return false;
		}
		else{
			if($result->num_rows<=0){
				return false;
			}
			else{
				$row = $result->fetch_assoc();
				return $row;
			}
		}
	}

	public function actionUser($id_user, $token, $action)
	{
		$id_user = $this->db_driver->real_escape_string($id_user);
		$token = $this->db_driver->real_escape_string($token);
		$action = $this->db_driver->real_escape_string($action);

		$query   =	"INSERT INTO ActionUser
								(id_user,token, action)
								VALUES('$id_user','$token', '$action')";

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno)
			die("No se pudeo hacer la inserccion");
		$this->db_driver->close();
		return true;
	}


	public function changePass($token)
	{
		$token = $this->db_driver->real_escape_string($token);

		$query = "SELECT * FROM ActionUser WHERE token='$token'";
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
				$query = "DELETE FROM ActionUser WHERE token='$token'";
				$result = $this ->db_driver-> query($query);
				$row = $this->getUsuario($row['id_user']);
				$this->db_driver->close();
				return $row;
			}
		}
	}

	public function getUsuario($id_user)
	{
		$id_user = $this->db_driver->real_escape_string($id_user);
		$query = "SELECT * FROM User WHERE id_user='$id_user'";

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

	public function changePassword($user, $password)
	{
		$user = $this->db_driver->real_escape_string($user);
		$password = $this->db_driver->real_escape_string($password);
		$query = "UPDATE User SET  password='$password' WHERE user_name='$user'";
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
		$query = "SELECT * FROM User WHERE status='high'";
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

		$query = "SELECT * FROM User  WHERE id_user='$id_user'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($user, $option, $join){
		$name = $this->db_driver->real_escape_string($user->name);
		$password = $this->db_driver->real_escape_string($user->password);
		$email = $this->db_driver->real_escape_string($user->email);
		$rol = $this->db_driver->real_escape_string($user->rol);
		$status = $this->db_driver->real_escape_string('high');
		$join = $this->db_driver->real_escape_string($join);

		if($join == ''){
			$query   =	"INSERT INTO User
						(user_name, password, user_email, rol, status)
						VALUES('$name','$password', '$email', '$rol', '$status')";
		}
		else{
			$query   =	"INSERT INTO User
						(user_name, password, user_email, rol, status, $option)
						VALUES('$name','$password', '$email', '$rol', '$status', '$join')";
		}

		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar {$this->db_driver->error}");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function edit($user, $id_user, $option, $join){

		$name = $this->db_driver->real_escape_string($user->name);
		$password = $this->db_driver->real_escape_string($user->password);
		$email = $this->db_driver->real_escape_string($user->email);
		$rol = $this->db_driver->real_escape_string($user->rol);
		$join = $this->db_driver->real_escape_string($join);
		if($join == ''){
			$query   =	"UPDATE User
						set user_name = '$name', password = '$password', user_email = '$email', rol = '$rol'
						WHERE id_user='$id_user'";
		}
		else{
			echo 'entro aca';
			$query   =	"UPDATE User
						set user_name = '$name', password = '$password', user_email = '$email', rol = '$rol', $option = '$join'
						WHERE id_user='$id_user'";
		}
		$result = $this ->db_driver-> query($query);

		if($this->db_driver->errno){
			//die("No se pudeo hacer la consulta de editar {$this->db_driver->error}");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_user){
		$query   =	"UPDATE User set status = 'down' WHERE id_user='$id_user'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

}

?>
