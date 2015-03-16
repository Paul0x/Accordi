<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: profile.php - Perfil do Usuário
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Mostra as principais informações sobre o usuário
 *    Principais funções:
 *      - Mostrar músicas do artista.
 *      - Mostrar eventos do contratante.
 *      - Mostrar comentários do usuário.
 *      - Mostrar contatos do usuário.
 *      - Mostrar atualizações do usuário.
 *      - Mostrar usuários relacionados do usuário.
 *      - Mostrar redes sociais do usuário.
 *      - Mostrar informações gerais do usuário.
 * 
 *********************************************/
if (IN_ACCORDI != true)
{
    exit();
}

class profile_1
{
   private $usuario;
   private $key;
   
   public function __construct($key1,$key2,$ajax_mode=false)
   {
       /**
        *  Inicializa a construção da página de perfil.
        */
       $this->usuario = new usuario($key1); 
       $this->key = $key2;
       if($this->usuario == false) // Realiza autenticação do usuário
       {
           $this->_404();
           exit();
       }
       
       if($ajax_mode != true)
           $this->load(); // Carrega classe de acordo com usuário.
       else
           $this->loadAjax();
   }
   
   private function load()
   {
       $this->usuario->viewCount();
       if($this->key == 'musicas' && $this->usuario->tipo == 2) $this->key = "";
       if($this->key == 'eventos' && $this->usuario->tipo == 1) $this->key = "";
       switch($this->key)
       {
           case 'atualizacoes':
               $this->loadBody(0);
               $this->loadTopSmall();
               $this->loadLeft();
               $this->loadMid(2);
               $this->loadRight(1);
               $this->loadBody(1);
               break;
           case 'relacionados':
               $this->loadBody(0);
               $this->loadTopSmall();
               $this->loadLeft();
               $this->loadMid(1);
               $this->loadRight(1);
               $this->loadBody(1);
               break;
           default:
               $this->loadBody(0);
               $this->loadTop();
               $this->loadLeft();
               $this->loadMid(0);
               $this->loadRight(0);
               $this->loadBody(1);
               break;
       }
   }
   
   private function loadAjax()
   {
       /**
        *  Carrega as informações necessárias via AJAX
        */
       
       switch($this->key)
       {
           case 'musicas':
           case 'eventos':
               if($this->usuario->tipo == 1)
                   $this->loadMusicas(1);
               else
                   $this->loadEventos(1);
               break;
           case 'contatos':
               $this->loadContatos(1);
               break;
           case 'comentarios':
               $this->loadComentarios(1);
               break;
           case 'playlists':
               $this->loadPlaylists(1);
               break;
           default:
               if($this->usuario->tipo == 1)
                   $this->loadMusicas(0);
               else
                   $this->loadEventos(0);
               $this->loadPlaylists(0);
               $this->loadComentarios(0);
       }
   }
   
   private function loadBody($tipo)
   {
       /**
        *  Carrega o wrap da página;
        */
       $user = $this->usuario;
       if($tipo == 0)
       {
           echo "<div id='profile-wrapper'>";
           echo "<div class='opc-box'></div>";
           echo "<div class='ajax-box-contato'></div>";
           echo "<input type=\"hidden\" value='$user->login' id='user-profile-login' />";
           echo "<input type=\"hidden\" value='$user->id' id='user-profile-id' />";
           echo "<script type='text/javascript'>b = new commentsClass(); b.reloadComments();</script>";
       }
       else
       {
           echo "</div><div class='clear-footer'></div>";
       }
       
   }
   private function loadTop()
   {
       /**
        *  Carrega o header da página de perfil.
        */
       
       $user = $this->usuario;
       // Abertura das div's do topo.
       echo "<div id='p-top-wrap'>";
       if($user->id == $_SESSION['id_usuario'])
       {
           echo "<div class='btn-link-editaprofile'><a href='".$_SESSION['root']."/home/edit'>Editar Perfil</a></div>";
       }
       echo "<div id='info-wrapper' class='default-box2'>";
       
       // Resumo
       echo "<div id='info-geral-wrap' class='gra_top1'><span class='ico-text'>Resumo do Perfil</span></div>";
       echo "<div id='info-g-left'>";
       
       switch($this->usuario->tipo)
       {
           case 1:
               echo "<ul class='profile-list-ul'><li class='profile-list-ul-title'>";
               if($user->pnome == '1')
               {
                  echo $user->apelido;
               }
               else
               {
                   echo "$user->nome $user->sobrenome";
               }
               
  
               if($user->email != "")
                       echo "</li><li class='profile-list-ul'><label class='profile-list-tag'>Email</label> $user->email</li>";
               echo "<li class='profile-list-ul'><label class='profile-list-tag'>Telefone</label>$user->telefone1</li>";
               if ($user->nascimento != "")
                   echo "<li class='profile-list-ul'><label class='profile-list-tag'>Idade</label> " . $user->calcularIdade() . " anos</li>";
               if ($user->sobre != "")
                   echo "<li class='profile-list-ul'><label class='profile-list-tag'>Sobre</label></li>
                   <li class='profile-list-ul'><label class='left' id='descricao-p-show'><div class='descri-box'>$user->sobre</div></label></li>";
               echo "</ul>";
               break;
           case 2:
               echo "<ul class='profile-list-ul'>";
               echo "<li class='profile-list-ul-title'>";
               echo $user->nome;
                             
               echo "</li>";
               if($user->email != "")
                       echo "<li class='profile-list-ul'><label class='profile-list-tag'>Email</label>$user->email</li>";
               echo "<li class='profile-list-ul'><label class='profile-list-tag'>Telefone</label>$user->telefone1</li>";
               if ($user->website != "")
                       echo "<li class='profile-list-ul'><label class='profile-list-tag'>Website</label> <a href='http://$user->website' class='nochange2'>$user->website</a></li>";
               if ($user->descricao != "")
                       echo "<li class='profile-list-ul'><label class='profile-list-tag'>Descrição</label></li><li class='profile-list-ul'><label class='left' id='descricao-p-show'><div class='descri-box'>$user->descricao</div></label></li>";
               echo "</ul>";
               break;
       }
       
       /**
        *  Carrega imagem e badge
        */
       echo "</div>";
       echo "<div id='info-g-mid'><div class='avt-public'>" . $user->mostrarImagem() . "</div>";
       //if ($user->tipo == 1)
       //    echo "<p><div class='bar-user-artista'>Artista</div></p>";
       //elseif ($user->tipo == 2)
       //    echo "<p><div class='bar-user-contratante'>Contratante</div></p>";
                    // Verifica se existe a opção de realizar contato.
               if($user->tipo != $_SESSION['tipo_usuario'] && $user->id != $_SESSION['id_usuario'] && !is_null($_SESSION['id_usuario']))
               {
                   echo "<div class='contato-button' id='contato-btn-1'>Enviar Contato</div>";
               }
               elseif($user->tipo == $_SESSION['tipo_usuario'] && $user->id != $_SESSION['id_usuario'] && !is_null($_SESSION['id_usuario']))
               {
                   try
                   {
                       $atualizacoes = new atualizacoes();
                       $atualizacoes->getId($_SESSION['id_usuario']);
                       if($atualizacoes->isAcompanhado($user->id))
                           echo "<div class='acompanha-button' id='acompanha-btn-2'>Acompanhando</div>";
                       else
                           echo "<div class='acompanha-button' id='acompanha-btn-1'>Acompanhar</div>";
                   }
                   catch(Exception $a)
                   {
                       
                   }
               }   
       echo "</div>";
       
       echo "</div>";
       
       /**
        *  Carrega atualizações.
        */
       $this->loadAtualizacoes(0);
       
       /**
        *  Fecha topo.
        */
       echo "</div>"; 
       
   }
   
   private function loadTopSmall()
   {
       /**
        *  Carrega topo minimizado, informando apenas o nome e imagem.
        */
       $user = $this->usuario;
       $root = $_SESSION['root'];
       echo "<div id='p-top-wrap'>";
       echo "<div id='small-top-div'>";
       /**
        * Carrega avatar
        */
       echo "<div class='left'>";
       if($this->usuario->imagem == '0')
           echo "<img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' />";
       else
           echo "<img src='$root/imagens/profiles/".$user->id."_thumb.$user->imagem' class='thumb' alt='thumb' />";
       echo "</div>";
       echo "<div class='smalltop-text'>";
       echo $user->nome." ";
       if($user->apelido != "")
           echo $user->apelido." ";
       echo $user->sobrenome;
       echo "<a href='$root/profile/$user->login'><p class='smalltop-link'>";       
       echo "Perfil Completo";
       echo "</p></a>";
       echo "</div>";
       echo "<div class='clear'></div>";
       echo "</div>";
       echo "</div>";
   }
   
   private function loadLeft()
   {
       /**
        *  Carrega canto esquerdo do perfil.
        */
       
       $user = $this->usuario;
       echo "<div class='profile-left-wrap'>";
       echo "<div id='general-info-profile' class='default-box2'>";
       echo "<div id='general-geral-wrap' class='gra_top1'><span class='ico-text'>Informações Gerais</span></div>";
       echo "<ul>";
       if ($user->tipo == 2)
       {
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Nome</label>$user->nome</li>";
           if ($user->email != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Email</label>$user->email</li>";
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Contato</label>$user->telefone1</li>";
           if ($user->telefone2 != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Contato 2</label>$user->telefone2</li>";
       }
       else if($this->usuario->tipo == 1)
       {
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Nome</label>$user->nome</li>";
           if ($user->sobrenome != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Sobrenome</label>$user->sobrenome</li>";
           if ($user->apelido != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Apelido</label>$user->apelido</li>";
           if ($user->email != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Email</label>$user->email</li>";
           if ($user->nascimento != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Idade</label>" . $user->calcularIdade() . " anos</li>";
           if ($user->sexo != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Sexo</label>" . $user->calcularSexo() . "</li>";
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Contato</label>$user->telefone1</li>";
           if ($user->telefone2 != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Contato 2</label>$user->telefone2</li>";
           
       }
       if ($user->logradouro != "" || $user->bairro != "" || $user->estado != "0" || $user->cidade != "" || ($user->pende == 1 && $user->id == $_SESSION['id_usuario']))
       {
           echo "<li class='p-general-info-t'>Localização</li>";

           if ($user->logradouro != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Endereço</label>$user->logradouro</li>";
           if ($user->bairro != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Bairro</label>$user->bairro</li>";
           if ($user->cidade != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Cidade</label>$user->cidade</li>";
           if ($user->estado != "0" && $user->estado != "")
                   echo "<li class='p-general-info-i'><label class='p-general-info-l'>Estado</label>" . $user->escolherEstado() . "</li>";
       }

       if ($user->twitter != "" || $user->facebook != "" || $user->youtube != "" || $user->orkut != "")
       {
               echo "<li class='p-general-info-t'>Redes Sociais</li>";

               if ($user->facebook != "")
                       echo "<li class='p-general-info-i'><label class='p-general-info-l'>Facebook</label>$user->facebook</li>";
               if ($user->twitter != "")
                       echo "<li class='p-general-info-i'><label class='p-general-info-l'>Twitter</label>$user->twitter</li>";
               if ($user->youtube != "")
                       echo "<li class='p-general-info-i'><label class='p-general-info-l'>Youtube</label>$user->youtube</li>";
               if ($user->orkut != "")
                       echo "<li class='p-general-info-i'><label class='p-general-info-l'>Orkut</label>$user->orkut</li>";
       }
           
       echo "<li class='p-general-info-t'>Estatísticas</li>";
       echo "<li class='p-general-info-i'><label class='p-general-info-l'>Visualizações</label>$user->view</li>";
       if($this->usuario->tipo == 2)
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Contatos</label>" . $user->numeroContatos() . "</li>";
       else
           echo "<li class='p-general-info-i'><label class='p-general-info-l'>Músicas</label>" . $user->numeroMusicas() . "</li>";          
       echo "</ul>";
       echo "</div>";
       
       /**
        *  Carrega social plugin das redes sociais.
        */
        if($this->usuario->facebook != "")
                $this->loadFb();
        if($this->usuario->twitter != "")
                $this->loadTwt();
        $this->likeButton();
        echo "</div>";
   }
   
   private function loadMid($tipo)
   {
       /**
        *  Carrega o conteúdo do meio.
        *  @param int $tipo
        */
       
        $user = $this->usuario;
        $root = $_SESSION['root'];
        echo "<div class='profile-mid-wrap'>";
        
        // Carrega o menu.
        echo "<div id='profile-menu-box-div'>";
        echo "<a href='$root/profile/$user->login'><div class='menu-box-profile' id='tudo-box-menu'>Geral</div></a>";
        if ($user->tipo == 2)
                echo "<a href='$root/profile/$user->login/eventos'><div class='menu-box-profile' id='eve-box-menu'>Eventos</div></a>";
        elseif ($user->tipo == 1)
                echo "<a href='$root/profile/$user->login/musicas'><div class='menu-box-profile' id='mus-box-menu'>Músicas</div></a>";
        echo "<a href='$root/profile/$user->login/comentarios'><div class='menu-box-profile'  id='com-box-menu'>Comentários</div></a>";
        echo "<a href='$root/profile/$user->login/playlists'><div class='menu-box-profile'  id='plays-box-menu'>Playlists</div></a>";
        echo "</div>";
        // Fim do menu.
        
        echo "<div id='profile-mid-content'>";
        
        
        // Seleciona o que vai aparecer nos menus.
        $m1 = 0;
        $m2 = 0;
        $m3 = 0;
        switch($this->key)
        {
            case 'musicas': 
            case 'eventos': $m1 = 1; break;
            case 'contatos': $m2 = 1; break;
            case 'comentarios': $m3 = 1; break;
            case 'playlists': $m4= 1; break;
        }
        
        switch($tipo)
        {
            case 0:
                if($m2 == 0 && $m3 == 0 && $m4 == 0)
                {
                    if($this->usuario->tipo == 1) $this->loadMusicas($m1);
                    else $this->loadEventos($m1);
                }
                if($m2 != 0)
                    $this->loadContatos($m2);
                if($m3 == 0 && $m2 == 0 && $m1  == 0)
                    $this->loadPlaylists($m4);
                if($m1 == 0 && $m2 == 0 && $m4 == 0)
                    $this->loadComentarios($m3);
                break;
            case 1:
                $this->loadRelacionados(1);
                break;
            case 2:
                $this->loadAtualizacoes(1);               
                break;
        }
        
        echo "</div>";
        echo "</div>";
     
   }
   
   private function loadRight($tipo)
   {
       /**
        *  Carrega o conteúdo do lado direito.
        *  @param int $tipo
        */
       
       $user = $this->usuario;
       echo "<div class='profile-right-wrap'>";
       if($tipo != 1)
       {
            $this->loadContatos(0);
            $this->loadRelacionados(0);       
       }
       
       echo "</div>";
   }
   
   private function loadAtualizacoes($tipo)
   {
       /**
        *  @param int $tipo
        *  Carrega ultimas atualizações do usuário.
        */
       
       //$v = new validate();
       //$atual = $v->mostrarAtualizacoes($this->usuario->id);
       $user = $this->usuario;
       $root = $_SESSION['root'];
       //unset($v);
       
       if($tipo == 0)
       {
           echo "<div id='atualiza-wrapper' class='default-box2'><div id='atualiza-geral-wrap' class='gra_top1'><span class='ico-text'>Atualizações</div>";
           echo "<div id='atualiza-sobe'></div>";
           echo "<div id='atualiza-profile-wrap-slide'>";
           echo "<div id='atualiza-slide-change'>";
           echo "<ul>";
           try
           {
               $atualizacoes = new Atualizacoes();
               $atualizacoes->getId($user->id);
               $atual = $atualizacoes->loadAtualizacoes();
               
               $count = 0;
               foreach ($atual as $i => $k)
               {
                   if ($count == 11)
                       break;
                   switch ($k["tipo_feed"])
                   {
                       // Forma do array:
                       //   [0] => tipo_atualizacao
                       //   [1] => nome_usuario
                       //   [2] => id_indicador
                       //   [3] => campo_atualizacao
                       //   [4] => data_formatada
                       case '0':
                           echo "<a href='$root/site/musica/".$k['id_musica']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/mu_ico.png' alt='Musica' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " criou a música ";
                           echo "<strong>".$k['nome_musica']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '1':
                           echo "<a href='$root/site/evento/".$k['id_evento']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/ev_ico.png' alt='Evento' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " criou o evento ";
                           echo "<strong>".$k['nome_evento']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '31':
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
                           echo "<a href='$root/$recado_sintax[1]/".$k['identificacao_receptor']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Recado' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " criou um recado $recado_sintax[0] ";
                           echo "<strong>".$k['nome_receptor']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;                       
                       case '32':
                           echo "<a href='$root/profile/".$k['identificacao_criador']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Recado' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " recebeu um recado de ";
                           echo "<strong>".$k['nome_criador']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '4':
                           echo "<a href='$root/site/evento/".$k['id_evento']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/ev_ico.png' alt='Evento' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " confirmou participação no evento ";
                           echo "<strong>".$k['nome_evento']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '5':
                           echo "<a href='$root/profile/".$k['login_usuario']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Curriculum' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " criou um curriculum.";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '6':
                           echo "<a href='$root/profile/".$k['login_usuario']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Curriculum' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " atualizou seu curriculum.";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '7':
                           echo "<a href='$root/site/musica/".$k['id_musica']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/mu_ico.png' alt='Recado' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " avaliou a música ";
                           echo "<strong>".$k['nome_musica']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '8':
                           echo "<a href='$root/profile/".$k['login_usuario2']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Curriculum' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " fechou um contato com ";
                           echo "<strong>".$k['nome_usuario2']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                       case '9':
                           echo "<a href='$root/profile/".$k['login_usuario2']."' class='nochange'>";
                           echo "<li class='atualiza-list'>";
                           echo "<img src='$root/imagens/site/note_ico.png' alt='Curriculum' /> ";
                           echo "<strong>".$k['nome_usuario']."</strong>";
                           echo " está acompanhando ";
                           echo "<strong>".$k['nome_usuario2']."</strong>";
                           echo "<div class='date-up'>".$k['data_formatada']."</div>";
                           echo "</li>";
                           echo "</a>";
                           break;
                   }
                   $count++;
               }
             echo "<a href='$root/profile/$user->login/atualizacoes'><div id='more-atualizacoes'>MAIS ATUALIZAÇÕES</div></a>";
           }
           catch(Exception $a)
           {
               echo "<div class='atualiza-list'>$user->nome não possui atividades recentes.</div>";
           }
           echo "</ul>";
           echo "</div>";
           echo "</div>";           
           echo "<div id='atualiza-desce'></div>";
           echo "</div>";
       }
       else 
       {
           echo "<div id='atual-full' class='default-box2'>";
           echo "<div id='atualiza-geral-wrap-full' class='gra_top1'><span class='ico-text'>Atualizações</div>";
          try
           {
               $atualizacoes = new Atualizacoes();
               $atualizacoes->getId($user->id);
               $atual = $atualizacoes->loadAtualizacoes();
               echo "<div id='feed-list-true-wrap'>";
               foreach ($atual as $i => $k)
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
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
                           echo "<div class='feed-full-list'>";
                           echo "<div class='ffl-imagem'>";
                           if($k['imagem_usuario'] == '0')
                               echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' /> ";
                           else
                               echo "<img src='$root/imagens/profiles/".$k['id_usuario']."_thumb.".$k['imagem_usuario']."' class='thumb alt='Thumb' /> ";                               
                           echo "</div>";
                           echo "<div class='ffl-content'>";
                           echo "<div class='ffl-title'>".$k['nome_usuario']."</div>";
                           echo "<div class='ffl-itens'>";
                           echo " está acompahando ";
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
             $first_feed = array_pop($atual);
             $first_feed = $first_feed['id_feed'];
             echo "<input type='hidden' id='fi-feed' value='".$first_feed."' />";
             echo "<div id='more-full-atualizacoes'>Carregar mais atualizações.</div>";
           }
           catch(Exception $a)
           {
               echo "<div class='atualiza-list'>$user->nome não possui atividades recentes.</div>";
           }
           echo "</div>";  
       }
       unset($user);
       unset($root);
   }
   
   private function loadEventos($tipo)
   {
       /**
        *  Carrega os eventos do usuário
        *  @param int $tipo
        */
       $user = $this->usuario;
       $root = $_SESSION['root'];
       echo"<div id='evento-info-profile' class='default-box3'><div id='info-evento-wrap' class='gra_top1'><span class='ico-text'>Eventos</span></div>";
       $eventos = new eventos();
       $ev = $eventos->listaEventos($user->id);
       if ($ev == true)
       {
           echo "<ul>";
           $i = 0;
           foreach ($ev as $key => $evento)
           {
               if ($i == 5 && $tipo != 1)
               {
                   break;
               }
               echo "<a href='" . $root . "/site/evento/" . $evento[0] . "'>";
               echo "<li class='music-lista-p'>";
               echo "<div class='left' class='event-pr-img'>";
               if($evento[6] != '0')
                   echo "<img class='thumb' src='$root/imagens/evento/$evento[0]_thumb.$evento[6]' />";
               else
                   echo "<img class='thumb' src='$root/imagens/evento/nobanner_thumb.png' />";                   
               echo "</div>";
               echo "<div class='event-pr-list'>";
               echo "<div class='music-list-div-title-home2'>$evento[1]</div>";
               echo "<div class='music-list-div-title4'>$evento[5] -  $evento[2] ás $evento[3] em $evento[4]</div>";
               echo "</div>";
               echo "</li>";
               echo "</a>";
               $i++;
           }
           echo "</ul>";
       }
       else
       {
           echo "<li><div class='music-list-div-title-warning' >$user->nome ainda não possui nenhuma evento.</div></li>";
       }
       echo "</div>";
       unset($user,$tipo,$ev,$evento,$root);
   }
   
   private function loadMusicas($tipo)
   {
       /**
        *  Carrega as músicas do usuário
        *  @param int $tipo
        */
       
       echo "<div id='musica-info-profile' class='default-box3'><div id='info-musica-wrap' class='gra_top1'><span class='ico-text'>Músicas</span></div>";
       $musicas = new musicas();
       $musicas->artista = $this->usuario->id;
       $ms = $musicas->carregaMusicasArtista();
       $user = $this->usuario;
       $root = $_SESSION['root'];
       if ($ms == true)
       {
           echo "<ul>";
           $i = 0;
           foreach ($musicas->listamusicas as $key => $musica)
           {
               if ($i == 5 && $tipo != 1)
               {
                   break;
               }
               echo "<a href='" . $root . "/site/musica/" . $musica[0] . "'><li class='music-lista'><div class='music-list-div-title-home2' >" . $musica[1] . "</div><div class='music-list-div-title3'>$musica[2] - " . $musica[3] . " - $musica[4]</div></li></a>";
               $i++;
           }
           echo "</ul>";
       }
       else
       {
           echo "<li><div class='music-list-div-title-warning' >$user->nome ainda não possui nenhuma música.</div></li>";
       }
       echo "</div>";
       unset($root,$musica,$user,$ms,$musicas,$key);
   }
   
   private function loadContatos($tipo)
   {
       /**
        *  Carrega os contatos do usuário
        *  @param int $tipo
        */
       
       $root = $_SESSION['root'];
       $user = $this->usuario;
       try
       {
           
           $contatos = $user->loadContatos();
           echo "<div id='contatos-wrap$tipo' class='default-box2'>";
           echo "<div class='pf-contato-rightwrap'>";
           echo "<div class='gra_top1'>Contatos Atuais</div>";
           $count = 0;
           foreach($contatos as $i => $v)
           {
               if($tipo != '1')
               {
                   if($count > 10)
                       break;
                   $count++;
               }
               echo "<li class='left'><div class='thumb-box4'>";
               if ($v[2] != "0")
                   echo "<div class='thumb-img'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/$v[3]_thumb.$v[2]' class='thumb' alt='thumb' /></a></div> ";
               else
                   echo "<div class='thumb-img'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></a></div>";
               echo "<a href='$root/profile/$v[1]' class='blacka'>$v[0]</a>";
               echo "</div></li>";
           }
           echo "<div class='clear'></div>";
           echo "</div>";
           if($tipo == 0)
           echo "<div class='gra_top1' id='contra-box-menu'>Ver Todos</div>";
           echo "</div>";
       }
       catch(Exception $a)
       {
           if($tipo == 1)
           {
               echo "<div id='contatos-wrap$tipo' class='default-box2'>";
               echo "<div class='gra_top1'>Contatos Atuais</div>";
               echo "<div class='music-list-div-title-warning'>O usuário não possui nenhum contato ativo.</div>";
               echo "</div>";
           }
       }
   }
   
   private function loadComentarios($tipo)
   {
       /**
        *  Carrega os comentarios do usuário
        *  @param int $tipo
        */
       
       $user = $this->usuario;
       $root = $_SESSION['root'];
       $comentarios = new comentarios();       
       echo "<div id='comentario-info-profile' class='default-box3'><div id='info-comentario-wrap' class='gra_top1'><span class='ico-text'>Comentários</span></div><div id='comentario-real-wrap'>";
       if ($_SESSION['id_usuario'] != "")
       {
        echo "<div class='comment-box-p-title' id='bx-cmt'><textarea wrap='on' class='c-textarea' id='text-comment-n'></textarea><input type='button' value='Comentar' id='comment-button' class='btn' /><span id='c-error'>";
        if ($adc != true && $_POST['mode2'] == 'ncomment')
            echo "<span class='side-tip'>Adicione um comentário válido.</span>";
        echo "</span></div>";
       }
       
       $cm = $comentarios->listarComentario($user->id, 0);
       if ($cm != "")
       {
           foreach ($cm as $i => $v)
           {
               if ($tipo != 1 && $i == 5)
                   break;
               echo "<div class='comment-box-p' id='comentario-box-t-$v[0]'>";
               echo "<div class='title-c'><a href='$root/profile/$v[5]'>$v[7]</a> - $v[3]</div>";
               echo "<div class='box-img-c'>";
               if ($v[6] != "0")
                   echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
               else
                   echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
               echo "</div>";
               echo "<div class='comment-content'>";
               echo "<input type='hidden' id='content-ajax-$v[0]' value='$v[8]' />";
               echo "<div id='content-$v[0]'>$v[2]";
               if ($ec != true && $_POST['mode2'] == 'ecomment' && $_POST['cid'] == $v[0])
                   echo "<p><span class='side-tip'>Falha ao alterar o comentário.</span></p>";
               echo "</div>";
               echo "</div>";
               echo "<div class='comment-footer'>";
               if ($_SESSION['id_usuario'] == $v[1] || $_SESSION['id_usuario'] == $v[4])
               {
                   echo "<span class='del-comment' id='dc-$v[0]'>Deletar</span>";
                   if ($_SESSION['id_usuario'] == $v[4])
                       echo "<span class='edit-comment' id='ec-$v[0]-0'>Editar</span>";
               }
               echo "</div>";
               echo "</div>";
           }
           echo "<div id='results-comments-more'><div id='results-c-m0'></div></div>";
           if ($tipo == 1)
               echo "<div class='more-comments' id='more-comments'>Mais</div>";
       }
       elseif($_SESSION['id_usuario'] == "" && $cm == "")
       {
           echo "<div class='no-comments2'>Nenhum comentário encontrado.</div>";
       }
       echo "</div></div>";
       echo "<input type='hidden' id='comentario-page' value='0' />";
       echo "<input type='hidden' id='comentario-count' value='".count($cm)."' />";
       echo "<input type='hidden' id='ultimo-comentario' value='".$cm[0][0]."' />";
       unset($comentarios,$cm,$v,$tipo,$user,$root);
   }
   
   private function rankPos()
   {
       
   }
   
   private function loadTwt()
   {
       /**
        * Geramos uma janela para mostras os ultimos twites do usuário.
        */
       
       echo "<div id='general-twitter-profile' class='default-box1'><div id='info-twitter-wrap' class='gra_top1'><span class='ico-text'>".$this->usuario->nome." no Twitter</span></div><script src=\"http://widgets.twimg.com/j/2/widget.js\"></script>
       <script>
       new TWTR.Widget({
       version: 3,
       type: 'profile',
       rpp: 3,
       interval: 6000,
       width: 202,
       height: 140,
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
       }).render().setUser('".$this->usuario->twitter."').start();
       </script>
       </div>";
       
   }
   
   private function loadFb()
   {
       /**
        *  Carrega uma caixa com informações sobre o facebook do usuário.
        */
       //echo "<div id='general-facebook-profile' class='default-box1'><div id='info-facebook-wrap' class='gra_top1'><span class='ico-text'>$user->nome no Facebook</span></div><iframe src=\"http://www.facebook.com/plugins/activity.php?site=http%3A%2F%2Fwww.facebook.com%2Fprofile.php%3Fid%".$this->usuario->facebook."&amp;width=222&amp;height=280&amp;header=false&amp;colorscheme=light&amp;font=lucida+grande&amp;border_color=white&amp;recommendations=false\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:222px; height:280px; background: #fff;\" allowTransparency=\"true\"></iframe></div>";  
   }
   
   private function likeButton()
   {
       echo "<div id=\"fb-root\"></div><script src=\"http://connect.facebook.net/en_US/all.js#appId=249179468433204&amp;xfbml=1\"></script><fb:like href=\"http://" . $_SERVER['SERVER_NAME'] . "".$_SESSION['root']."/profile/".$this->usuario->login."\" send=\"false\" layout=\"button_count\" width=\"50\" show_faces=\"false\" font=\"\"></fb:like>";
   }
   
   private function loadRelacionados($tipo)
   {
       /**
        *  Carrega os usuários relacionados com o usuário.
        *  @param int $tipo
        */
       $rel = $this->usuario->usuariosRelacionados();
       $user = $this->usuario;
       $root = $_SESSION['root'];
       if($tipo == 0)
       {
           echo "<div id='relacionado-info-profile' class='default-box2'>";
           echo "<div id='relacionado-geral-wrap' class='gra_top1'><span>Usuários Relacionados</span></div>";
           echo "<div class='relacionados-wrap'>";
           echo "<ul>";
           $count = 0;
           if($rel != "")
           {
               foreach ($rel as $i => $v)
               {
                   if ($count == 10)
                       break;
                   echo "<li class='left'><div class='thumb-box4'>";
                   if ($v[2] != "0")
                       echo "<div class='thumb-img'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/$v[3]_thumb.$v[2]' class='thumb' alt='thumb' /></a></div> ";
                   else
                       echo "<div class='thumb-img'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></a></div>";
                   echo "<a href='$root/profile/$v[1]' class='blacka'>$v[0]</a>";
                   echo "</div></li>";
                   $count++;
               }
           }
           else
               echo "<li class='no-comments2'>Nenhum usuário relacionado</li>";
           echo "</ul>";
           echo "<div class='clear'></div>";
           if($rel != "")
               echo "<a href='$root/profile/$user->login/relacionados'><div class='box-cabecalho'>Ver Todos</div></a></div>";
           echo "</div>";
       }
       else
       {
           echo "<div class='default-box2' id='prof-rel-all-wrap'>";
           echo "<div class='gra_top1'>Usuários relacionados com $user->nome $user->sobrenome</div>";
           echo "<ul>";
           if($rel != "")
           {
               foreach ($rel as $i => $v)
               {
                   echo "<li class='left'><div class='thumb-box3'>";
                   echo "<div class='img-left-wrap'>";
                   if ($v[2] != "0")
                       echo "<div class='thumb-img2'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/$v[3]_thumb.$v[2]' class='thumb' alt='thumb' /></a></div> ";
                   else
                       echo "<div class='thumb-img2'><a href='$root/profile/$v[1]' ><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></a></div>";
                   echo "</div>";
                   echo "<div class='img-right-wrap'>";
                   echo "<a href='$root/profile/$v[1]' class='blacka'>$v[0] $v[4]</a>";
                   if ($v[6] != "")
                   {
                       echo "<div class='mini-rel'>";
                       echo "<strong>Gosto</strong> $v[6]";
                       echo "</div>";
                   }
                   if ($v[7] != "")
                   {
                       echo "<div class='mini-rel'>";
                       echo "<strong>Cidade</strong> $v[7]";
                       echo "</div>";
                   }
                   echo "</div>";
                   echo "</div></li>";
               }
           }
           else
               echo "<li class='no-comments'>Nenhum usuário relacionado</li>";
           echo "</ul>";
           echo "</div>";
       }
       unset($root,$v,$rel,$user);
   }
   
   private function loadPlaylists($tipo)
   {
       /**
        *  Carrega as playlists do usuário.
        *  
        */
       
       $playlist = new Playlist();
       $playlist->getUser($this->usuario->id);
       try
       {
           $lista_playlists = $playlist->listaPlaylists(true);
           echo "<div class='default-box3' id='playlist-info-profile' >";
           echo "<div class='gra_top1'>Playlists</div>";
           foreach($lista_playlists as $i => $v)
           {
               if($tipo == 0 && $i == 5)
                   break;
               echo "<div class='prf-pl-list' id='playlist-box-$v[0]'>";
               echo "<a href='".$_SESSION['root']."/playlists/$v[0]'>";
               echo "<div class='playlist-box'>";
               echo "<div class='playlist-gra-top'></div>";
               echo "<div class='playlist-box-left'></div>";
               echo "<div class='playlist-box-right'>";
               echo "<div class='playlist-box-image'>";
               if($v[5] != '0')
                   echo "<img src='".$_SESSION['root']."/imagens/playlists/$v[0].$v[5]' alt='$v[0]' />";
               echo "</div>";
               echo "<div class='playlist-box-title'>$v[2]</div>";
               echo "</div>";
               echo "</div>";
               echo "</a>";
               echo "</div>";
           }
           echo "</div>";
           
       }
       catch(Exception $a)
       {   
           if($tipo == 1)
           {
               echo "<div class='default-box3' id='playlist-info-profile' >";
               echo "<div class='gra_top1'>Playlists</div>";
               echo "<div class='no-comments2'>Nenhuma playlist encontrada</div>";
               echo "</div>";
           }
       }
       
       
       
   }
}

?>