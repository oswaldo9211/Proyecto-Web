<?php

if(isset($_GET['Ctrl']) )
			 switch($_GET['Ctrl']) {
				 case 'Vehiculo':
				 	require('Controllers/VehiculoCtrl.php');
				 	$Vctrl = new VehiculoCtrl();
					$Vctrl->run();
				 break;
				 case 'Inv':
				 break;
				 case 'Ubicacion':
				 break;
				 default:
				 	//require('Views/default.htm');


			 }
			 else
			 	//require('Views/default.htm');

?>