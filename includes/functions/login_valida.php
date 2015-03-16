<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: login_valida.php - Realiza funções para validar o usuário e realizar logoff.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página para realizar processo de validação do usuário.
 *    Principais funções:
 *      - Realizar validação do usuário.
 *      - Adicionar e validar as variáveis de sessão do usuário.
 *      - Remover variáveis de sessão e realizar o logoff do usuário.
 * 
 * 
 * 
 *********************************************/
if($IN_ACCORDI != true)
{
 exit();
}

if($root == "")
{
$root2 = "/";
}
else
{
$root2 = $root."/";
}


if($_GET['func'] == "login")
{
if(isset($_POST['username']))
        {
        $login = (($_POST['username'])); ;
        $senha = (($_POST['password']));
        
        $vlogin = new validate();
        $vlogin->login = $login;
        $vlogin->senha = $senha;
        $vlogin->verificaLogin();
        $vlogin->pegaInfo($vlogin->id);
        $a = $vlogin->validaSessao();
         if($a == true)
        {
          if($_COOKIE['pw-change'] == 1)
          {
             $_COOKIE['pw-change'] = "";
             setcookie("pw-change", "0", time()+2, "/");
             header("location: $root2/home/edit&npw=true");
          }
          else
          {
          if($_GET['url'] != "")
          header("location: http://".$_GET['url']);
          elseif($_POST['url'] != "")
          header("location: ".$_POST['url']);
          else
          header("location: ".$root2."".$_GET['url']);
          }
        }
        else
        {
       header("location: ".$root2."login/&m=erro&url=".$_GET['url']."");  
        }
        unset($vlogin);
        }
        else{
        header("location: ".$root2);
        
        }
}
if($_GET['func'] == "logoff")
{
   $logout = new validate();
       $logout->validaLogoff();
       if($logout == true)
           header("location: ".$root2);
       unset($logout);

}
?>