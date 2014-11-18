<?php

/**
* Marco Antonio
*/
class Template
{
	
	private $tpl_file;
	private $Vars;
	private $PATH;
	private $EXT;
	private $htmlTemplate;
	private $Remplazo;
	
	function __construct($PATH='Views/',$EXT='.html')
	{
		$this->PATH = $PATH;
		$this->EXT  = $EXT;
	}

	public function setTemplate($FileName)
	{
		$this->tpl_file = $this->PATH . $FileName . $this->EXT;
		//echo "</br>", $this->tpl_file;
		$this->htmlTemplate = file_get_contents($this->tpl_file);
		//var_dump($this->htmlTemplate);
	}
	public function setVars($vars)
	{
		//echo "long" , strlen($this->htmlTemplate);
		if($this->htmlTemplate!= false){
			$this->vars = $vars;
			reset($this->vars);
			foreach ($this->vars as $key => $value) {
				$key = '{'. $key.'}';
				$this->Remplazo{$key} = $value; 
			}
			$this->htmlTemplate = strtr($this->htmlTemplate, $this->Remplazo);

		}
	}
	public function show()
	{
		if($this->htmlTemplate!= false){
			if(isset($this->htmlTemplate)){
				return $this->htmlTemplate;
			}
		}
		else
			return "{NO ESPESIFICO ARCHIVO}";
	}
}

?>