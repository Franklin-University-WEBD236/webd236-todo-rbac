<?php
class FrameworkController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  function get_createController($controller) {
    $controller = $this->sanitize($controller);
    $model = substr($controller, 0, strpos($controller, "Controller"));

    // -- template here ----------------------------
    $template =<<<END
<?php
class $controller extends Controller {

  public function __construct() {
    parent::__construct();
    // Need a model? Uncomment below:
    // \$this->model = '{$model}Model';
  }
}
END;
    // -- template here ----------------------------

    file_put_contents("controllers/{$controller}.php", $template);
    `refresh`; // force glitch to find the new file
    exit();
  }

  function get_createFunction($controller, $function) {
    $controller = $this->sanitize($controller);
    $function = $this->sanitize($function);
    $contents = file_get_contents("controllers/{$controller}.php");
    $view = substr($controller, 0, strlen($controller) - 10) . ucfirst(substr($function, strpos($function, "_") + 1));

// -- template here ----------------------------
  $template =<<<END

  public function {$function}() {
    // Put your code for {$function} here, something like
    // 1. Load and validate parameters or form contents
    // 2. Query or update the database
    // 3. Render a template or redirect
    \$this->view->renderTemplate(
      "views/{$view}.php",
      array(
        'title' => '{$view}',
      )
    );
  }

END;
// -- template here ----------------------------

    // append the new function to the end of the file (before the closing PHP tag, if any)
    $template = preg_replace("/(\A.*)(}\s*(\?>)?\Z)/msU", "$1$template$2", $contents, 1);
    file_put_contents("controllers/{$controller}.php", $template);
    `refresh`; // force glitch to find the new file
    exit();
  }

  function get_createView($dir, $view) {
    $view = $this->sanitize($view);

// -- template here ----------------------------
    $template =<<<'END'
%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    <p>Replace this with your view contents</p>
  </div>
</div>
          
%% views/footer.html %% 
END;
// -- template here ----------------------------

    file_put_contents("views/{$view}", $template);
    `refresh`; // force glitch to find the new file
    exit();
  }
  
  private function sanitize($str) {
    // sanitize controller and function names
    $str = preg_replace("/([^\w\d_\.])/", '', $str);
    $str = preg_replace("/([\.]{2,})/", ".", $str);
    return $str;
  }
}