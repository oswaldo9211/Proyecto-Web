<?php

/**
	*Oswaldo Marinez Fonseca
Controlador  de Ubicaciones
*/

require('Controllers/CtrlEstandar.php');

class LocationCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/LocationMdl.php');
		require('Models/location.php');
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
			case 'get_all':
				if($this->isAdmin())
					$this->get_all();
				else
					echo "No tienes permisos";
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function get_all(){
		$locations =$this->model->get_all();
		echo json_encode($locations);
	}

	private function show_all(){
		//get all employees to display
		$locations =$this->model->get_all();
		$section = file_get_contents('Views/Location/show_all.html');
		$info = "";
		if($locations)
			foreach ($locations as $location) {
				$info .= "<tr>
			         		<td> $location[name]</td>
			         		<td>
			         			<a href='index.php?ctrl=location&act=details&id=$location[id]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=location&act=edit&id=$location[id]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=location&act=delete&id=$location[id]'><i class='icon-remove'></i></a>
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
			$section = file_get_contents('Views/Location/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);

			$location = new Location($name);
			$result =$this->model->create($location);

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
			$id_location = $_GET['id'];
			$location  =$this->model->get($id_location);
			if($location){

				$this->model->delete($id_location);
				$this->show_all();

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

			    $dicc = array('{nombre}' => $location['name']
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

			    $dicc = array('{id}' => $location['id']
			    	         ,'{nombre}' => $location['name']
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
