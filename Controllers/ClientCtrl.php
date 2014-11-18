<?php

/**
	*Oswaldo Marinez Fonseca
Controlador  de Cliente
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
		$clients =$this->model->get_all();
		$section = file_get_contents('Views/Client/show_all.html');;
		$info = "";
		foreach ($clients as $client) {
			$info .= "<tr>
		         		<td> $client[name] $client[last_name] </td>
		         		<td> $client[RFC] </td>
		         		<td> $client[email] </td>
		         		<td>
		         			<a href='index.php?ctrl=client&act=details&id=$client[id]'><i class='icon-view'></i></a>
		         			<a href='index.php?ctrl=client&act=edit&id=$client[id]'><i class='icon-edit'></i></a>";
		    if($this->isAdmin()){
				$info .=   "<a href='index.php?ctrl=client&act=delete&id=$client[id]'><i class='icon-remove'></i></a>";
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
			$section = file_get_contents('Views/Client/create.html');
			$this->template($section);
		}
		else{
			$name = $_POST['name'];
			$last_name = $_POST['last_name'];
			$RFC = $_POST['RFC'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];

			$client = new Client($name, $last_name, $RFC, $email, $phone);
			$result =$this->model->create($client);

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
			$id_client = $_GET['id'];
			$client =$this->model->get($id_client);
			if($client){

				$this->model->delete($id_client);
				$this->show_all();

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

			    $dicc = array('{nombre}' => $client['name']
			    			 ,'{apellido}' => $client['last_name']
			    			 ,'{RFC}' => $client['RFC']
			    			 ,'{email}' => $client['email']
			    			 ,'{telefono}' => $client['phone']
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

			    $dicc = array('{id}' => $client['id']
			    	         ,'{nombre}' => $client['name']
			    			 ,'{apellido}' => $client['last_name']
			    			 ,'{RFC}' => $client['RFC']
			    			 ,'{email}' => $client['email']
			    			 ,'{telefono}' => $client['phone']
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
			$name = $_POST['name'];
			$last_name = $_POST['last_name'];
			$RFC = $_POST['RFC'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];

			$client = new Client($name, $last_name, $RFC, $email, $phone);

			$result =$this->model->edit($client, $id_client);

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
