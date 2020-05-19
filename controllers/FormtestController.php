<?php
class FormtestController extends Controller {

  public function __construct() {
    parent::__construct();
    // Need a model? Uncomment below:
    // $this->model = 'FormtestModel';
  }

  public function get_index() {
    $form = new Form(); //$name, $type, $label, $valid="", $options=[]
    $form->field("firstName", "text", "First Name", "notempty", ["min" => "1"]);
    $form->field("lastName", "text", "Last Name", "notempty", ["min" => "1"]);
    $form->field("password", "password", "Password", "notempty", ["min" => "1"]);
    // Put your code for get_index here, something like
    // 1. Load and validate parameters or form contents
    // 2. Query or update the database
    // 3. Render a template or redirect
    $this->view->renderTemplate(
      "views/FormtestIndex.php",
      array(
        'title' => 'FormtestIndex',
        'form' => $form -> toHtml(),
      )
    );
  }
}