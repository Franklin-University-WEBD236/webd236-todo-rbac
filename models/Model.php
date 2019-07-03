<?php

class Model {
  protected $db;  
  
  public function __construct() {
    try {
        $this->db = new PDO('sqlite:ToDoList.db3');
    } catch (PDOException $e) {
        die("Could not open database. " . $e->getMessage() . $e->getTraceAsString());
    }
  }

  public function adHocQuery($q) {
    $st = $this->db -> prepare($q);
    $st -> execute();
    return $st -> fetchAll(PDO::FETCH_ASSOC);
  }
}

?>
