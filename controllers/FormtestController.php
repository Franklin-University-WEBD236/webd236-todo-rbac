<?php
class FormtestController extends Controller {

  public function __construct() {
    parent::__construct();
    // Need a model? Uncomment below:
    // $this->model = 'FormtestModel';
  }

  public function get_index() {
    $form = new Form($this->view->url("formtest"));
    $form->field("firstName", "text", "First Name", "notempty", ["min" => "1"]);
    $form->field("lastName", "text", "Last Name", "notempty", ["min" => "1"]);
    $form->field("password", "password", "Password", "notempty", ["min" => "1"]);

    
    
    $this->view->renderTemplate(
      "views/FormtestIndex.php",
      array(
        'title' => 'FormtestIndex',
        'form' => $form -> toHtml(),
      )
    );
  }
}