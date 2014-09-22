

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
<<<<<<< HEAD
=======
		echo 'here';
>>>>>>> 25d6e71171fceff2cbbee6de9bba681daaa8b657
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
<<<<<<< HEAD
				 	$this->VE->Modificacion();
					 break;
				 case 'Consulta':
				 	$this->VE->Consulta();
				 	break;
				 case 'Baja':
				 	$this->VE->Baja();
=======
				 	$Vehicle->Modificacion();
					 break;
				 case 'Consulta':
				 	$Vehicle->Consulta();
				 	break;
				 case 'Baja':
				 	$Vehicle->Baja();
>>>>>>> 25d6e71171fceff2cbbee6de9bba681daaa8b657
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
<<<<<<< HEAD
			if(ValidarVIN($_POST['VIN']))
				$this->VIN = $_POST['VIN'];
		}
		if(isset($_POST['modelo'])){

			if(ValidarModelo($_POST['modelo']))
				$this->modelo = $_POST['modelo'];
		}
		if(isset($_POST['marca'])){
			if(ValidarMarca($_POST['marca']))
				$this->marca = $_POST['marca'];
		}
		if(isset($_POST['color'])){
			if(ValidarColor($_POST['color']))
				$this->color = $_POST['color'];
		}
		if(isset($_POST['caracteristicas'])){
			//if(ValidarCar($_POST['caracteristicas']))
			//Texto libre
				$this->caracteristicas = $_POST['caracteristicas'];
		}
		if($this->caracteristicas==NULL && $this->color == NULL &&
		   $this->marca==NULL           && $this->modelo== NULL &&
		   $this->VIN== NULL)
			echo 'Los campos son obligatorios favor de llenar el fomulario';
		else
			if($this->model->createVehicle($this->VIN,$this->marca,
										   $this->modelo,$this->caracteristicas,
										   $this->color
										  )
			  )
				echo 'Alta';
			else
				echo 'ERROR 2002 ';

=======
			var_dump($_POST['VIN']);

		}
>>>>>>> 25d6e71171fceff2cbbee6de9bba681daaa8b657

	}

	public  function Modificacion(){
<<<<<<< HEAD
		
		if(isset($_POST['VIN']))
		{
			if(ValidarVIN($_POST['VIN']))
				$this->VIN = $_POST['VIN'];
		}
		if($this->VIN== NULL)
		{
			echo 'Para modificar se necesita el VIN del automovil';
		}
		else{
			if($this->model->ROW('Vehiculo','VIN',$this->VIN)){

				if(isset($_POST['modelo'])){	
					if(ValidarModelo($_POST['modelo']))
					{
						$this->modelo = $_POST['modelo'];
					}
				}
				if(isset($_POST['marca'])){
					if(ValidarMarca($_POST['marca']))
						$this->marca = $_POST['marca'];
				}
				if(isset($_POST['color'])){
					if(ValidarColor($_POST['color']))
						$this->color = $_POST['color'];
				}
				if(isset($_POST['caracteristicas'])){
					//if(ValidarCar($_POST['caracteristicas']))
					//Texto libre
						$this->caracteristicas = $_POST['caracteristicas'];
				}
				
				if (isset($_POST['modelo']) && isset($_POST['marca']) &&
					isset($_POST['color'])  && isset($_POST['caracteristicas'])
				    )
				{
					if($this->model->ModificarVehiculo($this->VIN,$this->marca,
										   $this->modelo,$this->caracteristicas,
										   $this->color))
					{
			         	echo "Se modificoa";
			        }
			        else
			        	echo 'Error 2003';
				}
				else
					echo 'Los campos son obligatorios para modificar';

		    }
			else
				echo "No existe el Vehiculo";

		}
	}

	public  function Consulta(){
		$result=$this->model->ShowAll('Vehiculo');
		foreach ($result as $key => $value) {
			echo $value;
			echo "\n";
		}
=======
		echo "Modificacion";
	}

	public  function Consulta(){
		echo "Consulta";
>>>>>>> 25d6e71171fceff2cbbee6de9bba681daaa8b657
	}

	public  function Baja(){
		echo "Baja";
	}
}


?>