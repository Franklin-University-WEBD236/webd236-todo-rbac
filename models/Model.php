<?php

class Model {
  protected static $db = null;  
  protected $fields;
  
  public function __construct($id = null) {
    if (!isset($this->fields)) {
      $this->fields = array();
    }
    $fields['id'] = $id;
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
