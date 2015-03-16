<?
  /**********************************************
   * 
   * 
   *    Accordi© - 2011
   *    Arquivo: public.php - Página pública para visualizar e realizar operações sobre as músicas.
   *    Desenvolvida por Paulo Felipe Possa Parreira
   *    Descrição: 
   *      Página pública que permite visualizar as informações, ouvir a música, comentar a música e avaliar a música.
   *    Principais funções:
   *      - Layout de visualização das músicas
   *      - Função de avaliar as músicas.
   *      - Comentários das músicas.
   *      - Player da música.
   *
   * 
   * ********************************************/

  if (IN_ACCORDI != true)
  {
        exit();
  }
  
  class musica_interface
  {
      private $musica;
      private $key;
      private $info;
      
      public function __construct($id,$key,$ajax=false)
      {
          // Inícia a construção da classe.
          if($id == "" || !is_numeric($id))
          {
              $this->notFound();
              return;
          }
          $this->musica = new musicas();
          $this->key = $key;
          
          // Verifica se a música existe.
          $this->info = $this->musica->infoMusica($id,"public");
          if($this->info == false)
          {
              $this->notFound();
              return;
          }
          
          // Verifica se existe requisição de avaliação.
          if($_POST['mode'] == "aval")
          {
              $this->musica->avaliarMusica($_POST['id'],$_POST['val']);
          }
          // Verifica se o usuário possui permissão para visualizar a música.
          if($this->musica->isAllowed() == false)
          {
              $this->notAllowed();
              return;
          }
          else             
          {
              if($ajax == false)
              {
                  $this->musica->viewCount();
                  $this->loadBody();
                  return;
              }
              else
              {
                  $this->loadAjax();
              }
          }
      }
      
      private function loadBody()
      {
          /**
           *  Carrega as informações da música.
           */
          
          $musica = $this->musica;
          $root = $_SESSION['root'];
          
          echo "<div class='ajax-box-playlist'></div>";
          echo "<div class='left' id='musica-left-wrap'>";
          echo "<div class='left' id='music-real-wrap'>";
          echo "<input type='hidden' id='music-id' value='$musica->id' />"; // Id da música.
          echo "<script type='text/javascript'>b = new commentsClass(); b.reloadComments();</script>";
          
          echo "<div id='fb-like-m'>";
          echo "<div id='fb-root'></div>";
          echo "<script src=\"http://connect.facebook.net/en_US/all.js#appId=249179468433204&amp;xfbml=1\"></script><fb:like href=\"http://" . $_SERVER['SERVER_NAME'] . "$root/site/musica/$musica->id\" send=\"false\" layout=\"button_count\" width=\"50\" show_faces=\"false\" font=\"\"></fb:like>";
          echo "</div>";
          echo "<div id='music-wrapper-public' class='default-box2'>";
          
          // Informações Gerais
          
          echo "<div class='gra_top1'>Informações Gerais</div>";
          echo "<div id='music-menu-wrapper'>";
          echo "<a href='$root/site/musica/$musica->id/comentarios'><div id='music-show-comments'>Comentários</div></a>";
          echo "<a href='$root/site/musica/$musica->id/letras'><div id='music-show-letra'>Letra</div></a>";
          echo "<a href='$root/site/musica/$musica->id'><div id='music-show-todas'>Todas</div></a>";
          echo "</div>";
          echo "<div id='music-info-wrapper'>";
          echo "<div class='music-title'>$musica->nome</div>";
          echo "<div class='music-info'>$musica->genero, com duração de $musica->duracao por <strong>$musica->artista</strong></div>"; 
          echo "<div id='music-avaliacao-wrapper'>";
          
          //          Carrega sistema de avaliação.
          $this->carregaAvaliacao();
          echo "</div>";
          echo "<div class='music-view'>$musica->view visualizações</div>";
          echo "</div>";
          
          // Player de música
          echo "<div id='music-player-wrapper'>";
          echo "<object type=\"application/x-shockwave-flash\" value=\"transparent\" data=\"$root/swf/music.swf?dir=$root/files/musicas/$musica->id\" width=\"257\" height=\"130\" >";
          echo "<param name='wmode' value='transparent' />";
          echo "<param name='movie' value='$root/swf/music.swf?dir=$root/files/musicas/$musica->id' />";
          echo "<embed src=\"$root/swf/music.swf?dir=$root/files/musicas/$musica->id\" type=\"application/x-shockwave-flash\"  width=\"257\" height=\"130\" wmode=\"transparent\" quality=\"high\"></embed>";
          echo "</object>";
          echo "</div>";
          echo "</div>";
          
          echo "<div id='music-sub-left'>";
          // Carrega o vídeo do youtube
          $this->carregaYoutube();
          
          // Carrega informações sobre o usuário
          $this->carregaUInfo();
          
          echo "</div>";
          echo "<div id='music-sub-right'>";
          // Carrega letras e comentários
          if($this->key == 'letras')
                  $this->carregaLetras();
          elseif($this->key == 'comentarios')
                  $this->carregaComentarios(1);
          else
          {
              $this->carregaLetras();
              $this->carregaComentarios(0);
          }
          echo "</div>"; // Fecha aba de letras/comentários
          echo "</div>"; // Fecha aba esquerda
          echo "</div>"; // Fecha wrap
          
          // Carrega músicas relacionadas
          echo "<div class='left' id='music-right-wrap'>";
          $this->carregaRelacionadas();
          
          if($_SESSION['id_usuario'] != "")
          echo "<div class='btn-playlist' id='musica-$musica->id' >Adicionar na Playlist</div>";
          echo "</div>";
          echo "<div class='clear'></div>";
      }
      
      private function carregaAvaliacao()
      {
          /**
           *  Carrega o sistema de avaliações para música.
           */
          $usuario = new usuario();
          $aval = $this->musica->calculaAvaliacao();
          $am = $usuario->valorAval($this->musica->id);
          echo "<input type='hidden' id='aval-m' value='$am' />";
          echo "<input type='hidden' id='aval-true' value='$aval' />";
          for ($i = 1; $i <= 5; $i++)
          {
                if ($aval >= $i)
                      echo "<div class='aval-star2' id='aval-$i'></div>";
                else
                      echo "<div class='aval-star' id='aval-$i'></div>";
          }
          
          if ($am != false)
          {
                echo "<div id='show-aval'>$am</div>";
          }
      }
      
      private function carregaYoutube()
      {
          /**
           *  Carrega uma div contendo ID do youtube.
           */
          
          echo "<div id='music-clip-show' class='default-box2'>"; 
          echo "<div class='gra_top1'>Clipe</div>";
          if($this->musica->clipe != "")
          {
              echo "<object width=\"260\" height=\"230\">";
              echo "<param name='wmode' value='transparent' />";
              echo "<param name=\"movie\" value=\"http://www.youtube.com/v/".$this->musica->clipe."?fs=1\"></param>";
              echo "<embed src=\"http://www.youtube.com/v/".$this->musica->clipe."?fs=1\"
              type=\"application/x-shockwave-flash\"
              width=\"260\" height=\"230\" 
              allowfullscreen=\"true\" wmode=\"transparent\" quality=\"high\"></embed>";
              echo "</object>";
          }
          else
          {
              echo "<div class='novideowrap'>Vídeo Indisponível</div>";
          }
          echo "</div>";
      }
      
      private function carregaLetras()
      {
          /**
           *  Carrega as letras da música.
           */
          
          echo "<div id='music-lyrics-show' class='default-box2'>";
          echo "<div class='gra_top1'>Letra</div>";
          echo "<div class='music-lyrics'>";
          if($this->musica->letra != "")
              echo nl2br($this->musica->letra);
          else
              echo "Letra indisponível.";
          echo "</div>";
          echo "</div>";          
      }
      
      private function carregaComentarios($tipo)
      {
          /**
           *  Carrega o sistema de comentários
           *  @param int $tipo
           */
          
          // Instancia a classe comentários.
          $comentarios = new comentarios();
          // Especifica a variável $root
          $root = $_SESSION['root'];
          
          echo "<div id='music-comments-show' class='default-box3'>";
          echo "<div class='gra_top1'>Comentários</div>";
          echo "<div id='comentario-real-wrap'>";
          if ($_SESSION['id_usuario'] != "")
          {
              echo "<div class='comment-box-p3-title' id='bx-cmt'>";
              echo "<textarea class='c-textarea2' id='text-comment-n'></textarea>";
              echo "<input type='button' value='Comentar' id='comment-button2' class='btn' />";
              echo "<span id='c-error'>";
              echo "</span>";
              echo "</div>";
          }
          
          // Lista comentários
          $cm = $comentarios->listarComentario($this->musica->id, 1);
          if ($cm != "")
          {
            $count = 0;
            foreach ($cm as $i => $v)
            {
                if ($tipo == 0 && $i == 5)
                    break;
                echo "<div class='comment-box-p2' id='comentario-box-t-$v[0]'>";
                echo "<div class='title-c'><a href='$root/profile/$v[5]' class='title-c-a'>$v[7]</a> - $v[3]";
                if ($v[4] == $v[9])
                    echo "<span class='autor-badge'>Autor da Música</span>";
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
                echo "</div>";
                echo "</div>";
                echo "<div class='comment-footer3'>";
                if ($_SESSION['id_usuario'] == $v[9] || $_SESSION['id_usuario'] == $v[4])
                {
                    echo "<span class='del-comment' id='dc2-$v[0]'>Deletar</span>";
                    if ($_SESSION['id_usuario'] == $v[4])
                        echo "<span class='edit-comment' id='ec2-$v[0]'>Editar</span>";
                }
                echo "</div>";
                echo "</div>";
                $count++;
            }
            echo "<div id='results-comments-more'><div id='results-c-m0'></div></div>";
            if ($tipo == 1)
                echo "<div class='more-comments' id='more-comments'>Mais</div>";
            }
            elseif($_SESSION['id_usuario'] == "" && $cm == "")
            {
                echo "<div class='no-comments2'>Nenhum comentário encontrado.</div>";
            }
            echo "</div>";
            echo "<input type='hidden' id='comentario-page' value='0' />";
            echo "<input type='hidden' id='comentario-count' value='".count($cm)."' />";
            echo "<input type='hidden' id='ultimo-comentario' value='".$cm[0][0]."' />";
            echo "</div>";
      }
      
      private function carregaRelacionadas()
      {
          /**
           *  Carrega as músicas relacionadas.
           */
          
          try 
          {
          $rel = $this->musica->musicaRelacionadas();
          echo "<div id='music-wrapper-relacionadas' class='default-box1'>";
          echo "<div class='gra_top1'>Relacionadas</div>";
          echo "<ul>";
          $count++;
          foreach ($rel as $i => $v)
          {
              if ($count > 10)
                  break;
              echo "<a href='".$_SESSION['root']."/site/musica/$v[3]'><li class='rel-music'>$v[0]<div class='rel-ar'>$v[1] por $v[2]</div></li></a>";
              $count++;
          }
          echo "</ul>";
          echo "</div>";
          }
          catch(Exception $a)
          {
              
          }
      }
      
      private function loadAjax()
      {
          /** 
           * Carrega só as letras ou só os comentários.
           */
          
          switch($this->key)
          {
              case 'letra':
                  $this->carregaLetras();
                  break;
              case 'comentario':
                  $this->carregaComentarios(1);
                  break;
              default:
                  $this->carregaLetras();
                  $this->carregaComentarios(0);
                  break;
          }
          
      }
      
      private function notFound()
      {
          /**
           *  Carrega a página de error 404.
           */
          
          include("includes/site/404.php");
          
      }
      
      private function notAllowed()
      {
          /**
           *  Carrega uma página exibindo que você não tem permissões.
           */
          
          echo "<div class='warn-no-acess'>";
          echo "<h3>Música Indisponível.</h3>";
          echo "Esta música está disponível apenas para contratantes que possuem contatos ativos com o proprietário dela.";
          echo "</div>";
      }
      
      private function carregaUInfo()
      {
          /**
           *  Carrega informações sobre o usuário
           */
          
          if($this->musica->idartista != "")
          {
            $u = new usuario($this->musica->idartista);
            $mn = $u->numeroMusicas();
            $cn = $u->numeroContatos();
            echo "<div id='info-user-musica'>";
            echo "<a href='".$_SESSION['root']."/profile/$u->login'><div class='ium-title'>$u->nome</div></a>";
            echo "<span class='ium-info'>Possui $mn músicas e $cn contatos.</span>";
            echo "</div>";
          }
      }
      
      
      
      
      
      
      
  }
  