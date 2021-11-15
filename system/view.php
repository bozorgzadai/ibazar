<?php
class View {


  public static function render($theme, $filePath, $data = array()){
    extract($data);

    ob_start();
    require_once(getcwd() . "/mvc/view" . $filePath);

    //don't remove below $content that requires for rendering template
    $content = ob_get_clean();

    if($theme == 'default'){
      require_once(getcwd() . "/theme/default.php");
    }
/*    elseif ($theme == 'defaultDashboard'){
      require_once(getcwd() . "/theme/defaultDashboard.php");
    }*/
  }



  public static function renderPartial($filePath, $data = array(), $return = false){
    extract($data);

    if ($return) {
      ob_start();
    }

    require_once(getcwd() . "/mvc/view" . $filePath);

    if ($return) {
      return ob_get_clean();
    }
  }

}