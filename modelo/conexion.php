<?php
  class Conexion
  {
    public $conexion_db;

    public function __construct()
    {
      try {
        // $this->conexion_db=new PDO('mysql:host=localhost; dbname=tfg', 'root','jack');
        $this->conexion_db=new PDO('mysql:host=localhost; dbname=jhack', 'jhack','jhack');
        $this->conexion_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conexion_db->exec("SET CHARACTER SET UTF8");
        return $this->conexion_db;

      } catch (Exception $e) {
          die("Error: " . $e->getMessage());
          echo "La línea del error es: " . $e.getLine();
      }
    }
  }


?>
