<?php

/**
	*Oswaldo Marinez Fonseca
Controller the Clients
*/

require('Controllers/CtrlEstandar.php');

class ClientCtrl extends CtrlEstandar{
	private $model;
	function __construct(){
		require_once('Models/ClientMdl.php');
		require('Models/Client.php');
		$this->model =new ClientMdl();
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
		if($this->model->searchClient($_POST['name']))
			echo json_encode("Un cliente con ese nombre ya existe");
		else if ($this->model->searchEmail($_POST['email']))
			echo json_encode("El email del cliente ya existe");
		else if ($this->model->searchRFC($_POST['RFC']))
			echo json_encode("El RFC del cliente ya existe");
		else
			echo json_encode(true);
	}

	private function get_all(){
		$clients = $this->model->get_all();
		echo json_encode($clients);
	}

	private function show_all(){
		//get all employees to display
		$clients =$this->model->get_all();
		$section = file_get_contents('Views/Client/show_all.html');;
		$info = "";
		if($clients)
			foreach ($clients as $client) {
				$info .= "<tr>
			         		<td> $client[client_name]</td>
			         		<td> $client[client_RFC] </td>
			         		<td> $client[client_emai] </td>
			         		<td>
			         			<a href='index.php?ctrl=client&act=details&id=$client[id_client]'><i class='icon-view'></i></a>
			         			<a href='index.php?ctrl=client&act=edit&id=$client[id_client]'><i class='icon-edit'></i></a>";
			    if($this->isAdmin()){
					$info .=   "<a href='index.php?ctrl=client&act=delete&id=$client[id_client]'><i class='icon-remove'></i></a>";
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
			$section = file_get_contents('Views/Client/create.html');
			$this->template($section);
		}
		else{
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$RFC = validateRFC($_POST['RFC']);
			$email = validateEmail($_POST['email']);
			$phone = $_POST['phone'];
			$cellphone = $_POST['cellphone'];


			$client = new Client($name, $RFC, $email, $phone, $cellphone);
			$result =$this->model->create($client);

			if($result){
				$this->show_message("success", "El cliente se creo exitosamente");
			}
			else{
				$this->show_message("danger", "No se creo, no puede haber duplicados en el nombre, correo o RFC");
			}
		}
	}

	private function delete(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_client = $_GET['id'];
			$client =$this->model->get($id_client);
			if($client){

				$this->model->delete($id_client);
				$this->show_message("success", "El usuario se elimino exitosamente");

			}
			else{
				echo 'no existe cliente';
			}
		}
	}
	private function details(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else{
			$id_client= $_GET['id'];
			$client =$this->model->get($id_client);
			if($client){

				$section = file_get_contents('Views/Client/details.html');

			    $dicc = array('{nombre}' => $client['client_name']
			    			 ,'{RFC}' => $client['client_RFC']
			    			 ,'{email}' => $client['client_emai']
			    			 ,'{telefono}' => $client['client_phone']
			    			 ,'{celular}' => $client['client_cellphone']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese cliente';
			}
		}

	}
	private function edit(){
		if(empty($_GET['id'])){
			$this->show_all();
		}
		else if(empty($_POST)){
			$id_client= $_GET['id'];
			$client =$this->model->get($id_client);
			if($client){

				$section = file_get_contents('Views/Client/edit.html');

			    $dicc = array('{id}' => $client['id_client']
			    	         ,'{nombre}' => $client['client_name']
			    			 ,'{RFC}' => $client['client_RFC']
			    			 ,'{email}' => $client['client_emai']
			    			 ,'{telefono}' => $client['client_phone']
			    			 ,'{celular}' => $client['client_cellphone']
			    	);
			    $section = strtr($section, $dicc);
				$this->template($section);
			}
			else{
				echo 'no existe ese empleado para editarlo';
			}
		}
		else{
			$id_client= $_GET['id'];
			require_once("Controllers/Validaciones.php");
			$name = validateName($_POST['name']);
			$RFC = validateRFC($_POST['RFC']);
			$email = validateEmail($_POST['email']);
			$phone = $_POST['phone'];
			$cellphone = $_POST['cellphone'];

			$client = new Client($name, $RFC, $email, $phone, $cellphone);

			$result =$this->model->edit($client, $id_client);

			if($result){
				$this->show_message("success", "El cliente se edito correctamente");
			}
			else{
				$this->show_message("danger", "No se edito no puede haber duplicados en el nombre o el correo");
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
