

<?php
/**
*@Antonio De La Cruz
*
*/

class InspeccionMdl 
{
	private $db_driver;


	public function __construct()
	{
		include_once('connection.php');
		$conexion = getConnectionData();
		//var_dump($conexion);
		$this ->db_driver = new mysqli($conexion["SERVER"] , $conexion['USER'], $conexion["PASSWORD"], $conexion["DATABASE"]);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}

	}

	public function getRow($tabla, $campos, $condicion,&$ok){
		$ok['errno'] =0;
		$sql = "SELECT  ".$campos."  FROM    ".$tabla." ".$condicion."";
		//echo "SQL 38", $sql;
		$result = $this->db_driver->query($sql);
		if($this->db_driver->errno ){
			$ok['errno']=1;
			$ok['error'] .= "No se puedo optener la consulta ". $this->db_driver->error;
			
		}
		return $result;
	}
	public function Inset($tabla,$campos, $values)
	{
		$sql  = " INSERT INTO  ".$tabla." (".$campos.") VALUES(".$values." )";
		//echo $sql;
		$this->db_driver->query($sql);

		if($this->db_driver->errno){
			die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;

	}

	public function createVehicle($VIN,$marca,$modelo,$caracteristicas,$color){
		$query="";

		return true;
	}

	public function ROW($tabla,$campo,$where){
		
	$query ='';
	if( $this->Vehicle['VIN'] == $where)
	 return true;		
	}


	public function del_Rows($tabla,$condicion)
	{
		$sql = "DELETE FROM $tabla $condicion";
		//var_dump($sql);
		$result= $this->db_driver->query($sql);
	   	if($this->db_driver->errno){
			//die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;
	}
	public function ModificarVehiculo($id,$VIN,$Marca,$Modelo,$Car,$Color){
		$sql = "UPDATE  vehiculo  SET vin    	  = '$VIN',
								      marca  	  = '$Marca',
								      modelo 	  = '$Modelo',
								      descripcion = '$Car',
								      color       = '$Color'
				WHERE                 vehiculo_id = $id"; 
	    $result= $this->db_driver->query($sql);
	   	if($this->db_driver->errno){
			//die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;
		

	
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