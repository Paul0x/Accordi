<?
if ($IN_ACCORDI != true) {
    exit();
}
?>
<? if( $_SESSION['id_usuario'] != NULL) {$home = new usuario("");

if($_SESSION['tipo_usuario'] == 1 ){?>
<div class='member-menu'><div class='left'>Bem Vindo, <? echo $home->nome; ?></div><div class='right'> <ul class='member-menu-list'><a href='<? echo $root; ?>/profile/<? echo $home->login ?>' class='member-menu-a'><li class='member-menu-list'>Perfil</li></a><a href='<? echo $root; ?>/home/' class='member-menu-a'><li class='member-menu-list'>Home</li></a><a href='<? echo $root; ?>/musicas/' class='member-menu-a'><li class='member-menu-list'>Músicas</li></a><a href='<? echo $root; ?>/home/edit/' class='member-menu-a'><li class='member-menu-list'>Contratos</li></a> <a href='<? echo $root; ?>/home/edit/' class='member-menu-a'><li class='member-menu-list'>Editar Perfil</li></a> </ul></div></div>
<? }
if($_SESSION['tipo_usuario'] == 2 ){
    ?>
<div class='member-menu'><div class='left'>Bem Vindo, <? echo $home->nome; ?></div><div class='right'> <ul class='member-menu-list'><a href='<? echo $root; ?>/profile/<? echo $home->login ?>' class='member-menu-a'><li class='member-menu-list'>Perfil</li></a><a href='<? echo $root; ?>/home/' class='member-menu-a'><li class='member-menu-list'>Home</li></a><a href='<? echo $root; ?>/eventos/' class='member-menu-a'><li class='member-menu-list'>Eventos</li></a><a href='<? echo $root; ?>/home/edit/' class='member-menu-a'><li class='member-menu-list'>Contratos</li></a> <a href='<? echo $root; ?>/home/edit/' class='member-menu-a'><li class='member-menu-list'>Editar Perfil</li></a> </ul></div></div>
<?
}
} ?>