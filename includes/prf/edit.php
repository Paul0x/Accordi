<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: edit.php - Realizar edição do perfil.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página que realiza todas as funções para editar o perfil do usuário.
 *    Principais funções:
 *      - Layout para editar perfil.
 *      - Chamadas para realizar as funções para editar o perfil.
 *      - Sistema de abas. 
 * 
 * 
 *********************************************/

if ($IN_ACCORDI != true)
{
   exit();
}

$user = new usuario("");
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<input type="hidden" id="mid" value="<? echo $user->id; ?>" />
<?
if (isset($_POST['btn-pessoal']))
   $editou = $user->editarProfile("pessoal", (($_POST['nome'])), (($_POST['email'])), $_FILES['avatar'], (($_POST['tel'])), (($_POST['telefone2'])), (($_POST['sobrenome'])), (($_POST['pseudonimo'])), $_POST['sexo'], (($_POST['n-dia'])), (($_POST['n-mes'])), (($_POST['n-ano'])), (($_POST['sobre'])), (($_POST['descricao'])), (($_POST['website'])), (($_POST['youtube'])), (($_POST['twitter'])), (($_POST['facebook'])), (($_POST['orkut'])), (($_POST['logd'])), (($_POST['bairro'])), (($_POST['cidade'])), (($_POST['estado'])), $_POST['p-email'], $_POST['p-ende'], $_POST['p-nome'], $_POST['p-idade']);
if (isset($_POST['btn-redes']))
   $editou = $user->editarProfile("redes", (($_POST['nome'])), (($_POST['email'])), $_FILES['avatar'], (($_POST['tel'])), (($_POST['telefone2'])), (($_POST['sobrenome'])), (($_POST['pseudonimo'])), $_POST['sexo'], (($_POST['n-dia'])), (($_POST['n-mes'])), (($_POST['n-ano'])), (($_POST['sobre'])), (($_POST['descricao'])), (($_POST['website'])), (($_POST['youtube'])), (($_POST['twitter'])), (($_POST['facebook'])), (($_POST['orkut'])), (($_POST['logd'])), (($_POST['bairro'])), (($_POST['cidade'])), (($_POST['estado'])), $_POST['p-email'], $_POST['p-ende'], $_POST['p-nome'], $_POST['p-idade']);
if (isset($_POST['btn-local']))
   $editou = $user->editarProfile("local", (($_POST['nome'])), (($_POST['email'])), $_FILES['avatar'], (($_POST['tel'])), (($_POST['telefone2'])), (($_POST['sobrenome'])), (($_POST['pseudonimo'])), $_POST['sexo'], (($_POST['n-dia'])), (($_POST['n-mes'])), (($_POST['n-ano'])), (($_POST['sobre'])), (($_POST['descricao'])), (($_POST['website'])), (($_POST['youtube'])), (($_POST['twitter'])), (($_POST['facebook'])), (($_POST['orkut'])), (($_POST['logd'])), (($_POST['bairro'])), (($_POST['cidade'])), (($_POST['estado'])), $_POST['p-email'], $_POST['p-ende'], $_POST['p-nome'], $_POST['p-idade']);
if (isset($_POST['btn-secu']))
   $editou = $user->editarProfile("secu", (($_POST['nome'])), (($_POST['email'])), $_FILES['avatar'], (($_POST['tel'])), (($_POST['telefone2'])), (($_POST['sobrenome'])), (($_POST['pseudonimo'])), $_POST['sexo'], (($_POST['n-dia'])), (($_POST['n-mes'])), (($_POST['n-ano'])), (($_POST['sobre'])), (($_POST['descricao'])), (($_POST['website'])), (($_POST['youtube'])), (($_POST['twitter'])), (($_POST['facebook'])), (($_POST['orkut'])), (($_POST['logd'])), (($_POST['bairro'])), (($_POST['cidade'])), (($_POST['estado'])), $_POST['p-email'], $_POST['p-ende'], $_POST['p-nome'], $_POST['p-idade']);
if (isset($_POST['btn-senha']))
   $editou = $user->editarSenha($_POST['new-pw'], $_POST['old-pw']);
unset($user);
$user = new usuario("");
if($_GET['fb'] == true && $_SESSION['id_usuario'] != "" )
{
   if($_GET['fb'] == "del" && $user->facebook != "")
   {
       $user->upFb("delete");
   }
   else
   {
            $app = new snapps;
            $app->autenticaFacebook();
   }
}

?>
<div class='menu-abas'>
   <ul class='menu-abas-edit'>
      <li class='menu-abas-edit' id='aba-profile'>Perfil</li>
      <li class='menu-abas-edit' id='aba-redes'>Redes Sociais</li>
      <li class='menu-abas-edit' id='aba-local'>Localização</li>
      <li class='menu-abas-edit' id='aba-secu'>Segurança</li>
   </ul>
</div>
<div class='sub-area-edit'>
   <? if ($_GET['mode'] == "" || $_GET['mode'] == "geral")
   { ?>  
      <div class='edit-wrapper'>
   <? if ($_GET['npw'] == "true")
   { ?>
         <div class='opc-box' style='display: block;'>
            </div>
            <div  class='ajax-box-edit' id="edit-senha-box" style='display: block;'>
               <form id='form-senha' method='post' action='<? echo $root;?>/home/edit/'>
                   <div class='gra_top1'>Alteração de Senha</div>
                   <div class='info'>Detectamos que você realizou uma alteração de senha recentemente, para evitar problemas recomendamos que você troque sua senha agora.</div>
                  <ul class='edit-ul'>
                     <li ><label class='edit-ul'>Senha Antiga</label><input type='password' class='edit-text' name='old-pw' id='old-pw' /><span id='senha-error2'></span></li>
                     <li ><label class='edit-ul'>Senha Nova</label><input type='password' class='edit-text' name='new-pw' id='senha' /><span id='senha-error'></span></li>
                     <li ><label class='edit-ul'>Confirmar Senha Nova</label><input type='password' class='edit-text' name='new-pw2' id='senha-c' /></li>
                  </ul>
                  <input type='submit' name='btn-senha' id='but-senha' class='btn' value='Trocar'/>        </form>
            </div>
   <? } ?>
         <form method='post' action='#perfil'  enctype="multipart/form-data" id='form-edit-pessoal'>
            <h3>Informações do Perfil</h3> 
            <p>Mantenha seu perfil atualizado para obter sucesso na hora de realizar seus contatos profissionais! - Os campos com asterisco são obrigatórios.</p>
            <ul class='edit-ul'>
               <li ><label class='edit-ul' >Nome*</label><input type='text' class='edit-text' name='nome' id='nome' maxlength="20" value="<? echo $user->nome; ?>" /><span id='nome-error'><span class='smalltip'>Seu nome ou da sua empresa</span></span></li>
               <li ><label class='edit-ul'>E-mail*</label><input type='text' class='edit-text' name='email' id='email2'value="<? echo $user->email; ?>" /><span id='email-error'><span class='smalltip'>Altere a visibilidade na segurança.</span></span></li>
               <li ><label class='edit-ul' id='tool-tel'>Telefone*</label><input type='text' class='edit-text' name='tel' id='tel' maxlength="13" value="<? echo $user->telefone1; ?>"/><span id='telefone-error'><span class='smalltip'>Obrigatório</span></span></li>
               <li ><label class='edit-ul' >Telefone 2</label><input type='text' class='edit-text' name='telefone2' id='telefone2' value="<? echo $user->telefone2; ?>" /><span id='telefone2-error'></span></li>

   <? if ($_SESSION['tipo_usuario'] == 1)
   { ?>        <li ><label class='edit-ul' >Sobrenome</label><input type='text' class='edit-text' name='sobrenome' maxlength='20' id='sobrenome' value="<? echo $user->sobrenome; ?>" /></li>
                  <li ><label class='edit-ul' >Pseudônimo</label><input type='text' class='edit-text' name='pseudonimo' maxlength='20' id='pseudonimo' value="<? echo $user->apelido; ?>" /><span class='smalltip'>Seu apelido artístico.</span></li>
                  <li><label class='edit-ul'>Sexo</label></li>    
                  <div class='edit-ul2'><li><label class='edit-ul2' >Não Informar</label><input type='radio'  name='sexo' id='sexo-i' value="i" <? if ($user->sexo != "m" || $user->sexo != "f")
                                                                                             echo "checked='checked'" ?> /></li>
                     <li>
                        <label class='edit-ul2' >Masculino</label><input type='radio'  name='sexo' id='sexo-m' value="m" <? if ($user->sexo == "m")
                                                                   echo "checked='checked'" ?> />
                        <label class='edit-ul2' >Feminino</label><input type='radio'  name='sexo' id='sexo-f' value="f" <? if ($user->sexo == "f")
                                                                   echo "checked='checked'" ?> /></li></div>  

                  <div class='clear'></div>
                  <li ><label class='edit-ul' >Nascimento</label><input type='text' class='edit-text'size="2" maxlength="2" name='n-dia' id='n-dia' value="<? echo $user->dia; ?>" />
                     <select name='n-mes' class='edit-text' id='n-mes'>
                        <option value='01' <? if ($user->mes == '01')
                             echo "selected='selected'" ?>>Janeiro</option>
                        <option value='02' <? if ($user->mes == '02')
                             echo "selected='selected'" ?>>Fevereiro</option>
                        <option value='03' <? if ($user->mes == '03')
                             echo "selected='selected'" ?>>Março</option>
                        <option value='04' <? if ($user->mes == '04')
                             echo "selected='selected'" ?>>Abril</option>
                        <option value='05' <? if ($user->mes == '05')
                             echo "selected='selected'" ?>>Maio</option>
                        <option value='06' <? if ($user->mes == '06')
                             echo "selected='selected'" ?>>Junho</option>
                        <option value='07' <? if ($user->mes == '07')
                             echo "selected='selected'" ?>>Julho</option>
                        <option value='08' <? if ($user->mes == '08')
                             echo "selected='selected'" ?>>Agosto</option>
                        <option value='09' <? if ($user->mes == '09')
                             echo "selected='selected'" ?>>Setembro</option>
                        <option value='10' <? if ($user->mes == '10')
                             echo "selected='selected'" ?>>Outubro</option>
                        <option value='11' <? if ($user->mes == '11')
                             echo "selected='selected'" ?>>Novembro</option>
                        <option value='12' <? if ($user->mes == '12')
                             echo "selected='selected'" ?>>Dezembro</option>
                     </select>

                     <select name='n-ano' class='edit-text' id='n-ano' >
      <? $user->nascimentoAno(); ?>
                     </select>

                     <span id='dia-error'></span>
                  </li>
                  <li ><label class='edit-ul' >Sobre</label><textarea  name='sobre' id='sobre' class='edit-text' ><? echo $user->sobre; ?></textarea></li>
               <? } elseif ($_SESSION['tipo_usuario'] == 2)
               { ?>
                  <li ><label class='edit-ul' >Website</label><input type='text' class='edit-text' name='website' id='website' value="<? echo $user->website; ?>"  /></li>
                  <li ><label class='edit-ul' >Descrição</label><textarea  name='descricao' id='descricao' class='edit-text' ><? echo $user->descricao; ?></textarea></li>
   <? } ?>
            </ul>
            <input type="submit" value="Atualizar" name="btn-pessoal" class='btn'/>
   <? if ($_SESSION['tipo_usuario'] == 1)
      echo " <div class='edit-avatar'> "; elseif ($_SESSION['tipo_usuario'] == 2)
      echo " <div class='edit-avatar-b'> "; ?>
            <div class='edit-avatar-box'>
               <h3>Alterar Imagem de Exibição</h3>
   <? echo $user->mostrarImagem(); ?><ul><li><span class='smalltip'>Máximo de 3mb - Formatos: jpg,jpeg,png e gif.</span></li></ul>
               <p><input type='file'  name='avatar' id='avatar' /></p>
            </div>
         </form>
      </div></div>
<? } elseif ($_GET['mode'] == "redes")
{ ?>
      <div class='edit-wrapper'>
         <form method='post' action='#redes'>
            <h3>Informações das Redes Sociais</h3>
            <p>No accordi você pode se conectar com outras redes sociais, selecione seu profile e aumente a abrangência de seu perfil. (Acho que no futuro isso tem que ser feito por API)</p>
            <ul class='edit-ul'>
               <li ><label class='edit-ul' >Youtube</label><input type='text' class='edit-text' maxlength="45" name='youtube' id='youtube' value="<? echo $user->youtube; ?>" /><span class='smalltip'>ex em negrito: youtube.com/user/<strong>MARCOFILMES</strong></span></li>
               <li ><label class='edit-ul' >Twitter</label><input type='text' class='edit-text' name='twitter' maxlength="15" id='twitter' value="<? echo $user->twitter; ?>" /><span class='smalltip'>ex em negrito: twitter.com/<strong>ACCORDIMUSIC</strong></span> </li>
               <li><label class='edit-ul' >Facebook</label>
                   <?
                   if($user->facebook != "")
                       echo "<input type='text' class='edit-text' name='facebook' disabled='disabled' maxlength='15' id='facebook' value='$user->facebook'/> <span class='smalltip'>Verifique se o seu id do facebook bate com o informado, caso não bata clique <a href='$root/home/edit/&fb=del' class='smalltip'><strong>AQUI</strong></a>.</span>";
                   else
                   {
                       echo "<a href='$root/home/edit/&fb=true'><img src='$root/imagens/site/fb-connect-large.png' alt='Facebook' /></a>";
                   }
                   ?>
               </li>
            </ul>
            <input type="submit" value="Atualizar" name="btn-redes" class='btn' />
         </form>
      </div>
<? }
elseif ($_GET['mode'] == "local")
{ ?>
      <div class='edit-wrapper'>
         <form method='post' action='#local'>
            <h3>Informações da Localização</h3>
            Com a sua localização atualizada nós podemos lhe informar quais são as oportunidades de contrato mais próximas.
            <ul class='edit-ul'>
               <li ><label class='edit-ul'>Endereço</label><input type='text' class='edit-text' name='logd' id='logd' maxlength="90" value="<? echo $user->logradouro; ?>" /></li>
               <li ><label class='edit-ul'>Bairro</label><input type='text' class='edit-text' name='bairro' id='bairro' maxlength="25" value="<? echo $user->bairro; ?>"/></li>
               <li ><label class='edit-ul'>Cidade</label><input type='text' class='edit-text' name='cidade' id='cidade' maxlength="30" value="<? echo $user->cidade; ?>"/></li>
               <li ><label class='edit-ul'>Estado</label>
                  <select name="estado" id="estado" class='edit-text'>
                     <option value="0">Selecione o Estado</option>
                     <option value="ac" id="ac" <? if ($user->estado == "ac")
                             echo "selected='selected'" ?>  >Acre</option>
                     <option value="al" id="al" <? if ($user->estado == "al")
                             echo "selected='selected'" ?>  >Alagoas</option>
                     <option value="ap" id="ap" <? if ($user->estado == "ap")
                             echo "selected='selected'" ?>  >Amapá</option>
                     <option value="am" id="am" <? if ($user->estado == "am")
                             echo "selected='selected'" ?>  >Amazonas</option>
                     <option value="ba" id="ba" <? if ($user->estado == "ba")
                             echo "selected='selected'" ?>  >Bahia</option>
                     <option value="ce" id="ce" <? if ($user->estado == "ce")
                             echo "selected='selected'" ?>  >Ceará</option>
                     <option value="df" id="df" <? if ($user->estado == "df")
                             echo "selected='selected'" ?>  >Distrito Federal</option>
                     <option value="es" id="es" <? if ($user->estado == "es")
                             echo "selected='selected'" ?>  >Espirito Santo</option>
                     <option value="go" id="go" <? if ($user->estado == "go")
                             echo "selected='selected'" ?>  >Goiás</option>
                     <option value="ma" id="ma" <? if ($user->estado == "ma")
                             echo "selected='selected'" ?>  >Maranhão</option>
                     <option value="ms" id="ms" <? if ($user->estado == "ms")
                             echo "selected='selected'" ?>  >Mato Grosso do Sul</option>
                     <option value="mt" id="mt" <? if ($user->estado == "mt")
                             echo "selected='selected'" ?>  >Mato Grosso</option>
                     <option value="mg" id="mg" <? if ($user->estado == "mg")
                             echo "selected='selected'" ?>  >Minas Gerais</option>
                     <option value="pa" id="pa" <? if ($user->estado == "pa")
                             echo "selected='selected'" ?>  >Pará</option>
                     <option value="pb" id="pb" <? if ($user->estado == "pb")
                             echo "selected='selected'" ?>  >Paraíba</option>
                     <option value="pr" id="pr" <? if ($user->estado == "pr")
                             echo "selected='selected'" ?>  >Paraná</option>
                     <option value="pe" id="pe" <? if ($user->estado == "pe")
                             echo "selected='selected'" ?>  >Pernambuco</option>
                     <option value="pi" id="pi" <? if ($user->estado == "pi")
                             echo "selected='selected'" ?>  >Piauí</option>
                     <option value="rj" id="rj" <? if ($user->estado == "rj")
                             echo "selected='selected'" ?>  >Rio de Janeiro</option>
                     <option value="rn" id="rn" <? if ($user->estado == "rn")
                             echo "selected='selected'" ?>  >Rio Grande do Norte</option>
                     <option value="rs" id="rs" <? if ($user->estado == "rs")
                             echo "selected='selected'" ?>  >Rio Grande do Sul</option>
                     <option value="ro" id="ro" <? if ($user->estado == "ro")
                             echo "selected='selected'" ?>  >Rondônia</option>
                     <option value="rr" id="rr" <? if ($user->estado == "rr")
                             echo "selected='selected'" ?>  >Roraima</option>
                     <option value="sc" id="sc" <? if ($user->estado == "sc")
                             echo "selected='selected'" ?>  >Santa Catarina</option>
                     <option value="sp" id="sp" <? if ($user->estado == "sp")
                             echo "selected='selected'" ?>  >São Paulo</option>
                     <option value="se" id="se" <? if ($user->estado == "se")
                             echo "selected='selected'" ?>  >Sergipe</option>
                     <option value="to" id="to" <? if ($user->estado == "to")
                             echo "selected='selected'" ?>  >Tocantins</option>
                  </select>
               </li>
            </ul>
            <input type="submit" value="Atualizar" name="btn-local" class='btn' />
            <a href='#local'><span class='smalltip' onclick='getPos()' id='getloc'>Pegar minha localização.</span></a>
         </form>
      </div>
<? } elseif ($_GET['mode'] == "secu")
{ ?>
      <div class='edit-wrapper'>
         <form method='post' action='#secu'>
            <h3>Informações de Segurança e Privacidade</h3>
            Sua senha é secreta, evite ao máximo compartilhamento de sua conta.
            <ul class='edit-ul'>
               <li ><label class='edit-ul' id='tool-login'>Usu&aacute;rio*</label><input type='text' class='edit-text' name='login' id='login' disabled="disabled" value="<? echo $user->login; ?>"/>
                  <span class='smalltip'>Seu usuário é um meio de te identificar no nosso site, atualmente você não pode troca-lo.</span></li>

               <li><div id='edit-pw' class='pointer'>Alterar sua senha</div></li>
            </ul>
            <div>
               <div class='mid-edita'>
                  <p class='private1'>Privacidade do e-mail</p>
                  <p class='private2'>Controle quem consegue ter acesso ao seu e-mail.</p>
                  <ul class='edit-ul'>
                     <li class='span-edit' ><label class='edit-ul2' >Todos</label><input type='radio'  name='p-email' id='p-email-1' value="0" <? if ($user->pemail == 0)
      echo "checked='checked'" ?> />
                        <label class='edit-ul2' >Apenas Eu</label><input type='radio'  name='p-email' id='p-email-2' value="1" <? if ($user->pemail == 1)
      echo "checked='checked'" ?> />
                        <label class='edit-ul2' >Contatos</label><input type='radio'  name='p-email' id='p-email-3' value="2" <? if ($user->pemail == 2)
                                                                                      echo "checked='checked'" ?> /></ li>
                  </ul>
               </div>
               <div class='mid-edita'>
                  <p class='private1'>Privacidade do endereço</p>
                  <p class='private2'>Controle quem consegue ter acesso ao seu endereço.</p>
                  <ul class='edit-ul'>
                     <li class='span-edit' ><label class='edit-ul2' >Todos</label><input type='radio'  name='p-ende' id='p-ende-1' value="0" <? if ($user->pende == 0)
                                                                      echo "checked='checked'" ?> />
                        <label class='edit-ul2' >Apenas Eu</label><input type='radio'  name='p-ende' id='p-ende-2' value="1" <? if ($user->pende == 1)
                                                                      echo "checked='checked'" ?> />
                        <label class='edit-ul2' >Contatos</label><input type='radio'  name='p-ende' id='p-ende-3' value="2" <? if ($user->pende == 2)
                                                                      echo "checked='checked'" ?> /></ li>
                  </ul>
               </div>
   <? if ($user->tipo == 1)
   { ?>
                  <div class='mid-edita' >
                     <p class='private1'>Identificação</p>
                     <p class='private2'>Como você deseja ser identificado no título do seu perfil?</p>
                     <ul class='edit-ul'>
                        <li class='span-edit' ><label class='edit-ul2' >Pelo meu nome.</label><input type='radio'  name='p-nome' id='p-nome-1' value="0" <? if ($user->pnome == 0)
         echo "checked='checked'" ?> />
                           <label class='edit-ul2' >Pelo meu apelido.</label><input type='radio'  name='p-nome' id='p-nome-2' value="1" <? if ($user->pnome == 1)
         echo "checked='checked'" ?> />
                     </ul>
                  </div>
                  <div class='mid-edita'>
                     <p class='private1'>Idade</p>
                     <p class='private2'>Quem pode ter acesso a sua idade?</p>
                     <ul class='edit-ul'>
                        <li class='span-edit' ><label class='edit-ul2' >Todos</label><input type='radio'  name='p-idade' id='p-idade-1' value="0" <? if ($user->pidade == 0)
         echo "checked='checked'" ?> />
                           <label class='edit-ul2' >Apenas Eu</label><input type='radio'  name='p-idade' id='p-idade-2' value="1" <? if ($user->pidade == 1)
         echo "checked='checked'" ?> />
                           <label class='edit-ul2' >Contatos</label><input type='radio'  name='p-idade' id='p-idade-3' value="2" <? if ($user->pidade == 2)
         echo "checked='checked'" ?> /></ li>
                     </ul>
                  </div>
   <? } ?>
               <div class='clear'></div>
            </div>
            <input type="submit" value="Atualizar" name="btn-secu" class='btn' />
         </form>


         <div class='opc-box'>
         </div>
         <div  class='ajax-box-edit' id="edit-senha-box" <? if ($_GET['npw'] == "on")
      echo "style='display: block;'"; ?>>
            <form id='form-senha' method='post' action=''>
                <div class='gra_top1'>Editar Senha</div>
                <div class='info'>Recomendamos que você utilize senhas seguras com mais de 10 caracteres, variando entre números e letras.</div>
               <ul class='edit-ul'>
                  <li ><label class='edit-ul'>Senha Antiga</label><input type='password' class='edit-text' name='old-pw' id='old-pw' /><span id='senha-error2'></span></li>
                  <li ><label class='edit-ul'>Senha Nova</label><input type='password' class='edit-text' name='new-pw' id='senha' /><span id='senha-error'></span></li>
                  <li ><label class='edit-ul'>Confirmar Senha Nova</label><input type='password' class='edit-text' name='new-pw2' id='senha-c' /></li>
               </ul>
               <input type='submit' name='btn-senha' id='but-senha' class='btn' value='Trocar'/>        </form>
               </div>
</div>
   <? }
?>
</div><div class='clear'></div>
<span id="submit-error">
<?
if ($_POST)
{
   if ($editou == "true")
      echo "<div class='query-sucess'>Seus dados foram alterados com sucesso!</div>";
   elseif ($editou == "false")
      echo "<div class='query-fail'>Ocorreu um erro durante a alteração de dados. :(</div>";
   else
      echo "<div class='query-fail'>" . $editou . " :(</div>";
}
?>
</span>

<div class='clear-footer'></div>
<script type='text/javascript'>alterarEditTab();</script>