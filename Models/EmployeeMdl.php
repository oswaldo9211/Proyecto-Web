<?php

	/**
	*/

class EmployeeMdl {
	public $db_driver;
	function __construct() {
	require('config.ini');
	$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}
	}


	public function get_all(){
		$query = "SELECT * FROM Employee";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar todos");
			return false;
		}
		return $result;

	}

	public function get($id_employee){

		$query = "SELECT * FROM Employee  WHERE id='$id_employee'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		$row = $result->fetch_assoc();
		return $row;
	}


	public function create($employee){
		$name = $this->db_driver->real_escape_string($employee->name);
		$last_name = $this->db_driver->real_escape_string($employee->last_name);
		$RFC = $this->db_driver->real_escape_string($employee->RFC);
		$email = $this->db_driver->real_escape_string($employee->email);
		$street = $this->db_driver->real_escape_string($employee->street);
		$colony = $this->db_driver->real_escape_string($employee->colony);
		$municipality = $this->db_driver->real_escape_string($employee->municipality);
		$no_external = $this->db_driver->real_escape_string($employee->no_external);
		$no_internal = $this->db_driver->real_escape_string($employee->no_internal);
		$phone = $employee->phones;
		var_dump($RFC);

		$query   =	"INSERT INTO Employee
					(name, last_name, RFC, email, id_phone, street, colony, municipality, no_external, no_internal)
					VALUES('$name','$last_name','$RFC','$email', '$phone', '$street', '$colony', '$municipality', '$no_external', '$no_internal')";
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

	public function edit($employee, $id_employee){

		$name = $this->db_driver->real_escape_string($employee->name);
		$last_name = $this->db_driver->real_escape_string($employee->last_name);
		$RFC = $this->db_driver->real_escape_string($employee->RFC);
		$email = $this->db_driver->real_escape_string($employee->email);
		$street = $this->db_driver->real_escape_string($employee->street);
		$colony = $this->db_driver->real_escape_string($employee->colony);
		$municipality = $this->db_driver->real_escape_string($employee->municipality);
		$no_external = $this->db_driver->real_escape_string($employee->no_external);
		$no_internal = $this->db_driver->real_escape_string($employee->no_internal);
		$phone = $employee->phones;


		$query   =	"UPDATE Employee
					set name = '$name', last_name = '$last_name', RFC = '$RFC', email = '$email', id_phone = $phone, 
					street = '$street', colony= '$colony', municipality = '$municipality', no_external = '$no_external', no_internal='$no_internal'
					WHERE id='$id_employee'";
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

	public function delete($id_employee){
		$query = "DELETE  FROM Employee  WHERE id='$id_employee'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de insertar");
			return false;
		}
		return true;
	}

}

?>
