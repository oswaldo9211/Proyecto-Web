<?php
$Vctrl ='';
if(isset($_GET['Ctrl']) ){
			 switch($_GET['Ctrl']) {
				 case 'Vehiculo':
				 	require('Controllers/VehiculoCtrl.php');
				 	$Vctrl = new VehiculoCtrl();
				 break;
				 case 'Inv':
				 break;
				 case 'Ubicacion':
				 break;
				 default:
				 	//require('Views/default.htm');


			 }
			 $Vctrl->run();
}
			 	//require('Views/default.htm');
?>