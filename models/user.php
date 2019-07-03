<?php
include_once 'models/db.php';

class TodoModel extends Model {
  
  public function __construct() {
    parent::__construct();
  }
  

function findUserById($id) {
  global $db;
  $st = $db -> prepare('SELECT * FROM user WHERE id = ?');
  $st -> bindParam(1, $id);
  $st -> execute();
  return $st -> fetch(PDO::FETCH_ASSOC);
}

function findByEmailAndPassword($email, $password) {
  global $db;
  $st = $db -> prepare('SELECT * FROM user WHERE email = :email AND password = :password');
  $st -> bindParam(':email', $email);
  $st -> bindParam(':password', $password);
  $st -> execute();
  return $st -> fetch(PDO::FETCH_ASSOC);
}

function findAllUsers() {
  global $db;
  $st = $db -> prepare('SELECT * FROM user ORDER BY id');
  $st -> execute();
  return $st -> fetchAll(PDO::FETCH_ASSOC);
}

function addUser($email, $password, $firstName="", $lastName="") {
  global $db;
  $st = $db -> prepare("INSERT INTO user (email, password, firstName, lastName) values (:email, :password, :firstName, :lastName)");
  $st -> bindParam(':email', $email);
  $st -> bindParam(':password', $password);
  $st -> bindParam(':firstName', $firstName);
  $st -> bindParam(':lastName', $lastName);
  $st -> execute();
  return $db->lastInsertId();
}

function updateUser($id, $email, $password, $firstName, $lastName) {
  global $db;
  $st = $db -> prepare("UPDATE user SET email = :email, password = :password, firstName = :firstName, lastName = :lastName WHERE id = :id");
  $st -> bindParam(':email', $email);
  $st -> bindParam(':password', $password);
  $st -> bindParam(':firstName', $firstName);
  $st -> bindParam(':lastName', $lastName);
  $st -> bindParam(':id', $id);
  $st -> execute();
}


?>