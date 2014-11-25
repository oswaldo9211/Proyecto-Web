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
	case 'Inv':
		require('Controllers/InvCtrl.php');
		$ctrl = new InspeccionCtrl();
	break;
	case 'piece':
		require('Controllers/PieceCtrl.php');
		$ctrl = new PieceCtrl();
	break;
	case 'location':
		require('Controllers/LocationCtrl.php');
		$ctrl = new LocationCtrl();
	break;
	case 'service':
		require('Controllers/ServiceCtrl.php');
		$ctrl = new ServiceCtrl();
	break;
	case 'Inventory':
		require('Controllers/Inventory.php');
		$ctrl = new InventoryCtrl();
	break;
	case 'brand':
		require('Controllers/BrandCtrl.php');
		$ctrl = new BrandCtrl();
	break;
	case 'model':
		require('Controllers/ModelCtrl.php');
		$ctrl = new ModelCtrl();
	break;
	default:
		require('Controllers/UserCtrl.php');
		$ctrl = new UserCtrl();
}

$ctrl->run();

?>
