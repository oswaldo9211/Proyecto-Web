<?php

	/**
	*/

class ServiceMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT * FROM Servicio";
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

	public function get($id_service){

		$query = "SELECT * FROM Servicio  WHERE idservicio='$id_service'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($service){
		$name = $this->db_driver->real_escape_string($service->name);
		$id_location = $this->db_driver->real_escape_string($service->id_location);

		$query   =	"INSERT INTO Servicio
					(nombre, ubicacion)
					VALUES('$name','$id_location')";
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

	public function edit($service, $id_service){

		$name = $this->db_driver->real_escape_string($service->name);
		$id_location = $this->db_driver->real_escape_string($service->id_location);


		$query   =	"UPDATE Servicio
					set nombre = '$name', ubicacion = '$id_location'
					WHERE idservicio='$id_service'";
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

	public function delete($id_service){
		$query = "DELETE  FROM Servicio  WHERE idservicio='$id_service'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

}

?>
