<?php

/**
	*Oswaldo Marinez Fonseca
Controller Locations
*/

require('Controllers/CtrlEstandar.php');

class LocationCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/LocationMdl.php');
		require('Models/Location.php');
		$this->model =new LocationMdl();
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
		if($this->model->searchLocation($_POST['name']))
			echo json_encode("La ubicacion ya existe");
		else
			echo json_encode(true);
	}

	private function get_all(){
		$locations =$this->model->get_all();
		echo json_encode($locations);
	}

	private function show_all(){
		//get all locations to display
		$locations =$this->model->get_all();
		$section = file_get_contents('Views/Location/show_all.html');
		$info = "";
		if($locations)
			foreach ($locations as $location) {
				$info .= "<tr>
			         		<td> $location[location_name]</td>
			         		<td>
			         			<a href='index.php?ctrl=location&act=details&id=$location[id_location]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=location&act=edit&id=$location[id_location]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=location&act=delete&id=$location[id_location]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Location/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);

			$location = new Location($name);
			$result =$this->model->create($location);

			if($result){
				$this->show_message("success", "La ubicacion se creo exitosamente");
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
			$id_location = $_GET['id'];
			$location  =$this->model->get($id_location);
			if($location){

				$this->model->delete($id_location);
				$this->show_message("success", "La ubicacion se elimino exitosamente");

			}
			else{
				echo 'no existe la Ubicacion';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_location= $_GET['id'];
			$location =$this->model->get($id_location);
			if($location){

				$section = file_get_contents('Views/Location/details.html');

			    $dicc = array('{nombre}' => $location['location_name']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe  la Ubicacion';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_location= $_GET['id'];
			$location =$this->model->get($id_location);
			if($location){

				$section = file_get_contents('Views/Location/edit.html');

			    $dicc = array('{id}' => $location['id_location']
			    	         ,'{nombre}' => $location['location_name']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ubicacion para editarla';
			}
		}
		else{
			$id_location= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);

			$location = new Location($name);

			$result =$this->model->edit($location, $id_location);

			if($result){
				$this->show_message("success", "La ubicacion se edito exitosamente");
			}
			else{
				$this->show_message("danger", "No se edito, no puede haber duplicados en el nombre");
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
