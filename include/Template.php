<?php
/**
* 
*/
class Template
{
	public $tpl_file;
	public $htmlTemplate;
	public $fileReaded ;
	public $PATH;
	public $EXT;
	public $fileData;
	public $vars = array();
	public $htmlText ;

	function __construct()
	{
		$this->PATH = 'Views';
		$this->EXT  = '.html';
	}

	public function set_path($PATH)
	{
		$this->PATH = $PATH;
	}
	public function setTemplate($TemplateFile)
	{

		$this->tpl_file = $this->PATH . $TemplateFile . $this->EXT;
		//echo "</br> ", $this->tpl_file;
		$this->fileReaded = $this->fileData =  @fopen($this->tpl_file, 'r');
		if (!$this->fileReaded) {
			return false;
		}
		else
		{
			$this->htmlTemplate = fread($this->fileData, filesize($this->tpl_file));
			$this->htmlTemplate = str_replace("'", "\'", $this->htmlTemplate);
			fclose($this->fileData);
		}
		return true;
	}

	public function setVars($vars)
	{
		if ( $this->fileReaded ) {
			$this->vars = $vars;
			$this->htmlText = $this->htmlTemplate;
			$this->htmlText = preg_replace('#\{([a-z0-9\-_]*?)\}#is' ,  "' . $\\1 . '" , $this->htmlText);
			reset ($this->vars);
			while (list($key, $val) = each($this->vars)) {//
					$$key = $val;
			}
			eval("\$this->htmlText = '$this->htmlText';");//Su uso está totalmente desaconsejado.//Evaluar una cadena como código PHP
			reset ($this->vars);

			while (list($key, $val) = each($this->vars)) {
					unset($$key);// Destruye una variable especificada
			}

			$this->htmlText = str_replace ("\'", "'", $this->htmlText);//linea 27
			
			return true;
		
		}else{
			return false;
		}
	}

	public function show(){

		if ( $this->fileReaded ) {
			if(isset($this->htmlText)){// Destruye una variable especificada
				return $this->htmlText;
			}else{
				return $this->htmlTemplate;
			}
		}else{
			//Error, you must set a template file
			return "[ERROR]";
		}
	}
}
?>