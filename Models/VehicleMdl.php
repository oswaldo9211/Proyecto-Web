<?php

	/**
	*/

class VehicleMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT Vehicle.*, Client.client_name, Model.model FROM Vehicle 
				    INNER JOIN Client  ON Vehicle.id_client=Client.id_client 
				    INNER JOIN Model ON Vehicle.id_model=Model.id_model WHERE status='high'";
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

	public function get($id_vehicle){
		$query = "SELECT Vehicle.*, Client.client_name, Model.model, Brand.brand FROM Vehicle 
				    INNER JOIN Client  ON Vehicle.id_client=Client.id_client 
				    INNER JOIN Model ON Vehicle.id_model=Model.id_model
				     INNER JOIN Brand ON Brand.id_brand=Model.id_brand
				    WHERE id_vehicle='$id_vehicle'";
		//$query = "SELECT * FROM Vehicle  WHERE id_vehicle='$id_vehicle'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($vehicle){
		$VIN = $this->db_driver->real_escape_string($vehicle->VIN);
		$model = $this->db_driver->real_escape_string($vehicle->model);
		$color = $this->db_driver->real_escape_string($vehicle->color);
		$description = $this->db_driver->real_escape_string($vehicle->description);
		$client = $this->db_driver->real_escape_string($vehicle->client);
		$status = $this->db_driver->real_escape_string('high');
		$query   =	"INSERT INTO Vehicle
					(vin, id_model, color, description, id_client, status)
					VALUES('$VIN','$model','$color', '$description','$client', '$status')";
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

	public function edit($vehicle, $id_vehicle){

		$VIN = $this->db_driver->real_escape_string($vehicle->VIN);
		$model = $this->db_driver->real_escape_string($vehicle->model);
		$color = $this->db_driver->real_escape_string($vehicle->color);
		$description = $this->db_driver->real_escape_string($vehicle->description);
		$client = $this->db_driver->real_escape_string($vehicle->client);

		$query   =	"UPDATE Vehicle
					set vin = '$VIN', id_model = '$model', color = '$color', description = '$description', id_client = '$client'
					WHERE id_vehicle='$id_vehicle'";
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

	public function delete($id_vehicle){
		$query = "UPDATE Vehicle set status = 'down' WHERE id_vehicle='$id_vehicle'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

	public function searchVIN($VIN)
	{
		$VIN = $this->db_driver->real_escape_string($VIN);

		$query = "SELECT * FROM Vehicle WHERE vin='$VIN'";

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
