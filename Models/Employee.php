<?php
class Employee {
    public $name;
	public $last_name;
	public $RFC;
	public $email;
	public $phone;
	public $cellphone;
	public $address;
	public $colony;
	public $city;


    public function __construct($name, $last_name, $RFC, $email, $phone, $cellphone, $address, $colony, $city)    
    {    
    	$this->name = $name;
		$this->last_name = $last_name;
		$this->RFC = $RFC;
		$this->email = $email;
		$this->phone = $phone;
		$this->cellphone = $cellphone;
		$this->address = $address;
		$this->colony = $colony;
		$this->city = $city;
    }
} 

?>