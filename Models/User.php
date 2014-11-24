<?php
class User {
    public $name;
    public $password;
    public $email;
    public $rol;
      
    public function __construct($name, $password, $email, $rol)    
    {    
        $this->name = $name;
        $this->password = $password;  
        $this->email = $email;    
        $this->rol = $rol;
    }   
} 
 