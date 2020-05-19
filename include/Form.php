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
  
  public function field($name, $type, $label, $valid="", $options=[]) {
    $this->fields[$name] = [
      "type" => $type,
      "label" => $label,
      "valid" => $valid,
      "options" => $options,
    ];
  }
  
  public function toHtml() {
    $result = "<form action=\"{$this->action}\" method=\"{$this->method}\">\n";
    foreach ($this->fields as $name => $params) {
      $temp = "";
      switch ($params['type']) {
        case "text" : $temp = $this->_text($name, $params['label'], $params['options']); break;
        case "password" : break;
      }
      $result .= $temp;
    }
    return $result . "\n</form>\n";
//      <div class="form-group">
//        <label for="email">Email address</label>
//        <input type="text" min="1" id="email" name="form[email]" class="form-control"
// placeholder="Enter email address" value="{{$this->value($form['email'])}}" />
//      </div>
  }
  
  private function _text($name, $label, $options) {
    $opts = [
      "class" => "form-control",
      "placeholder" => "Enter $label",
    ];
    $opts = array_merge($opts, $options);
    $opts = $this->_build($opts);
    return <<<END
<div class="form-group">
  <label for="$name">$label</label>
  <input type="text" id="$name" name="form[$name]" $opts />
</div>
END;
  }
  
  private function _build($options) {
    $result = [];
    foreach ($options as $key => $value) {
      $result[] = "$key=\"$value\"";
    }
    return implode($result, " ");
  }
}