<?php

/**
 * Oswaldo Martinez Fonseca
 * Marco Antonio
 *  since
 *
 *Este archivo recibe la peticion y decide que controlador
 *se debe ejecutar
 */


session_start();

//Recibe get
$ctrl = isset($_GET['ctrl']) ? $_GET['ctrl'] : '' ;

switch($ctrl){
	case 'controlador':
		require('Controllers/GenericoCtrl.php');
		$ctrl = new GenericoCtrl();
	break;
	case 'Vehiculo':
		 	require('Controllers/VehiculoCtrl.php');
		 	$ctrl = new VehiculoCtrl();
		 break;
	case 'usuario':
		require('Controllers/UserCtrl.php');
		$ctrl = new UserCtrl();
	break;
	case 'employee':
		require('Controllers/EmployeeCtrl.php');
		$ctrl = new EmployeeCtrl();
	break;
	case 'client':
		require('Controllers/ClientCtrl.php');
		$ctrl = new ClientCtrl();
	break;
	case 'inspeccion':
		require('Controllers/inspeccionCtrl.php');
		$ctrl = new InspeccionCtrl();
	break;
	default:
		require('Controllers/UserCtrl.php');
		$ctrl = new UserCtrl();
}

$ctrl->run();

?>
