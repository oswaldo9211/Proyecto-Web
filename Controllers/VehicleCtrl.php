<?php

/**
	*Oswaldo Marinez Fonseca
Controller Vehicle
*/

require('Controllers/CtrlEstandar.php');

class VehicleCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/VehicleMdl.php');
		require('Models/Vehicle.php');
		$this->model =new VehicleMdl();
	}

	function run()
	{
		if(!$this->isLogged())
			header('Location:  index.php');
		$act = isset($_GET['act'])?$_GET['act'] : 'show_all';
		switch($act){
			case 'create':
				if($this->isUser())
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
				if($this->isUser())
					$this->details();
				else
					require('Views/error.html');
				break;
			case 'edit':
				if($this->isUser())
					$this->edit();
				else
					require('Views/error.html');
				break;
			case 'show_all':
				if($this->isUser())
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
			case 'validate':
				if($this->isUser())
					$this->validate();
				else
					header('Location:  index.php');
				break;
			case 'unloadVehicles':
				if($this->isAdmin())
					$this->unloadVehicles();
				else
					header('Location:  index.php');
				break;
			case 'unload':
				if($this->isAdmin()){
					require('include/unloadVehicles.php');
				}
				else
					header('Location:  index.php');
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function unloadVehicles(){
		$section = file_get_contents('Views/Client/unloadVehicle.html');;
		$this->template($section);
	}

	private function validate(){
		if($this->model->searchVIN($_POST['VIN']))
			echo json_encode("Un VIN ya existe");
		else
			echo json_encode(true);
	}

	private function get_all(){
		$vehicles = $this->model->get_all();
		echo json_encode($vehicles);
	}

	private function show_all(){
		//get all employees to display
		$vehicles =$this->model->get_all();
		$section = file_get_contents('Views/Vehicle/show_all.html');;
		$info = "";
		if($vehicles)
			foreach ($vehicles as $vehicle) {
				$info .= "<tr>
			         		<td> $vehicle[vin]</td>
			         		<td> $vehicle[model] </td>
			         		<td> $vehicle[client_name] </td>
			         		<td>
			         			<a href='index.php?ctrl=vehicle&act=details&id=$vehicle[id_vehicle]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=vehicle&act=edit&id=$vehicle[id_vehicle]'><i class='icon-edit'></i></a>";
			    if($this->isAdmin()){
					$info .=   "<a href='index.php?ctrl=vehicle&act=delete&id=$vehicle[id_vehicle]'><i class='icon-remove'></i></a>";
				}
					$info .="</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Vehicle/create.html');
			$this->template($section);
		}
		else{
			$VIN = $_POST['VIN'];
			$model = $_POST['model'];
			$color = $_POST['color'];
			$description = $_POST['des'];
			$client = $_POST['client'];


			$vehicle = new Vehicle($VIN, $model, $color, $description, $client);
			$result =$this->model->create($vehicle);

			if($result){
				$this->show_message("success", "El vehiculo se creo exitosamente");
			}
			else{
				$this->show_message("danger", "No se creo, no puede haber duplicados en el VIN");
			}
		}
	}

	private function delete(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_vehicle = $_GET['id'];
			$vehicle  =$this->model->get($id_vehicle);
			if($vehicle){

				$this->model->delete($id_vehicle);
				$this->show_message("success", "El vehiculo se elimino exitosamente");

			}
			else{
				echo 'no existe vehiculo';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_vehicle= $_GET['id'];
			$vehicle =$this->model->get($id_vehicle);
			if($vehicle){

				$section = file_get_contents('Views/Vehicle/details.html');

			    $dicc = array('{cliente}' => $vehicle['client_name']
			    			 ,'{VIN}' => $vehicle['vin']
			    			 ,'{color}' => $vehicle['color']
			    			 ,'{marca}' => $vehicle['brand']
			    			 ,'{modelo}' => $vehicle['model']
			    			 ,'{descripcion}' => $vehicle['description']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese vehiculo';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_vehicle= $_GET['id'];
			$vehicle =$this->model->get($id_vehicle);
			if($vehicle){

				$section = file_get_contents('Views/Vehicle/edit.html');

			    $dicc = array('{id}' => $vehicle['id_vehicle']
			    	         ,'{cliente}' => $vehicle['client_name']
			    			 ,'{VIN}' => $vehicle['vin']
			    			 ,'{color}' => $vehicle['color']
			    			 ,'{marca}' => $vehicle['brand']
			    			 ,'{modelo}' => $vehicle['model']
			    			 ,'{descripcion}' => $vehicle['description']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese empleado para editarlo';
			}
		}
		else{
			$id_vehicle= $_GET['id'];
			$VIN = $_POST['VIN'];
			$model = $_POST['model'];
			$color = $_POST['color'];
			$description = $_POST['des'];
			$client = $_POST['client'];

			$vehicle = new Vehicle($VIN, $model, $color, $description, $client);

			$result =$this->model->edit($vehicle, $id_vehicle);

			if($result){
				$this->show_message("success", "El vehiculo se edito correctamente");
			}
			else{
				$this->show_message("danger", "No se edito no puede haber duplicados en el VIN");
			}
		}
	}

	private function template($section){
		//views for different user
		if($this->isAdmin())
			$header = file_get_contents('Views/header.html');
		else if ($this->isUser())
			$header = file_get_contents('Views/headerEmployee.html');
		$footer = file_get_contents('Views/footer.html');
		$dicc = array('{user}' => $this->getUserName());
	    $header = strtr($header, $dicc);
	    
		echo $header . $section . $footer;
	}

	private function show_message($tipo, $message){
		require_once('include/Message.php');
		$this->show_all();
		Message($tipo, $message);
	}
	
}

?>