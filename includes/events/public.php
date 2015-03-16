<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: public.php - Página de pesquisa de eventos
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página onde o usuário consegue visualizar os eventos ativos e realizar busca por nome/cidade/gênero.
 *    Principais funções:
 *      - Mostrar eventos ordenados pela data. (Próximos > Distantes)
 *      - Realizar busca de eventos baseada no nome e(ou) música e(ou) gênero.
 *      - Mostrar sugestões de eventos caso o usuários esteja logado no site. (Baseado na cidade e gosto musical)
 * 
 * 
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

if (isset($_POST['cidade']))
   $_GET['cidade'] = $_POST['cidade'];
if (isset($_POST['nome']))
   $_GET['nome'] = $_POST['nome'];
if (isset($_POST['genero']))
   $_GET['genero'] = $_POST['genero'];
if (isset($_POST['page']))
   $_GET['page'] = $_POST['page'];
$searcher = new buscar();
$resultadose = $searcher->filtroEvento($_GET['nome'], $_GET['cidade'], $_GET['genero'], $_GET['page']);
if (isset($_SESSION['id_usuario']))
{
   $u = new usuario();
   $u->pegarGostoMusical($u->tipo);
   $resultadosug = $searcher->filtroEvento("", $u->cidade, $u->tipomusical, 0);
   if(is_array($resultadosug))
       shuffle($resultadosug);
}
?>
<div class="default-box1" id="evento-search-wrapper">
   <div class='box-cabecalho'>Encontrar Eventos</div>
   <div class='info'><p>Você pode encontrar eventos baseados em sua localização e gosto musical. Aproveite nossos filtros de busca.</p></div>
   <div class='search-div-option-box'><label>Pesquisar por nome</label>
      <input type='text' name='p-e-nome' class='edit-text' id="input-search-e1" /></div>
   <div class='search-div-option-box'><label>Pesquisar por cidade</label>
      <input name='cidades-e' type='text' class='edit-text' id="input-search-e2" />
   </div>
   <div class='search-div-option-box'><label>Pesquisar por gênero</label>
      <input type='text' name='generos-e' class='edit-text' id="input-search-e3">
   </div>
   <input type='button' class='btn' value='Pesquisar' id='e-s-button' />
</div>
<? if (isset($_SESSION['id_usuario']) && $resultadosug != "")
{ ?>
   <div id='sugestao-eventos-wrap' class='default-box2'>
      <div class='gra_top1'>Sugestões</div>
      <ul>
         <?
            foreach ($resultadosug as $i => $ev)
            {
               if ($i == 4)
                  break;
               echo "<li class='left'><div class='event-result-box'><a href='$root/site/evento/$ev[0]/' class='nochange'><div class='event-result-img'>";
               if ($ev[5] != "0")
                  echo "<img src='$root/imagens/evento/$ev[0]_thumb.$ev[5]' class='banner-evento2' alt='$ev[1]' />";
               else
                  echo "<img src='$root/imagens/evento/nobanner_thumb.png' class='banner-evento2' alt='$ev[1]' />";               
               echo "</div><div class='result-title'><span>$ev[1]</span></div><div class='result-text'>$ev[3] às $ev[2]</div></a>";
               echo "<div class='result-text'>";
               echo "<a href='$root/site/eventos/&genero=$ev[4]' class='link-s-e";
               if($u->tipomusical == $ev[4])
               echo "2";
               echo "'>$ev[4]</a><span class='result-text'> em </span>";
               if ($u->cidade == $ev[6])
                  echo "<a href='$root/site/eventos/&cidade=$ev[6]' class='link-s-e2'>$ev[6]</a>";
               else
                  echo "<a href='$root/site/eventos/&cidade=$ev[6]' class='link-s-e'>$ev[6]</a>";
               echo "</div></div></li>";
           }
         ?></ul>
   </div> 
<? } ?>

<div class='default-box2' id='evento-resul-wrap<? if($_SESSION['id_usuario'] == "" || $resultadosug == "") echo "2";?>'><div class='box-cabecalho'>Eventos</div>
<div id='result-evento-wrap'>

   <div id='real-result-evento-wrap'>
      <ul>
         <?
         if (!is_array($resultadose))
         {
            echo "<li class='search-not-found'>Não foi possível encontrar resultados dentro das suas especificações, tente novamente...</li>";
            echo "<div id='result-n' class='hidden'>0</div>";
         }
         else
         {
            foreach ($resultadose as $i => $ev)
            {
               if ($i == 15)
                  break;
               echo "<li class='left'>";
               echo "<div class='event-result-box'>";
               echo "<a href='$root/site/evento/$ev[0]' class='nochange'>";
               echo "<div class='event-result-img'>";
               if ($ev[5] != "0")
                  echo "<img src='$root/imagens/evento/$ev[0]_thumb.$ev[5]' class='banner-evento2' alt='$ev[1]' />";
               else
                  echo "<img src='$root/imagens/evento/nobanner_thumb.png' class='banner-evento2' alt='$ev[1]' />";
               echo "</div>";
               echo "<div class='result-title'><span>$ev[1]</span></div>";
               echo "</a>";
               echo "<div class='result-text'>$ev[3] às $ev[2]</div>";
               echo "<div class='result-text'>";
               echo "<a href='$root/site/eventos/&genero=$ev[4]' class='link-s-e'>$ev[4]</a>";
               echo "<span class='result-text'> em </span>";
               echo "<a href='$root/site/eventos/&cidade=$ev[6]' class='link-s-e'>$ev[6]</a>";
               echo "</div>";
               echo "</div>";
               echo "</li>";
               }
         }
         ?>
      </ul>
   </div>
   <div id='more-results-15'></div>
   <div id='loading-more'></div>
</div>
</div>
<input type='hidden' id='cidade-query' value='<? echo $_GET['cidade']; ?>' />
<input type='hidden' id='genero-query' value='<? echo $_GET['genero']; ?>' />
<input type='hidden' id='nome-query' value='<? echo $_GET['nome']; ?>' />
<input type='hidden' id='page-query' value='<? echo $_GET['page'] + 15; ?>' />
<div class='more-wrapper'><span id='more-events'>+ Mais</span></div>
<div class='clear'></div>
