<?php
require_once 'include/config.php';
require_once 'include/util.php';


function routeUrl() {
  $method = $_SERVER['REQUEST_METHOD'];
  $requestURI = explode('/', $_SERVER['REQUEST_URI']);
  $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

  for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
      unset($requestURI[$i]);
    }
  }

  $entity = array_values($requestURI);
  $className = ucfirst($entity[0]) . 'Controller';
  $controller = 'controllers/' . $className . '.php';
  $func = strtolower($method) . '_' . (isset($entity[1]) ? $entity[1] : 'index');
  $params = array_slice($entity, 2);

  if (!file_exists($controller)) {
    new ErrorController(404, "Controller <code>$controller</code> doesn't exist. Do you want to <a href='/framework/createController/${className}'>create it</a>?");
  }

  $object = new $className();
  
  if (!method_exists($object, $func)) {
    new ErrorController(404, "Method <code>$func()</code> doesn't exist in controller <code>$controller</code>. Do you want to <a href='/framework/createFunction/$className/$func'>create it</a>?");
  }

  call_user_func_array(array($object, $func), $params);
  new ErrorController(404, "It looks like you're not redirecting or rendering a template in <code>$func()</code> in the <code>$controller</code> controller. Maybe edit that function?");
  exit();
}

spl_autoload_register(function ($name) {
  foreach (array('controllers/', 'models/', 'include/', 'views/') as $dir) {
    $path = $dir . $name . '.php';
    if (file_exists($path)) {
      include_once($path);
      return;
    }
  }
  die("Could not find class $name.");
});


// note, GDPR says that you need to notify about cookies like this.
session_set_cookie_params(60*60*24*14, '/', $_SERVER['SERVER_NAME'], true, true);
session_start();
routeUrl();
