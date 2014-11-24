<?php


/**
* Marco 
*Ctrl de inspecciones, generar nueva inspección y concluirla
*/
class InspeccionCtrl
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



	public function init()
	{
		
	}
	public function showIns()
	{
		# code...
	}
	public function DefaultIns()
	{
		$dataHeader = array();
		$dataFooter = array();
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
				$data['ListaInspeccion'].= $Lista{'status'} ;
				$data['ListaInspeccion'].= '</td>';
				$data['ListaInspeccion'].= '<td>';
				$data['ListaInspeccion'].= $Lista{'id_vehicle'};
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
			foreach ($result as $key => $value) {
				$data{'Piezas'} .= "<option  id='".$value{'id_piece'}."' value='".$value{'id_piece'}."'>".$value{'piece_name'}." </option>";
			}
		}

	}

	public function getServicios(&$data)
	{
		$data{'Servicios'} ='';
		$result =$this->model->getRow('Service',' * ', ' ', $this->ok);
		//var_dump($result);
		if($result!= false && $result->num_rows > 0)
		{

			foreach ($result as $key => $value) {
				$data{'Servicios'} .= "<option  id='".$value{'id_service'}."' value='".$value{'id_service'}."'>".$value{'service_name'}." </option>";
			}
		}	
	}

	public function getClientes(&$data)
	{
		$data['clientes'] ='';
		$resultCs = $this->model->getRow('Client','*'," WHERE   1 ", $this->ok);
					if($resultCs!= false  && $resultCs->num_rows > 0)
						foreach ($resultCs as $key => $Clies) {
								$data['clientes'] .= "<option  value='$Clies[id_client]'>$Clies[client_name] </option>";
						}
	}

	public function getVehiculos(&$data)
	{
		$data['vehiculos'] ='';
		$resultCs = $this->model->getRow('Vehicle','*'," WHERE   1 ", $this->ok);
					if($resultCs!= false  && $resultCs->num_rows > 0)
						foreach ($resultCs as $key => $Vehi) {
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
					$vehiculo= $_POST['vehiculo'];
				else
					$vehiculo= 10;

				$IdInspeccion =$this->model->getMaxid( 'id_inspection' ,'Inspection');
				//var_dump($IdInspeccion);
				$values = "$IdInspeccion,'$data[FechaEmision]','process',$vehiculo,1";
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
				foreach ($result as $key => $value) {
					$data['VerInspeccion'] .= '<tr>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= $value{'id_inspection'};
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<input  type='text' name='fecha' value='$value[date]' eadonly=''/> ";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<input type='text' name='usr_id'  value='$value[id_user]'readonly=''/>";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<select id='idvehiculo'  name='idvehiculo'>
													<option selected value='$value[id_vehicle]'>$value[id_vehicle]</option>";
					$resultV = $this->model->getRow('Vehicle', '*', "  ", $this->ok);
					if($resultV != false  && $resultV->num_rows > 0)
						foreach ($resultV as $key => $vehiculo) {
							$data['VerInspeccion'] .= "<option value='$vehiculo[id_vehicle]'>$vehiculo[vin] </option >";
						}						
					$data['VerInspeccion']	.=  "</select>";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					//$data['VerInspeccion'] .= "<input type='text' name='estatus' value='$value[estatus]'/>";
					$data['VerInspeccion'] .= "<select id='estatus'  name='estatus'>
													<option selected value='$value[status]'>$value[status]</option>
													<option value='CANCELAR'>CANCELAR </option >
													<option value='CONCLUIDO'>CONCLUIDO </option >
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
					foreach ($result as $key => $value) {
						//var_dump($value);
						$data['VerInspeccionDetalle'] .= '<tr>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[idpieza]' />";
						if($value['id_piece'] != 0)
							$resultP = $this->model->getRow('Piece', '*', " WHERE id_piece = $value[id_piece]  ", $this->ok);
							if($resultP != false  && $resultP->num_rows > 0){
								foreach ($resultP as $key => $Pieza) {
									$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$Pieza[piece_name]' readonly=''/>";
								}
							}
						else{
							$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[id_piece]' />";
						}

						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='severidad' value='$value[severity] ' />";;
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idservicio' value='$value[idservicio] ' />";;
						if($value['id_service'] != 0)
							$resultS = $this->model->getRow('Service', '*', " WHERE idservicio = $value[id_service]  ", $this->ok);
							if($resultS != false  && $resultS->num_rows > 0){
								foreach ($resultS as $key => $Ser) {	
									$data['VerInspeccionDetalle'] .= "<input type='text' name='idservicio' value='$Ser[name]' readonly=''/>";
								}
							}
						else{
							$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[id_piece]' />";
						}
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='observaciones' value='$value[observations] ' />";;
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
			foreach ($result as $key => $value) {
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
					$Vin = $resultC->fetch_assoc();
					if($Vin['vin']==NULL)
						$data['VerInspeccion'] .=  "<td> No se selcciono </td>";
					else
						$data['VerInspeccion'] .=  "<td>" .$Vin{'vin'} . "</td>";
				}
				$data['VerInspeccion'] .= '<td>';
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
			foreach ($result as $key => $value) {
				//var_dump($value);
				$data['VerInspeccionDetalle'] .= '<tr>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'id_piece'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'severity'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'id_service'};
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
		$data       = array('area' => '', 'error'  => '');
		$result = $this->model->getRow('Location' , '*', ' ', $this->ok);
		if($result !=false  && $result->num_rows > 0)
			foreach ($result as $key => $Area) {
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
					$upvehiculo = $this->model->UpdateVehiculo($_GET['idV'],$_POST['idvehiculo'],$this->ok);
					if($upvehiculo != false){
						$campos = 'movement,inv_date,service,`service_ destination`,observations,status,id_vehicle,id_user';
						$hoy = getdate();
						$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
						$data['hora'] = $hoy['hours']. ':' . $hoy['minutes']. ':'. $hoy['seconds'];
						$fecha =$data['FechaEmision'] . " " . $data['hora'];
						$values = "'in','$data[FechaEmision]','Inspeccion','$_POST[area]','Entrada de inspeccion','slope',$_POST[idvehiculo],1";
						$resultInv=$this->model->Inset('Inventory',$campos, $values,$this->ok);
						if($resultInv != false) header("Location: ?ctrl=inspeccion");
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
			 $this->Inspe->Alta();
			 	break;
			 case 'Edit':
			 	$this->Inspe->Modificacion();
				 break;
			 case 'Consulta':
			 	$this->Inspe->Consulta();
			 	break;
			 case 'Delete':
			 	$this->Inspe->Baja();
			 	break;
			 case 'Ver':
			 	$this->Inspe->Ver();
			 	break;
				 break;
			 default:
			 	$this->Inspe->DefaultIns();
			 	break;
			 }
		
	}

}
?>