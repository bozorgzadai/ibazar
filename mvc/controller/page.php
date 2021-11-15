<?php
class PageController {

  public function home() {
/*    if (! isset($_SESSION['email'])){
      header("Location: " . baseUrl() . "/account/login");
      return;
    }*/

    $data['activePage'] = 'home';
    View::render("default", "/page/home.php", $data);
  }

}