<?php

/**
	*Oswaldo Marinez Fonseca
Controlador de Pieza
*/

require('Controllers/CtrlEstandar.php');

class PieceCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/PieceMdl.php');
		$this->model =new PieceMdl();
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
				if($this->isUser())
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
		$pieces =$this->model->get_all();
		$section = file_get_contents('Views/Piece/show_all.html');;
		$info = "";
		foreach ($pieces as $piece) {
			$info .= "<tr>
		         		<td> $piece[nombre] </td>
		         		<td>
		         			<a href='index.php?ctrl=piece&act=details&id=$piece[idpieza]'><i class='icon-view'></i></a>
		         			<a href='index.php?ctrl=piece&act=edit&id=$piece[idpieza]'><i class='icon-edit'></i></a>";
		    if($this->isAdmin()){
				$info .=   "<a href='index.php?ctrl=piece&act=delete&id=$piece[idpieza]'><i class='icon-remove'></i></a>";
			}
				$info .="</td>
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
			$section = file_get_contents('Views/Piece/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateText($_POST['name']);

			$result =$this->model->create($name);

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
			$id_piece= $_GET['id'];
			$piece =$this->model->get($id_piece);
			if($piece){

				$this->model->delete($id_piece);
				$this->show_all();

			}
			else{
				echo 'no existe pieza';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_piece= $_GET['id'];
			$piece =$this->model->get($id_piece);
			if($piece){

				$section = file_get_contents('Views/Piece/details.html');

			    $dicc = array('{nombre}' => $piece['nombre']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe pieza';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_piece= $_GET['id'];
			$piece =$this->model->get($id_piece);
			if($piece){

				$section = file_get_contents('Views/Piece/edit.html');

			    $dicc = array('{id}' => $piece['idpieza']
			    	         ,'{nombre}' => $piece['nombre']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe esa pieza';
			}
		}
		else{
			$id_piece= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateText($_POST['name']);

			$result =$this->model->edit($name, $id_piece);

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
