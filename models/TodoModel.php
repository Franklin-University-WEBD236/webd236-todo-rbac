<?php
include_once 'models/Model.php';

class TodoModel extends Model {
  
  private static $fieldNames = array('description', 'done', 'user_id');
  
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
  
  private static function fromRow($row) {
    $todo = null;
    if ($row) {
      $todo = new TodoModel($row);
    }
    return $todo;
  }
  
  private static function fromRows($rows) {
    $result = array();
    foreach ($rows as $row) {
      $result[] = self::fromRow($row);
    }
    return $result;
  }

  public static function findToDoById($id) {
    $st = self::$db -> prepare('SELECT * FROM todo WHERE id = ?');
    $st -> bindParam(1, $id);
    $st -> execute();
    return self::fromRow($st -> fetch(PDO::FETCH_ASSOC));
  }

  public static function findAllCurrentToDos($user_id) {
    $st = self::$db -> prepare('SELECT * FROM todo WHERE done = 0 AND user_id = :user_id ORDER BY id');
    $st -> bindParam(':user_id', $user_id);
    $st -> execute();
    return self::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
  }

  public static function findAllDoneToDos($user_id) {
    $st = self::$db -> prepare('SELECT * FROM todo WHERE done != 0  AND user_id = :user_id ORDER BY id');
    $st -> bindParam(':user_id', $user_id);
    $st -> execute();
    return self::fromRows($st -> fetchAll(PDO::FETCH_ASSOC));
  }

  public function insert() {
    $st = self::$db -> prepare("INSERT INTO todo (description, done, user_id) values (:description, 0, :user_id)");
    $st -> bindParam(':description', $this->description);
    $st -> bindParam(':user_id', $this->user_id);
    $st -> execute();
    $this->id = self::$db->lastInsertId();
    return $this;
  }

  public function delete() {
    $statement = self::$db -> prepare("DELETE FROM todo WHERE id = :id");
    $statement -> bindParam(':id', $this->id);
    $statement -> execute();
  }

  public function update() {
    $statement = self::$db -> prepare("UPDATE todo SET description = :description, done = :done WHERE id = :id");
    $statement -> bindParam(':description', $this->description);
    $statement -> bindParam(':done', $this->done);
    $statement -> bindParam(':id', $this->id);
    $statement -> execute();
    return $this;
  }
  
  public function toggleDone() {
    $this->done = $this->done ? 0 : 1;
    $this->update();
    return $this;
  }

/*  
  public static function toggleDoneToDo($id) {
    $todo = self::findToDoById($id);
    self::updateToDo($id, $todo['description'], $todo['done'] ? 0 : 1);
  }

  public static function updateToDo($id, $description, $done) {
    $statement = self::$db -> prepare("UPDATE todo SET description = :description, done = :done WHERE id = :id");
    $statement -> bindParam(':description', $description);
    $statement -> bindParam(':done', $done);
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }

  public static function deleteToDo($id) {
    $statement = self::$db -> prepare("DELETE FROM todo WHERE id = :id");
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }
*/  
}

?>