<?php

	/**
	*/

class ClientMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT * FROM Client";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar todos");
			return false;
		}
		return $result;

	}

	public function get($id_client){

		$query = "SELECT * FROM Client  WHERE id='$id_client'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($client){
		$name = $this->db_driver->real_escape_string($client->name);
		$last_name = $this->db_driver->real_escape_string($client->last_name);
		$RFC = $this->db_driver->real_escape_string($client->RFC);
		$email = $this->db_driver->real_escape_string($client->email);
		$phone = $this->db_driver->real_escape_string($client->phone);

		$query   =	"INSERT INTO Client
					(name, last_name, RFC, email, phone)
					VALUES('$name','$last_name','$RFC','$email', '$phone')";
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

	public function edit($client, $id_client){

		$name = $this->db_driver->real_escape_string($client->name);
		$last_name = $this->db_driver->real_escape_string($client->last_name);
		$RFC = $this->db_driver->real_escape_string($client->RFC);
		$email = $this->db_driver->real_escape_string($client->email);
		$phone = $this->db_driver->real_escape_string($client->phone);


		$query   =	"UPDATE Client
					set name = '$name', last_name = '$last_name', RFC = '$RFC', email = '$email', phone = '$phone'
					WHERE id='$id_client'";
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

	public function delete($id_client){
		$query = "DELETE  FROM Client  WHERE id='$id_client'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		return true;
	}

}

?>
