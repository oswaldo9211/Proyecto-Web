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
			case 'validate':
				if($this->isUser())
					$this->validate();
				else
					header('Location:  index.php');
				break;
		    default:
		    	header('Location:  index.php');
		}
	}

	private function validate(){
		if($this->model->searchPiece($_POST['name']))
			echo json_encode("La pieza ya existe");
		else
			echo json_encode(true);
	}

	private function show_all(){
		//get all piece to display
		$pieces =$this->model->get_all();
		$section = file_get_contents('Views/Piece/show_all.html');;
		$info = "";
		if($pieces)
			foreach ($pieces as $piece) {
				$info .= "<tr>
			         		<td> $piece[piece_name] </td>
			         		<td>
			         			<a href='index.php?ctrl=piece&act=details&id=$piece[id_piece]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=piece&act=edit&id=$piece[id_piece]'><i class='icon-edit'></i></a>";
			    if($this->isAdmin()){
					$info .=   "<a href='index.php?ctrl=piece&act=delete&id=$piece[id_piece]'><i class='icon-remove'></i></a>";
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
			$section = file_get_contents('Views/Piece/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateText($_POST['name']);

			$result =$this->model->create($name);

			if($result){
				$this->show_message("success", "La pieza se creo exitosamente");
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
			$id_piece= $_GET['id'];
			$piece =$this->model->get($id_piece);
			if($piece){

				$this->model->delete($id_piece);
				$this->show_message("success", "La pieza se elimino exitosamente");

			}
			else{
				require('Views/error.html');
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

			    $dicc = array('{nombre}' => $piece['piece_name']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				require('Views/error.html');
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

			    $dicc = array('{id}' => $piece['id_piece']
			    	         ,'{nombre}' => $piece['piece_name']
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
				$this->show_message("success", "La pieza se edito exitosamente");
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
