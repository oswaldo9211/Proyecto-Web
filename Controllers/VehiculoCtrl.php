

<?php
/**
*@Antonio De La Cruz
*Vehicle controller class
*/
class VehiculoCtrl
{
	private $VIN;
	private $marca;
	private $modelo;
	private $color;
	private $caracteristicas;
	private $model;
	public $VE;
	
	function __construct()
	{
		include_once('Models/VehiculoMdl.php');
		$this->model = new VehiculoMdl();
		include_once 'Controllers/Validaciones.php';
		

	}

	public function run(){
		$this->VE = new VehiculoCtrl();
		echo 'here';
		$Act ='';
		if(isset($_GET['Act']) )
			$Act = $_GET['Act'];
		elseif (isset($_POST['Act'])) {
			$Act = $_POST['Act'];
		}

		if($Act!='')
		{
			 switch($Act) {
				 case 'Alta':
				 $this->VE->Alta();
				 	break;
				 case 'Modificacion':
				 	$Vehicle->Modificacion();
					 break;
				 case 'Consulta':
				 	$Vehicle->Consulta();
				 	break;
				 case 'Baja':
				 	$Vehicle->Baja();
				 	break;
				 default:
				 	//require('Views/defaultVehicle.html');


			 }
		}
		//else
			//require('Views/defaultVehicle.html');

	}

	public  function Alta(){
		//require('Views/VehicleAlta.html');
		if(isset($_POST['VIN']))
		{
			var_dump($_POST['VIN']);

		}

	}

	public  function Modificacion(){
		echo "Modificacion";
	}

	public  function Consulta(){
		echo "Consulta";
	}

	public  function Baja(){
		echo "Baja";
	}
}


?>