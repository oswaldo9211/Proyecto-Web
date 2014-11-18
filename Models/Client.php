<?php
class Client {
    public $name;
	public $last_name;
	public $RFC;
	public $email;
	public $phone;

    public function __construct($name, $last_name, $RFC, $email, $phone)    
    {    
    	$this->name = $name;
		$this->last_name = $last_name;
		$this->RFC = $RFC;
		$this->email = $email;
		$this->phone = $phone;
    }
} 

?>