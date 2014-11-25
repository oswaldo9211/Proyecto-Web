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
		$query = "SELECT * FROM Employee WHERE status='high'";
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

	public function get($id_employee){
		$query = "SELECT ms_ciudades.ciu_nombre, Employee.* , ms_estados.edo_nombre FROM Employee 
				    INNER JOIN ms_ciudades  on Employee.id_state=ms_ciudades.ciu_id 
				    INNER JOIN ms_estados ON ms_estados.edo_id=ms_ciudades.ciu_estado
				    WHERE id_employee='$id_employee'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de mostrar");
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
		$phone = $this->db_driver->real_escape_string($employee->phone);
		$cellphone = $this->db_driver->real_escape_string($employee->cellphone);
		$address = $this->db_driver->real_escape_string($employee->address);
		$colony = $this->db_driver->real_escape_string($employee->colony);
		$city = $this->db_driver->real_escape_string($employee->city);
		$status = $this->db_driver->real_escape_string('high');

		$query   =	"INSERT INTO Employee
					(emp_name, emp_last_name, RFC, emp_email, emp_phone, emp_cellpone, address, colony, id_state, status)
					VALUES('$name','$last_name','$RFC','$email', '$phone', '$cellphone', '$address', '$colony', '$city', '$status')";
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

	public function edit($employee, $id_employee){
		
		$name = $this->db_driver->real_escape_string($employee->name);
		$last_name = $this->db_driver->real_escape_string($employee->last_name);
		$RFC = $this->db_driver->real_escape_string($employee->RFC);
		$email = $this->db_driver->real_escape_string($employee->email);
		$phone = $this->db_driver->real_escape_string($employee->phone);
		$cellphone = $this->db_driver->real_escape_string($employee->cellphone);
		$address = $this->db_driver->real_escape_string($employee->address);
		$colony = $this->db_driver->real_escape_string($employee->colony);
		$city = $this->db_driver->real_escape_string($employee->city);
		if($city == '')
			$city=1;
		$query   =	"UPDATE Employee
					set emp_name = '$name', emp_last_name = '$last_name', RFC = '$RFC', emp_email = '$email', emp_phone = $phone, emp_cellpone = $cellphone,
					address = '$address', colony= '$colony', id_state = '$city'
					WHERE id_employee='$id_employee'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			return false;
		}
		if($result)
			return true;
		else
			return false;
	}

	public function delete($id_employee){
		$query   =	"UPDATE Employee set status = 'down' WHERE id_employee='$id_employee'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
			die("No se pudeo hacer la consulta de eliminar");
			return false;
		}
		return true;
	}

	public function searchEmail($email)
	{
		$email = $this->db_driver->real_escape_string($email);

		$query = "SELECT * FROM Employee WHERE emp_email='$email'";

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

		$query = "SELECT * FROM Employee WHERE RFC='$RFC'";

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

	public function states(){
		$query = "SELECT * FROM ms_estados";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
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

	public function cities($id_city){
		$id_city = $this->db_driver->real_escape_string($id_city);
		$query = "SELECT ms_ciudades.ciu_nombre, ms_ciudades.ciu_id FROM ms_estados 
				    INNER JOIN ms_ciudades  on ms_estados.edo_id=ms_ciudades.ciu_estado WHERE edo_id='$id_city'";
		$result = $this ->db_driver-> query($query);
		if($this->db_driver->errno){
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

}

?>
