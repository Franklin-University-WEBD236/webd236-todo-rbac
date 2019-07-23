<?php
class CreateTodoForm extends Form {
  public function __construct() {
    parent::__construct(
      array(
        'description' => array('required'),
      )
    );
  }  
}
?>