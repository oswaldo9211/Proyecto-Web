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

	public function get($id_client){

		$query = "SELECT * FROM Client  WHERE id_client='$id_client'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($client){
		$razon_social = $this->db_driver->real_escape_string($client->razon_social);
		$RFC = $this->db_driver->real_escape_string($client->RFC);
		$email = $this->db_driver->real_escape_string($client->email);
		$phone = $this->db_driver->real_escape_string($client->phone);
		$cellphone = $this->db_driver->real_escape_string($client->cellphone);

		$query   =	"INSERT INTO Client
					(client_name, client_RFC, client_emai, client_phone, client_cellphone)
					VALUES('$razon_social','$RFC','$email', '$phone','$cellphone')";
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

	public function edit($client, $id_client){

		$razon_social = $this->db_driver->real_escape_string($client->razon_social);
		$RFC = $this->db_driver->real_escape_string($client->RFC);
		$email = $this->db_driver->real_escape_string($client->email);
		$phone = $this->db_driver->real_escape_string($client->phone);
		$cellphone = $this->db_driver->real_escape_string($client->cellphone);


		$query   =	"UPDATE Client
					set client_name = '$razon_social', client_RFC = '$RFC', client_emai = '$email', client_phone = '$phone', client_cellphone = '$cellphone'
					WHERE id_client='$id_client'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			//die("No se pudeo hacer la consulta de editar");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_client){
		$query = "DELETE  FROM Client  WHERE id_client='$id_client'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

	public function searchClient($client_name)
	{
		$client_name = $this->db_driver->real_escape_string($client_name);

		$query = "SELECT * FROM Client WHERE client_name='$client_name'";

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

	public function searchEmail($email)
	{
		$email = $this->db_driver->real_escape_string($email);

		$query = "SELECT * FROM Client WHERE client_emai='$email'";

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

	public function searchRFC($RFC)
	{
		$RFC = $this->db_driver->real_escape_string($RFC);

		$query = "SELECT * FROM Client WHERE client_RFC='$RFC'";

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
}

?>
