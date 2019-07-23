<?php
class TodoForm extends Form {
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