<?php

class Model {
  protected static $db = null;  
  private static $fieldNames = array('id');
  
  public function __construct($fields = null) {
    foreach (self::$fieldNames as $attribute) {
      if (isset($fields[$attribute])) {
        $this->$attribute = $fields[$attribute];
      } else {
        $this->$attribute = null;
      }
    }
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
