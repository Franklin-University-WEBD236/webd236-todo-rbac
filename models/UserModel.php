<?php

class UserModel extends Model {

  private static $fieldNames = array('email', 'password', 'firstName', 'lastName');

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
  
  public static function fromRow($row) {
    $user = null;
    if ($row) {
      $user = new UserModel($row);
    }
    return $user;
  }

  public static function fromRows($rows) {
    $result = array();
    foreach ($rows as $row) {
      $result[] = self::fromRow($row);
    }
    return $result;
  }
  
  public function getFullName() {
    return $this->lastName . ", " . $this->firstName;
  }
  
  public static function findAll() {
    $st = self::$db -> prepare('SELECT * FROM user order by lastName');
    $st -> execute();
    return self::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
  }

  public static function findById($id) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE id = ?');
    $st -> bindParam(1, $id);
    $st -> execute();
    return self::fromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findByEmail($email) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE email = :email');
    $st -> bindParam(':email', $email);
    $st -> execute();
    return self::fromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public function insert() {
    $st = self::$db -> prepare("INSERT INTO user (email, password, firstName, lastName) values (:email, :password, :firstName, :lastName)");
    $st -> bindParam(':email', $this->email);
    $st -> bindParam(':password', $this->password);
    $st -> bindParam(':firstName', $this->firstName);
    $st -> bindParam(':lastName', $this->lastName);
    $st -> execute();
    return $this->id = self::$db->lastInsertId();
  }
  
  public function delete() {
    $statement = self::$db -> prepare("DELETE FROM user WHERE id = :id");
    $statement -> bindParam(':id', $this->id);
    $statement -> execute();
  }

  public function update() {
    $st = self::$db -> prepare("UPDATE user SET email = :email, password = :password, firstName = :firstName, lastName = :lastName WHERE id = :id");
    $st -> bindParam(':email', $this->email);
    $st -> bindParam(':password', $this->password);
    $st -> bindParam(':firstName', $this->firstName);
    $st -> bindParam(':lastName', $this->lastName);
    $st -> bindParam(':id', $this->id);
    $st -> execute();
  }
}

?>