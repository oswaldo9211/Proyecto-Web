<?php
/**
*functions to validate fields
**/


<<<<<<< HEAD

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

=======
>>>>>>> 25d6e71171fceff2cbbee6de9bba681daaa8b657
?>