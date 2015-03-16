<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: events.php - Página privada de eventos
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página particular do evento, onde o usuário pode realizar alterações nos eventos e visualizar os visitantes/participantes recentes.
 *    Principais funções:
 *      - Listar e visualizar os eventos do membro.
 *      - Mostrar informações do evento.
 *      - Realizar funções de edição ao evento.
 * 
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}
   $eventos = new eventos();
   
   // Função acionado ao submit do formulário para adicionar um novo evento.
   if ($_POST['adbutn'])
   {
      $apost = $eventos->adicionaEvento($_POST['nome'], $_POST['n-dia'], $_POST['n-mes'], $_POST['hora'], $_POST['minuto'], $_POST['descricao'], $_POST['logradouro'], $_POST['bairro'], $_POST['cidade'], $_POST['estado'], $_FILES['banner-e'], $_POST['participantes'], $_POST['genero']);
   }
   
   // Função acionada para realizar a adição/edição de redes sociais no evento.
   if ($_POST['mode'] == "rbuttonacao")
   {
      $eventos->id = $_POST['id'];
      $a = $eventos->pegaInfo();
      if ($a == true)
      {
         $apost = $eventos->addRede($_POST['param'][0], $_POST['param'][1], $_POST['param'][2]);
         if ($apost == "true")
         {
            echo "<div id='response'><span class='side-tip-ok'>Redes Adicionadas</span></div>";
         }
         else
         {
            echo "<div id='response'><span class='side-tip'>Falha ao alterar rede</span></div>";
         }
      }
      else
         echo "<div id='response'><span class='side-tip'>Falha ao alterar rede</span></div>";
   }
   
   // Função acionada para edição do banner do evento via requisição AJAX.
   if($_FILES['banner-e'])
   {
     $a = $eventos->createBanner($_FILES['banner-e'],$_POST['data-info-e']);
   }
   
   // Função acionada durante o submit para edição do evento via requisição AJAX.
   if ($_POST['mode'] == "editbuttone")
   {
      $param = $_POST['param'];
      $eventos->id = $_POST['id'];
      $eventos->pegaInfo();
      $edev = $eventos->editaEvento($param[0], $param[1], $param[2], $param[3], $param[4], $param[5], $param[6], $param[7], $param[8], $param[9], $param[10], $param[11], $param[12]);
      if ($edev == "true")
         echo "<div id='response'><span class='side-tip-ok'>Evento atualizado com sucesso.</span></div>";
      else
         echo "<div id='response'><span class='side-tip'>Falha ao atualizar evento.</span></div>";
   }
   $lista = $eventos->listaEventos("");
   
   
   
   /*
    *  Início do layout
    */
   
   ?>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
   <div class='opc-box'></div>
   <div class='ajax-box-edit'></div>
   <?
   if ($_POST['hora'])
   {
      if ($apost != "true")
         echo "<span class='side-tip'>$apost</span>";
      else
         echo "<span class='side-tip-ok'>Evento Adicionado</span>";
      echo "<div class='clear'></div>";
   }
   ?>
   <div id='main-evento-wrapper'>
      <div id='main-load-wrapper'>
         <div class='default-box1' id='meus-eventos'>
            <div class='box-cabecalho'>Eventos Atuais</div>
            <ul>
               <?
               if ($lista[0] != "")
               {
                  foreach ($lista as $key => $info)
                  {
                     if ($key >= 6)
                        break;
                     echo "<li class='list-eventos' id='evento-list-small-$info[0]'><a href='$root/eventos/$info[0]' class='nochange'><div class='title-event'>" . $info[1] . "</div>";
                     echo $info[2];
                     echo " ás " . $info[3] . "</a><img src='$root/imagens/site/excluir.png' alt='Excluir' evento='$info[0]' id='devento-$info[0]'  class='e-show-del' /></li>";
                  }
               }
               else
                  echo "<li class='list-eventos'><div class='title-event'>Sem Eventos</div>";
               ?>
            </ul>
            <a class='event-link' href="<? echo $root; ?>/eventos/"><div class='evento-link-1'>Ver Todos</div></a>
            <a class='event-link' href='#' id='nevento'><div class='evento-link-1'>Criar Evento</div></a>
           </div>

         <div class='evento-wrapper'>
            <div class='default-box1' id='evento-info'>
               <? if ($url[1] != "")
               { ?>
                  <div class='evento-show-info'><div class='box-cabecalho'>Informações do evento:</div>
                    <?
                     if ($url[1] != "")
                     {
                        $evento = new eventos();
                        $evento->id = $url[1];
                        echo "<input type='hidden' id='data-info' value='$evento->id' />";
                        if ($evento->pegaInfo() == true)
                        {
                           if ($url[2] == "" || $url[2] == "geral")
                           {
                              echo "<div class='evento-wrap-info'>";
                              $evento->mostraBanner();
                              echo "<ul class='ei-li-1'>";
                              echo "<li><label  class='label-evento-i'>Data:</label> $evento->data</li>";
                              echo "<li><label  class='label-evento-i'>Horário de Início:</label> $evento->hora</li>";
                              echo "<li><label  class='label-evento-i'>Gênero:</label> $evento->genero</li>";
                              echo "<li><label  class='label-evento-i-d'>Localização:</label></li>";
                              echo "<li><p>$evento->logradouro, bairro $evento->bairro. </p><p> $evento->cidade, " . $evento->escolherEstado() . "</p></li>";
                              echo "<li><label  class='label-evento-i-d'>Descrição</label></li>";
                              echo "<li><p>$evento->descricao</p></li>";
                              echo "</ul>";
                              echo "
                               <ul class='ei-li-2'>
                               <li><div  class='label-evento-i-d'>Twitter:</div> $evento->twitter</li>
                               <li><div  class='label-evento-i-d'>Facebook:</div> $evento->facebook</li>
                               <li><div  class='label-evento-i-d'>Youtube:</div> $evento->youtube</li>
                               </ul>";
                              echo "</div>";
                              echo "<a href='#' id='addrede' evento='$evento->id' class='event-link'><div class='btn-e'>Adicionar Redes Sociais</div></a>";
                              echo "<a href='#' id='editevento' evento='$evento->id' class='event-link'><div class='btn-e'>Editar Evento</div></a>";
                              
                           }
                           if ($url[2] == "atracoes")
                           {
                              echo "<div class='info'>Visualiza que atrações você confirmou no seu evento.</div>";

                              $a = $evento->mostraMembros(1);
                              if ($a != false)
                              {
                                 echo "<div class='lista-participantes-box'>";
                                 echo "<ul>";
                                 foreach ($a as $key => $user)
                                 {
                                    echo "<li><div class='thumb-box'>";
                                    echo "<div class='e-del-p'><a href='#' class='delparticipante' id='delparticipante-$user[3]-b'> <img src='$root/imagens/site/excluir_2.png'/></a></div>";
                                    if ($user[1] != "0")
                                       echo "<div class='thumb-img'><a href='$root/profile/$user[2]' ><img src='$root/imagens/profiles/$user[3]_thumb.$user[1]' class='thumb' alt='thumb' /></a></div> ";
                                    else
                                       echo "<div class='thumb-img'><a href='$root/profile/$user[2]' ><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></a></div>";
                                    echo "$user[0]<p></p>";
                                    echo "</div></li>";
                                 }
                                 echo "</ul>";
                                 echo "</div>";
                              }
                              else
                                 echo "<div class='music-list-div-title-warning'>Você não realizou cadastro de atrações no evento.</div>";
                           }
                           if ($url[2] == "visitantes")
                           {
                              echo "<div class='info'>Saiba quem vai comparecer no seu evento!</div>";
                              $a = $evento->mostraMembros(0);
                              if ($a != false)
                              {
                                 echo "<ul>";

                                 foreach ($a as $key => $user)
                                 {
                                    echo "<li><a href='$root/profile/$user[2]' ><div class='thumb-box'>";
                                    if ($user[1] != "0")
                                       echo "<div class='thumb-img'><img src='$root/imagens/profiles/$user[3]_thumb.$user[1]' class='thumb' alt='thumb' /></div> ";
                                    else
                                       echo "<div class='thumb-img'><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></div>";
                                    echo "$user[0]";
                                    echo "</div></a></li>";
                                 }

                                 echo "</ul>";
                              }
                              else
                                 echo "<div class='music-list-div-title-warning'>Ninguém se inscreveu para seu evento.</div>";
                           }
                           if ($url[2] == "localizacao")
                           {
                              echo "<h6>Localização</h2>";
                              echo "<ul class='localiza-list'>";
                              echo "<li class='localiza-list'><label class='list-label'>Endereço</label> $evento->logradouro</li>";
                              echo "<li class='localiza-list'><label class='list-label'>Bairro</label> $evento->bairro</li>";
                              echo "<li class='localiza-list'><label class='list-label'>Cidade</label> $evento->cidade</li>";
                              echo "<li class='localiza-list'><label class='list-label'>Estado</label> " . $evento->escolherEstado() . "</li>";
                              echo "</ul>";
                              echo "<input type='hidden' id='adress' value='$evento->logradouro, $evento->cidade ," . $evento->escolherEstado() . "' />";
                              ?><div id='mapevento-wrapper'><h6>Mapa</h6>
                                 <div id="mapevento"></div></div>
                              <?
                           }
                        }
                     }
                     else
                     {
                        ?>
                        Evento não disponível.
                        <?
                     }
                     ?>

                  </div>
                  <div class='evento-menu-info'>
                     <div class='box-cabecalho'>Menu</div>
                     <? echo "<ul class='menu-evento'>
                <a href='$root/eventos/$evento->id/geral'><li class='menu-evento'>Descrição</li></a>
                <a href='$root/eventos/$evento->id/localizacao'><li class='menu-evento'>Localização</li></a>
                <a href='$root/eventos/$evento->id/atracoes'><li class='menu-evento'>Atrações</li></a>
                <a href='$root/eventos/$evento->id/visitantes'><li class='menu-evento'>Visitantes</li></a>
                </ul>" ?>
                     <div class='box-cabecalho'>Últimos Visitantes</div>
                     <div class='ultimos-visitantes-menu'>
                     <?
                     $a = $evento->mostraMembros(0);
                     $c = 0;
                     foreach($a as $key => $user)
                     {
                         if($c == 2)
                             break;
                         echo "<li><a href='$root/profile/$user[2]' ><div class='thumb-box'>";
                         if ($user[1] != "0")
                            echo "<div class='thumb-img'><img src='$root/imagens/profiles/$user[3]_thumb.$user[1]' class='thumb' alt='thumb' /></div> ";
                         else
                            echo "<div class='thumb-img'><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></div>";
                         echo "$user[0]";
                         echo "</div></a></li>";
                         $c++;
                     }
                     
                     ?>
                     </div>
                  </div>
                  <?
               }
               else
               {
                  ?>
                  <div class='box-cabecalho'>Todos os seus eventos ativos</div>
                  <ul>
                     <?
                     if ($lista[0] != "")
                     {
                        foreach ($lista as $key => $info)
                        {
                           echo "<li class='list-eventos' id='evento-list-top-$info[0]'><a href='$root/eventos/$info[0]'><div class='title-event'>" . $info[1] . "</div></a>";
                           echo $info[2];
                           echo " ás " . $info[3] . "<img src='$root/imagens/site/excluir.png' alt='Excluir' id='devento2-$info[0]' evento='$info[0]' class='e-show-del2' /></li>";
                        }
                     }
                     else
                        echo "<li class='list-eventos'><div class='title-event'>Sem Eventos</div>";
                     ?>
                  </ul>
               <? } ?>
            </div>
         </div>
      </div>
   </div>

   <div class='clear'></div>

<?
if ($_POST['mode'])
{
   include("add.php");
}
?>