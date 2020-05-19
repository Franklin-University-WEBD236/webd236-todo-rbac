<?php
class Form {
  
  private $fields;
  private $action;
  private $method;
  
  public function __construct($action="", $method="POST") {
    $this -> fields = [];
    $this -> action = $action;
    $this -> method = $method;
  }
  
  public function start_div($options=[]) {
    $this->fields[] = [
      "type" => "div",
      "options" => $options,
    ];
  }

  public function end_div() {
    $this->fields[] = [
      "type" => "/div",
    ];
  }
  
  public function field($name, $type, $label, $valid="", $options=[]) {
    $this->fields[$name] = [
      "type" => $type,
      "label" => $label,
      "valid" => $valid,
      "options" => $options,
    ];
    return $this;
  }
  
  public function toHtml() {
    $result = "\n<form action=\"{$this->action}\" method=\"{$this->method}\">\n";
    foreach ($this->fields as $name => $params) {
      $temp = "";
      switch ($params['type']) {
        case "text" : $temp = $this->_input($name, "text", $params['label'], $params['options']); break;
        case "password" : $temp = $this->_input($name, "password", $params['label'], $params['options']); break;
        case "div" : $temp = $this->_div($params['options']); break;
        case "/div" : $temp = "</div>\n"; break;
        case "button" : $temp = $this->_button(); break;
      }
      $result .= $temp;
    }
    return $result . "</form>\n";
//      <div class="form-group">
//        <label for="email">Email address</label>
//        <input type="text" min="1" id="email" name="form[email]" class="form-control"
// placeholder="Enter email address" value="{{$this->value($form['email'])}}" />
//      </div>
  }
  
  private function _input($name, $type, $label, $options) {
    $opts = [
      "class" => "form-control",
      "placeholder" => "Enter $label",
    ];
    $opts = array_merge($opts, $options);
    $opts = $this->_buildOptions($opts);
    return <<<END
  <div class="form-group">
    <label for="$name">$label</label>
    <input type="$type" id="$name" name="form[$name]" $opts />
  </div>

END;
  }
  
  private function _div($options) {
    $opts = $this->_buildOptions($options);
    if ($opts) {
      return "<div $opts>\n";
    }
    return "<div>\n";
  }
  
  private function _button($name, $label, $options) {
    
  }
  
  private function _buildOptions($options) {
    $result = [];
    foreach ($options as $key => $value) {
      $result[] = "$key=\"$value\"";
    }
    return implode($result, " ");
  }
}