<?php

	/**
	*/

class BrandMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT * FROM Brand";
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

	public function get($id_brand){

		$query = "SELECT * FROM Brand  WHERE id_brand='$id_brand'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($brand){
		$brand = $this->db_driver->real_escape_string($brand);

		$query   =	"INSERT INTO Brand (brand)
								   VALUES('$brand')";
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

	public function edit($brand, $id_brand){

		$brand = $this->db_driver->real_escape_string($brand);

		$query   =	"UPDATE Brand set brand = '$brand' WHERE id_brand='$id_brand'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_brand){
		$query = "DELETE  FROM Brand  WHERE id_brand='$id_brand'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

	public function searchLocation($brand)
	{
		$brand = $this->db_driver->real_escape_string($brand);

		$query = "SELECT * FROM Brand WHERE brand='$brand'";

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
