<?php

class PermissionModel extends Model {

  private static $fieldNames = array('name');
  
  public function __construct($fields = null) {
    parent::__construct($fields);
    foreach (self::$fieldNames as $attribute) {
      if (isset($fields[$attribute])) {
        $this->$attribute = $fields[$attribute];
      } else {
        $this->$attribute = null;
      }
    }
  }

  public function toArray() {
    $fields = parent::toArray();
    foreach (self::$fieldNames as $attribute) {
      $fields[] = $this[$attribute];
    }
    return $fields;
  }
  
  public function validate($throw = false) {
    $validator = new Validator();
    $validator -> required('name', $this -> name, "Permission name is required");
    if ($throw && $validator -> hasErrors()) {
      throw new IllegalStateException(implode(", ", $validator -> allErrors()));
    }
    return $validator;
  }

  private function clean() {
    $this -> name = htmlentities($this -> name, ENT_QUOTES);
  }

  public function setName($name) {
    $this -> name = trim($name);
    return $this;
  }

  public function getName() {
    return $this -> name;
  }

  public static function fromRows($rows) {
    $result = array();
    foreach ($rows as $row) {
      $result[] = new PermissionModel($row);
    }
    return $result;
  }

  public static function findAll() {
    $st = self::$db -> prepare("SELECT * FROM permissions ORDER BY name");
    $st -> execute();
    return self::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
  }

  public static function findById($id) {
    $st = self::$db -> prepare("SELECT * FROM permissions WHERE id = :id");
    $st -> bindParam(':id', $id);
    $st -> execute();
    return new PermissionModel($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findByName($name) {
    $st = self::$db -> prepare("SELECT * FROM permissions WHERE name = :name");
    $st -> bindParam(':name', $name);
    $st -> execute();
    return new PermissionModel($st -> fetch(PDO::FETCH_ASSOC));
  }

  public function insert() {
    $this -> validate(true);
    $this -> clean();
    $statement = self::$db -> prepare("INSERT INTO permissions (name) VALUES (:name)");
    $statement -> bindParam(':name', $this -> name);
    $statement -> execute();
    $this -> setId($db -> lastInsertId());
    return $this;
  }

  public function update() {
    $this -> validate(true);
    $this -> clean();
    $statement = self::$db -> prepare("UPDATE permissions SET name=:name WHERE id=:id");
    $statement -> bindParam(':name', $this -> name);
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    return $this;
  }

  public function delete() {
    self::deleteById($this -> getId());
  }

  private static function deleteById($id) {
    $statement = self::$db -> prepare("DELETE FROM permissions WHERE id = :id");
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }

}
?>