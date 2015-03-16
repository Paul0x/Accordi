<?php
/*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: public2.php - Página pública de eventos
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página onde o usuário consegue visualizar todas as informações de um determinado evento, confirmar presença e postar comentários sobre o mesmo.
 *    Principais funções:
 *      - Mostrar informações do evento
 *      - Visualizar participantes e visitantes do evento
 *      - Confirmar participação no evento
 *      - Ler e visualizar comentários do evento
 * 
 * 
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}
$e = new eventos();
$e->id = $url[2];
$e->pegaInfo();
$v = new validate();

if($_POST['majax'] != true)
    $e->adicionaView();
if($url[3] == "visitantes" || $_POST['mode'] == "visitantes")
    $_POST['pmode'] = "visitantes";
$comentarios = new comentarios();
if($_POST['mode'] == "pm")
{
  if($_SESSION['id_usuario'] == "")
      echo "<div id='response'>2</div>";
  else
    $r = $e->manageVisitante($_POST['tipo']);
}
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<input type='hidden' id='event-id' value='<? echo $e->id;?>'/>
<input type='hidden' id='mode-page' value='<? echo $_POST['pmode']; ?>'/>
<script type='text/javascript'>b = new commentsClass(); b.reloadComments();</script>
<div id='evento-head-wrap'>
    <div id='evento-lh-wrap'>
        <div class='participantes-count'>
            <?
            // Mostramos os membros que vão participar do evento.
            $cm = $e->contaMembros(0);
            if($cm == 0)
                echo "Não existem participantes cadastrados.";
            elseif($cm == 1)
                echo "<span class='green2'>$cm</span> pessoa vai nesse evento.";
            else
                echo "<span class='green2'>$cm</span> pessoas irão nesse evento.";
            ?>
        </div>
    </div>
    <div id='evento-mh-wrap'>
        <div class='banner-e-w'><a href='<? echo "$root/site/evento/$e->id"; ?>'><?$e->mostraBanner();?></a></div>
    </div>
    <div id='evento-rh-wrap'>
        <div id='btn-p-wrap'>
        <?         
            // Verificamos se o membro já é cadastro.
            if($e->status == 'f')
                echo "<div class='e-no-participa'>Você não pode participar de um evento que já aconteceu.</div>";
            else
            {
                $ex = $v->ifExist(array($_SESSION['id_usuario'],$e->id),array("id_usuario","id_evento"),"membros_evento");
                if($ex == true)
                    echo "<div class='e-participar' id='participar-$e->id'>Participar</div>";
                else
                    echo "<div class='e-participar2' id='pcd-$e->id'>Participando</div>";
            }
        
        ?>
        </div>
    </div>
</div>
    <div id='evento-menu-wrap'>
         <a href='<? echo $root."/site/evento/".$e->id;?>'><div class='menu-box-profile2' id='evento-show-geral'>Geral</div></a>
         <a href='<? echo $root."/site/evento/".$e->id;?>/visitantes'><div class='menu-box-profile2' id='evento-show-p'>Visitantes</div></a>
    </div>
<div id='evento-body-wrap' class='default-box2'>
    <?
    if($_POST['pmode'] == "")
    {?>
    <div id='evento-lb-wrap'>
    <?
    
        /**
         *  Pega informações do contratante
         */
        $usu = new usuario($e->contratante);
        echo "<ul>";
        echo "<li class='profile-list-ul-title'>$e->nome</li>";
        echo "<li class='profile-list-ul'><label class='blue-e'>Criador:</label><a href='$root/profile/$usu->login' class='link-blue'>$usu->nome</a></li>";
        echo "<li class='profile-list-ul'><label class='blue-e'>Gênero:</label>$e->genero</li>";
        echo "<li class='profile-list-ul'><label class='blue-e'>Data:</label>$e->data com início ás $e->hora</li>";
        echo "<li class='profile-list-ul'><label class='blue-e'>Local:</label>$e->logradouro, $e->bairro - $e->cidade / ".$e->escolherEstado()."</li>";
        echo "<li class='profile-list-ul'><label class='blue-e'>Informações:</label></li>";
        echo "<li class='profile-list-ul'><p class='clear'>$e->descricao</p></li>";
        echo "</ul>";
    ?>
    </div>
    <div id='evento-rb-wrap'>
        <ul>
        <li class='profile-list-ul-title'>Mapa</li>
        <li class='profile-list-ul'>Entenda como chegar ao local do evento.</li>
        <li>
           <? echo "<input type='hidden' id='adress' value='$e->logradouro, $e->cidade ," . $e->escolherEstado() . "' />";?>
            <div id="mapevento2"></div>
        </li>
        </ul>
    </div>
<?}
  elseif($_POST['pmode'] == "visitantes")
  {?>
    <div class='gra_top1'>Todos os participantes cadastrados</div>  
    <div id='full-visitantes'>        <?
        $visitantes = $e->mostraMembros(0);
        if($visitantes != "")
        {
            $count = 0;
            echo "<ul>";
            foreach($visitantes as $i => $v)
            {
                    echo "<a href='$root/profile/$v[2]' ><li><div class='thumb-box2'>";
                if ($v[1] != "0")
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/$v[3]_thumb.$v[1]' class='thumb' alt='thumb' /></div> ";
                else
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></div>";
                echo "$v[0]<p></p>";
                echo "</div></li></a>";
                $count++;
            }
            echo "</ul>";
        }
        else
            echo "<div class='warn-e'>Nenhum participante foi confirmado para o evento</div>";
        ?>
    </div>
  <?}
?>
</div>
    <div id='evento-mb-wrap' class='default-box2'>
        <div class='gra_top1'>Atrações do evento</div>
        <?
        // Lista dos participantes no evento.
        $participantes = $e->mostraMembros(1);
        if($participantes != "")
        {
            echo "<ul>";
            foreach($participantes as $i => $v)
            {
                echo "<a href='$root/profile/$v[2]' ><li><div class='thumb-box4'>";
                if ($v[1] != "0")
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/$v[3]_thumb.$v[1]' class='thumb' alt='thumb' /></div> ";
                else
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></div>";
                echo "$v[0]<p></p>";
                echo "</div></li></a>";
            }
            echo "</ul>";
        }
        else
            echo "<div class='warn-e'>Nenhuma atração foi confirmado para o evento</div>";
        ?>
    </div>
    <?
    
    if($e->facebook != "" || $e->twitter != "" || $e->youtube != "" || $e->lastfm != "")
    {
        echo "<div id='evento-rd-wrap' class='default-box2'>";
        echo "<div class='gra_top1'>$e->nome nas redes</div>";

        // Adiciona os links para redes sociais
        if($e->facebook != "")
        {
            echo "<div id='fb-site' class='hidden'>$e->facebook</div>";
            echo "<a href='http://facebook.com/$e->facebook'><div id='fb-ev-logo'></div></a>";
        }
        if($e->twitter != "")
        {
            echo "<div id='twt-site' class='hidden'>$e->twitter</div>";
            echo "<a href='http://twitter.com/$e->twitter'><div id='twt-ev-logo'></div></a>";
        }
        if($e->youtube != "")
        {
            echo "<div id='yt-site' class='hidden'>$e->youtube</div>";
            echo "<a href='http://youtube.com/$e->youtube'><div id='yt-ev-logo'></div></a>";
        }
        if($e->lastfm != "")
        {
            echo "<div id='lm-site' class='hidden'>$e->lastfm</div>";
            echo "<a href='http://lastfm.com/event/$e->lastfm'><div id='lm-ev-logo'></div></a>";
        }

        echo "</div>";
    }
    ?>
<div id='evento-footer-wrap'>
    <div id='evento-lf-wrap' class='default-box2'>
        <div class='gra_top1'>Comentários</div>
                <div id='comentario-real-wrap'>
                  <? if($_SESSION['id_usuario'] != "")
                  { ?>
                  <div class='comment-box-p-title' id='bx-cmt'>
                      <textarea class='c-textarea' id="text-comment-n"></textarea>
                      <input type='button' value='Comentar' id='comment-button3' class='btn' />
                      <span id='c-error'>
                      <? if($adc != true && $_POST['mode2'] == 'ncomment') echo "<span class='side-tip'>Adicione um comentário válido.</span>";
                      ?>
                      </span>
                  </div>
                  <? } ?>
                  <?
                  $cm = $comentarios->listarComentario($e->id,2);
                  if($cm != ""){
                  foreach($cm as $i => $v)
                  {
                      echo "<div class='comment-box-p' id='comentario-box-t-$v[0]'>";
                      echo "<div class='title-c'><a href='$root/profile/$v[5]' class='title-c-a'>$v[7]</a> - $v[3]";
                      if($v[4] == $v[9])
                          echo "<span class='autor-badge'>Gerente do Evento</span>";
                      echo "</div>";
                      echo "<div class='box-img-c'>";
                      if ($v[6] != "0")
                      echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                      else
                      echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim'/></a></div>";
                      echo "</div>";
                      echo "<div class='comment-content2'>";
                      echo "<input type='hidden' id='content-ajax-$v[0]' value='$v[8]' />";
                      echo "<div id='content-$v[0]'>$v[2]";
                      if($ec != true && $_POST['mode2'] == 'ecomment' && $_POST['cid'] == $v[0]) echo "<p><span class='side-tip'>Falha ao alterar o comentário.</span></p>";
                      echo "</div>";
                      echo "</div>";
                      echo "<div class='comment-footer'>";
                      if($_SESSION['id_usuario'] == $v[9] || $_SESSION['id_usuario'] == $v[4])
                      {
                        
                         echo "<span class='del-comment' id='dc3-$v[0]'>Deletar</span>";
                         if($_SESSION['id_usuario'] == $v[4])
                           echo "<span class='edit-comment' id='ec3-$v[0]'>Editar</span>";
                         
                      }
                      echo "</div>";
                      echo "</div>";
                  }
                  echo "<div id='results-comments-more'><div id='results-c-m0'></div></div>";
                  if (count($cm) >= 20)
                      echo "<div class='more-comments' id='more-comments'>Mais</div>";
                  }
                  elseif($_SESSION['id_usuario'] == "" && $cm == "")
                  {
                       echo "<div class='no-comments2'>Nenhum comentário encontrado.</div>";
                  }
                  
                  ?>
                  </div>
                   <input type='hidden' id='comentario-page' value='0' />
                   <input type='hidden' id='comentario-count' value='<? echo count($cm); ?>' />
                   <input type='hidden' id='ultimo-comentario' value='<? echo $cm[0][0]; ?>' />
    </div>
    <div id='evento-rf-wrap' class='default-box3'>
        <div class='gra_top1'>Saiba os últimos que marcaram presença</div>
        <?
        $visitantes = $e->mostraMembros(0);
        if($visitantes != "")
        {
            $count = 0;
            echo "<ul>";
            foreach($visitantes as $i => $v)
            {
                if($count == 10)
                    break;
                echo "<a href='$root/profile/$v[2]' ><li><div class='thumb-box4'>";
                if ($v[1] != "0")
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/$v[3]_thumb.$v[1]' class='thumb' alt='thumb' /></div> ";
                else
                echo "<div class='thumb-img'><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></div>";
                echo "$v[0]<p></p>";
                echo "</div></li></a>";
                $count++;
            }
            echo "</ul>";
        }
        else
            echo "<div class='warn-e'>Ninguém confirmou presença no evento ainda.</div>";
        ?>
    </div>

</div>
<div class='clear'></div>