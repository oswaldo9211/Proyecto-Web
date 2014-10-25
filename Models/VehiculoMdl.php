

<?php
/**
*@Antonio De La Cruz
*Vehicle controller class
*/

class VehiculoMdl 
{
	private $Vehicle;
	
	public function __construct()
	{
		$this->Vehicle = array('VIN' => 4040,
		                 'Marca'=>'Renault','Modelo'=>'Clio',
		                 'Color'=>'Rojo',
		                 'Car'=>'1898 - 1900 Una idea que nace en una cabañaLa aventura de la marca francesa comienza cuando la Sociedad Renault Frères da sus primeros pasos en 1898');
	}
	private $vin;
	private $brand;
    private $type;


	public function createVehicle($VIN,$marca,$modelo,$caracteristicas,$color){
		$query="";

		return true;
	}

	public function ROW($tabla,$campo,$where){
		
	$query ='';
	if( $this->Vehicle['VIN'] == $where)
	 return true;		
	}

	public function ModificarVehiculo($VIN,$Marca,$Modelo,$Car,$Color){
		if( $this->Vehicle['VIN'] = $VIN){
			$this->Vehicle['Marca']= $Marca;
		    $this->Vehicle['Modelo']=$Modelo;
		    $this->Vehicle['Car']=$Car;
		    $this->Vehicle['Color']=$Color;

		    var_dump($this->Vehicle); 
			return true;
		}
		else
			return false;
	
	}
	public function ShowAll($tabla){
		return $this->Vehicle;
	}
	/*	public function createVehicle($vin,$brand,$type,$model){
		$this->vin   = $vin;
		$this->brand = $brand;
		$this->type  = $type;
		$this->model =  $model;
		return true;
	}*/
	
}

?>