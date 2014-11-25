

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
	





	

}

?>