<?php
function getFile( $nameFile, $vars)
	{

		//echo ": ",$nameFile, ': ',var_dump($vars);
		require_once('include/Template.php');
		$FileTemplate = new Template();
		$FileTemplate->setTemplate($nameFile);
		if(count($vars) > 0)
			$FileTemplate->setvars($vars);
		return $FileTemplate->show();
	}
?>