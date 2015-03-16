<?php
session_start();
$_SESSION['timing'] = array_sum(explode(' ', microtime()));
require("conf.php");
include("includes/classes/classes.php");
$error = new errorhandle();

// Testamos conexão com o banco
$con = new conn();
$con->conecta();
unset($con);
// Carrega classes.
// Iniciadores de Login

switch ($url[0]) {
   case "valida":
      include("includes/functions/login_valida.php");
      break;
   case "async":
       include("includes/ajax/main.php");
       break;
}
if($url[0] != "async")
{
if (($url[0] == "home" || $url[0] == "musicas" || $url[0] == "eventos" || $url[0] == "contato" || $url[0] == "playlists") && $_SESSION['id_usuario'] == NULL) {
   $ref = htmlentities($_SERVER['SERVER_NAME']."$root/$url[0]/$url[1]/$url[2]");
   header("location: $root/login/&m=acess&url=$ref");
}
include("includes/src/header.php");
//

switch ($url[0]) {
   case 'cadastro':
      if (!isset($_SESSION['login_usuario']))
         include("includes/src/cadastro.php");
      else
         include("includes/src/main.php");
      break;
   case 'top':
      include("includes/top100/ranking.php");
       $html = new rankingInterface($url[1]);
      break;
  case 'termos':
      include("includes/wp-portal/portal.php");
      break;
   case 'portal':
      include("includes/wp-portal/portal.php");
      $html = new portal_interface($url[1],$url[2]."/".$url[3]."/".$url[4]);
      break;
   case 'playlists':
      include("includes/playlist/playlist_interface.php");
      $html = new playlist_interface($url[1],$url[2],$_POST['id-play-tobanner']);
      break;
   case 'busca':
      include("includes/searcher/search.php");
      break;
   case "":
      include("includes/src/main.php");
      break;
  case "eventos":      
      include("includes/events/events2.php");
      $html = new manage_eventos($url[1],$url[2],$_POST['evento-add-btn']);
      break;
  case "eventosn":
      include("includes/events/events.php");
      break;
   case "musicas":
      include("includes/music/music2.php");
       $html = new musica_manage($url[1],false);
      break;
   case "contato":
      include("includes/contato/main.php");
      $html = new contato_view();
      break;
   case "login":
      if (!isset($_SESSION['login_usuario']))
         include("includes/src/login.php");
      else
         include("includes/src/main.php");
      break;
   case "recovery":
      if (!isset($_SESSION['login_usuario']))
         include("includes/src/precovery.php");
      else
         include("includes/src/main.php");
      break;
   case "home":
      include("includes/src/main.php");
      break;
  case "profile2":
      $existe = new validate();
      $a = $existe->ifExist($url[1],"login","usuario","login");
      if($a == false && $url[1] != "") include("includes/prf/public.php");
      else include("includes/site/404.php");
      break;
   case "profile":
      $existe = new validate();
      $a = $existe->ifExist($url[1],"login","usuario","login");
      if($a == false && $url[1] != "")
      {
          $b = $existe->pegaId($url[1]);
          include("includes/prf/public2.php");
          $html = new profile_1($b,$url[2]);
      }
      else
          include("includes/site/404.php");
      break;
   case "site":
      switch($url[1]){
      case "eventos":
         include("includes/events/public.php");
         break;
     case "musicas":
         include("includes/music/public.php");
         $html = new search_musica($_GET['nome'],$_GET['genero'],$_GET['page']);
         break;
     case "musica":
         $existe = new validate();
         $a = $existe->ifExist($url[2],"id","musica");
         if($a == false && $url[2] != "")
         {
             include("includes/music/public2.php");
             $html = new musica_interface($url[2],$url[3]);
         }
         else include("includes/site/404.php");
         break;
     case "evento":
         $existe = new validate();
         $a = $existe->ifExist($url[2],"id","evento");
         if($a == false && $url[2] != "") include("includes/events/public2.php");
         else include("includes/site/404.php");
         break;
      default:
         include("includes/site/404.php");
         break;
      }
      break;
   case "sobre":
       include("includes/site/about.php");
       break;
   default:
      if ($url[0] != "")
         include("includes/site/404.php");
      break;
}
//
include("includes/src/footer.php");
}
?>