

<?php
/**
*@Antonio De La Cruz
*Vehicle controller class
*/
require('Controllers/CtrlEstandar.php');

class VehiculoCtrl  extends CtrlEstandar
{
	private $VIN;
	private $marca;
	private $modelo;
	private $color;
	private $cliente;
	private $caracteristicas;
	private $model;
	public  $VE;
	private $ok = array('error' => '', 'errno'=> 0, 'errorSQL' => '' );
	public $data  = array( );
	public $dataHeader = array();
	public $dataFooter  = array( );
	private $fTemplate;
	private $nameFile ='';
	
	function __construct()
	{
		include_once('Models/VehiculoMdl.php');
		$this->model = new VehiculoMdl();
		include_once 'Controllers/Validaciones.php';
		require_once('include/funtios.php');
	}


	public function run(){
		$this->dataHeader{'user'} = $_SESSION['username'];
		$this->VE = new VehiculoCtrl();
		$Act ='';
		//var_dump($_SESSION);
		if(isset($_GET['Act']) )
			$Act = $_GET['Act'];
		elseif (isset($_POST['Act'])){
			$Act = $_POST['Act'];
		}
		 switch($Act) {
			 case 'Alta':
			  if($this->isAdmin())
			 	$this->VE->Alta();
			 else
			 	require('Views/error.html');
			 	break;
			 case 'Edit':
			  	 if($this->isAdmin())
			 		$this->VE->Modificacion();
			 	else
			 		require('Views/error.html');
				 break;
			 case 'Consulta':
			 	$this->VE->Consulta();
			 	break;
			 case 'Delete':
			 if($this->isAdmin())
			 	$this->VE->Baja();
			 else
			 	require('Views/error.html');
			 	break;
			 case 'Ver':
				 if($this->isUser())
				 	$this->VE->Ver();
				 else
				 	require('Views/error.html');
			 	break;
				 break;
			 default:
			 	 if($this->isUser()){
			 		$this->nameFile = '/Vehiculo/defaultVehicle';
				 	$this->data['VehiculosLista'] ='';
					$this->VE->init($this->data);
				 	echo  getFile('header',$this->dataHeader) . getFile('/Vehiculo/defaultVehicle',$this->data) . getFile('footer',$this->dataFooter);
			 	}
			 	break;
			 }
	}

	public function init(&$data)
	{
		$band= true;
		$data['VehiculosLista'] ='';
		$result = $this->model->getRow('Vehicle', ' * ', ' WHERE status="high" ', $this->ok);
		if($result!= false  &&  $result->num_rows > 0){
			
			while ($vehiculos=$result->fetch_assoc()) {	
				$data['estatus'] = $vehiculos['status'];
				$data['VehiculosLista'] .='<tr >';
				$resultC  = $this->model->getRow('Client', ' *', " WHERE id_client = $vehiculos[id_client]", $thi->ok);
				if($resultC != false && $resultC->num_rows > 0)
				{
					$ClienteNombre = $resultC->fetch_assoc();
					//var_dump($ClienteNombre{'client_name'});
					$data['VehiculosLista'] .=  "<td>" .$ClienteNombre{'client_name'} . "</td>";
				}
				else
					$data['VehiculosLista'] .="<td> CLIENTE </td>";
				$data['VehiculosLista'] .="<td> $vehiculos[vin] </td>";
				//$data['VehiculosLista'] .="<td> $vehiculos[marca] </td>";
				$resulM = $this->model->getRow('Model', '*' , "WHERE id_model = $vehiculos[id_model] ",$this->ok);
				if($resulM != false && $resulM->num_rows > 0)
				{
					while ( $value = $resulM->fetch_assoc()) {
						$data['VehiculosLista'] .="<td> $value[model] </td>";
					}
				}
				else
					$data['VehiculosLista'] .="<td> NULL </td>";
				$data['VehiculosLista'] .="<td><a href='?ctrl=Vehiculo&Act=Ver&idV=$vehiculos[id_vehicle]'><i class='icon-view'></i></a>";
				$data['VehiculosLista'] .="<a href='?ctrl=Vehiculo&Act=Edit&idV=$vehiculos[id_vehicle]'><i class='icon-edit'></i></a>";
				$data['VehiculosLista'] .="<a href='?ctrl=Vehiculo&Act=Delete&idV=$vehiculos[id_vehicle]'><i class='icon-remove'></i></a> </td>";
				$data['VehiculosLista'] .='</tr>';
			}
		}

	}

	public  function Modificacion(){
		$this->data['modelos'] ='';
		$this->data['clientes'] ='';
		$this->data['id'] = '';
		$this->data['vin'] = '';
		$this->data['modelo'] =  '';
		$this->data['des'] = '';
		$this->data['color'] = '';
		$this->nameFile='/Vehiculo/modVehiculo';
		$this->data['Act'] ='Edit';
		$dataHeader = array();
		$dataFooter  = array( );
		$Ver ='';
		if(isset($_GET['idV']))
			if($_GET['idV']!= '')
			{
				$this->data['VIN'] = $_GET['idV'];
				$result=$this->model->getRow(' Vehicle ',' * ', "WHERE  id_vehicle =$_GET[idV] ",$this->ok);
				if($result != false  && $result->num_rows > 0)
					$Ver = $result->fetch_assoc();
					$this->data['id'] = $Ver{ "id_vehicle"};
					$this->data['vin'] = $Ver{ "vin"};
					$resulM = $this->model->getRow('Model', '*' , "WHERE id_model = $Ver[id_model] ",$this->ok);
					if($resulM != false && $resulM->num_rows > 0)
					{
						while ( $value = $resulM->fetch_assoc()) {
							//$data['VehiculosLista'] .="<td> $value[model] </td>";
							$this->data['model'] = $value['model'];
						}
					}
					$resultMs = $this->model->getRow('Model','*',' WHERE  1', $this->ok);
					if($resultMs!= false  && $result->num_rows > 0)
						while ($model =$resultMs->fetch_assoc()) {
							//var_dump($model);
							$this->data['modelos'] .= "<option value='$model[id_model]'> $model[model]";
							$this->data['modelos'] .= "</option>";
						}
					$resultC = $this->model->getRow('Client','*'," WHERE   id_client = $Ver[id_client] ", $this->ok);
					if($resultC!= false  && $resultC->num_rows > 0)
						while ( $Clie = $resultC->fetch_assoc()) {
							//var_dump($Clie);
							$this->data['cliente'] = $Clie{'client_name'};
						}
					$resultCs = $this->model->getRow('Client','*'," WHERE   1 ", $this->ok);
					if($resultCs!= false  && $resultCs->num_rows > 0)
						while ( $Clies = $resultCs->fetch_assoc()) {
							//echo $Clies{'client_name'};
							if($this->data['cliente'] != $Clies{'client_name'} )
							$this->data['clientes'] .= "<option  value='$Clies[id_client]'>$Clies[client_name] </option>";
						}
					$this->data['des'] = $Ver{ "description"};
					$this->data['color'] = $Ver{ "color"};
					
			}
		
		if(isset($_POST['Edit'])){
			//validar al modificar que no este ingresado ya ese VIN
			//var_dump($_POST['vin']);
			if(isset($_POST['vin'])  && strlen($_POST['vin'])!= 0)
			{
				var_dump($_POST['vin']);
				$this->vin = $_POST['vin'];
				$result = $this->model->getRow('Vehicle', '*', " WHERE  id_vehicle != $_GET[idV]  AND vin =  '$_POST[vin]' ", $this->ok);
				//var_dump($result);
				if($result != false &&  $result->num_rows == 0){
					if( isset($_POST['modelo'])  ){
						echo 'model ',var_dump($_POST['modelo']);
						$this->modelo = $_POST['modelo'];
					}
					if(isset($_POST['des']))
					{
						var_dump($_POST['des']);
						$this->caracteristicas = $_POST['des'];
					}
					if(isset($_POST['cliente'])){
						echo 'cliente ',var_dump($_POST['cliente']);
						$this->cliente = $_POST['cliente'];
					}
					if(isset($_POST['color'])){
						var_dump($_POST['color']);
						$this->color= $_POST['color'];
					}
					
					if(strlen($this->vin) != 0 && strlen($this->modelo) != 0  && strlen($this->cliente) != 0  && $this->caracteristicas != NULL  && $this->color != NULL ){
						
						if($this->cliente == "0"){
							if($this->modelo =="0"){
								$sql = "UPDATE Vehicle SET vin='$this->vin',
															description = '$this->caracteristicas',
															color = '$this->color'
										WHERE   id_vehicle = $_GET[idV]";
								$result= $this->model->UpdateVehiculo($sql,$this->ok);
								if($result!= false)
								{
									header("Location: index.php?ctrl=Vehiculo&Act=$_GET[Act]&idV=$_GET[idV]");
								}
							}
							else
							{
								$sql = "UPDATE Vehicle SET vin='$this->vin',
															description = '$this->caracteristicas',
															color = '$this->color',
															id_model = $this->modelo
										WHERE   id_vehicle = $_GET[idV]";
								$result= $this->model->UpdateVehiculo($sql,$this->ok);
								if($result!= false)
								{
									header("Location: index.php?ctrl=Vehiculo&Act=$_GET[Act]&idV=$_GET[idV]");
								}
							}
						}
						else
						{
							if($this->cliente != "0" && $this->modelo =="0")
							{
								$sql = "UPDATE Vehicle SET vin='$this->vin',
															description = '$this->caracteristicas',
															color = '$this->color',
															id_client = $this->cliente
										WHERE   id_vehicle = $_GET[idV]";
								$result= $this->model->UpdateVehiculo($sql,$this->ok);
								if($result!= false)
								{
									header("Location: index.php?ctrl=Vehiculo&Act=$_GET[Act]&idV=$_GET[idV]");
								}
							}
							else
							if($this->cliente != "0" && $this->modelo !="0"){
								$sql = "UPDATE Vehicle SET vin='$this->vin',
															description = '$this->caracteristicas',
															color = '$this->color',
															id_model = $this->modelo,
															id_client = $this->cliente
										WHERE   id_vehicle = $_GET[idV]";
								$result= $this->model->UpdateVehiculo($sql,$this->ok);
								if($result!= false)
								{
									header("Location: index.php?ctrl=Vehiculo&Act=$_GET[Act]&idV=$_GET[idV]");
								}
							}
						}
					}
					else{
						$this->ok['error'] .= "Los campos son obligatorios";
					}
				}
				else
					$this->ok['error'] .= "Ya se encuentra un vehiculo con el VIN identico favor de verificarlo";
			}
			
			
		}
		echo  getFile('header',$dataHeader) . getFile($this->nameFile,$this->data). getFile('footer',$dataFooter);
	}

	public function Ver()
	{
		$this->data['modelos'] ='';
		$this->data['id'] = '';
		$this->data['vin'] = '';
		$this->data['modelo'] =  '';
		$this->data['des'] = '';
		$this->data['color'] = '';
		$dataHeader = array();
		$dataFooter  = array( );
		$this->nameFile='/Vehiculo/verVehiculo';
		$this->data['Act'] ='Ver';
		if(isset($_GET['idV']))
			if($_GET['idV']!= '')
			{
				$result=$this->model->getRow(' Vehicle ',' * ', "WHERE  id_vehicle =$_GET[idV] ",$this->ok);
				if($result != false  && $result->num_rows > 0)
					$Ver = $result->fetch_assoc();
					$this->data['estatus'] = $Ver['status'];
					$this->data['id'] = $Ver{ "id_vehicle"};
					$this->data['vin'] = $Ver{ "vin"};
					$resulM = $this->model->getRow('Model', '*' , "WHERE id_model = $Ver[id_model] ",$this->ok);
					if($resulM != false && $resulM->num_rows > 0)
					{
						while ( $value = $resulM->fetch_assoc()) {
							//$data['VehiculosLista'] .="<td> $value[model] </td>";
							$this->data['model'] = $value['model'];
						}
					}
					/*$resultMs = $this->model->getRow('Model','*',' WHERE  1', $this->ok);
					if($resultMs!= false  && $resultMs->num_rows > 0)
						foreach ($resultMs as $key => $model) {
							//var_dump($model);
							$this->data['modelos'] .= "<option value='$model[id_model]'> $model[model]";
							$this->data['modelos'] .= "</option>";
						}*/
					$resultC = $this->model->getRow('Client','*'," WHERE   id_client = $Ver[id_client] ", $this->ok);
					
					if($resultC!= false  && $resultC->num_rows > 0)
						while ( $Clie = $resultC->fetch_assoc()) {
							//var_dump($Clie);
							$this->data['cliente'] = $Clie{'client_name'};
						}
					$this->data['des'] = $Ver{ "description"};
					$this->data['color'] = $Ver{ "color"};
					
			}
		echo  getFile('header',$dataHeader) . getFile($this->nameFile,$this->data). getFile('footer',$dataFooter);
	}

	public  function Alta(){
		$this->data['modelos'] ='';
		$result = $this->model->getRow('Model','*',' WHERE  1', $this->ok);
		if($result!= false  && $result->num_rows > 0)
			while ( $model = $result->fetch_assoc()) {
				//var_dump($model);
				$this->data['modelos'] .= "<option value='$model[id_model]'> $model[model]";
				$this->data['modelos'] .= "</option>";
			}
		if(isset($_POST['guardar']))
		{
			if(isset($_POST['vin']))
			{
				if(ValidarVIN($_POST['vin'], $this->ok))
					$this->VIN = $_POST['vin'];
			}
			if(isset($_POST['modelo'])){

				if($_POST['modelo'] != 0)
					$this->modelo = $_POST['modelo'];
				else
					$this->ok['error'] .= "Debe de seleccionar un modelo";
			}
			if(isset($_POST['marca'])){
				if(ValidarMarca($_POST['marca'],$this->ok))
					$this->marca = $_POST['marca'];
			}
			if(isset($_POST['color'])){
				//if(ValidarColor($_POST['color']))
				//en hexadecimal
				$this->color = $_POST['color'];
			}
			if(isset($_POST['des'])){
				//Texto libre
				$this->caracteristicas = $_POST['des'];
			}
			
			if($this->caracteristicas==NULL || $this->color == NULL 
				|| $this->modelo== NULL ||
			   $this->VIN== NULL)
				{
					$this->ok['error'] .= '<th>Los campos son obligatorios favor de llenar el fomulario</th>';}
			else{
				 $campos = " vin,color,description,id_model,id_client";
				 $values = " '$this->VIN','$this->color',' $this->caracteristicas',$this->modelo,1";
				 //$values = $this->VIN.",'".$this->color."','".$this->caracteristicas."',".$this->model."";
				 $result = $this->model->Inset("Vehicle",$campos,$values,$this->ok);
				 /*echo '<br>';
				 echo '<br>', $campos;
				 echo '<br>', $values;*/
				if($result)
				{
						header('Location: index.php?ctrl=Vehiculo');
				}
				else
					echo 'ERROR 2002 ';
			}
			//var_dump($this->ok['error']);
			$this->data['error'] = $this->ok['error'];
			echo  getFile('header',$this->dataHeader) . getFile('Error',$this->data). getFile('footer',$this->dataFooter);
		}
		else
		{
			$dataHeader = array();
			$dataFooter = array();
			$this->data['Act'] ='Alta';
			echo  getFile('header',$dataHeader) . getFile('Vehiculo/AltaVehiculo',$this->data). getFile('footer',$dataFooter);
		}

	}//fin de alta
	
	

	public  function Consulta(){
		if(isset($_POST['VIN']))
		{
			if(ValidarVIN($_POST['VIN'])){
				$this->VIN = $_POST['VIN'];
			}
			$result=$this->model->getRow('vehiculo','*', ' WHERE  VIN = '.$this->VIN.'',$this->ok);
			
			if($result != false  && $result->num_rows > 0){
			   $VehiculoM = $result->fetch_assoc();
			   echo " .ID: ", $VehiculoM['vehiculo_id'];
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
			$result = $this->model->UpdateEstado($_GET['idV'],'down',$this->ok);
			if ($result!= false) {
				header('Location: index.php?ctrl=Vehiculo');
			}
		}
	}
}



?>