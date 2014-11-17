<?php

/**
	*Oswaldo Marinez Fonseca
Controlador Empleados
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
		$act = isset($_GET['act'])?$_GET['act'] : 'show_all';
		switch($act){
			case 'create':
				//Validar permisos en cada uno de estas
				//if($this->isAdmin())
					$this->create();
				//else
				//	echo "No tienes permisos";
				break;
			case 'delete':
				//Validate User and permissions
				$this->delete();
				break;
			case 'details':
				//Validate User and permissions
				$this->details();
				break;
			case 'edit':
				//Validate User and permissions
				$this->edit();
				break;
			case 'show_all':
				//Validate User and permissions
				$this->show_all();
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function show_all(){
		//get all employees to display
		$staff =$this->model->get_all();

		$header = file_get_contents('Views/header.html');
		$section = file_get_contents('Views/Employee/show_all.html');
		$footer = file_get_contents('Views/footer.html');

		$info = "";
		foreach ($staff as $employee) {
			$info .= "<tr>
		         		<td> $employee[name] $employee[last_name] </td>
		         		<td> $employee[RFC] </td>
		         		<td> $employee[email] </td>
		         		<td>
		         			<a href='index.php?ctrl=employee&act=details&id=$employee[id]'><i class='icon-view'></i></a>
							<a href='index.php?ctrl=employee&act=edit&id=$employee[id]'><i class='icon-edit'></i></a>
							<a href='index.php?ctrl=employee&act=delete&id=$employee[id]'><i class='icon-remove'></i></a>
						</td>
	      			</tr>";
	    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);
		echo $header . $section. $footer;
	}
	
	private function create(){
		//include('Controllers/validacionesCtrl.php');
		//Validate variables
		if(empty($_POST)){
			$header = file_get_contents('Views/header.html');
			$section = file_get_contents('Views/Employee/create.html');
			$footer = file_get_contents('Views/footer.html');
			echo $header . $section. $footer;
		}
		else{
			$name = $_POST['name'];
			$last_name = $_POST['last_name'];
			$RFC = $_POST['RFC'];
			$email = $_POST['email'];
			$phones = $_POST['phones'];
			$street = $_POST['street'];
			$colony = $_POST['colony'];
			$municipality = $_POST['municipality'];
			$no_external = $_POST['no_external'];
			$no_internal = $_POST['no_internal'];

			$employee = new Employee($name, $last_name, $RFC, $email, $phones, $street, $colony, $municipality, $no_external, $no_internal);
			$result =$this->model->create($employee);

			if($result){
				//require('Views/Created.html');
				$this->show_all();
			}
			else{
				echo 'no se inserto';
				//require('Views/Error.html');
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
				$this->show_all();

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
				$header = file_get_contents('Views/header.html');
				$section = file_get_contents('Views/Employee/details.html');
				$footer = file_get_contents('Views/footer.html');

			    $dicc = array('{nombre}' => $employee['name']
			    			 ,'{apellido}' => $employee['last_name']
			    			 ,'{RFC}' => $employee['RFC']
			    			 ,'{email}' => $employee['email']
			    			 ,'{telefono}' => $employee['id_phone']
			    			 ,'{calle}' => $employee['street']
			    			 ,'{no. exterior}' => $employee['no_external']
			    			 ,'{no. interior}' => $employee['no_internal']
			    			 ,'{colonia}' => $employee['colony']
			    			 ,'{municipio}' => $employee['municipality']
			    	);
			    $section = strtr($section, $dicc);
				echo $header . $section. $footer;
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
				$header = file_get_contents('Views/header.html');
				$section = file_get_contents('Views/Employee/edit.html');
				$footer = file_get_contents('Views/footer.html');

			    $dicc = array('{id}' => $employee['id']
			    	         ,'{nombre}' => $employee['name']
			    			 ,'{apellido}' => $employee['last_name']
			    			 ,'{RFC}' => $employee['RFC']
			    			 ,'{email}' => $employee['email']
			    			 ,'{telefono}' => $employee['id_phone']
			    			 ,'{calle}' => $employee['street']
			    			 ,'{no. exterior}' => $employee['no_external']
			    			 ,'{no. interior}' => $employee['no_internal']
			    			 ,'{colonia}' => $employee['colony']
			    			 ,'{municipio}' => $employee['municipality']
			    	);
			    $section = strtr($section, $dicc);
				echo $header . $section. $footer;
			}
			else{
				echo 'no existe ese empleado para editarlo';
			}
		}
		else{
			$id_employee= $_GET['id'];
			$name = $_POST['name'];
			$last_name = $_POST['last_name'];
			$RFC = $_POST['RFC'];
			$email = $_POST['email'];
			$phones = $_POST['phones'];
			$street = $_POST['street'];
			$colony = $_POST['colony'];
			$municipality = $_POST['municipality'];
			$no_external = $_POST['no_external'];
			$no_internal = $_POST['no_internal'];

			$employee = new Employee($name, $last_name, $RFC, $email, $phones, $street, $colony, $municipality, $no_external, $no_internal);

			$result =$this->model->edit($employee, $id_employee);

			if($result){
				//require('Views/Created.html');
				$this->show_all();
			}
			else{
				echo 'no se edito';
				//require('Views/Error.html');
			}
		}
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
