<?php
include_once 'models/Model.php';

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
  
  private static function makeUserFromRow($row) {
    $user = null;
    if ($row) {
      $user = new UserModel($row);
    }
    return $user;
  }

  public static function findUserById($id) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE id = ?');
    $st -> bindParam(1, $id);
    $st -> execute();
    return self::makeUserFromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findByEmail($email) {
    $st = self::$db -> prepare('SELECT * FROM user WHERE email = :email');
    $st -> bindParam(':email', $email);
    $st -> execute();
    return self::makeUserFromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function addUser($email, $password, $firstName="", $lastName="") {
    $st = self::$db -> prepare("INSERT INTO user (email, password, firstName, lastName) values (:email, :password, :firstName, :lastName)");
    $st -> bindParam(':email', $email);
    $st -> bindParam(':password', $password);
    $st -> bindParam(':firstName', $firstName);
    $st -> bindParam(':lastName', $lastName);
    $st -> execute();
    return self::$db->lastInsertId();
  }

  public static function updateUser($id, $email, $password, $firstName, $lastName) {
    $st = self::$db -> prepare("UPDATE user SET email = :email, password = :password, firstName = :firstName, lastName = :lastName WHERE id = :id");
    $st -> bindParam(':email', $email);
    $st -> bindParam(':password', $password);
    $st -> bindParam(':firstName', $firstName);
    $st -> bindParam(':lastName', $lastName);
    $st -> bindParam(':id', $id);
    $st -> execute();
  }
}

?>