<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: home.php - Página privada para exibir informações para o usuário.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Home do usuário, utilizada para visualizar todas as informções gerencias do usuário.
 *    Principais funções:
 *      - Mostrar os útlimos eventos do contratante.
 *      - Mostrar as últimas músicas do artista.
 *      - Mostrar os últimos comentários.
 *      - Mostrar as informações do usuário.
 *      - Mostrar os eventos que acontecem na cidade do usuário.
 *      - Mostrar os 3 artistas e 3 contratantes mais altos no ranking.
 * 
 *********************************************/

if ($IN_ACCORDI != true) {
    exit();
}
$user = new usuario("");
$rankings = new ranking();
$rankings->rankingArtista();
$rankings->rankingContratante();
$musicas = new musicas();
$limusica = $musicas->carregaMusicasArtista();

echo "<input type='hidden' id='user-profile-id' value='$user->id' />";
?>
<div class='home-logado'>
    <div class='btn-link-editaprofile'><a href='<?echo $root; ?>/home/edit'>Editar Perfil</a></div>
    <div class='home-left-bar'>
    <? if($user->cidade != "") 
        {?>    
        <div class='default-box3'><div class='box-cabecalho'>Eventos em minha cidade<span class='slideDown' id='slide-box-eventos-proximos'>-</span></div><div id="box-eventos-proximos-wrapper">
                <? $e = new eventos();
                $a = $e->eventosCidade();
                
                 ?>
                       <ul>
         <?
         if ($a != "") {
            $count = 0;
            foreach($a as $key => $info) {
               if($count == 5)
                   break;
               
               echo "<a href='$root/site/evento/$info[0]' class='nochange2'>";
               echo "<li class='list-eventos'>";
               echo "<div class='left'>";
               if($info[5] != '0')
                   echo "<img src='$root/imagens/evento/$info[0]_thumb.$info[5]' class='banner-evento2' alt='$info[1]' />";
               else
                   echo "<img src='$root/imagens/evento/nobanner_thumb.png' class='banner-evento2' alt='$info[1]' />";
               echo "</div>";
               echo "<div class='evento-show-home'>";
               echo "<div class='title-event'>" . $info[1] . "</div>";
               echo $info[2];
               echo "<p>$info[4] ás $info[6]</p>";
               echo "</div>";
               echo "</li>";               
               echo "</a>";
               $count++;
            }
         }
         else
            echo "<li class='list-eventosn'><div class='title-event'>Sem eventos na sua cidade <p><a href='$root/site/eventos' class='nochange2'>Visualizar outros eventos...</a></p></div>";
         unset($e);
         ?>
      </ul>
            </div></div>
        <? } ?>
        
        <?
        
        
        // Músicas relacionadas com o usuário
        
        try
        {
            $musicas_relacionadas = $musicas->musicasRelacionadas($_SESSION['id_usuario']);
            echo "<div id='home-musicas-relacionadas' class='default-box3'>";
            echo "<div class='gra_top1'>Músicas para você</div>";
            echo "<ul>";
            foreach($musicas_relacionadas as $i => $v)
            {
                echo "<li class='mer-li' id='mer-".$v['id']."'>";
                echo "<a href='$root/site/musica/".$v['id']."'>";
                echo "<div class='mer-list'>";
                echo "<div class='mer-icon'></div>";
                echo "<div class='mer-title'>".$v['nome']."</div>";
                echo "<div class='mer-info'>".$v['genero']." - ".$v['duracao']."</div>";
                echo "</div>";
                echo "</a>";
                echo "<div class='mer-add-pl' id='mer-add-".$v['id']."'>Playlist</div>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        catch(Exception $a)
        { }
        
        // Eventos que o usuário participa
        
        $e = new eventos();
        try
        {
            $eventos_participa = $e->eventosAll(0,$_SESSION['id_usuario'],0);
            echo "<div id='home-eventos-participa' class='default-box3'>";
            echo "<div class='gra_top1'>Meus Eventos Planejados</div>";
            echo "<div id='mel-wrap'>";
            foreach($eventos_participa as $i => $v)
            {
                echo "<a href='$root/site/evento/".$v['id']."'><div class='me-list'>";
                echo "<div class='mel-title'>".$v['nome']."</div>";
                echo "<div class='mel-data'>".$v['data']."</div>";
                echo "</div></a>";
            }
            echo "</div>";
            echo "<div class='mel-more'>Mais</div>";
            echo "<input type='hidden' id='mel-page' value='".count($eventos_participa)."' />";
            echo "</div>";
        }
        catch(Exception $a){}
        
        unset($e,$eventos_participa);
                
        ?>
        
        <div class='default-box3'><div class='box-cabecalho'>Melhores Artistas<span class='slideDown' id='slide-box-ranking-artista'>-</span></div>
            <div id="box-ranking-artista-wrapper">    <ul class='ranking-artista-home'><?
if ($rankings->erro[0] == null) {
    foreach ($rankings->rartista as $pos => $info) {
        $pos = $pos + 1;
        if ($pos == 4) {
            break;
        }
        echo "<a href='$root/profile/$info[4]'><li class='ranking-artista-home' ><div class='ranking-home-list'>$pos</div>" . $info[0] . "</li></a>";
        
    }
    echo "<a href='$root/top/artista/'><div class='home-more-ranking'>Ranking Completo</div></a>";
} else {
    echo "<li class='ranking-artista-home-error'>Ranking Vazio</li>";
}
?></ul>
         </div></div>
        <div class='default-box3'><div class='box-cabecalho'>Melhores Contratantes<span class='slideDown' id='slide-box-ranking-contratante'>-</span></div>
          <div id="box-ranking-contratante-wrapper">  <ul class='ranking-artista-home'><?
                if ($rankings->erro[1] == null) {
                    foreach ($rankings->rcontratante as $pos => $info) {
        $pos = $pos + 1;
        if ($pos == 4) {
            break;
        }
         echo "<a href='$root/profile/$info[4]'><li class='ranking-artista-home' ><div class='ranking-home-list' >$pos</div>" . $info[0] . "</li></a>";
        
                    }
                    echo "<a href='$root/top/contratante/'><div class='home-more-ranking'>Ranking Completo</div></a>";
                } else {
        echo "<li class='ranking-artista-home-error'>Ranking Vazio</li>";
                }
?></ul>
          </div>
        </div>
        
         <?
if($user->twitter != "")
{
  ?>
       <div id='general-twitter-profile' class='default-box1'>
           <div class='gra_top1'>Twitter<span class='slideDown' id='slide-box-twitter-profile'>-</span></div>
           <div id='box-twitter-profile-wrapper'>
            <script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 3,
  type: 'profile',
  rpp: 3,
  interval: 6000,
  width: 197,
  height: 150,
    theme: {
    shell: {
    background: '#0e90d0',
    color: '#ffffff'
    },
    tweets: {
    background: '#0e99d6',
    color: '#ffffff',
    links: '#005f7a'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('<? echo $user->twitter; ?>').start();
</script>
           </div>
       </div>
            <?
}
?>
    </div>
    <div class='home-center-bar'>
        <?
        if ($_SESSION['tipo_usuario'] == 1) {
            ?>

            <div class='default-box3'><div class='box-cabecalho'>Suas Músicas Recentes<span class='slideDown' id='slide-box-musicas-recentes'>-</span></div>
               <div id="box-musicas-recentes-wrapper"> <ul class='music-lista-ul'>
                    <?
                    if ($limusica == true) {
                      $i = 0;
                        foreach ($musicas->listamusicas as $key => $musica) {
                            if($key == 5)
                            {
                              break;  
                            }
                            echo "<a href='" . $root . "/site/musica/" . $musica[0] . "'><li class='music-lista'><div class='music-list-div-title-home2' >" . $musica[1] . "</div><div class='music-list-div-title3'>$musica[2] - " . $musica[3] . " - $musica[4]</div></li></a>";
                            $i++;
                            
                        }
                    }
                    else
                    echo "<li class='music-list-div-title-warning'><div class='music-list-div-title-warning' >Você ainda não possui nenhuma música em sua conta! <p><a href='$root/musicas/'class='nochange2'><strong>Clique aqui para criar uma</strong></a></p></div></li>";   
                    ?>
                </ul>
            </div></div>
           
    <?
} elseif ($_SESSION['tipo_usuario'] == 2) {
    ?>
            <div class='default-box3'><div class='box-cabecalho'>Seus Eventos Recentes<span class='slideDown' id='slide-box-eventos2-recentes'>-</span></div><div id="box-eventos2-recentes-wrapper">
                  <ul class='music-lista-ul'>
                    <?
                    $evento = new eventos();
                    $a = $evento->listaEventos("");
                    if ($a == true) {
                        foreach ($a as $key => $evento) {
                            if($key == 5)
                            {
                              break;  
                            }
                            echo "<a href='" . $root . "/site/evento/" . $evento[0] . "'><li class='music-lista'><div class='music-list-div-title-home' >" . $evento[1] . "</div><div class='music-list-div-home'>" . $evento[2] . "</div><div class='music-list-div-home'>" . $evento[3] . "</div><div class='music-list-div-home'>" . $evento[5] . "</div></li></a>";
                            $i++;
                            
                        }
                    }
                    else{
                        echo "<li class='music-list-div-title-warning'><div class='music-list-div-title-warning' >Você ainda não possui nenhuma evento em sua conta! <p><a href='$root/eventos/' class='nochange2'><strong>Clique aqui para criar um</strong></a></p></div></li>";   
                    }
                    ?>
                </ul>
               </div></div>
                <?
} ?>
      <div class='default-box3'><div class='box-cabecalho'>Seus Contatos Recentes<span class='slideDown' id='slide-box-contratos-recentes'>-</span></div>
                <div id="box-contratos-recentes-wrapper">
                        <?
                        $c = new contato();
                        $c->getUser($user->id);
                        $c_list = $c->listar_contatos(4,$user->tipo);
                        if($c_list != false)
                        {
                        foreach($c_list as $i => $v)
                        {
                        if($count == 5)
                        break;
                        /**
                        *  0 - id
                        *  1 - id_remetente
                        *  2 - id_receptor
                        *  3 - assunto
                        *  4 - data
                        *  5 - valor
                        *  6 - descricao
                        *  7 - id_artista
                        *  8 - id-contratante
                        *  9 - termos
                        *  10 - status
                        *  11 - data_insercao
                        *  12 - nome_remetente
                        *  13 - nome_receptor
                        *  14 - nome_artista
                        *  15 - nome_contratante
                        *  16 - status_string
                        *  17 - login_artista
                        *  18 - login_contratante
                        */
                        $c->getId($v[3]);
                        $info = $c->pegarInfo();
                        if($user->tipo == 1)
                        $lg = $info[18];
                        else
                        $lg = $info[17];
                        echo "<a href='" . $root . "/profile/" . $lg . "'><li class='music-lista'><div class='music-list-div-title-home2' >" . $info[3] . "</div><div class='music-list-div-title3'>Artista: $info[14] - Contratante:" . $info[15] . " - $info[4]</div></li></a>";
                        $count++;
                        }
                        }
                        else
                        {
                        echo "<li><div class='music-list-div-title-warning' >$user->nome não possui nenhum contato recente.</div></li>";    
                        }
                        unset($c,$clist);
                        ?>
                    
                </div></div>
      <div id='comentario-info-profile2' class='default-box3'>
                  <div id='info-comentario-wrap' class='box-cabecalho'>Feed<span class='slideDown' id='slide-box-comentarios-h'>-</span></div>   
                  <div id='box-comentarios-h-wrapper'>
                  <div id='comentario-real-wrap'>
                  <?
                      // Carrega a interface dos feeds
                  
                  try
                  {
                      $atualizacao = new atualizacoes();
                      $atualizacao->getId($user->id);
                      $atualizacao->loadFeed();
                      $feed_list = $atualizacao->loadAtualizacoes(0,true);
                      echo "<script type='text/javascript'>";
                      echo "$(document).ready(function () {";
                      echo "var fc = new feedClass();";
                      echo "fc.newestFeed();";
                      echo "})";
                      echo "</script>";
                      echo "<div id='newest-feed-wrap'>";
                      echo "<input type='hidden' id='nf-number' value='0' />";
                      echo "<div id='newest-feed-content'>";
                      echo "</div>";
                      echo "</div>";
                      echo "<div id='feed-list-true-wrap'>";
                      foreach ($feed_list as $i => $k)
                      {
                          switch ($k['tipo_feed'])
                          {
                              // Forma do array:
                              //   [0] => tipo_atualizacao
                              //   [1] => nome_usuario
                              //   [2] => id_indicador
                              //   [3] => campo_atualizacao
                              //   [4] => data_formatada
                              //   [5] => imagem_usuario 
                              //   [6] => id_usuario
                               case "0":
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " criou a música ";
                                  echo "<a href='$root/site/musica/".$k['id_musica']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_musica']."</strong>";       
                                  echo "<img src='$root/imagens/site/mu_ico.png' class='ffl-ico' alt='Música' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case "1":
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " criou o evento ";
                                  echo "<a href='$root/site/evento/".$k['id_evento']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_evento']."</strong>";       
                                  echo "<img src='$root/imagens/site/ev_ico.png' class='ffl-ico' alt='Evento' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case "2":
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " criou a playlist ";
                                  echo "<a href='$root/playlists/".$k['id_playlist']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_playlist']."</strong>";       
                                  echo "<img src='$root/imagens/site/pl_ico.png' class='ffl-ico' alt='Música' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case "31":
                                  switch($k['tipo_recado'])
                                  {
                                     case 0:
                                         $recado_sintax = array("no perfil de","profile");
                                         break;
                                     case 1:                                  
                                         $recado_sintax = array("na música","site/musica");
                                         break;
                                     case 2:                                  
                                         $recado_sintax = array("no evento","site/evento");
                                         break;
                                     case 4:
                                         $dn = explode("-",$k['data_original']);
                                         $recado_sintax = array("na notícia","portal/show/$dn[0]/$dn[1]");
                                         break;                           
                                  }
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " criou um recado $recado_sintax[0] ";
                                  echo "<a href='$root/$recado_sintax[1]/".$k['identificacao_receptor']."/comentarios/#content-".$k['id_recado']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_receptor']."</strong>";       
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                                  echo "</a>";
                                  echo "<div class='ffl-recado-text'>";
                                  echo $k['texto_recado'];
                                  echo "</div>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;  
                              case '32':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " recebeu um recado de ";
                                  echo "<a href='$root/profile/".$k['identificacao_criador']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_criador']."</strong>";       
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                                  echo "</a>";
                                  echo "<div class='ffl-recado-text'>";
                                  echo $k['texto_recado'];
                                  echo "</div>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '4':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " confirmou participação no evento ";
                                  echo "<a href='$root/site/evento/".$k['id_evento']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_evento']."</strong>";       
                                  echo "<img src='$root/imagens/site/ev_ico.png' class='ffl-ico' alt='Evento' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '5':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " criou um curriculum. ";      
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Curriculum' />";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '6':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " atualizou seu curriculum. ";      
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Curriculum' />";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '7':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " avaliou a música ";
                                  echo "<a href='$root/site/musica/".$k['id_musica']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_musica']."</strong>";       
                                  echo "<img src='$root/imagens/site/mu_ico.png' class='ffl-ico' alt='Música' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '8':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " fechou contato com ";
                                  echo "<a href='$root/profile/".$k['login_usuario2']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_usuario2']."</strong>";       
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                              case '9':
                                  echo "<div class='feed-full-list2'>";
                                  echo "<div class='ffl-imagem'>";
                                  if($k['imagem_usuario'] == '0')
                                      echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                                  else
                                      echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                                  echo "</div>";
                                  echo "<div class='ffl-content'>";
                                  echo "<a href='$root/profile/".$k['login_usuario']."'><div class='ffl-title'>".$k['nome_usuario']."</div></a>";
                                  echo "<div class='ffl-itens'>";
                                  echo " está acompanhando ";
                                  echo "<a href='$root/profile/".$k['login_usuario2']."' class='nochange2'>";
                                  echo "<strong>".$k['nome_usuario2']."</strong>";       
                                  echo "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                                  echo "</a>";
                                  echo "</div>";
                                  echo "</div>";
                                  echo "<div class='ffl-date'>".$k['data_formatada']."</div>";
                                  echo "<div class='clear'></div>";
                                  echo "</div>";
                                  break;
                          }
                      }
                      echo "</div>";
                      $first_feed = array_pop($feed_list);
                      $first_feed = $first_feed['id_feed'];
                      echo "<input type='hidden' id='fi-feed' value='".$first_feed."' />";
                      $last_feed = $feed_list[0]['id_feed'];
                      echo "<input type='hidden' id='lastf-value' value='".$last_feed."' />";
                      echo "<div id='more-full-feed'>Carregar mais atualizações.</div>";
                  }
                  catch(Exception $a)
                  {
                     echo "<div class='no-comments2'>Nenhum feed encontrado.</div>";
                  }
                  
                  
                  ?>
                  </div>
                  </div>
               </div>     
    </div>
    <div class='home-right-bar'>
        <div class='box-home-info'><div class='box-cabecalho'>Informações<span class='slideDown' id='slide-box-home-info'>-</span></div><div id="box-home-info-wrapper">
            <div class='home-pic'><? echo $user->mostrarImagem(); ?></div>
            <ul class='info-home-lista'>
                <li class='p-general-info-t'>Geral</li>
                <li class='info-home-lista'><span class='bold-info-home'>Nome</span> <? echo $user->nome; ?> </li>
<? if ($user->tipo == 1) { ?>
                  <? if($user->sobrenome != "") { echo "<li class='info-home-lista'><span class='bold-info-home'>Sobrenome</span> $user->sobrenome</li>"; } ?>
                    <? if ($user->calcularIdade() != false) { ?> <li class='info-home-lista'><span class='bold-info-home'>Idade</span><? echo $user->calcularIdade() . " anos";
                    } ?>
                    <li class='info-home-lista'><span class='bold-info-home'>Sexo</span> <? echo $user->calcularSexo(); ?></li>
                    
                    <? if ($user->apelido != NULL) { ?> <li class='info-home-lista'><span class='bold-info-home'>Pseudonimo</span> <? echo $user->apelido; ?></li> <? } ?>
                <? } ?>
                <li class='p-general-info-t'>Contato</li>
                <li class='info-home-lista'><span class='bold-info-home'>Telefone 1</span> <? echo $user->telefone1; ?></li>
<? if ($user->telefone2 != NULL) { ?> <li class='info-home-lista'><span class='bold-info-home'>Telefone 2</span> <? echo $user->telefone2; ?></li> <? } ?>
                <? if ($user->logradouro != NULL || $user->bairro != NULL || $user->cidade != NULL || $user->estado != "0") { ?>  <li class='p-general-info-t'>Localização</li> <? } ?>
                <? if ($user->logradouro != NULL) { ?> <li class='info-home-lista'><span class='bold-info-home'>Endereço</span> <? echo $user->logradouro; ?></li><? } ?>
                <? if ($user->bairro != NULL) { ?> <li class='info-home-lista'><span class='bold-info-home'>Bairro</span> <? echo $user->bairro; ?></li><? } ?>
                <? if ($user->cidade != NULL) { ?> <li class='info-home-lista'><span class='bold-info-home'>Cidade</span> <? echo $user->cidade; ?></li><? } ?>
                <? if ($user->estado != "0") { ?> <li class='info-home-lista'><span class='bold-info-home'>Estado</span> <? echo $user->escolherEstado(); ?></li><? } ?>



            </ul>

            <div class='box-cabecalho'><a href='<? echo $root; ?>/home/edit'>Editar Perfil -></a></div></div> </div>
        <div class='default-box3' id='c-box'>
            <?
            /*
             * Calendário - Mostra todos os eventos do membro durante o mês atual. 
             */
            $event = new eventos();
            $eventos_mes = $event->eventosMes(0);
            $e = new dataformat;
            $e->pegarData(date("Y")."-".date("m")."-01");
            $wd = $e->pegarWeekDay();
            $mes_nome = $e->nomeMes();
            $max_day = $e->pegarMaxMes(date("m"));
            $count_day = 1;
            $count_mes = date("m");
            if(strpos($count_mes,"0") === 0)
                    $count_mes = $count_mes[1];
            echo "<input type='hidden' value='".$count_mes."' id='mes-atual'>";
            echo "<input type='hidden' value='".date("Y")."' id='ano-atual'>";
            echo "<div class='gra_top1'><span id='calendario-back'><div id='seta_2'></div></span><strong>$mes_nome / ".date("Y")."</strong><span id='calendario-next'><div id='seta_1'></div></span></div>";
            
               echo "<div class='calendario-space-t'>D</div>";
               echo "<div class='calendario-space-t'>S</div>";
               echo "<div class='calendario-space-t'>T</div>";
               echo "<div class='calendario-space-t'>Q</div>";
               echo "<div class='calendario-space-t'>Q</div>";
               echo "<div class='calendario-space-t'>S</div>";
               echo "<div class='calendario-space-t'>S</div>";
               for($row_mes=0;$row_mes<=5;$row_mes++)
               {
                   for($col_mes=0;$col_mes<=6;$col_mes++)
                   {
                      if($row_mes == 0 && $col_mes < $wd[0])
                          echo "<div class='calendario-space-w'></div>";
                      else
                      {
                          if($eventos_mes[$count_day] != "")
                          echo "<div class='calendario-space-n' id='c-tt-ajax-$count_day'>$count_day</div>";
                          else
                          echo "<div class='calendario-space'>$count_day</div>";
                          $count_day++;
                          if($count_day > $max_day)
                              break;
                      }

                   }
                   if($count_day > $max_day)
                   break;
               }
            
            
            ?>
         </div>
        <?
       /*
        *  Vamos carregar as informações dos eventos mensais aqui e carregar pelo toolTip utilizando javascript.
        */
        unset($i,$v);
        if($eventos_mes != "")
        {
        foreach($eventos_mes as $i => $v)
        {
            echo "<div class='hidden' id='hide-e-c-$i'>";
            echo "<ul>";
                echo "<li><div class='gra_top1'>Eventos do dia</div>";
                foreach($v as $iv => $vv)
                {
                    echo "<li class='padding-list'><a href='".$_SESSION['root']."/site/evento/$vv[3]' class='link-blue'>$vv[0]</a> ás $vv[2]</li>";
                }
            echo "</ul>";    
            echo"</div>";
        }
        }
        unset($eventos_mes);
        ?>
    </div>
      
    <!-- Sistema de abas -->
    <script type='text/javascript'>
        desceAbas();
    </script>
    <!-- /sistema de abas -->
</div>
<div class='clear-footer'></div>