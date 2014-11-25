<?php

	/**
	*/

class ModelMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT Brand.brand, Model.* FROM Model 
					INNER JOIN Brand on Model.id_brand=Brand.id_brand";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar todos {$this->db_driver->error}");
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

	public function models($id_brand){
		$query = "SELECT Brand.brand, Model.* FROM Model 
					INNER JOIN Brand on Model.id_brand=Brand.id_brand WHERE Model.id_brand='$id_brand'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar todos {$this->db_driver->error}");
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

	public function get($id_model){

		$query = "SELECT Brand.brand, Model.* FROM Model 
					INNER JOIN Brand on Model.id_brand=Brand.id_brand WHERE id_model='$id_model'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($model, $id_brand){
		$model = $this->db_driver->real_escape_string($model);
		$id_brand = $this->db_driver->real_escape_string($id_brand);

		$query   =	"INSERT INTO Model
					(model, id_brand)
					VALUES('$model','$id_brand')";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			//die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function edit($model, $id_brand, $id_model){

		$model = $this->db_driver->real_escape_string($model);
		$id_brand = $this->db_driver->real_escape_string($id_brand);

		$query   =	"UPDATE Model
					set model = '$model', id_brand = '$id_brand'
					WHERE id_model='$id_model'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_model){
		$query = "DELETE  FROM Model  WHERE id_model='$id_model'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

	public function searchModel($model)
	{
		$model = $this->db_driver->real_escape_string($model);

		$query = "SELECT * FROM Model WHERE model='$model'";

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
