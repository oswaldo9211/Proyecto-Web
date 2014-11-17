<?php
class User {
	public $id_usuario;
    public $usuario;
    public $password;
    public $email;
    public $rol;
      
    public function __construct($id_usuario, $usuario, $password, $email, $rol)    
    {    
        $this->id_usuario = $id_usuario;
        $this->usuario = $usuario;
        $this->password = $password;  
        $this->email = $email;    
        $this->rol = $rol;
    }   
} 

?> 