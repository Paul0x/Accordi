<?
if ($IN_ACCORDI != true)
{
   exit();
}
?>
<? if ($_SESSION['id_usuario'] != NULL)
{
?>
      
      <div class='member-menu'><div class='center2'>
            <ul class='member-menu-list'>
               <a href='<? echo $root; ?>/home/' class='member-menu-a'><li class='member-menu-list' id='member-menu-home'>Home</li></a>
               <a href='<? echo $root; ?>/portal/' class='member-menu-a'><li class='member-menu-list'>Portal</li></a>
               <a href='<? echo $root; ?>/profile/<? echo $home->login ?>' class='member-menu-a'><li class='member-menu-list' id="member-menu-profile">Perfil</li></a>
               <a href='<? echo $root; ?>/contato/' class='member-menu-a'><li class='member-menu-list'>Contatos</li></a>
               <a href='<? echo $root; ?>/musicas/' class='member-menu-a'><li class='member-menu-list' id='member-menu-mmusica'>Músicas</li></a>
               <a href='<? echo $root; ?>/eventos/' class='member-menu-a'><li class='member-menu-list'>Eventos</li></a>
               <a href='<? echo $root; ?>/playlists/' class='member-menu-a'><li class='member-menu-list'>Playlists</li></a>
               <li class='member-menu-list' id='ranking-show'>Pesquisa
                   <div id='ranking-down-menu' class='down-menu'>
                       <ul>
                           <a href='<? echo $root; ?>/site/eventos/' class='member-menu-a'><li class='menu-li'>Eventos</li></a>
                           <a href='<? echo $root; ?>/site/musicas/' class='member-menu-a'><li class='menu-li'>Músicas</li></a>
                           <a href='<? echo $root; ?>/busca/' class='member-menu-a'><li class='menu-li'>Usuários</li></a>
                       </ul>
                   </div>
               </li> 
            
               
               
            </ul>
          </div></div>
         
<?}
else
{
?>
   <div class='member-menu'><div class='center'>
         <ul class='member-menu-list'>
            <a href='<? echo $root; ?>/login/' class='member-menu-a'><li class='member-menu-list' id="member-menu-st">Logar</li></a>
            <a href='<? echo $root; ?>/portal/' class='member-menu-a'><li class='member-menu-list'>Portal</li></a>
            <a href='<? echo $root; ?>/site/musicas/' class='member-menu-a'><li class='member-menu-list'>Músicas</li></a>
            <a href='<? echo $root; ?>/site/eventos/' class='member-menu-a'><li class='member-menu-list'>Eventos</li></a>
            <li class='member-menu-list' id='ranking-show'>Rankings<div id='ranking-down-menu' class='down-menu'><ul><a href='<? echo $root; ?>/top/artista' class='member-menu-a'><li class='menu-li'>Artista</li></a><a href='<? echo $root; ?>/top/contratante' class='member-menu-a'><li class='menu-li'>Contratante</li></a></ul></div></span></li> 
            <a href='<? echo $root; ?>/cadastro/' class='member-menu-a'><li class='member-menu-list'>Cadastro</li></a>
         </ul></div></div>

<? 
}
?>
