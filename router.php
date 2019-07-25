<?php
include_once 'include/util.php';

function routeUrl() {
  $method = $_SERVER['REQUEST_METHOD'];
  $requestURI = explode('/', $_SERVER['REQUEST_URI']);
  $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

  for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
      unset($requestURI[$i]);
    }
  }
  # continued...

  $entity = array_values($requestURI);
  $className = ucfirst($entity[0]) . 'Controller';
  $controller = 'controllers/' . $className . '.php';
  $func = strtolower($method) . '_' . (isset($entity[1]) ? $entity[1] : 'index');
  $params = array_slice($entity, 2);

  error_log("Looking for controller ${controller}", 0);

  if (!file_exists($controller)) {
    die("Controller '$controller' doesn't exist.");
  }

  // require $controller; // no longer needed due to auto loader
  $object = new $className();
  
  if (!method_exists($object, $func)) {
    die("Method '$func' doesn't exist in controller '$className'.");
  }

  call_user_func_array(array($object, $func), $params);
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
