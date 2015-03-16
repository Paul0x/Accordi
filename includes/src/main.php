<?
if($IN_ACCORDI != true)
{
 exit();
} 

// Accordi - Página Inicial


// HEADER

if($_SESSION['id_usuario'] == NULL && $url[0] == "")
{
   include("includes/wp-portal/portal.php");
   $html = new portal_interface($url[1],$url[2]."/".$url[3]."/".$url[4]);
}
elseif($_SESSION['id_usuario'] == NULL && $url[0] != "")
{
 include("includes/wp-portal/portal.php");
 $html = new portal_interface($url[1],$url[2]."/".$url[3]."/".$url[4]);
}
else
{
   switch($url[1])
   {
   case "edit":
       include("includes/prf/edit.php");
       break;
   default:
       include("includes/prf/home.php");
       break;
   }
}

?>
