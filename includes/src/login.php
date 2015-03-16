<?php
if ($IN_ACCORDI != true)
{
   exit();
}
switch ($_GET['ac'])
{
   case "csucess":
      ?>
      <div class='blue-box'>
         <font class='super-font'>Bem vindo ao Accordi!</font>
         <p>Ficamos satisfeitos em saber que você demonstrou interesse em nosso serviço, você já pode começar a desfrutar de todas as oportunidades e opções que o Accordi oferece simplesmente clicando <a href="<? echo $root; ?>/login/">AQUI</a>.</p>
      </div>
      <?
      break;
   case "falha":
      ?>
      <div class='blue-box'>
         <font class='super-font-red'>OCOREU UM ERRO</font>
         <p>Não conseguimos realizar o seu cadastro. Verifique se suas informações estão corretas e válidas, caso estejam contacte nosso suporte técnico.</p>
      </div>
      <?
      break;
   case "mini":
      ?>
          <div class='login-box'>
            <div class='login-box-div2'>
               <div class='mini-login-title'>Realizar Login</div>
               <form method='post' action="<? echo $root; ?>/valida/&func=login&url=<? echo $_GET['url']; ?>">
                  <ul class='login-list-info'>
                     <li class="login-list-info">
                        <label class='login-label'>Usuário</label>
                        <input type='text' autofocus="autofocus" name='username' maxlength="30" class='login-text-input' />
                     </li>
                     <li class="login-list-info">
                        <label class='login-label'>Senha</label>
                        <input type='password' name='password' class='login-text-input' maxlength="30" />
                        <input type='hidden' name='url' id='ref-url-l' value=''/>
                     </li>
                     <li>
                        <input type='submit' value='Logar' class='login-button-input' />
                     </li>
                  </ul>
                  <ul class='login-list2'>
                        <li class='login-list2'><a href="<? echo $root; ?>/recovery/" class='login-list2'>Recuperar sua senha.</a></li>
                        <li class='login-list2'><a href="<? echo $root; ?>/cadastro/" class='login-list2'>Junte-se a n&oacute;s.</a></li>
                        </ul>
               </form>

            </div>
         </div>
      <?
      break;
   default:
      ?>


      <div id='login-wrap'>
         <div class='login-box'>
            <div class='login-box-div'>
               <font class='green-title'>Realizar Login</font>
               <form method='post' action="<? echo $root; ?>/valida/&func=login&url=<? echo $_GET['url']; ?>">
                  <ul class='login-list-info'>
                     <li class="login-list-info">
                        <label class='login-label'>Usuário</label>
                        <input type='text' autofocus="autofocus" name='username' maxlength="30" class='login-text-input' />
                     </li>
                     <li class="login-list-info">
                        <label class='login-label'>Senha</label>
                        <input type='password' name='password' class='login-text-input' maxlength="30" />
                     </li>
                     <li>
                        <input type='submit' value='Logar' class='login-button-input' />
                        <?
                        if ($_GET['m'] == "erro")
                           echo "<strong class='side-tip'>Login ou senha incorretos!</strong>";
                        if ($_GET['m'] == "acess")
                           echo "<strong class='side-tip'>Permissão necessária, realize login.</strong>";
                        ?>
                     </li>
                  </ul>
               </form>

            </div>
            <ul class='login-list'>
               <li><a href="<? echo $root; ?>/recovery/" class='login-list'>Recuperar sua senha.</a></li>
               <li><a href="<? echo $root; ?>/cadastro/" class='login-list'>Junte-se a n&oacute;s.</a></li>
            </ul>
         </div>
         <div class='login-presentation'><div class='super-font'>Introdução</div>
         <p>Accordi é um portal e uma rede que procura ajudar artistas e contratantes, iniciantes ou experientes, a criarem laços profissionais e manterem contato constante com os últimos trabalhos do ramo musical brasileiro.
             
         </p>
         <a href="<? echo $root; ?>/cadastro/" class='login-presentation2'><div class='invita-cadastro'>Cadastro</div></a></div>
      
    <?
    $v = new validate();
    $v = $v->getLastUsers(9);
    if($v != "")
    {
        echo "<div class='new2-login'>
        <h7>Últimos usuários cadastrados...</h7>";
        echo "<ul>";
        foreach($v as $i => $v)
        {
          echo "<li class='left'><a href='$root/profile/$v[2]'><div class='thumb-box4'>";
          if($v[1] == '0')
              echo "<img class='thumb' src='$root/imagens/profiles/noavatar_thumb.png' />";
          else
              echo "<img class='thumb' src='$root/imagens/profiles/$v[3]_thumb.$v[1]' />";
          echo "<div>$v[0]</div>";
          echo "</div></a></li>";              
        }
        echo "</ul>";
        echo "</div>";
    }
    ?>
    

      </div>

<div class='clear'></div>
      <? break;
} ?>
