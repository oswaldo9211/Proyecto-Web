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
	case 'login':
		require('Controllers/loginCtrl.php');
		$ctrl = new LoginCtrl();
	break;
	case 'usuario':
		require('Controllers/UsuarioCtrl.php');
		$ctrl = new UsuarioCtrl();
	break;
	case 'employee':
		require('Controllers/EmployeeCtrl.php');
		$ctrl = new EmployeeCtrl();
	break;
	case 'show':
		require('Views/changePass.html');
	break;
	default:
		require('Controllers/loginCtrl.php');
		$ctrl = new LoginCtrl();
}

$ctrl->run();

?>
