<?php

/**
	*Oswaldo Marinez Fonseca
Controlador  de Servicio
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
					echo "No tienes permisos";
				break;
			case 'delete':
				if($this->isAdmin())
					$this->delete();
				else
					echo "No tienes permisos";
				break;
			case 'details':
				if($this->isAdmin())
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
				if($this->isAdmin())
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
		$services =$this->model->get_all();
		$section = file_get_contents('Views/Service/show_all.html');;
		$info = "";
		if($services)
			foreach ($services as $service) {
				$info .= "<tr>
			         		<td> $service[nombre] </td>
			         		<td> $service[ubicacion] </td>
			         		<td>
			         			<a href='index.php?ctrl=service&act=details&id=$service[idservicio]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=service&act=edit&id=$service[idservicio]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=service&act=delete&id=$service[idservicio]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		//include('Controllers/validacionesCtrl.php');
		//Validate variables
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
			$id_service = $_GET['id'];
			$service =$this->model->get($id_service);
			if($service){

				$this->model->delete($id_service);
				$this->show_all();

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

			    $dicc = array('{nombre}' => $service['name']
			    			 ,'{ubicacion}' => $service['ubicacion']
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

			    $dicc = array('{id}' => $service['idservicio']
			    	         ,'{nombre}' => $service['nombre']
			    			 ,'{ubicacion}' => $service['ubicacion']
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
	
}

?>
