<?php

/**
	*Oswaldo Marinez Fonseca
Controller Brand
*/

require('Controllers/CtrlEstandar.php');

class BrandCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/BrandMdl.php');
		$this->model =new BrandMdl();
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
				if($this->isUser())
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
			echo json_encode("La marca ya existe");
		else
			echo json_encode(true);
	}

	private function get_all(){
		$brands =$this->model->get_all();
		echo json_encode($brands);
	}

	private function show_all(){
		//get all brands to display
		$brands =$this->model->get_all();
		$section = file_get_contents('Views/Brand/show_all.html');
		$info = "";
		if($brands)
			foreach ($brands as $brand) {
				$info .= "<tr>
			         		<td> $brand[brand]</td>
			         		<td>
			         			<a href='index.php?ctrl=brand&act=details&id=$brand[id_brand]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=brand&act=edit&id=$brand[id_brand]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=brand&act=delete&id=$brand[id_brand]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Brand/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$brand = validateName($_POST['name']);

			$result =$this->model->create($brand);

			if($result){
				$this->show_message("success", "La marca se creo exitosamente");
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
			$id_brand = $_GET['id'];
			$brand  =$this->model->get($id_brand);
			if($brand){

				$this->model->delete($id_brand);
				$this->show_message("success", "La marca se elimino exitosamente");

			}
			else{
				echo 'no existe la marca';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_brand= $_GET['id'];
			$brand =$this->model->get($id_brand);
			if($brand){

				$section = file_get_contents('Views/Brand/details.html');

			    $dicc = array('{nombre}' => $brand['brand']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe  la marca';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_brand= $_GET['id'];
			$brand =$this->model->get($id_brand);
			if($brand){

				$section = file_get_contents('Views/Brand/edit.html');

			    $dicc = array('{id}' => $brand['id_brand']
			    	         ,'{nombre}' => $brand['brand']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe marca para editarla';
			}
		}
		else{
			$id_brand= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$brand = validateName($_POST['name']);

			$result =$this->model->edit($brand, $id_brand);

			if($result){
				$this->show_message("success", "La marca se edito exitosamente");
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
