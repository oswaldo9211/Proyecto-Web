<?php
/**
*functions to validate fields
**/


function ValidarVIN($VIN=''){
	if($VIN != "")
		{
			if(preg_match("/^(^(\d+)$)/i", $VIN))
			{
				return true;
			}
			else
			{
				echo " Sintaxis error  VIN incorrecto  favor de seguir el formato solicitado (solo digitos)";
				return false;
			}
		}
		else
			echo "</br> el VIN es un campo obligatorio";
		return false;
}

function ValidarModelo($modelo)
{
	if($modelo != "")
		{
			if(preg_match("/^(^(([a-zA-Z]+))$)/i", $modelo))
			{
				return true;
			}
			else
			{
				echo " Sintaxis error modelo incorrecto  favor de seguir el formato solicitado (solo letras sin espacios)";
				return false;
			}
			return true;
		}
		else
			echo "</br> el modelo es un campo obligatorio";
		return false;
}

function ValidarMarca($marca)
{
	if($marca != "")
		{
			if(preg_match("/^(^(([a-zA-Z]+)(((-{1})([a-zA-Z]+)))|([a-zA-Z]*))$)/i", $marca))
			{
				return true;
			}
			else
			{
				echo " Sintaxis error marca incorrecto  favor de seguir el formato solicitado (letra * o letra* - letra*)";
				return false;
			}
			return true;
		}
		else
			echo "</br> la marca es un campo obligatorio";
		return false;
}



function ValidarColor($color)
{
	if($color != "")
		{
			if(preg_match("/^(^(([a-zA-Z]+))$)/i", $color))
			{
				return true;
			}
			else
			{
				echo " Sintaxis error color incorrecto  favor de seguir el formato solicitado (solo letras sin espacios)";
				return false;
			}
			return true;
		}
		else
			echo "</br> el modelo es un campo obligatorio";
		return false;
}


function validateName($name)
{
	if($name != ""){
		if(preg_match("/^([a-zA-ZáéóíúñÑ\s]+$)/i", $name)){
			return $name;
		}
		else{
			echo "</br> Name incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validateEmail($email)
{
	if($email != ""){
		if(preg_match("/^([a-zA-Z0-9._%+-]+[@][a-zA-Z]+\.com[a-zA-Z.]*$)/i", $email))
		{
			return $email;
		}
		else
		{
			echo "</br> Email incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validateText($texto)
{
	if($texto != ""){
		if(preg_match("/^([a-zA-ZáéóíúñÑ\s.]+$)/i", $texto)){
			return $texto;
		}
		else{
			echo "</br> Texto incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validatePhone($phone)
{
	if($phone != ""){
		if(preg_match("/^([0-9]{10}$)/i", $phone)){
			return $phone;
		}
		else
		{
			echo "</br> Phone incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validateAdressNumber($number)
{
	if($number != ""){
		if(preg_match("/^([0-9a-zA-Z.]+$)/i", $number)){
			return $number;
		}
		else
		{
			echo "</br> Number Adress incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validateRFC($RFC)
{
	if($RFC != ""){
		if(preg_match("/^([A-Z]?[A-Z]{3}[0-9]{6}[A-Za-z0-9]{3}$)/i", $RFC)){
			return $RFC;
		}
		else
		{
			echo "</br> RFC incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}

function validateNumber($number)
{
	if($number != ""){
		if(preg_match("/^([0-9]+$)/i", $number)){
			return $number;
		}
		else
		{
			echo "</br> RFC incorrecto Sintaxis invalida";
			return null;
		}
	}
	else{
		return null;
	}
}



?>