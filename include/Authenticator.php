<?php

class Authenticator {

  private $cache;
  private static $instance;

  const PERMS_QUERY =
    "SELECT DISTINCT permissions.id as id, permissions.name as name
     FROM
        user, usergroups, rbac_groups, grouppermissions, permissions
     WHERE
        user.id = :userId AND
        user.id = usergroups.userId AND
        usergroups.groupId = rbac_groups.id AND
        rbac_groups.id = grouppermissions.groupId AND
        grouppermissions.permissionId = permissions.id";

  private function __construct() {
    $cache = array();
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new Authenticator();
    }
    return self::$instance;
  }

  private function realUserId($userId) {
    if (isset($userId) && is_numeric($userId)) {
      return $userId;
    }
    if (gettype($userId) == 'object') {
      return $userId -> getId();
    }
    if (isLoggedIn()) {
      return $_SESSION['user'] -> id;
    }
    return null;
  }

  public function can($permissionKey, $userId = false) {
    $userId = $this -> realUserId($userId);
    $permissions = $this -> permissionsFor($userId);
    foreach ($permissions as $permission) {
      if ($permission -> getName() === $permissionKey) {
        return true;
      }
    }
    return false;
  }

  private function permissionsFor($userId) {
    if (!isset($this -> cache[$userId])) {
      $db = Model::getDb();
      $st = $db -> prepare(self::PERMS_QUERY);
      $st -> bindParam(':userId', $userId);
      $st -> execute();
      $this -> cache[$userId] = PermissionModel::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
    }
    return $this -> cache[$userId];
  }

  public function ensure($permissionKey, $userId = false) {
    if (!$this -> can($permissionKey, $userId)) {
      $userId = $this -> realUserId($userId);
      Logger::instance() -> warn("User $userId attempted unauthorized operation $permissionKey");
      die("You do not have permission to access this resource.  This attempt has been logged.");
    }
  }

}
?>