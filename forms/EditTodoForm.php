<?php
class EditTodoForm extends Form {
  public function __construct() {
    parent::__construct(
      array(
        'description' => array('required'),
        'done' => array('required'),
      )
    );
  }  
}
?>