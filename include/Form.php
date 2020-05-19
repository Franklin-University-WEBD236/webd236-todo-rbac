<?php
class Form {
  
  private $fields;
  
  public function __construct() {
    $this -> fields = array();
    
  }
  
  public function textbox($name) {
    
  }
  
  public function toHtml() {
//      <div class="form-group">
//        <label for="email">Email address</label>
//        <input type="text" min="1" id="email" name="form[email]" class="form-control"
// placeholder="Enter email address" value="{{$this->value($form['email'])}}" />
//      </div>
  }
}