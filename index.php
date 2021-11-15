<?php

require_once(getcwd() . '/system/loader.php');

$uri = getRequestUri();
$uri = str_replace(baseUrl() . '/', '/', $uri);

$queryString = $_SERVER['QUERY_STRING'];

if (strlen($queryString)>0){
  $qvars = explode('&', $queryString);
  foreach ($qvars as $qvar){
    list($key, $value) = explode('=', $qvar);
    $value = urldecode($value);
    $_GET[$key] = $value;
  }

  $uri = str_replace('?' . $queryString, '' , $uri);
}

global $config;
$route = $config['route'];

$uri = urldecode($uri);
foreach ($route as $alias=>$target){
  $alias = '^' . $alias;
  $alias = str_replace('/', '\/', $alias);
  $alias = str_replace('*', '(.*)', $alias);

  if (preg_match('/'.$alias.'/', $uri)) {
    $uri = preg_replace('/'.$alias.'/', $target, $uri);
  }
}

$parts = explode('/', $uri);
$controller = $parts[1];
if (strlen($controller) == 0){
  $controller = 'page';
}

if (count($parts) > 2) {
  $method = $parts[2];
} else {
  $method = 'home';
}

$params = array();
for ($i=3; $i<count($parts); $i++){
  $params[] = $parts[$i];
}

$controllerClassname = ucfirst($controller) . "Controller";
$controllerInstance = new $controllerClassname();
call_user_func_array(array($controllerInstance, $method), $params);
