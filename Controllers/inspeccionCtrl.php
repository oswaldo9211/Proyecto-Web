<?php


/**
* Marco 
*Ctrl de inspecciones, generar nueva inspección y concluirla
*/
require('Controllers/CtrlEstandar.php');

class InspeccionCtrl extends CtrlEstandar
{
	private $fTemplate;
	private $Inspe;
	public  $model;
	public $ok  = array("error" => '', "errno" => 0);
	function __construct()
	{
		require_once('include/funtios.php');
		require_once('Models/InspeccionMdl.php');
		$this->model = new  InspeccionMdl(); 
	}
	public function DefaultIns()
	{
		$dataHeader = array();
		$dataFooter = array();
		$dataHeader{'user'} = $_SESSION['username'];
		$data       = array('ListaInspeccion' => '');
		$Inspeccion = new InspeccionCtrl();
		$result= $this->model->getRow('Inspection', ' * ',  ' WHERE status="process" ',$this->ok);

		if($result!=  false)
		{

			while ( $Lista = $result->fetch_assoc()) {
				$data['ListaInspeccion'].= '<tr>';
				$data['ListaInspeccion'].= '<td>';
				$data['ListaInspeccion'].= $Lista{'date'} ;
				$data['ListaInspeccion'].= '</td>';
				$data['ListaInspeccion'].= '<td>';
				if($Lista{'status'} == 'process'){
					$data['ListaInspeccion'].= "Por procesar" ;
				}
				else
					$data['ListaInspeccion'].= $Lista{'status'} ;
				$data['ListaInspeccion'].= '</td>';
				$data['ListaInspeccion'].= '<td>';
				$rs= $this->model->getRow('Vehicle', "*","WHERE id_vehicle = $Lista[id_vehicle]",$this->ok);
				if($rs!= false && $rs->num_rows > 0){
					$Ver = $rs->fetch_assoc();
					//var_dump($Ver);
					$data['ListaInspeccion'].= $Ver{'vin'};
				}
				else	$data['ListaInspeccion'].= $Lista{'id_vehicle'};
				$data['ListaInspeccion'].= '</td>';
				$data['ListaInspeccion'] .="<td><a href='?ctrl=inspeccion&Act=Ver&idV=$Lista[id_inspection]'><i class='icon-view'></i></a>";
				$data['ListaInspeccion'] .="<a href='?ctrl=inspeccion&Act=Edit&idV=$Lista[id_inspection]'><i class='icon-edit'></i></a>";
				$data['ListaInspeccion'] .="<a href='?ctrl=inspeccion&Act=Delete&idV=$Lista[id_inspection]'><i class='icon-remove'></i></a> </td>";
				$data['ListaInspeccion'].= '</tr>';
				
			}
		}
		else
		{
			$data['ListaInspeccion'].= '<tr>';
			$data['ListaInspeccion'].= '<td>';
			$data['ListaInspeccion'].= '</td>';
			$data['ListaInspeccion'].= '</tr>';
		}
		
		echo  getFile('header',$dataHeader) . getFile('Inspeccion/defaultInspeccion',$data).  getFile('footer',$dataFooter);
	}

	public function getPiezas(&$data)
	{
		$data{'Piezas'} ='';
		$result =$this->model->getRow('Piece',' * ', ' ', $this->ok);
		if($result!= false && $result->num_rows > 0)
		{
			while( $value= $result->fetch_assoc()) {
				$data{'Piezas'} .= "<option  id='".$value{'id_piece'}."' value='".$value{'id_piece'}."'>".$value{'piece_name'}." </option>";
			}
		}
//we entrego el proyecto en la noche yo de todos modos  sabe que tenemos los comit de quien trabajo , doy la presentacion 
		//yo solo como el erik
		//ya lo que me diga de Modificacion te digo 
		//imprime pvalue
	}

	public function getServicios(&$data)
	{
		$data{'Servicios'} ='';
		$result =$this->model->getRow('Service',' * ', ' ', $this->ok);
		//var_dump($result);
		if($result!= false && $result->num_rows > 0)
		{

			while ( $value = $result->fetch_assoc()) {
				$data{'Servicios'} .= "<option  id='".$value{'id_service'}."' value='".$value{'id_service'}."'>".$value{'service_name'}." </option>";
			}
		}	
	}

	public function getClientes(&$data)
	{
		$data['clientes'] ='';
		$resultCs = $this->model->getRow('Client','*'," WHERE   1 ", $this->ok);
					if($resultCs!= false  && $resultCs->num_rows > 0)
						while ( $Clies = $resultCs->fetch_assoc()) {
								$data['clientes'] .= "<option  value='$Clies[id_client]'>$Clies[client_name] </option>";
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

	public function Alta()
	{
		$dataHeader = array('' => '' );
		$dataFooter = array('' => '' );
		$data       = array('error' => '' );
		$Inspeccion = new InspeccionCtrl();

		if(isset($_POST['Guardar'])   )
		{

			if( isset($_POST['servicio']) && $_POST['servicio'] != 0  && isset($_POST['pieza']) && $_POST['pieza'] != 0)
			{
				$cont= 1;
				$band=1;
				$campos = "id_inspection,date,status,id_vehicle,id_user";
				$hoy = getdate();
				$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
				if(isset($_POST['vehiculo']) &&  $_POST['vehiculo'] != 0)
				{
					$vehiculo= $_POST['vehiculo'];
					$rsV = $this->model->getRow('Inspection',"*", "WHERE id_vehicle=$_POST[vehiculo] AND status ='process' ",$this->ok );
					if($rsV!= false && $rsV->num_rows > 0){
						$band=0;
						$data['error'] = 'EL vehiculo ya esta en cola de espera en Inspeccion';
					}
					//slope
					$rsInv =$this->model->getRow('Inventory',"*", "WHERE id_vehicle=$_POST[vehiculo] AND status ='slope' ",$this->ok );
					if($rsInv!= false && $rsInv->num_rows > 0){
						$band=0;
						$data['error'] = 'El vehiculo se encuentra en reparación';
					}
				}
				else
				{
					$data['error'] = "Debe selccionar un vehiculo";
					$band=0;
				}
				if($band==1){
					$IdInspeccion =$this->model->getMaxid( 'id_inspection' ,'Inspection');
				//var_dump($IdInspeccion);
				$values = "$IdInspeccion,'$data[FechaEmision]','process',$vehiculo,$_SESSION[user]";
				if($IdInspeccion!= false)
					$result=$this->model->Inset('Inspection',$campos, $values,$this->ok);
				if($result != false )
					if(isset($_POST['pieza']) && isset($_POST['severidad']) && isset($_POST['servicio']) && isset($_POST['observaciones']))
					{
						$campos = "	id_inspection,id_piece,id_service,severity,observations";
						$values = " $IdInspeccion,$_POST[pieza] ,  $_POST[servicio] ,$_POST[severidad]  , '$_POST[observaciones]' ";
					    $this->model->Inset('InspectionDetails',$campos, $values,$this->ok);
						//echo '<br> Pieza ' , $_POST['pieza'], " severidad " ,$_POST['severidad'] , " servicio ", $_POST['servicio'], " observaciones ", $_POST['observaciones'];
						while ( $band ==1  && $cont < 10) {
							if(isset($_POST['pieza'.$cont.'']))
							{
								$values = "$IdInspeccion,".$_POST['pieza'.$cont.'']."  , ".$_POST['servicio'.$cont.'']." ,".$_POST['severidad'.$cont.'']."  , '".$_POST['observaciones'.$cont.'']."' ";
								$this->model->Inset('InspectionDetails',$campos, $values,$this->ok);
							}
							else 
								$band=0;
							$cont ++;
						}
						if($this->ok['errno'] == 0)
						header("Location: ?ctrl=inspeccion");
							//var_dump($this->ok );
					else
						$data['error'] .= "No se inserto la inspeccion por que SQL precento una inconsistencia  : SQL:". $this->ok['error']; 
					}
				}
				
			}
			else
				$data['error'] = "Debe de seleccionar una pieza y/o  un servicio";
			
		}
		
		$Inspeccion->getPiezas($data);
		$Inspeccion->getServicios($data);
		$Inspeccion->getClientes($data);
		$Inspeccion->getVehiculos($data);
		echo  getFile('header',$dataHeader) . getFile('Inspeccion/Altainspeccion',$data). getFile('footer',$dataFooter);
	}

	public function getInspeccionModificar(&$data,$id)
	{

			$data['VerInspeccion'] = '';
			$data['VerInspeccionDetalle'] = '';
			$data['id']=$id;
			$result = $this->model->getRow('Inspection', '*', " WHERE id_inspection = $id ", $this->ok);
			
			if($result != false && $result->num_rows > 0){
				while ($value= $result->fetch_assoc()) {
					$data['VerInspeccion'] .= '<tr>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= $value{'id_inspection'};
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<input  type='text' name='fecha' value='$value[date]' readonly=''/> ";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$rsU = $this->model->getRow('User', '*',"WHERE id_user =$value[id_user]",$this->ok);
					if($rsU != false && $rsU->num_rows > 0) {
						$User = $rsU->fetch_assoc();

					$data['VerInspeccion'] .= "<input type='text' name='usr_id'  value='$User[user_name]' readonly=''/>";}
					else
						$data['VerInspeccion'] .= "<input type='text' name='usr_id'  value='Baja de usuario' readonly=''/>";

					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$resultV = $this->model->getRow('Vehicle', '*', " WHERE id_vehicle = $value[id_vehicle] ", $this->ok);
					if($resultV != false  && $resultV->num_rows > 0){
						 $vehiculo = $resultV->fetch_assoc();
						$data['VerInspeccion'] .= "<input  type='text' name='idvehiculo' value='$vehiculo[vin]' readonly=''/> ";
					}
					else		
						$data['VerInspeccion'] .= "<input  type='text' name='idvehiculo' value='Vehículo no seleccionado' readonly=''/> ";

					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					//$data['VerInspeccion'] .= "<input type='text' name='estatus' value='$value[estatus]'/>";
					$data['VerInspeccion'] .= "<select id='estatus'  name='estatus'>";
					if($value{'status'}== 'process'){
						$status= "Por procesar" ;
					}
					else
						$status= "No tine estatus";
					$data['VerInspeccion'] .="<option selected value='$value[status]'>$status</option>
													<option value='CANCELAR'>Cancelar </option >
													<option value='CONCLUIDO'>Concluir</option >
											  </select>";
					$data['VerInspeccion'] .= '</td>';
					
					//$data['VerInspeccion'] .= '<td>';
				    //$data['VerInspeccion'] .= "<button name='procesar'>PROCESAR</buuton>";
				    //$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '</tr>';
				}
				$data['VerInspeccion'] .= '<tr><th  align="center" colspan ="6">Detalles</th></tr>';
				$result = $this->model->getRow('InspectionDetails', '*', " WHERE id_inspection = $id ", $this->ok);
				//var_dump($result);
				$cont=1;//indice que maneja el numero de invd
				if($result != false && $result->num_rows > 0){
					while ( $value = $result->fetch_assoc()) {
						//var_dump($value);
						$data['VerInspeccionDetalle'] .= '<tr>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[idpieza]' />";
						if($value['id_piece'] != 0)
							$resultP = $this->model->getRow('Piece', '*', " WHERE id_piece = $value[id_piece]  ", $this->ok);
							if($resultP != false  && $resultP->num_rows > 0){
								while ($Pieza = $resultP->fetch_assoc()) {
									$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$Pieza[piece_name]' readonly=''/>";
								}
							}
						else{
							$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[id_piece]' readonly=''/>";
						}

						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='severidad' value='$value[severity] ' readonly=''/>";;
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idservicio' value='$value[idservicio] ' />";;
						//var_dump($value['id_service']);
						if($value['id_service'] != 0)
						{
							$resultS = $this->model->getRow('Service', '*', " WHERE id_service = $value[id_service]  ", $this->ok);
							if($resultS != false  && $resultS->num_rows > 0){
								while ( $Ser = $resultS->fetch_assoc()) {	
									//var_dump($Ser);
									$data['VerInspeccionDetalle'] .= "<input type='text' name='id_service' value='$Ser[service_name]' readonly=''/>";
								}
							}
							else{
								$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[id_service]' readonly='' />";
							}
						}
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='observaciones' value='$value[observations] ' readonly='' />";
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '</tr>';
						}
				}
			}
	}
	public function getInspeccion(&$data,$id)
	{
		$data['VerInspeccion'] = '';
		$data['VerInspeccionDetalle'] = '';
		
		$result = $this->model->getRow('Inspection', '*', " WHERE id_inspection = $id ", $this->ok);
		if($result != false && $result->num_rows > 0){
			while (  $value = $result->fetch_assoc()) {
				//var_dump($value);
				$data['VerInspeccion'] .= '<tr>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'id_inspection'};
				$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'date'};
				$data['VerInspeccion'] .= '</td>';
				$resultC  = $this->model->getRow('User', ' *', " WHERE id_user = $value[id_user]", $thi->ok);
				if($resultC != false && $resultC->num_rows > 0)
				{
					$UserNombre = $resultC->fetch_assoc();
					//var_dump($ClienteNombre{'client_name'});
					$data['VerInspeccion'] .=  "<td>" .$UserNombre{'user_name'} . "</td>";
				}
				//$data['VerInspeccion'] .= $value{'id_user'};
				$resultV  = $this->model->getRow('Vehicle', ' *', " WHERE id_vehicle = $value[id_vehicle]", $this->ok);
				if($resultV != false && $resultV->num_rows > 0)
				{
					$Vin = $resultV->fetch_assoc();
					//var_dump($Vin);
					if($Vin['vin']==NULL)
						$data['VerInspeccion'] .=  "<td> No se selcciono </td>";
					else
						$data['VerInspeccion'] .=  "<td>" .$Vin{'vin'} . "</td>";
				}
				$data['VerInspeccion'] .= '<td>';
				if($value{'status'}== 'process'){
					$data['VerInspeccion'].= "Por procesar" ;
				}
				else
					$data['VerInspeccion'] .= $value{'status'};
				$data['VerInspeccion'] .= '</td>';
				//$data['VerInspeccion'] .= '<td>';
			    //$data['VerInspeccion'] .= "<button name='procesar'>PROCESAR</buuton>";
			    //$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '</tr>';
			}
			$data['VerInspeccion'] .= '<tr><th  align="center" colspan ="6">Detalles</th></tr>';
			$result = $this->model->getRow('InspectionDetails', '*', " WHERE id_inspection = $id ", $this->ok);
			//var_dump($result);
			if($result != false && $result->num_rows > 0){
			while ( $value  = $result->fetch_assoc()) {
				//var_dump($value);
				$data['VerInspeccionDetalle'] .= '<tr>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$resultP = $this->model->getRow('Piece', '*', " WHERE id_piece = $value[id_piece] ", $this->ok);
				//var_dump($result);
				if($resultP != false && $resultP->num_rows > 0){
				 $Pieza=$resultP->fetch_assoc();

				$data['VerInspeccionDetalle'] .= $Pieza{'piece_name'};}
				else
					$data['VerInspeccionDetalle'] .= "Pieza no definida";
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'severity'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$resultS = $this->model->getRow('Service', '*', " WHERE id_service = $value[id_service]; ", $this->ok);
				//var_dump($result);
				if($resultS != false && $resultS->num_rows > 0){
				 	$Servicio=$resultS->fetch_assoc();
					$data['VerInspeccionDetalle'] .= $Servicio{'service_name'};
				}
				else
					$data['VerInspeccionDetalle'] .= "Servicio no definido";
				//$data['VerInspeccionDetalle'] .= $value{'id_service'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'observations'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '</tr>';
			}
		}
	}
}
	public function Ver()
	{
		$dataHeader = array('' => '' );
		$dataFooter = array('' => '' );
		$data       = array('' => '' );
		$dataHeader{'user'} = $_SESSION['username'];
		if (isset($_GET['idV']) && $_GET['idV'] != 0) {
			$Inspeccion = new InspeccionCtrl();
			$Inspeccion->getInspeccion($data,$_GET['idV']);
			echo getFile('header',$dataHeader) . getFile('Inspeccion/Verinspeccion',$data). getFile('footer',$dataFooter);
		}
	}
	public function Modificacion()
	{
		$dataHeader = array('' => '' );
		$dataFooter = array('' => '' );
		$dataHeader{'user'} = $_SESSION['username'];
		$data       = array('area' => '', 'error'  => '');
		$result = $this->model->getRow('Location' , '*', ' ', $this->ok);
		if($result !=false  && $result->num_rows > 0)
			while ($Area  = $result->fetch_assoc()) {
				$data['area'] .=  "<option value='$Area[id_location]'> $Area[location_name] </option >";

			}
			//var_dump($_POST['estatus']);
		if(isset($_POST['procesar']) && isset($_POST['estatus']) && $_POST['estatus'] != 'process' )
		{
			$band=0;
			if(!isset($_POST['idvehiculo']) )
			{
				$data['error'] .= "Necesita escojer un vehiculo para procesar la solicitud";
				$band=1;
			}
			if( !isset($_POST['area'])  )
			{
				$data['error'] .= "No se selecciono ninguna área";
				$band=1;
			}


			if($band ==0  && isset($_GET['idV']) && $_GET['idV'] != 0  && ($_POST['estatus'] == 'CONCLUIDO'  || $_POST['estatus'] == 'affected') ){
				$estado = $this->model->UpdateEstado($_GET['idV'],'affected',$this->ok);
				if($estado != false)
				{
						//echo "here";
						$campos = 'movement,inv_date,service,`service_ destination`,observations,status,id_vehicle,id_user';
						$hoy = getdate();
						$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
						$data['hora'] = $hoy['hours']. ':' . $hoy['minutes']. ':'. $hoy['seconds'];
						$fecha =$data['FechaEmision'] . " " . $data['hora'];
						$rsultV = $this->model->getRow('Vehicle','*',"WHERE vin LIKE '%$_POST[idvehiculo]%'",$this->ok);
						if($rsultV != false && $rsultV->num_rows > 0)
						{
							//echo "here";
							$Vehiculo = $rsultV->fetch_assoc();
							$values = "'in','$data[FechaEmision]','Inspeccion','$_POST[area]','Entrada de inspeccion','slope',$Vehiculo[id_vehicle],$_SESSION[user]";
							$resultInv=$this->model->Inset('Inventory',$campos, $values,$this->ok);
							if($resultInv != false) header("Location: ?ctrl=inspeccion&M");
							else
								$data['error'] = $this->ok['error'];
						}
					
				}
			}
			elseif ( isset($_GET['idV']) && $_GET['idV'] != 0  && $_POST['estatus'] == 'CANCELAR') {
				$estado = $this->model->UpdateEstado($_GET['idV'],'cancel',$this->ok);
				$usrCancelar = $this->model->UpdateUsrCancelar($_GET['idV'],1, $this->ok );
				if($estado != false  && $usrCancelar!= false)
				{
					 header("Location: ?ctrl=inspeccion");
				}
				else
				{
					$data['error'] = $this->ok['error'];
					//var_dump($data['error']);
				}
			}
		}

		if (isset($_GET['idV']) && $_GET['idV'] != 0) {
			$Inspeccion = new InspeccionCtrl();
			$Inspeccion->getInspeccionModificar($data,$_GET['idV']);
			echo  getFile('header',$dataHeader) . getFile('Inspeccion/Modinspeccion',$data). getFile('footer',$dataFooter);
		}
	}

	public  function Baja(){
		if(isset($_GET['idV']))
		{
			if($_GET['idV'] != ''){
				$idIns = $_GET['idV'];
			}
			$result=$this->model->UpdateEstado($idIns,'cancel',$this->ok);
			if ($result!= false) {
				$result=$this->model->UpdateEstado($idIns,'cancel',$this->ok);
				if($result != false)
				header('Location: index.php?ctrl=inspeccion');
			}
		}
	
}
	public function run()
	{
		$this->Inspe  = new  InspeccionCtrl();
		//$Inspe->init();
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
			 $this->Inspe->Alta();
			 	break;
			 case 'Edit':
			 if($this->isAdmin())
			 	$this->Inspe->Modificacion();
				 break;
			 case 'Consulta':
			 if($this->isAdmin())
			 	$this->Inspe->Consulta();
			 	break;
			 case 'Delete':
			 if($this->isAdmin())
			 	$this->Inspe->Baja();
			 	break;
			 case 'Ver':
			  if($this->isUser())
			 	$this->Inspe->Ver();
			 	break;
				 break;
			 default:
			  if($this->isUser())
			 	$this->Inspe->DefaultIns();
			 	break;
			 }
		
	}

}
?>