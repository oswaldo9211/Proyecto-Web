<?php


/**
* Marco 
*Ctrl de inspecciones, generar nueva inspección y concluirla
*/
require('Controllers/CtrlEstandar.php');

class ReporteCtrl  extends CtrlEstandar
{
	private $dataHeader = array();
	private	$dataFooter = array();
	private $data  = array('ListInventory' => '');
	public $ok  = array("error" => '', "errno" => 0);
	function __construct()
	{
		require_once('include/funtios.php');
		require_once('Models/ReporteMdl.php');
		$this->model = new  InspeccionMdl(); 
	}

	public function run()
	{
		$this->Inventory  = new  ReporteCtrl();
		//$Inspe->init();
		$Act ='';
		//var_dump($_SESSION);
		if(isset($_GET['Act']) )
			$Act = $_GET['Act'];
		elseif (isset($_POST['Act'])){
			$Act = $_POST['Act'];
		}
		if(array_key_exists('username', $_SESSION) )
			$this->dataHeader{'user'} = $_SESSION['username'];
		else header("Location: index.php");
		 switch($Act) {

			 case 'Ver':
			 	if($this->isUser())
			 		$this->Inventory->Ver();
			 	else
			 		require('Views/error.html');
			 	break;
			 default:
			 if($this->isUser())
			 	$this->Inventory->DefaultIns();
			 	break;
			 }
		
	}
	public function ExistenciaInspeccion(&$data)
	{
			/*SELECT *
	FROM `Inspection`
	WHERE STATUS = 'process'*/
		$rs= $this->model->getRoW('Inspection','*'," WHERE status = 'process' ",$this->ok);
		//var_dump($rs);
		if ($rs!= false && $rs->num_rows > 0) {
			while ($Inspeccion = $rs->fetch_assoc() ) {
				//var_dump($Inspeccion);
				$data['cont'] ++;
				$data['ListaReportes'] .="<tr>";
				$data['ListaReportes'] .= "<td>";
				$rsV= $this->model->getRoW('Vehicle','*'," WHERE id_vehicle = $Inspeccion[id_vehicle] ",$this->ok);
				if($rsV != false && $rsV->num_rows > 0){
					$VIN=$rsV->fetch_assoc() ;
					$data['ListaReportes'] .= $VIN{'vin'};
				}
				else
					$data['ListaReportes'] .= $Inspeccion{'id_vehicle'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= "Inspeccion";
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= $Inspeccion{'date'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .="</tr>";
			}
		}
	}

	public function ExistenciaInventario(&$data)
	{
			/*SELECT *
				FROM `Inventory`
				WHERE `status` = 'slope'*/
		$rs= $this->model->getRoW('Inventory','*'," WHERE status = 'slope' ",$this->ok);
		//var_dump($rs);
		if ($rs!= false && $rs->num_rows > 0) {
			while ($Inv = $rs->fetch_assoc() ) {
				//var_dump($Inspeccion);
				$data['cont'] ++;
				$data['ListaReportes'] .="<tr>";
				$data['ListaReportes'] .= "<td>";
				$rsV= $this->model->getRoW('Vehicle','*'," WHERE id_vehicle = $Inv[id_vehicle] ",$this->ok);
				if($rsV != false && $rsV->num_rows > 0){
					$VIN=$rsV->fetch_assoc() ;
					$data['ListaReportes'] .= $VIN{'vin'};
				}
				else
					$data['ListaReportes'] .= $Inv{'id_vehicle'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$rsU= $this->model->getRoW('Location','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
				if($rsU != false && $rsU->num_rows > 0){
					$ubicacion=$rsU->fetch_assoc() ;
					$rsS= $this->model->getRoW('Service','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
					$Servicio = $rsS->fetch_assoc();
					//var_dump($Servicio);
					$data['ListaReportes'] .= $ubicacion{'location_name'}. " " . $Servicio{'service_name'};
				}
				else
					//$data['ListaReportes'] .= $Inv{'id_vehicle'};
					$data['ListaReportes'] .= $Inv['service_ destination'];
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= $Inv{'inv_date'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .="</tr>";
			}
		}
	}
	public function allInspeccion(&$data,$id_vehicle)
	{
		$rs= $this->model->getRoW('Inventory','*'," WHERE id_vehicle = $id_vehicle ",$this->ok);
		//var_dump($rs);
		if ($rs!= false && $rs->num_rows > 0) {
			while ($Inv = $rs->fetch_assoc() ) {
				//var_dump($Inspeccion);
				$data['cont'] ++;
				$data['ListaReportes'] .="<tr>";
				$data['ListaReportes'] .= "<td>";
				$rsV= $this->model->getRoW('Vehicle','*'," WHERE id_vehicle = $Inv[id_vehicle] ",$this->ok);
				if($rsV != false && $rsV->num_rows > 0){
					$VIN=$rsV->fetch_assoc() ;
					$data['ListaReportes'] .= $VIN{'vin'};
				}
				else
					$data['ListaReportes'] .= $Inv{'id_vehicle'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$rsU= $this->model->getRoW('Location','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
				if($rsU != false && $rsU->num_rows > 0){
					$ubicacion=$rsU->fetch_assoc() ;
					$rsS= $this->model->getRoW('Service','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
					$Servicio = $rsS->fetch_assoc();
					//var_dump($Servicio);
					$data['ListaReportes'] .= $ubicacion{'location_name'}. " " . $Servicio{'service_name'};
				}
				else
					//$data['ListaReportes'] .= $Inv{'id_vehicle'};
					$data['ListaReportes'] .= $Inv['service_ destination'];
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= $Inv{'inv_date'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .="</tr>";
			}
		}
	}

	public function getVehiculos(&$data)
	{
		$data['vehiculos'] ='';
		$resultCs = $this->model->getRow('Vehicle','*'," WHERE   status='high'  ", $this->ok);
					if($resultCs!= false  && $resultCs->num_rows > 0)
						while($Vehi =$resultCs->fetch_assoc() ) {
								$data['vehiculos'] .= "<option  value='$Vehi[id_vehicle]'> $Vehi[vin] </option>";
						}
	}

	public function VehiculoPorestado(&$data,$estado)
	{
		$rs= $this->model->getRoW('Inventory','*'," WHERE status = '$estado' ",$this->ok);
		if ($rs!= false && $rs->num_rows > 0) {
			while ($Inv = $rs->fetch_assoc() ) {
				//var_dump($Inspeccion);
				$data['cont'] ++;
				$data['ListaReportes'] .="<tr>";
				$data['ListaReportes'] .= "<td>";
				$rsV= $this->model->getRoW('Vehicle','*'," WHERE id_vehicle = $Inv[id_vehicle] ",$this->ok);
				if($rsV != false && $rsV->num_rows > 0){
					$VIN=$rsV->fetch_assoc() ;
					$data['ListaReportes'] .= $VIN{'vin'};
				}
				else
					$data['ListaReportes'] .= $Inv{'id_vehicle'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$rsU= $this->model->getRoW('Location','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
				if($rsU != false && $rsU->num_rows > 0){
					$ubicacion=$rsU->fetch_assoc() ;
					$rsS= $this->model->getRoW('Service','*'," WHERE id_location = ".$Inv['service_ destination']."",$this->ok);
					$Servicio = $rsS->fetch_assoc();
					//var_dump($Servicio);
					$data['ListaReportes'] .= $ubicacion{'location_name'}. " " . $Servicio{'service_name'};
				}
				else
					//$data['ListaReportes'] .= $Inv{'id_vehicle'};
					$data['ListaReportes'] .= $Inv['service_ destination'];
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= $Inv{'inv_date'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .= "<td>";
				$data['ListaReportes'] .= $Inv{'status'};
				$data['ListaReportes'] .= "</td>";
				$data['ListaReportes'] .="</tr>";
			}
		}
	}

	public function Ver()
	{
		$this->data['cont'] =0;
		$this->data['ListaReportes'] ='';
		$Reporte = new ReporteCtrl();
		if(isset($_GET['id']))
		{
			if($_GET['id']==1)
			{	
				$Reporte->ExistenciaInspeccion($this->data);
				$Reporte->ExistenciaInventario($this->data);
				$name='Reporte/ReporteExistencia';
			}
			if($_GET['id']==2)
			{	$Reporte->getVehiculos($this->data);
				if (isset($_POST['buscar'])) {
					var_dump($_POST['vehiculo']);
					$Reporte->allInspeccion($this->data,$_POST['vehiculo']);
					//$Reporte->ExistenciaInventario($this->data);
				}
				$name='Reporte/ReporteMovVehiculo';
			}

			if($_GET['id']==3)
			{	$Reporte->getVehiculos($this->data);
				if (isset($_POST['buscar'])) {
					var_dump($_POST['estado']);
					$Reporte->VehiculoPorestado($this->data,$_POST['estado']);
					//$Reporte->allInspeccion($this->data,$_POST['vehiculo']);
					//$Reporte->ExistenciaInventario($this->data);
				}
				$name='Reporte/ReporteTotales';
			}
		}
		echo  getFile('header',$this->dataHeader) . getFile($name,$this->data) . getFile('footer',$this->dataFooter);
	}
	public function DefaultIns()
	{
		$this->data['ListaReportes'] ='<tr>';
		$this->data['ListaReportes'] .="<td>Exitencias</td>";
		$this->data['ListaReportes'] .="<td colspan='2'>Reporte que dice cuantos vehiculos se encuentran ya sea en Inspeccion para ser procesados o en algun servicio siendo reparados</td>";
		$this->data['ListaReportes'] .="<td><a href='?ctrl=Reporte&Act=Ver&id=1'><i class='icon-view'></i></a>";
		$this->data['ListaReportes'] .='</tr>';

		$this->data['ListaReportes'] .='<tr>';
		$this->data['ListaReportes'] .="<td>Movimientos de un vehiculos</td>";
		$this->data['ListaReportes'] .="<td colspan='2'>Reporte que muestra los movimientos que tubo un vehiculo desdes su estancia en Dr.Car</td>";
		$this->data['ListaReportes'] .="<td><a href='?ctrl=Reporte&Act=Ver&id=2'><i class='icon-view'></i></a>";
		$this->data['ListaReportes'] .='</tr>';

		$this->data['ListaReportes'] .='<tr>';
		$this->data['ListaReportes'] .="<td>Total de vehiculos en salida, en proceso , o compluidos en un area</td>";
		$this->data['ListaReportes'] .="<td colspan='2'>Reporte que muestra el total de vehiculos en el inventario ya se canceldos , en proceso , concluidos y o cancelados Dr.Car</td>";
		$this->data['ListaReportes'] .="<td><a href='?ctrl=Reporte&Act=Ver&id=3'><i class='icon-view'></i></a>";
		$this->data['ListaReportes'] .='</tr>';

		echo  getFile('header',$this->dataHeader) . getFile('Reporte/defaultReporte',$this->data) . getFile('footer',$this->dataFooter);
	}
}
?>