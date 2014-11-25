

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
		require('config.ini');
		$this ->db_driver = new mysqli($servidor, $user, $password, $nombre);
		if($this->db_driver->connect_errno){
			die("No se pudo conectar porque {$this->db_driver->connect_error}");
		}

	}

	public function getMaxid($id, $table){
		$sql = "Select Max(".$id.") as id from ".$table."";
		$rs = $this->db_driver->query($sql);
		$rsql = $rs->fetch_assoc();
		$lid = $rsql["id"];
		$lid = !empty($lid) ? $lid : 1;
		return $lid+1;
	}

	public function getRow($tabla, $campos, $condicion,&$ok){
		$ok['errno'] =0;
		$sql = "SELECT  ".$campos."  FROM    ".$tabla." ".$condicion."";
		//echo "SQL 38", $sql;
		$result = $this->db_driver->query($sql);
		if($this->db_driver->errno ){
			
			$ok['error'] .= "No se puedo optener la consulta ". $this->db_driver->error;
			
		}
		return $result;
	}
	
	public function Inset($tabla,$campos, $values,&$ok)
	{
		$sql  = " INSERT INTO  ".$tabla." (".$campos.") VALUES(".$values." )";
		//echo $sql;
		$result=$this->db_driver->query($sql);

		if($this->db_driver->errno){
			$ok['errno'] = 1;
			$ok['error'] .= "No se pudo hacer la consulta al insertar ".$this->db_driver->error;
			return false;
		}
		else
			return  true;

	}




	public function del_Rows($tabla,$condicion)
	{
		$sql = "DELETE FROM $tabla $condicion";
		var_dump($sql);
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
	public function UpdateEstado($id,$estado,&$ok)
	{
		$sql =" UPDATE Inventory
				SET    status = '$estado'
				WHERE id_inventory = $id";
		//echo "114: ", $sql;
		$result= $this->db_driver->query($sql);
	   	if($this->db_driver->errno){
			//die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;
	}
	public function UpdateVehiculo($id,$vehiculo,&$ok)
	{
		$sql =" UPDATE Inspection
				SET    id_vehicle = '$vehiculo'
				WHERE  id_inspection = $id";
				//echo "here :", $sql;
		$result= $this->db_driver->query($sql);
	   	if($this->db_driver->errno){
			//die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;
	}
	public function UpdateUsrCancelar($id,$usr_id,&$ok)
	{
		$sql =" UPDATE Inspection
				SET    id_usercancel = '$usr_id'
				WHERE  id_inspection = $id";
				//echo "here :", $sql;
		$result= $this->db_driver->query($sql);
	   	if($this->db_driver->errno){
			//die("No se pudo hacer la consulta al insertar ".$this->db_driver->error);
			return false;
		}
		else
			return true;
	}
}

?>