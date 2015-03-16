<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: add.php - Layout para adicionar nova música.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página contendo APENAS o layout para adicionar música.
 *    Principais funções:
 *      - Layout para adicionar música.
 * 
 *********************************************/
if (IN_ACCORDI != true)
{
   exit();
}

$addmusica = new musicas(); 
if (isset($_POST['music-add']))
{
    $add = $addmusica->addMusica((($_POST['titulo'])), (($_POST['genero'])), (($_POST['classificacao'])), (($_POST['letra'])), (($_POST['p-musica'])), $_FILES['mp3'], $_POST['video']);
}
$root = $_SESSION['root'];
?>
<a href='<?echo $root;?>/musicas'><div class='back1'>Voltar</div></a>
<div class='default-box2' id='music-add-wrap'>
   <div class='box-cabecalho'>Adicionar Música</div>
   <div class='info'>Adicionando músicas no Accordi você pode aumentar suas chances de ser descoberto por contratantes e de ser encontrado por outros artistas que gostem do seu estilo musical.</div>
   <form id='add-musica' class='form-add' method='post' action='' enctype="multipart/form-data">
      <div class='add-musica01'>
         <ul class='edit-ul-mini'>
            <li ><label class='music-add-label'>T&iacute;tulo*</label></li><li><input type='text' class='edit-text2' maxlength='20' name='titulo' id='titulo' maxlength="25" value="" /><span id='nome-error'></span></li>
            <li ><label class='music-add-label'>Genero*</label></li><li><input type='text' autocomplete='off' class='edit-text2' name='genero' maxlength='15' id='genero' value="" /></li>
            <li ><label class='music-add-label'>Classifica&ccedil;&atilde;o*</label></li><li>
            <select class='edit-text22' name='classificacao' id='classificacao'>
                <option value='0'>Livre</option>
                <?
                    echo "<option value='10'>Maiores de 10 anos</option>";
                    echo "<option value='14'>Maiores de 14 anos</option>";
                    echo "<option value='16'>Maiores de 16 anos</option>";
                    echo "<option value='18'>Maiores de 18 anos</option>";
                    echo "<option value='21'>Maiores de 21 anos</option>";
                    
                ?>
            </select></li>
            <li ><label class='music-add-label' >Clipe</label></li><li><input type='text' class='edit-text2' maxlength='11' name='video' id='video' value="" /></li>
            <li ><span class='smalltip2'>Apenas o código ex. youtube.com/watch?v=<strong>uFMPLEqAK6w</strong></span></li>
         </ul>
      </div>
      <div class='add-musica02'>
         <ul class='edit-ul-mini'>
            <li ><label class='music-add-label' >Letra</label></li><li><textarea  name='letra' id='letra' class='edit-text2' ></textarea></li>

         </ul>
      </div>
      <div class='add-musica01'>
         <ul class='edit-ul-mini'>
            <li ><label class='music-add-label'>Arquivo:</label></li><li><p><input type='file' name='mp3' id='mp3' class='file01' /></p></li>
            <li ><span class='smalltip2'>São suportados arquivos .mp3 de até 15mb.</span></li>
            <li class='music-add-list'><p class="music-add-label">Acesso</p></li>
            <li><span class='smalltip2'>Quem pode acessar essa m&uacute;sica</span></li>

            <li class='music-add-label'><input type='radio'  name='p-musica' id='p-musica-1' value="0" <? if ($editmusica->permissao == "Todos")
   echo "checked='checked'" ?> /><label class='edit-ul-mini2' >Todos</label></li>
            <li class='music-add-label'><input type='radio'  name='p-musica' id='p-musica-2' value="1" <? if ($editmusica->permissao == "Contatos")
   echo "checked='checked'" ?> /><label class='edit-ul-mini2' >Contatos</label></li>
            <li><input type='submit'  name='music-add' id='music-add' class='btn' value='Adicionar' /></li>


            <?
            echo "<li><span id='form-error'>";
            if (isset($_POST['music-add']))
            {
               if ($add == "true")
               {
                  echo "<strong class='side-tip-ok'>Música Adicionada</strong>";
               }
               else
               {
                  echo "<strong class='side-tip'>" . $add . "</strong>";
               }
            }
            echo "</span></li>";
            ?>
         </ul>
      </div> </form>
</div>