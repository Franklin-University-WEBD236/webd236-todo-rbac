<?php

class Model {
  protected static $db = null;  
  
  public function __construct() {
  }

  public static function init() {
    if (!self::$db) {
      try {
        self::$db = new PDO('sqlite:ToDoList.db3');
      } catch (PDOException $e) {
        die("Could not open database. " . $e->getMessage() . $e->getTraceAsString());
      }
    }
    return self::$db;
  }

  public function adHocQuery($q) {
    $st = $this->db -> prepare($q);
    $st -> execute();
    return $st -> fetchAll(PDO::FETCH_ASSOC);
  }
}
Model::init();
?>
