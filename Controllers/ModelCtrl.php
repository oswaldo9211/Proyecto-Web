<?php

/**
	*Oswaldo Marinez Fonseca
Controller  Model vehicle
*/

require('Controllers/CtrlEstandar.php');

class ModelCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/ModelMdl.php');
		$this->model =new ModelMdl();
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
			case 'models':
				if($this->isUser()){
					$models = $this->model->models($_POST['brand']);
					echo json_encode($models);
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
		if($this->model->searchModel($_POST['name']))
			echo json_encode("El modelo ya existe");
		else
			echo json_encode(true);
	}


	private function show_all(){
		//get all models to display
		$models =$this->model->get_all();
		$section = file_get_contents('Views/Model/show_all.html');;
		$info = "";
		if($models)
			foreach ($models as $model) {
				$info .= "<tr>
			         		<td> $model[model] </td>
			         		<td> $model[brand] </td>
			         		<td>
			         			<a href='index.php?ctrl=model&act=details&id=$model[id_model]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=model&act=edit&id=$model[id_model]'><i class='icon-edit'></i></a>
								<a href='index.php?ctrl=model&act=delete&id=$model[id_model]'><i class='icon-remove'></i></a>
							</td>
		      			</tr>";
		    }

	    $dicc = array('{info}' => $info);
	    $section = strtr($section, $dicc);

		$this->template($section);
	}
	
	private function create(){
		if(empty($_POST)){
			$section = file_get_contents('Views/Model/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$model = $_POST['name'];
			$id_brand = validateNumber($_POST['brand']);
			$result =$this->model->create($model, $id_brand);

			if($result){
				$this->show_message("success", "El modelo creo exitosamente");
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
			$id_model = $_GET['id'];
			$model =$this->model->get($id_model);
			if($model){

				$this->model->delete($id_model);
				$this->show_message("success", "El modelo se elimino exitosamente");

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
			$id_model= $_GET['id'];
			$model =$this->model->get($id_model);
			if($model){

				$section = file_get_contents('Views/Model/details.html');

			    $dicc = array('{nombre}' => $model['model']
			    			 ,'{marca}' => $model['brand']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese modelo';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_model= $_GET['id'];
			$model =$this->model->get($id_model);
			if($model){

				$section = file_get_contents('Views/Model/edit.html');

			    $dicc = array('{id}' => $model['id_model']
			    	         ,'{nombre}' => $model['model']
			    			 ,'{marca}' => $model['brand']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese servicio para editarlo';
			}
		}
		else{
			$id_model= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$model = $_POST['name'];
			$id_brand = validateNumber($_POST['brand']);


			$result =$this->model->edit($model, $id_brand, $id_model);

			if($result){
				$this->show_message("success", "El modelo se edito correctamente");
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
