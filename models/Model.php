<?php

class Model implements ArrayAccess {
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
    self::adHocQuery("PRAGMA foreign_keys=ON;");
  }

  public static function getDB() {
    if (!self::$db) {
      try {
        $fileName = CONFIG['databaseFile'] . ".db3";
        self::$db = new PDO('sqlite:' . $fileName);
        if (!self::$db) {
          errorPage(500, print_r($db->errorInfo(), 1) );
        }
      } catch (PDOException $e) {
          errorPage(500, "Could not open database. " . $e->getMessage() . $e->getTraceAsString());
      }
    }
    return self::$db;
  }

  public static function adHocQuery($q) {
    $st = self::$db -> prepare($q);
    $st -> execute();
    return $st -> fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function offsetExists($offset) {
    return isset($this->$offset);
  }

  public function offsetGet($offset) {
    return $this->$offset;
  }

  public function offsetSet($offset , $value) {
    $this->$offset = $value;
  }

  public function offsetUnset($offset) {
    unset($this->$offset);
  }
}
Model::getDB();
?>
