<?
if($IN_ACCORDI != true)
{
 exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="title" content="Accordi" />
<meta name="description" content="accordi é um portal de negócios com foco em música. encontre artistas e contratantes para firmar parcerias" />
<meta name="keywords" content="accordi,social,network,musica,musicas,contratos,artistas,contratantes,musical,perfil,acorde,acordi" />
<meta name="autor" content="Paulo Felipe" />
<meta name="company" content="Accordi" />
<meta name="revisit-after" content="1" />
<link rev="made" href="mailto:paul.0@live.de" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?
if($_POST['majax'] == false)
{
?>
<link rel="stylesheet" href="<? echo $root; ?>/css/core.css?v3" type="text/css" media="screen" charset="utf-8" />
<script type='text/javascript' src='<?echo $root;?>/js/jquery.js'></script>
<script type='text/javascript' src='<?echo $root;?>/js/jquery-ui.js'></script>
<script type='text/javascript' src='<?echo $root;?>/js/jquery.qtip-1.0.0-rc3.min.js'></script>
<script type='text/javascript' src='<?echo $root;?>/js/mask.js'></script>
<script type='text/javascript' src='<?echo $root;?>/js/functions.js?v2.9'></script>
<script type='text/javascript' src='<?echo $root;?>/js/core.js?1.9'></script>
<!--[if IE 8]><link rel="stylesheet" href="<? echo $root; ?>/css/core-ie.css" type="text/css" media="screen" charset="utf-8" /><![endif]-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25866299-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<? } ?>
<title><? include("title.php"); ?></title>
</head>
<body>
    
<div id='error-log'></div>
<noscript>
        <meta http-equiv=refresh content="0; URL=http://lmgtfy.com/?q=How+to+enable+my+javascript." />
</noscript>
      <div class='opc-box'></div>
      <div id='drop-down-notifications'></div>
<div class='login-hidden'> 
</div>
   <?
   if($_SESSION['id_usuario'])
   {
   $home = new usuario("");
   if($home->facebook != "" && $_SESSION['fb_142206992525179_access_token'] == "")
   {
       $appfb = new snapps();
       if($_POST['access_token'] == "")
       $appfb->jsfbGetTokenID();
       else
       $appfb->getToken($_POST['access_token']);
   }
   }
   ?>
<div class='header'></div><input type="hidden" value="<? echo $root; ?>" id="dir-root" />
<div id='true-wrap'>
<div class='header-content'>
   <? if($_SESSION['id_usuario']) { ?>
<div class='left-login-info'>Você está logado como <strong><? echo "$home->nome"; ?></strong>  <a href='<? echo $root; ?>/valida/&func=logoff' class='nochange'><span class='logout-title'>Sair</span></a></div>
<? } else {?>
<div class='left'>

<div class='login-show'>Logar</div><a href='<? echo $root; ?>/cadastro/' class='nochange'><div class='cadastro-show'>Cadastrar</div></a></div>
<?}?>
<?
if($_SESSION['id_usuario'] != "")
{
    $c = new contato();
    $c->getUser($_SESSION['id_usuario']);
    $count = $c->countNotificacoes();
    if($count != 0)
    {
        if($count == 1) $nt = "notificação";
        else $nt = "notificações";
        echo "<div class='notifica-btn' id='nt-1'>Você tem $count $nt.</div>";
        unset($nt,$count,$c,$home,$appfb);
    }
}

?>
<div class='busca-rapida'><form method='get' id="query-form-busca" action='<? echo $root; ?>/busca'>
<ul class='busca-rapida'>
<li class='busca-rapida'><div class='busca'><button type='submit' id='busca-button' class='busca-rapida'></button><input type='text' name='s' id='query' class='busca-rapida' value=''/><div class='search-overlay1'>Pesquisar Usuários</div></div></form></li>
</ul>
</div>
</div>
   <? if($_SESSION['id_usuario'] == "")
   {?>
     <div id='banner-div2'><a href="<? echo $root ?>/"><img src="<? echo $root; ?>/imagens/banner0.png" alt="Accordi" class='no-outline' /></a>
         <? 
         $v = new validate();
         $total = $v->countUser(1)+$v->countUser(2);
         if($total == '1')
             $s = array("tem","");
         else
             $s = array("são","s");
         ?>
    </div>   
    <div class='announce-header'>Já <? echo $s[0]; ?> <? echo $total;?> usuário<? echo $s[1];?> participando. <a class='nochange' href='<? echo $root; ?>/cadastro'><span class='blue'>Cadastre-se</span></a> agora.</div>
    
   <?}
   else{
   ?>   
   <div id='banner-div2'><a href="<? echo $root ?>/"><img src="<? echo $root; ?>/imagens/banner0.png" alt="Accordi" class='no-outline' /></a></div>
   <? } ?>

<div class='menu-header'>
<? include("menu.php");
?>
</div>
<div id="wrap">
