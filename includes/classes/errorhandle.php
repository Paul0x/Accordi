<?php

if ($IN_ACCORDI != true) {
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Usuário                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class errorhandle {

   private $error;

   public function __construct() {
      $this->sessionProtect();
      $this->escapePost();
      $this->escapeGet();
   }

   private function sessionProtect() {
      //if (isset($_SESSION['id_usuario'])) {
      //   session_regenerate_id();
      //}
      if (array_key_exists('HTTP_USER_AGENT', $_SESSION)) {
         if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
             session_destroy();
            header("location: index.php");
            exit;
         }
      } else {
         $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
      }
   }

   private function escapePost() {
      foreach ($_POST as $key => $get) {
         if(is_array($_POST[$key]))
         {
             foreach($_POST[$key] as $k2 => $v2)
             {
                 $_POST[$key][$k2] = strip_tags($_POST[$key][$k2]);
             }
         }
         else
         $_POST[$key] = strip_tags($_POST[$key]);
      }
   }

   private function escapeGet() {
      foreach ($_GET as $key => $get) {
                   if(is_array($_GET[$key]))
         {
             foreach($_GET[$key] as $k2 => $v2)
             {
                 $_GET[$key][$k2] = strip_tags($_GET[$key][$k2]);
             }
         }
         else
            $_GET[$key] = htmlentities(strip_tags($_GET[$key]),ENT_QUOTES,"UTF-8");
      }
   }

}

?>