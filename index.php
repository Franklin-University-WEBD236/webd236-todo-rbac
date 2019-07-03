<?php
include_once "controllers/TodoController.php";

$controller = new TodoController();
$controller -> get_list();
?>