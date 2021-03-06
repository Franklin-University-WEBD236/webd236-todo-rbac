<?php
class GroupModel extends Model {

  private static $fieldNames = ['name'];
  
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

  public function validate($throw = false) {
    $validator = new Validator();
    $validator -> required('name', $this -> name, "Group name is required");
    if ($throw && $validator -> hasErrors()) {
      throw new Exception(implode(", ", $validator -> allErrors()));
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

  private static function fromRow($row) {
    $group = null;
    if ($row) {
      $group = new GroupModel($row);
    }
    return $group;
  }

  private static function fromRows($rows) {
    $result = array();
    foreach ($rows as $row) {
      $result[] = self::fromRow($row);
    }
    return $result;
  }

  public function toArray() {
    $fields = parent::toArray();
    foreach (self::$fieldNames as $attribute) {
      $fields[$attribute] = $this[$attribute];
    }
    return $fields;
  }
  
  public static function findAll() {
    $st = self::$db -> prepare("SELECT * FROM groups ORDER BY name");
    $st -> execute();
    return self::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
  }

  public static function findById($id) {
    $st = self::$db -> prepare("SELECT * FROM groups WHERE id = :id");
    $st -> bindParam(':id', $id);
    $st -> execute();
    return self::fromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findByName($name) {
    $st = self::$db -> prepare("SELECT * FROM groups WHERE name = :name");
    $st -> bindParam(':name', $name);
    $st -> execute();
    return new GroupModel($st -> fetch(PDO::FETCH_ASSOC));
  }

  public function insert() {
    $this -> validate(true);
    $this -> clean();
    $statement = self::$db -> prepare("INSERT INTO groups (name) VALUES (:name)");
    $statement -> bindParam(':name', $this -> name);
    $statement -> execute();
    $this -> id = self::$db -> lastInsertId();
    return $this;
  }

  public function update() {
    $this -> validate(true);
    $this -> clean();
    $statement = self::$db -> prepare("UPDATE groups SET name = :name WHERE id = :id");
    $statement -> bindParam(':name', $this -> name);
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    return $this;
  }

  public function delete() {
    debug(var_dump($this));
    self::delete_by_id($this -> id);
  }

  private static function delete_by_id($id) {
    $statement = self::$db -> prepare("DELETE FROM groups WHERE id = :id");
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }

  public static function findByUserId($userId) {
    $statement = self::$db -> prepare("SELECT * FROM groups where groups.id in (SELECT groups.id from groups, usergroups WHERE groups.id = usergroups.groupId AND usergroups.userId = :userId) order by name");
    $statement -> bindParam(':userId', $userId);
    $statement -> execute();
    return self::fromRows($statement->fetchAll(PDO::FETCH_ASSOC));
  }

  public static function findByNotUserId($userId) {
    $statement = self::$db -> prepare("SELECT * FROM groups where groups.id not in (SELECT groups.id from groups, usergroups WHERE groups.id = usergroups.groupId AND usergroups.userId = :userId) order by name");
    $statement -> bindParam(':userId', $userId);
    $statement -> execute();
    return self::fromRows($statement->fetchAll(PDO::FETCH_ASSOC));
  }

  public function addUser($user) {
    $statement = self::$db -> prepare("INSERT INTO usergroups (groupId, userId) VALUES (:groupId, :userId)");
    $statement -> bindValue(':groupId', $this -> id);
    $statement -> bindValue(':userId', $user -> id);
    $statement -> execute();
  }

  public function removeUser($user) {
    $statement = self::$db -> prepare("DELETE FROM usergroups WHERE groupId = :groupId AND userId = :userId");
    $statement -> bindValue(':groupId', $this -> id);
    $statement -> bindValue(':userId', $user -> id);
    $statement -> execute();
  }

  public function addPermission($permission) {
    $statement = self::$db -> prepare("INSERT INTO grouppermissions (groupId, permissionId) VALUES (:groupId, :permissionId)");
    $statement -> bindValue(':groupId', $this -> id);
    $statement -> bindValue(':permissionId', $permission -> id);
    $statement -> execute();
  }

  public function removePermission($permission) {
    $statement = self::$db -> prepare("DELETE FROM grouppermissions WHERE permissionId = :permissionId AND groupId = :groupId");
    $statement -> bindValue(':groupId', $this -> id);
    $statement -> bindValue(':permissionId', $permission -> id);
    $statement -> execute();
  }

  public function getMembers() {
    $statement = self::$db -> prepare("SELECT user.id AS id, user.firstName AS firstName, user.lastName AS lastName, user.password AS password FROM user, usergroups WHERE user.id = usergroups.userId and usergroups.groupId = :id ORDER BY lastName, firstName");
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return UserModel::fromRows($rows);
  }

  public function getNonMembers() {
    $statement = self::$db -> prepare("SELECT * FROM user WHERE user.id not in (SELECT user.id FROM user, usergroups WHERE user.id = usergroups.userId and usergroups.groupId = :id) ORDER BY lastName, firstName;");
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return UserModel::fromRows($rows);
  }

  public function getPermissions() {
    $statement = self::$db -> prepare("SELECT permissions.id AS id, permissions.name as name FROM permissions, grouppermissions WHERE permissions.id = grouppermissions.permissionId and grouppermissions.groupId = :id ORDER BY name");
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return PermissionModel::fromRows($rows);
  }

  public function getNonPermissions() {
    $statement = self::$db -> prepare("SELECT * FROM permissions WHERE permissions.id not in (SELECT permissions.id FROM permissions, grouppermissions WHERE permissions.id = grouppermissions.permissionId and grouppermissions.groupId = :id) ORDER BY name;");
    $statement -> bindParam(':id', $this -> id);
    $statement -> execute();
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return PermissionModel::fromRows($rows);
  }

}