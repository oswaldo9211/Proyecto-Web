

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
	public  $VE;
	private $ok = array('error' => '', 'errno'=> 0, 'errorSQL' => '' );
	public $data ;
	private $fTemplate;
	private $nameFile ='';
	
	function __construct()
	{
		include_once('include/Template.php');
		$this->fTemplate = new Template();
		include_once('Models/VehiculoMdl.php');
		$this->model = new VehiculoMdl();
		include_once 'Controllers/Validaciones.php';
	}

	/*object(mysqli_result)#9 (5) 
	{ ["current_field"]=> int(0) ["field_count"]=> int(7) 
	["lengths"]=> array(7) 
	{[0]=> int(1) [1]=> int(2) [2]=> int(3) [3]=> int(3) 
	 [4]=> int(3) [5]=> int(4) [6]=> int(0) 
	 } 
	 ["num_rows"]=> int(1) ["type"]=> int(0) }
	*/
	public function init(&$data)
	{
		$band= true;
		$data['VehiculosLista'] ='';
		$result = $this->model->getRow(' vehiculo ', ' * ', ' WHERE 1 ', $this->ok);
		if($result!= false  &&  $result->num_rows > 0){
			
			while ($vehiculos=$result->fetch_assoc()) {
				//var_dump($vehiculos);
				if(!$band){
					$data['VehiculosLista'] .='<tr class="alt">';
					$band =true;	
				}
				else
				{
					$band=false;
					$data['VehiculosLista'] .='<tr >';
				}
				$data['VehiculosLista'] .="<td> CLIENTE </td>";
				$data['VehiculosLista'] .="<td> $vehiculos[vin] </td>";
				$data['VehiculosLista'] .="<td> $vehiculos[marca] </td>";
				$data['VehiculosLista'] .="<td> $vehiculos[modelo] </td>";
				$data['VehiculosLista'] .="<td><a href='?ctrl=Vehiculo&Act=Ver&idV=$vehiculos[vehiculo_id]'><i class='icon-view'></i></a>";
				$data['VehiculosLista'] .="<a href='?ctrl=Vehiculo&Act=Edit&idV=$vehiculos[vehiculo_id]'><i class='icon-edit'></i></a>";
				$data['VehiculosLista'] .="<a href='?ctrl=Vehiculo&Act=Delete&idV=$vehiculos[vehiculo_id]'><i class='icon-remove'></i></a> </td>";
				$data['VehiculosLista'] .='</tr>';
			}
		}

	}
	public function run(){
		$this->VE = new VehiculoCtrl();
		$Act ='';

		if(isset($_GET['Act']) )
			$Act = $_GET['Act'];
		elseif (isset($_POST['Act'])){
			$Act = $_POST['Act'];
		}

		
		 switch($Act) {
			 case 'Alta':
			 $this->VE->Alta();
			 	break;
			 case 'Edit':
			 	$this->VE->Modificacion();
				 break;
			 case 'Consulta':
			 	$this->VE->Consulta();
			 	break;
			 case 'Delete':
			 	$this->VE->Baja();
			 	break;
			 case 'Ver':
			 	$this->VE->Ver();
			 	break;
				 break;
			 default:
			 	$this->nameFile = '/Vehiculo/defaultVehicle';
			 	$this->data['VehiculosLista'] ='';
				$this->VE->init($this->data);
				$this->fTemplate->setTemplate($this->nameFile);
				$this->fTemplate->setVars($this->data);
				echo $this->fTemplate->show();
			 	break;
			 }
		

	}
	public function Ver()
	{

		$this->data['id'] = '';
		$this->data['vin'] = '';
		$this->data['marca'] = '';
		$this->data['modelo'] =  '';
		$this->data['des'] = '';
		$this->data['color'] = '';
		$this->nameFile='/Vehiculo/verVehiculo';
		$this->data['Act'] ='Ver';
		if(isset($_GET['idV']))
			if($_GET['idV']!= '')
			{
				$result=$this->model->getRow(' vehiculo ',' * ', "WHERE  vehiculo_id =$_GET[idV] ",$this->ok);
				if($result != false  && $result->num_rows > 0)
					$Ver = $result->fetch_assoc();
					$this->data['id'] = $Ver{ "vehiculo_id"};
					$this->data['vin'] = $Ver{ "vin"};
					$this->data['marca'] = $Ver{ "marca"};
					$this->data['modelo'] = $Ver{ "modelo"};
					$this->data['des'] = $Ver{ "descripcion"};
					$this->data['color'] = $Ver{ "color"};
			}
		$this->fTemplate->setTemplate($this->nameFile);
		$this->fTemplate->setVars($this->data);
		echo $this->fTemplate->show();
	}
	public  function Alta(){
		//require('Views/VehicleAlta.html');
		if(isset($_POST['guardar']))
		{

			if(isset($_POST['vin']))
			{
				if(ValidarVIN($_POST['vin']))
					$this->VIN = $_POST['vin'];
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
				//if(ValidarColor($_POST['color']))
				$this->color = $_POST['color'];
			}
			if(isset($_POST['des'])){
				//if(ValidarCar($_POST['caracteristicas']))
				//Texto libre
				$this->caracteristicas = $_POST['des'];
			}
			if($this->caracteristicas==NULL || $this->color == NULL ||
			   $this->marca==NULL           || $this->modelo== NULL ||
			   $this->VIN== NULL)
				{
					echo 'Los campos son obligatorios favor de llenar el fomulario';}
			else{
				 $campos = " vin,marca,modelo,color,descripcion";
				 $values = $this->VIN.",'".$this->marca."','".$this->modelo."','".$this->color."','".$this->caracteristicas."'";
				 $result = $this->model->Inset("vehiculo",$campos,$values);
				 /*echo '<br>';
				 echo '<br>', $campos;
				 echo '<br>', $values;*/
				if($result)
				{
						header('Location: index.php?Ctrl=Vehiculo');
				}
				else
					echo 'ERROR 2002 ';
			}
		}
		else
		{
			$this->nameFile='/Vehiculo/AltaVehiculo';
			$this->data['Act'] ='Alta';
			$this->fTemplate->setTemplate($this->nameFile);
			$this->fTemplate->setVars($this->data);
			echo $this->fTemplate->show();
		}

	}//fin de alta

	

	public  function Modificacion(){
		$this->data['id'] = '';
		$this->data['vin'] = '';
		$this->data['marca'] = '';
		$this->data['modelo'] =  '';
		$this->data['des'] = '';
		$this->data['color'] = '';
		$this->nameFile='/Vehiculo/modVehiculo';
		$this->data['Act'] ='Edit';
		$Ver ='';
		if(isset($_GET['idV']))
			if($_GET['idV']!= '')
			{
				$this->data['VIN'] = $_GET['idV'];
				$result=$this->model->getRow(' vehiculo ',' * ', "WHERE  vehiculo_id =$_GET[idV] ",$this->ok);
				if($result != false  && $result->num_rows > 0)
					$Ver = $result->fetch_assoc();
					if ( $Ver != NULL ) {
						$this->data['id'] = $Ver{ "vehiculo_id"};
						$this->data['vin'] = $Ver{ "vin"};
						$this->data['marca'] = $Ver{ "marca"};
						$this->data['modelo'] = $Ver{ "modelo"};
						$this->data['des'] = $Ver{ "descripcion"};
						$this->data['color'] = $Ver{ "color"};
					}
			}
		$this->fTemplate->setTemplate($this->nameFile);
		$this->fTemplate->setVars($this->data);
		echo $this->fTemplate->show();
		if(isset($_POST['Edit'])){

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
				//if(ValidarColor($_POST['color']))
					$this->color = $_POST['color'];
			}
			if(isset($_POST['des'])){
				//if(ValidarCar($_POST['caracteristicas']))
				//Texto libre
					$this->caracteristicas = $_POST['des'];
			}
			
			if (isset($_POST['modelo']) && isset($_POST['marca']) &&
				isset($_POST['color'])  && isset($_POST['des'])
			    )
			{
				if($this->model->ModificarVehiculo($_GET['idV'],$this->data['VIN'],$this->marca,
									   $this->modelo,$this->caracteristicas,
									   $this->color))
				{
		         	header('Location: index.php?ctrl=Vehiculo');
		        }
		        else
		        	echo 'Error 2003';
			}
			else
				echo 'Los campos son obligatorios para modificar';

	 }
	}
	

	public  function Consulta(){
		if(isset($_POST['VIN']))
		{
			if(ValidarVIN($_POST['VIN'])){
				$this->VIN = $_POST['VIN'];
			}
			$result=$this->model->getRow('vehiculo','*', ' WHERE  VIN = '.$this->VIN.'',$this->ok);
			
			if($result != false  && $result->num_rows > 0){
			   $VehiculoM = $result->fetch_assoc();
			   echo ".ID: ", $VehiculoM['vehiculo_id'];
			   echo " .VIN:",$VehiculoM['vin']  ;
			   echo " .Marca: ",$VehiculoM['marca'];
			   echo " .modelo: ",$VehiculoM['modelo'];
			   echo " .color: ",$VehiculoM['color'];
			   echo " .caracteristicas: ",$VehiculoM['descripcion'];
			}
			else
				echo 'No se encontro ningun Vehiculo con ese VIN';
			if($this->ok['errno'] == 1){
				echo 'Error de SQL ', $this->ok['errorSQL'];
			}
		}
		else
			if($this->VIN == NULL  )
			{
				echo 'Se Necesita el VIN para consultar';
			}
	}


	public  function Baja(){
		if(isset($_GET['idV']))
		{
			if(ValidarVIN($_GET['idV'])){
				$this->VIN = $_GET['idV'];
			}
			$result=$this->model->del_Rows('vehiculo', ' WHERE  vehiculo_id = '.$this->VIN.'');
			if ($result!= false) {
				header('Location: index.php?ctrl=Vehiculo');
				echo "ELIMINO";
			}
		}
		else
			if($this->VIN == NULL  )
			{
				echo 'Se Necesita el VIN para consultar';
			}
	}
}



?>