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
	public $ok  = array();
	function __construct()
	{
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
	public function getFile( $nameFile, $vars)
	{

		//echo ": ",$nameFile, ': ',var_dump($vars);
		require_once('include/Template.php');
		$FileTemplate = new Template();
		$FileTemplate->setTemplate($nameFile);
		if(count($vars) > 0)
			$FileTemplate->setvars($vars);
		return $FileTemplate->show();
	}
	public function DefaultIns()
	{
		$dataHeader = array();
		$dataFooter = array();
		$data       = array('ListaInspeccion' => '');
		$Inspeccion = new InspeccionCtrl();
		$result= $this->model->getRow('Inspeccion', ' * ',  ' WHERE estatus="ENPROCESO" ',$this->ok);

		if($result!=  false)
		{

		
		}
		else
		{
			$data['ListaInspeccion'].= '<tr>';
			$data['ListaInspeccion'].= '<td>';
			$data['ListaInspeccion'].= '</td>';
			$data['ListaInspeccion'].= '</tr>';
		}
		
		echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inv/defaultInv',$data). $Inspeccion->getFile('footer',$dataFooter);
	}

	public function getPiezas(&$data)
	{
		$data{'Piezas'} ='';
		$result =$this->model->getRow('Pieza',' * ', ' ', $this->ok);
		if($result!= false && $result->num_rows > 0)
		{
			foreach ($result as $key => $value) {
				$data{'Piezas'} .= "<option  id='".$value{'idpieza'}."' value='".$value{'idpieza'}."'>".$value{'nombre'}." </option>";
			}
		}

	}

	public function getServicios(&$data)
	{
		$data{'Servicios'} ='';
		$result =$this->model->getRow('Servicio',' * ', ' ', $this->ok);
		if($result!= false && $result->num_rows > 0)
		{

			foreach ($result as $key => $value) {
				$data{'Servicios'} .= "<option  id='".$value{'idservicio'}."' value='".$value{'idservicio'}."'>".$value{'nombre'}." </option>";
			}
		}	
	}
	public function Alta()
	{
		$dataHeader = array('' => '' );
		$dataFooter = array('' => '' );
		$data       = array('error' => '' );
		if(isset($_POST['Guardar'])   ){
			if( isset($_POST['servicio']) && $_POST['servicio'] != 0  && isset($_POST['pieza']) && $_POST['pieza'] != 0)
			{
				$cont= 1;
				$band=1;
				$campos = 'idinspeccion,fecha,usr_id,usr_idcancelacion,idvehiculo,estatus';
				$hoy = getdate();
				$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
				if(isset($_POST['vehiculo']) &&  $_POST['vehiculo'] != 0)
					$vehiculo= $_POST['vehiculo'];
				else
					$vehiculo= 0;

				$IdInspeccion =$this->model->getMaxid( 'idinspeccion' ,'Inspeccion');
				//var_dump($IdInspeccion);
				$values = "$IdInspeccion,'$data[FechaEmision]',25,0,$vehiculo,'ENPROCESO'";
				if($IdInspeccion!= false)
					$result=$this->model->Inset('Inspeccion',$campos, $values);
				if($result != false )
					if(isset($_POST['pieza']) && isset($_POST['severidad']) && isset($_POST['servicio']) && isset($_POST['observaciones']))
					{
						$campos = "	idinspeccion,idpieza,severidad,idservicio,observaciones";
						$values = "$IdInspeccion,$_POST[pieza] , $_POST[severidad] , $_POST[servicio] , '$_POST[observaciones]' ";
					    $this->model->Inset('Inspeccionn',$campos, $values);
						//echo '<br> Pieza ' , $_POST['pieza'], " severidad " ,$_POST['severidad'] , " servicio ", $_POST['servicio'], " observaciones ", $_POST['observaciones'];
						while ( $band ==1  && $cont < 10) {
							if(isset($_POST['pieza'.$cont.'']))
							{
								$values = "$IdInspeccion,".$_POST['pieza'.$cont.'']." ,".$_POST['severidad'.$cont.'']." , ".$_POST['servicio'.$cont.'']." , '".$_POST['observaciones'.$cont.'']."' ";
								$this->model->Inset('Inspeccionn',$campos, $values);
								//echo '<br> Pieza ' ,$_POST['pieza'.$cont.''], " severidad " ,$_POST['severidad'.$cont.''] , " servicio ", $_POST['servicio'.$cont.''], " observaciones ", $_POST['observaciones'.$cont.''];
							}
							else 
								$band=0;
							$cont ++;
						}
						header("Location: ?ctrl=inspeccion");
					}
			}
			else
				$data['error'] = "Debe de seleccionar una pieza y/o  un servicio";
			
		}
		
		$Inspeccion = new InspeccionCtrl();
		$Inspeccion->getPiezas($data);
		$Inspeccion->getServicios($data);
		echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inspeccion/Altainspeccion',$data). $Inspeccion->getFile('footer',$dataFooter);
	}

	public function getInspeccionModificar(&$data,$id)
	{
			$data['VerInspeccion'] = '';
			$data['VerInspeccionDetalle'] = '';
			$data['id']=$id;
			$result = $this->model->getRow('Inspeccion', '*', " WHERE idinspeccion = $id ", $this->ok);
			
			if($result != false && $result->num_rows > 0){
				foreach ($result as $key => $value) {
					//var_dump($value);
					$data['VerInspeccion'] .= '<tr>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= $value{'idinspeccion'};
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<input  type='text' name='fecha' value='$value[fecha]' eadonly=''/> ";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<input type='text' name='usr_id'  value='$value[usr_id]'readonly=''/>";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					$data['VerInspeccion'] .= "<select id='idvehiculo'  name='idvehiculo'>
													<option selected value='$value[idvehiculo]'>$value[idvehiculo]</option>";
					$resultV = $this->model->getRow('vehiculo', '*', "  ", $this->ok);
					if($resultV != false  && $resultV->num_rows > 0)
						foreach ($resultV as $key => $vehiculo) {
							$data['VerInspeccion'] .= "<option value='$vehiculo[vehiculo_id]'>$vehiculo[vin] </option >";
						}						
					$data['VerInspeccion']	.=  "</select>";
					$data['VerInspeccion'] .= '</td>';
					$data['VerInspeccion'] .= '<td>';
					//$data['VerInspeccion'] .= "<input type='text' name='estatus' value='$value[estatus]'/>";
					$data['VerInspeccion'] .= "<select id='estatus'  name='estatus'>
													<option selected value='$value[estatus]'>$value[estatus]</option>
													<option value='ENPROCESO'>ENPROCESO </option >
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
				$result = $this->model->getRow('Inspeccionn', '*', " WHERE idinspeccion = $id ", $this->ok);
				//var_dump($result);
				$cont=1;//indice que maneja el numero de invd
				if($result != false && $result->num_rows > 0){
					foreach ($result as $key => $value) {
						//var_dump($value);
						$data['VerInspeccionDetalle'] .= '<tr>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[idpieza]' />";
						if($value['idpieza'] != 0)
							$resultP = $this->model->getRow('Pieza', '*', " WHERE idpieza = $value[idpieza]  ", $this->ok);
							if($resultP != false  && $resultP->num_rows > 0){
								foreach ($resultP as $key => $Pieza) {
									$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$Pieza[nombre]' readonly=''/>";
								}
							}
						else{
							$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[idpieza]' />";
						}

						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='severidad' value='$value[severidad] ' />";;
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						//$data['VerInspeccionDetalle'] .= "<input type='text' name='idservicio' value='$value[idservicio] ' />";;
						if($value['idservicio'] != 0)
							$resultS = $this->model->getRow('Servicio', '*', " WHERE idservicio = $value[idservicio]  ", $this->ok);
							if($resultS != false  && $resultS->num_rows > 0){
								foreach ($resultS as $key => $Ser) {	
									$data['VerInspeccionDetalle'] .= "<input type='text' name='idservicio' value='$Ser[nombre]' readonly=''/>";
								}
							}
						else{
							$data['VerInspeccionDetalle'] .= "<input type='text' name='idpieza' value='$value[idpieza]' />";
						}
						$data['VerInspeccionDetalle'] .= '</td>';
						$data['VerInspeccionDetalle'] .= '<td>';
						$data['VerInspeccionDetalle'] .= "<input type='text' name='observaciones' value='$value[observaciones] ' />";;
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
		
		$result = $this->model->getRow('Inspeccion', '*', " WHERE idinspeccion = $id ", $this->ok);
		if($result != false && $result->num_rows > 0){
			foreach ($result as $key => $value) {
				//var_dump($value);
				$data['VerInspeccion'] .= '<tr>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'idinspeccion'};
				$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'fecha'};
				$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'usr_id'};
				$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'idvehiculo'};
				$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '<td>';
				$data['VerInspeccion'] .= $value{'estatus'};
				$data['VerInspeccion'] .= '</td>';
				//$data['VerInspeccion'] .= '<td>';
			    //$data['VerInspeccion'] .= "<button name='procesar'>PROCESAR</buuton>";
			    //$data['VerInspeccion'] .= '</td>';
				$data['VerInspeccion'] .= '</tr>';
			}
			$data['VerInspeccion'] .= '<tr><th  align="center" colspan ="6">Detalles</th></tr>';
			$result = $this->model->getRow('Inspeccionn', '*', " WHERE idinspeccion = $id ", $this->ok);
			//var_dump($result);
			if($result != false && $result->num_rows > 0){
			foreach ($result as $key => $value) {
				//var_dump($value);
				$data['VerInspeccionDetalle'] .= '<tr>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'idpieza'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'severidad'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'idservicio'};
				$data['VerInspeccionDetalle'] .= '</td>';
				$data['VerInspeccionDetalle'] .= '<td>';
				$data['VerInspeccionDetalle'] .= $value{'observaciones'};
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
			echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inspeccion/Verinspeccion',$data). $Inspeccion->getFile('footer',$dataFooter);
		}
	}
	public function Modificacion()
	{
		$dataHeader = array('' => '' );
		$dataFooter = array('' => '' );
		$data       = array('area' => '', 'error'  => '');
		$result = $this->model->getRow('Area' , '*', ' ', $this->ok);
		if($result !=false  && $result->num_rows > 0)
			foreach ($result as $key => $Area) {
				$data['area'] .=  "<option value='$Area[idarea]'> $Area[nombre] </option >";

			}
		if(isset($_POST['procesar']) && isset($_POST['estatus']) && $_POST['estatus'] != 'ENPROCESO' )
		{
			$band=0;
			if(isset($_POST['idvehiculo'])  && $_POST['idvehiculo']!= 0)
				var_dump($_POST['idvehiculo']);
			else
			{
				$data['error'] .= "Necesita escojer un vehiculo para procesar la solicitud";
				$band=1;
			}
			if( !isset($_POST['area'])  )
			{
				$data['error'] .= "No se selecciono ninguna área";
				$band=1;
			}

			if($band ==0  && isset($_GET['idV']) && $_GET['idV'] != 0  && $_POST['estatus'] == 'CONCLUIDO'){
				$estado = $this->model->UpdateEstado($_GET['idV'],'AFECTADO',$this->ok);
				if($estado != false)
				{
					$upvehiculo = $this->model->UpdateVehiculo($_GET['idV'],$_POST['idvehiculo'],$this->ok);
					if($upvehiculo != false){
						$campos = 'mov,fechaemision,area,areaDestino,observaciones,estatus,hora,usr_id,idvehiculo';
						$hoy = getdate();
						$data['FechaEmision'] = $hoy['year'] . '-' .$hoy['mon'] . '-' . $hoy['mday'];
						$data['hora'] = $hoy['hours']. ':' . $hoy['minutes']. ':'. $hoy['seconds'];
						$values = "'Entrada','$data[FechaEmision]','inspeccion','$_POST[area]','Entrada de inspeccion','PENDIENTE','$data[hora]',25, $_POST[idvehiculo]";
						$resultInv=$this->model->Inset('inv',$campos, $values);
						if($resultInv != false) header("Location: ?ctrl=inspeccion");
					}
				}
			}
			elseif ( isset($_GET['idV']) && $_GET['idV'] != 0  && $_POST['estatus'] == 'CANCELAR') {
				$estado = $this->model->UpdateEstado($_GET['idV'],'CANCELADO',$this->ok);
				$usrCancelar = $this->model->UpdateUsrCancelar($_GET['idV'],25, $this->ok );
				if($estado != false  && $usrCancelar!= false)
				{
					 header("Location: ?ctrl=inspeccion");
				}
			}
		}
		if (isset($_GET['idV']) && $_GET['idV'] != 0) {
			$Inspeccion = new InspeccionCtrl();
			$Inspeccion->getInspeccionModificar($data,$_GET['idV']);
			echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inspeccion/Modinspeccion',$data). $Inspeccion->getFile('footer',$dataFooter);
		}
	}

	public  function Baja(){
		if(isset($_GET['idV']))
		{
			if($_GET['idV'] != ''){
				$idIns = $_GET['idV'];
			}
			$result=$this->model->del_Rows('Inspeccion', ' WHERE  idinspeccion = '.$idIns.'');
			if ($result!= false) {
				$result=$this->model->del_Rows('Inspeccionn', ' WHERE  idinspeccion = '.$idIns.'');
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