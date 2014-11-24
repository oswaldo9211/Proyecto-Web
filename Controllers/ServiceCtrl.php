<?php

/**
	*Oswaldo Marinez Fonseca
Controller  de Service
*/

require('Controllers/CtrlEstandar.php');

class ServiceCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/ServiceMdl.php');
		require('Models/Service.php');
		$this->model =new ServiceMdl();
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
		if($this->model->searchService($_POST['name']))
			echo json_encode("El servicio ya existe");
		else
			echo json_encode(true);
	}

	private function show_all(){
		//get all services to display
		$services =$this->model->get_all();
		$section = file_get_contents('Views/Service/show_all.html');;
		$info = "";
		if($services)
			foreach ($services as $service) {
				$info .= "<tr>
			         		<td> $service[service_name] </td>
			         		<td> $service[location_name] </td>
			         		<td>
			         			<a href='index.php?ctrl=service&act=details&id=$service[id_service]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=service&act=edit&id=$service[id_service]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=service&act=delete&id=$service[id_service]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Service/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$id_location = validateNumber($_POST['location']);


			$service = new Service($name, $id_location);
			$result =$this->model->create($service);

			if($result){
				$this->show_message("success", "El servicio creo exitosamente");
			}
			else{
				$this->show_message("danger", "No se creo, no puede haber duplicados en el nombre");
			}
		}
	}

	private function delete(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_service = $_GET['id'];
			$service =$this->model->get($id_service);
			if($service){

				$this->model->delete($id_service);
				$this->show_message("success", "El servicio se elimino exitosamente");

			}
			else{
				echo 'no existe servicio';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_service= $_GET['id'];
			$service =$this->model->get($id_service);
			if($service){

				$section = file_get_contents('Views/Service/details.html');

			    $dicc = array('{nombre}' => $service['service_name']
			    			 ,'{ubicacion}' => $service['location_name']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese servicio';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_service= $_GET['id'];
			$service =$this->model->get($id_service);
			if($service){

				$section = file_get_contents('Views/Service/edit.html');

			    $dicc = array('{id}' => $service['id_service']
			    	         ,'{nombre}' => $service['service_name']
			    			 ,'{ubicacion}' => $service['location_name']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese servicio para editarlo';
			}
		}
		else{
			$id_service= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$id_location = validateNumber($_POST['location']);

			$service = new Service($name, $id_location);

			$result =$this->model->edit($service, $id_service);

			if($result){
				$this->show_message("success", "El servicio se edito correctamente");
			}
			else{
				$this->show_message("danger", "No se edito no puede haber duplicados en el nombre");
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
	
}

?>
