<?php
class Usuario {
	private $id_usuario;
    private $usuario;
    private $password;
    private $correo;
    private $rol;
      
    public function __construct($id_usuario, $usuario, $password, $correo, $rol)    
    {    
        $this->id_usuario = $id_usuario;
        $this->usuario = $usuario;
        $this->password = $password;  
        $this->correo = $correo;    
        $this->rol = $rol;
    }   
} 

?> 