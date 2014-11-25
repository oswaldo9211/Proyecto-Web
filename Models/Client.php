<?php
class Client {
    public $razon_social;
	public $RFC;
	public $email;
	public $phone;
	public $cellphone;

    public function __construct($razon_social, $RFC, $email, $phone, $cellphone)    
    {    
    	$this->razon_social = $razon_social;
		$this->RFC = $RFC;
		$this->email = $email;
		$this->phone = $phone;
		$this->cellphone = $cellphone;
    }
} 

?>