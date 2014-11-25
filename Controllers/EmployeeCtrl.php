<?php

/**
	*Oswaldo Marinez Fonseca
Controller Employee
*/

require('Controllers/CtrlEstandar.php');

class EmployeeCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/EmployeeMdl.php');
		require('Models/Employee.php');
		$this->model =new EmployeeMdl();
	}

	function run()
	{
		if(!$this->isLogged())
			header('Location:  index.php');
		$act = isset($_GET['act'])?$_GET['act'] : 'show_all';
		switch($act){
			case 'create':
				if($this->isAdmin())
					$this->create();
				else
					require('Views/error.html');
				break;
			case 'delete':
				if($this->isAdmin())
					$this->delete();
				else
					require('Views/error.html');
				break;
			case 'details':
				if($this->isAdmin())
					$this->details();
				else
					require('Views/error.html');
				break;
			case 'edit':
				if($this->isAdmin())
					$this->edit();
				else
					require('Views/error.html');
				break;
			case 'show_all':
				if($this->isAdmin())
					$this->show_all();
				else
					require('Views/error.html');
				break;
			case 'get_all':
				if($this->isAdmin())
					$this->get_all();
				else
					require('Views/error.html');
				break;
			case 'states':
				if($this->isAdmin()){
					$states = $this->model->states();
					echo json_encode($states);
				}
				else
					require('Views/error.html');
				break;
			case 'cities':
				if($this->isAdmin()){
					$cities = $this->model->cities($_POST['state']);
					echo json_encode($cities);
				}
				else
					require('Views/error.html');
				break;
			case 'validate':
				if($this->isAdmin())
					$this->validate();
				else
					header('Location:  index.php');
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function validate(){
		if($this->model->searchRFC($_POST['RFC']))
			echo json_encode("El RFC ya existe");
		else if ($this->model->searchEmail($_POST['email']))
			echo json_encode("El email ya existe");
		else
			echo json_encode(true);
	}

	private function get_all(){
		$staff = $this->model->get_all();
		echo json_encode($staff);
	}

	private function show_all(){
		//get all employees to display
		$staff =$this->model->get_all();
		$section = file_get_contents('Views/Employee/show_all.html');;
		$info = "";
		foreach ($staff as $employee) {
			$info .= "<tr>
		         		<td> $employee[emp_name] $employee[emp_last_name] </td>
		         		<td> $employee[RFC] </td>
		         		<td> $employee[emp_email] </td>
		         		<td>
		         			<a href='index.php?ctrl=employee&act=details&id=$employee[id_employee]'><i class='icon-view'></i></a>
							<a href='index.php?ctrl=employee&act=edit&id=$employee[id_employee]'><i class='icon-edit'></i></a>
						    <a href='index.php?ctrl=employee&act=delete&id=$employee[id_employee]'><i class='icon-remove'></i></a>
						    </td>
	      				</tr>";
			}
	      	/*$info .= "<tr>
		         		<td> $employee[name] $employee[last_name] </td>
		         		<td> $employee[RFC] </td>
		         		<td> $employee[email] </td>
		         		<td>
		         			<a href='index.php?ctrl=employee&act=details&id=$employee[id]'><i class='icon-view'></i></a>
							<a href='index.php?ctrl=employee&act=edit&id=$employee[id]'><i class='icon-edit'></i></a>
							<a href='index.php?ctrl=employee&act=delete&id=$employee[id]'><i class='icon-remove'></i></a>
						</td>
	      			</tr>";*/
	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Employee/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$last_name = validateName($_POST['last_name']);
			$RFC = validateRFC($_POST['RFC']);
			$email = validateEmail($_POST['email']);
			$phone = $_POST['phone'];
			$cellphone = $_POST['cellphone'];
			$address = $_POST['address'];
			$colony = validateText($_POST['colony']);
			$city = $_POST['city'];

			$employee = new Employee($name, $last_name, $RFC, $email, $phone, $cellphone, $address, $colony, $city);
			$result =$this->model->create($employee);

			if($result){
				$this->show_message("success", "El empleado se creo exitosamente");
			}
			else{
				$this->show_message("danger", "No se creo, no puede haber duplicados en el correo o RFC");
			}
		}
	}

	private function delete(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_employee= $_GET['id'];
			$employee =$this->model->get($id_employee);
			if($employee){

				$this->model->delete($id_employee);
				$this->show_message("success", "El empleado se elimino exitosamente");

			}
			else{
				echo 'no existe empleado';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_employee= $_GET['id'];
			$employee =$this->model->get($id_employee);
			if($employee){

				$section = file_get_contents('Views/Employee/details.html');

			    $dicc = array('{nombre}' => $employee['emp_name']
			    			 ,'{apellido}' => $employee['emp_last_name']
			    			 ,'{RFC}' => $employee['RFC']
			    			 ,'{email}' => $employee['emp_email']
			    			 ,'{telefono}' => $employee['emp_phone']
			    			 ,'{celular}' => $employee['emp_cellpone']
			    			 ,'{direccion}' => $employee['address']
			    			 ,'{colonia}' => $employee['colony']
			    			 ,'{estado}' => $employee['edo_nombre']
			    			 ,'{municipio}' => $employee['ciu_nombre']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese empleado';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_employee= $_GET['id'];
			$employee =$this->model->get($id_employee);
			if($employee){

				$section = file_get_contents('Views/Employee/edit.html');

			    $dicc = array('{id}' => $employee['id_employee']
			    	         ,'{nombre}' => $employee['emp_name']
			    			 ,'{apellido}' => $employee['emp_last_name']
			    			 ,'{RFC}' => $employee['RFC']
			    			 ,'{email}' => $employee['emp_email']
			    			 ,'{telefono}' => $employee['emp_phone']
			    			 ,'{celular}' => $employee['emp_cellpone']
			    			 ,'{direcccion}' => $employee['address']
			    			 ,'{colonia}' => $employee['colony']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese empleado para editarlo';
			}
		}
		else{
			$id_employee= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$last_name = validateName($_POST['last_name']);
			$RFC = validateRFC($_POST['RFC']);
			$email = validateEmail($_POST['email']);
			$phone = $_POST['phone'];
			$cellphone = $_POST['cellphone'];
			$address = $_POST['address'];
			$colony = validateText($_POST['colony']);
			$city = $_POST['city'];

			$employee = new Employee($name, $last_name, $RFC, $email, $phone, $cellphone, $address, $colony, $city);

			$result =$this->model->edit($employee, $id_employee);
			if($result){
				$this->show_message("success", "El empleado se edito correctamente");
			}
			else{
				$this->show_message("danger", "No se edito no puede haber duplicados en el correo o el RFC");
			}
		}
	}

	private function template($section){
		$header = file_get_contents('Views/header.html');
		$footer = file_get_contents('Views/footer.html');
		$dicc = array('{user}' => $this->getUserName());
	    $header = strtr($header, $dicc);
		echo $header. $section . $footer;
	}

	private function show_message($tipo, $message){
		require_once('include/Message.php');
		$this->show_all();
		Message($tipo, $message);
	}

	/**
	* @param string $data
	* @return string $data
	* Valide a string to be 
	*/
	private function validateDates($data){
	}
	
}

?>
