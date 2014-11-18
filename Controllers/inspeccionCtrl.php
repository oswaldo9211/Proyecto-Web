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
		$data       = array();
		$Inspeccion = new InspeccionCtrl();
	 	
		
		echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inspeccion/defaultInspeccion',$data). $Inspeccion->getFile('footer',$dataFooter);
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
		$data       = array('' => '' );
		if(isset($_POST['Guardar'])){
			$cont= 1;
			$band=1;
			if(isset($_POST['pieza']) && isset($_POST['severidad']) && isset($_POST['servicio']) && isset($_POST['observaciones']))
			{
				echo '<br> Pieza ' , $_POST['pieza'], " severidad " ,$_POST['severidad'] , " servicio ", $_POST['servicio'], " observaciones ", $_POST['observaciones'];
				while ( $band ==1  && $cont < 10) {
				if(isset($_POST['pieza'.$cont.'']))
					echo '<br> Pieza ' ,$_POST['pieza'.$cont.''], " severidad " ,$_POST['severidad'] , " servicio ", $_POST['servicio'], " observaciones ", $_POST['observaciones'];
				else 
					$band=0;
				$cont ++;
			}
			}
		}
		$Inspeccion = new InspeccionCtrl();
		$Inspeccion->getPiezas($data);
		$Inspeccion->getServicios($data);
		echo  $Inspeccion->getFile('header',$dataHeader) . $Inspeccion->getFile('Inspeccion/Altainspeccion',$data). $Inspeccion->getFile('footer',$dataFooter);
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