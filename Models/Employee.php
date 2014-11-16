<?php
class Employee {
    public $name;
	public $last_name;
	public $RFC;
	public $email;
	public $phones;
	public $street;
	public $colony;
	public $municipality;
	public $no_external;
	public $no_internal;



    public function __construct($name, $last_name, $RFC, $email, $phones, $street, $colony, $municipality, $no_external, $no_internal)    
    {    
    	$this->name = $name;
		$this->last_name = $last_name;
		$this->RFC = $RFC;
		$this->email = $email;
		$this->phones = $phones;
		$this->street = $street;
		$this->colony = $colony;
		$this->municipality = $municipality;
		$this->no_external = $no_external;
		$this->no_internal = $no_internal;
    }
} 

?>