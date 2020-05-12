<?php

class ResetController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function post_index() {
    $dbFilename = CONFIG['databaseFile'];
    if (!file_exists("$dbFilename.sql")) {
      new ErrorController(500, "SQL script '{$dbFilename}.sql' not found. Maybe create it?");
    }
    $output = `sqlite3 {$dbFilename}.db3 < {$dbFilename}.sql 2>&1`;
    if ($output) {
      new ErrorController(500, "SQLite errors in {$dbFilename}.sql\n " . $output);
    }
    $this->view->flash("Database reset successfully.");
    $this->view->redirectRelative("index");
  }
}
?>