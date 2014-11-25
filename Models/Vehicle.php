<?php
class Vehicle {
    public $VIN;
	public $model;
	public $color;
	public $description;
	public $client;

    public function __construct($VIN, $model, $color, $description, $client)    
    {    
    	$this->VIN = $VIN;
		$this->model = $model;
		$this->color = $color;
		$this->description = $description;
		$this->client = $client;
    }
} 

?>