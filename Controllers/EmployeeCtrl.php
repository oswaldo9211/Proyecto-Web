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
		if(!$this->isLogged())
			header('Location:  index.php');
		$act = isset($_GET['act'])?$_GET['act'] : 'show_all';
		switch($act){
			case 'create':
				if($this->isAdmin())
					$this->create();
				else
					echo "No tienes permisos";
				break;
			case 'delete':
				if($this->isAdmin())
					$this->delete();
				else
					echo "No tienes permisos";
				break;
			case 'details':
				if($this->isUser())
					$this->details();
				else
					echo "No tienes permisos";
				break;
			case 'edit':
				if($this->isAdmin())
					$this->edit();
				else
					echo "No tienes permisos";
				break;
			case 'show_all':
				if($this->isUser())
					$this->show_all();
				else
					echo "No tienes permisos";
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function show_all(){
		//get all employees to display
		$staff =$this->model->get_all();
		$section = file_get_contents('Views/Employee/show_all.html');;
		$info = "";
		foreach ($staff as $employee) {
			$info .= "<tr>
		         		<td> $employee[name] $employee[last_name] </td>
		         		<td> $employee[RFC] </td>
		         		<td> $employee[email] </td>
		         		<td>
		         			<a href='index.php?ctrl=employee&act=details&id=$employee[id]'><i class='icon-view'></i></a>";
		    if($this->isAdmin()){
				$info .=   "<a href='index.php?ctrl=employee&act=edit&id=$employee[id]'><i class='icon-edit'></i></a>
						    <a href='index.php?ctrl=employee&act=delete&id=$employee[id]'><i class='icon-remove'></i></a>";
			}
				$info .="</td>
	      			</tr>";
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
	    }

	    if(!$this->isAdmin()){
			$info .= '<script type="text/javascript">
						var boton = document.getElementById("NewButton");
						boton.parentNode.removeChild(boton);
					  </script>';
	    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		//include('Controllers/validacionesCtrl.php');
		//Validate variables
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
			$phones = validatePhone($_POST['phones']);
			$street = validateText($_POST['street']);
			$colony = validateText($_POST['colony']);
			$municipality = validateText($_POST['municipality']);
			$no_external = validateAdressNumber($_POST['no_external']);
			$no_internal = validateAdressNumber($_POST['no_internal']);

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

				$section = file_get_contents('Views/Employee/details.html');

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
			$phones = validatePhone($_POST['phones']);
			$street = validateText($_POST['street']);
			$colony = validateText($_POST['colony']);
			$municipality = validateText($_POST['municipality']);
			$no_external = validateAdressNumber($_POST['no_external']);
			$no_internal = validateAdressNumber($_POST['no_internal']);

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

	private function template($section){
		$header = file_get_contents('Views/header.html');
		$footer = file_get_contents('Views/footer.html');
		$dicc = array('{user}' => $this->getUserName());
	    $header = strtr($header, $dicc);
		echo $header. $section . $footer;
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
