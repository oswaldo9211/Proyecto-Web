<?php

	/**
	*/

class LocationMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT * FROM Location";
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

	public function get($id_location){

		$query = "SELECT * FROM Location  WHERE id='$id_location'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($location){
		$name = $this->db_driver->real_escape_string($location->name);

		$query   =	"INSERT INTO Location (name)
								   VALUES('$name')";
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

	public function edit($location, $id_location){

		$name = $this->db_driver->real_escape_string($location->name);

		$query   =	"UPDATE Location set name = '$name' WHERE id='$id_location'";
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

	public function delete($id_location){
		$query = "DELETE  FROM Location  WHERE id='$id_location'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

}

?>
