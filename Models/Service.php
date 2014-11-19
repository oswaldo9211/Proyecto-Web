<?php
class Service {
    public $name;
    public $id_location;

    public function __construct($name, $id_location)    
    {    
    	$this->name = $name;
    	$this->id_location = $id_location;
    }
} 

?>