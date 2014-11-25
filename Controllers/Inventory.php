<?php


/**
* Marco 
*Ctrl de inspecciones, generar nueva inspección y concluirla
*/
require('Controllers/CtrlEstandar.php');

class InventoryCtrl  extends CtrlEstandar
{

	private $Inventory;
	public  $model;
	private $dataHeader = array();
	private	$dataFooter = array();
	private $data  = array('ListInventory' => '');
	public $ok  = array("error" => '', "errno" => 0);
	function __construct()
	{
		require_once('include/funtios.php');
		require_once('Models/InventoryCtrlnMdl.php');
		$this->model = new  InspeccionMdl(); 
	}

	public function Baja()
	{
		$rsUpdate = $this->model-> UpdateEstado($_GET['id'],'cancel',$this->ok);
		if($rsUpdate  != false) header("Location: ?ctrl=Inventory");
		else
			$data['error'] = $this->ok['error'];	
	}

	public function run()
	{
		$this->Inventory  = new  InventoryCtrl();
		//$Inspe->init();
		$Act ='';
		//var_dump($_SESSION);
		if(isset($_GET['Act']) )
			$Act = $_GET['Act'];
		elseif (isset($_POST['Act'])){
			$Act = $_POST['Act'];
		}
		//var_dump($Act);
		 switch($Act) {
			 case 'Alta':
			 	if($this->isUser())
			 		$this->Inventory->Alta();
			 else
			 		require('Views/error.html');
			 	break;
			 case 'Edit':
			 	if($this->isAdmin())
			 		$this->Inventory->Modificacion();
			 		else
			 			require('Views/error.html');
				 break;
			 case 'Consulta':
			 	$this->Inventory->Consulta();
			 	break;
			 case 'Delete':
			 	if($this->isAdmin())
			 		$this->Inventory->Baja();
			 	else
			 		require('Views/error.html');
			 	break;
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
	public function Modificacion()
	{
		
		$this->dataHeader{'user'} = $_SESSION['username'];
		if(isset($_GET['id']) && $_GET['id'] != 0)
		{
			$this->data['id'] = $_GET['id'];
			$this->data['Act'] = $_GET['Act'];
			$rs= $this->model->getRow('Inventory','*', "WHERE id_inventory = $_GET[id]",$this->ok);
			if($rs != false && $rs->num_rows > 0){
				$Inv = $rs->fetch_assoc();
				if($Inv{'status'}== 'completed' || $Inv{'status'} == 'exit' || $Inv{'status'} == 'cancel') header("Location: ?ctrl=Inventory");
				$this->data{'ListInventory'} .='<tr>';
				$this->data{'ListInventory'} .="<td>$Inv[id_inventory]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[inv_date]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[service]</td>";
				$rsU=$this->model->getRow('Location','*', "WHERE id_location = '".$Inv['service_ destination']."' ",$this->ok);
				if($rsU  != false && $rsU->num_rows > 0){
					//echo 'here',var_dump($rs);
					$Ubicacion = $rsU->fetch_assoc();
					
					$this->data{'ListInventory'} .="<td>$Ubicacion[location_name]</td>" ;
				}
				else
					$this->data{'ListInventory'} .="<td>'No hay ubucacion'</td>";
				$this->data{'ListInventory'} .="<td>$Inv[observations]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[status]</td>";
				
				$rsV=$this->model->getRow('Vehicle','*', "WHERE id_vehicle = $Inv[id_vehicle] ",$this->ok);
				if($rsV  != false && $rsV->num_rows > 0){
					//echo 'here',var_dump($rs);
					$VIN = $rsV->fetch_assoc();
					$this->data{'ListInventory'} .="<td>$VIN[vin]</td>";
				}
				else
					$this->data{'ListInventory'} .="<td>No existe el Vehiculo</td>";
				$this->data{'ListInventory'} .='</tr>';
				//$this->data{'ListInventory'} .="<td>";

				//$this->data{'ListInventory'} .= "</td>";
				$this->data['servicios'] ='';
				$rsS= $this->model->getRow('Service', '*', "WHERE  1", $this->ok);
				if($rsS != false && $rsS->num_rows > 0){
					while($value = $rsS->fetch_assoc()) {
						//var_dump($value);
						if($value['id_location'] != $Inv['service_ destination'] ){
							$this->data['servicios'] .= "<option value='$value[id_location]'>";
							$this->data['servicios'] .= $value{'service_name'};
							$this->data['servicios'] .= "</option>";
						}
					}
				}
				
			}
		}

		if (isset($_POST['mover'])) {
			//var_dump($_POST['observaciones']);
			
			if(isset($_POST['servicio']) &&  $_POST['servicio'] != "0"){
				//var_dump($_POST['servicio']);
				if($_POST['servicio']== "Salida")
					$estado='exit';
				else
					if($_POST['servicio'] == "Cancelar")
						$estado='cancel';
					else
						$estado='completed';
				if($estado == 'completed'){
					$rsUpdate = $this->model-> UpdateEstado($_GET['id'],$estado,$this->ok);
					if($rsUpdate!= false)
					{
						$campos = 'movement,inv_date,service,`service_ destination`,observations,status,id_vehicle,id_user';
						
						$hoy = getdate();
						$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
						$data['hora'] = $hoy['hours']. ':' . $hoy['minutes']. ':'. $hoy['seconds'];
						
						$fecha =$data['FechaEmision'] . " " . $data['hora'];
						
						$values = "'transfer','$data[FechaEmision]','$Inv[service]','$_POST[servicio]','$_POST[observaciones]','slope',$Inv[id_vehicle],1";
						$resultInv=$this->model->Inset('Inventory',$campos, $values,$this->ok);
						if($resultInv != false) header("Location: ?ctrl=Inventory");
						else
							$data['error'] = $this->ok['error'];	
					}
				}
				elseif ($estado=='exit' || $estado=='cancel' ) {
						$rsUpdate = $this->model-> UpdateEstado($_GET['id'],$estado,$this->ok);
						if($rsUpdate  != false) header("Location: ?ctrl=Inventory");
						else
							$data['error'] = $this->ok['error'];	

					}	
			}
			else
				$this->ok['error'] .= "Debe de escoger un area para poder mover el vehiculo";
				
		}
		$this->data['error'] = $this->ok['error'];
		echo  getFile('header',$this->dataHeader) . getFile('Inventory/EditInventory',$this->data) . getFile('footer',$this->dataFooter);
	}
	public function Ver()
	{
		$this->dataHeader{'user'} = $_SESSION['username'];
		if(isset($_GET['id']) && $_GET['id'] != 0)
		{
			$rs= $this->model->getRow('Inventory','*', "WHERE id_inventory = $_GET[id]",$this->ok);
			if($rs != false && $rs->num_rows > 0){
				$Inv = $rs->fetch_assoc();
				//var_dump($Inv);
				$this->data{'ListInventory'} .='<tr>';
				$this->data{'ListInventory'} .="<td>$Inv[id_inventory]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[movement]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[inv_date]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[service]</td>";
				$this->data{'ListInventory'} .="<td>'".$Inv['service_ destination']."'</td>";
				$this->data{'ListInventory'} .="<td>$Inv[observations]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[status]</td>";
				$this->data{'ListInventory'} .="<td>$Inv[id_vehicle]</td>";
				$this->data{'ListInventory'} .='</tr>';
			}
		}
		echo  getFile('header',$this->dataHeader) . getFile('Inventory/VerInventory',$this->data) . getFile('footer',$this->dataFooter);
	}
	public function DefaultIns()
	{
		if(array_key_exists('username', $_SESSION) )
			$this->dataHeader{'user'} = $_SESSION['username'];
		else header("Location: ?ctrl=Inventory");
		$Inspeccion = new InventoryCtrl();
		$result= $this->model->getRow('Inventory', ' * ',  ' WHERE status="slope" ',$this->ok);
		if($result != false && $result->num_rows > 0){
			while ($inv = $result->fetch_assoc()) {
			// echo "</br>: ",var_dump($inv);
				$this->data{'ListInventory'} .= "<tr>";
				$this->data{'ListInventory'} .="<td>$inv[movement]</td>" ;
				$this->data{'ListInventory'} .="<td>$inv[inv_date]</td>" ;
				
				$rs=$this->model->getRow('Location','*', "WHERE id_location = '".$inv['service_ destination']."' ",$this->ok);
				if($rs  != false && $rs->num_rows > 0){
					//echo 'here',var_dump($rs);
					$Ubicacion = $rs->fetch_assoc();
					$this->data{'ListInventory'} .="<td>$Ubicacion[location_name]</td>" ;
				}
				else
					$this->data{'ListInventory'} .="<td>".$inv['service_ destination']."</td>" ;
				$this->data{'ListInventory'} .="<td>$inv[status]</td>" ;
				$rs=$this->model->getRow('Vehicle', '*', "WHERE id_vehicle=$inv[id_vehicle]",$this->ok);
				if($rs!= false && $rs->num_rows > 0){
					$Vehicle_name= $rs->fetch_assoc();
					$this->data{'ListInventory'} .="<td>$Vehicle_name[vin]</td>" ;
				}
				else
					$this->data{'ListInventory'} .="<td>NULL</td>" ;
				$this->data['ListInventory'] .="<td><a href='?ctrl=Inventory&Act=Ver&id=$inv[id_inventory]'><i class='icon-view'></i></a>";
				$this->data['ListInventory'] .="<a href='?ctrl=Inventory&Act=Edit&id=$inv[id_inventory]'><i class='icon-edit'></i></a>";
				$this->data['ListInventory'] .="<a href='?ctrl=Inventory&Act=Delete&id=$inv[id_inventory]'><i class='icon-remove'></i></a> </td>";
				$this->data['ListInventory'] .='</tr>';
				$this->data{'ListInventory'} .= "</tr>";
			}
		}
		echo  getFile('header',$this->dataHeader) . getFile('Inventory/defaultInventory',$this->data) . getFile('footer',$this->dataFooter);
	}
}
?>