<?php

//  http::/localhost:8888/index.php?
//    c=<controller_name>&a=<action_name>

class View {
  protected $data =[];
  protected $path;

  public function __construct($path) {
    $this->path = $path;
  }

  public function __set($name, $val) {
    $this->data[$name] = $val;
  }

  public function render($tpl) {
    extract($this->data);
    include($this->path . $tpl.'.php');
  }
}



class Controller {
  protected $controller;
  protected $action = 'index';
  protected $view;

  public function init() {
    $this->controller = $_GET['c'];
    if (isset($_GET['a'])) {
      $this->action     = $_GET['a'];
    }
    $this->view = new View(__DIR__ .'/tpl/');
  }

  public function run() {
    $a = $this->action .'Action';
    $this->$a();
  }

  public function __call($name, $arg) {
    echo 'Page not found!!!';
  }
}


function getController($name) {
  $cName = ucfirst($name) .'Controller';
  $c = new $cName();
  $c->init();
  return $c;
}

$c = getController(isset($_GET['c']) ? $_GET['c'] : 'page');
$c->run();


/////////////////////////////////////

class PageController extends Controller {

  public function indexAction() {
    $this->view->title = 'Main page!!!';
    $this->view->render('page/index');
  }

  public function aboutAction() {
    $arr = [];
    for ($i = 0; $i < 10; $i++) {
      $str = "Item $i";
      $arr[] = $str;
    }
    $this->view->list = $arr;
    $this->view->text = 'Hello, World!!!';
    $this->view->render('page/about');
  }

  public function testAction() {
    $this->view->render('page/test');
  }

}

class FormController extends Controller {
  public function indexAction() {
    $this->view->render('form/form');
  }

  public function acceptAction() {
    print '<pre>';
    print_r($_POST);
    print '</pre>';
  }

}
