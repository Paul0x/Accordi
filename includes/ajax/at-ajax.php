<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: profile.php - Pegar informações relaciondas com o perfil.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Enviar o código HTML para o servidor pós requisição AJAX.
 *    Principais funções:
 *      - Processar as chamadas realizadas via AJAX.
 *      - Enviar para o javascript as requisições recebidas.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

require("main-ajax.php");

class AJAXfeeds extends AJAX
{
    /**
     *  Id do usuário
     * 
     */
    private $id;
    
    /**
     *  Classe atualização
     * 
     */
    private $atualizacao;
    
    
   public function __construct()
   {
       /**
        *  Inicia a classe de atualização
        */
       
       $this->atualizacao = new Atualizacoes();
       
   }
   
   public function getId($id)
   {
       /**
        *  Adiciona o ID do usuário.
        *  @param int $id
        * 
        */
       
       if($id == "" || !is_numeric($id))
           return false;
       else
       {
           $this->id = $id;
           return true;
       }
       
   }
   
   private function loadBoxes($feeds)
   {
       /**
        *  Realiza a formatação das box nos feeds.
        * 
        */
       
       if(!is_array($feeds))
       {
           throw new Exception("Feeds inválidos.");
       }
           
       $root = $_SESSION['root'];
       
       foreach($feeds as $i => $v)
       {
           // As informações do feed já serão pré-formatadas para serem imprimidas no javascript.
           $atualizacoes[$i]['nome_usuario'] = $v['nome_usuario'];
           $atualizacoes[$i]['login_usuario'] = $v['login_usuario'];

           // Verifica a imagem
           if($v['imagem_usuario'] == '0')
               $atualizacoes[$i]['imagem_usuario'] = "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='Thumb' class='thumb' />";
           else
               $atualizacoes[$i]['imagem_usuario'] = "<img src='$root/imagens/profiles/".$v['id_usuario']."_thumb.".$v['imagem_usuario']."' class='thumb alt='Thumb' /> ";

           // Pega a data
           $atualizacoes[$i]['data_formatada'] = $v['data_formatada'];

           // Agora pega o corpo da página
           switch($v['tipo_feed'])
           {
              case "0":
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " criou a música ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/site/musica/".$v['id_musica']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_musica']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/mu_ico.png' class='ffl-ico' alt='Música' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case "1":
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " criou o evento ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/site/evento/".$v['id_evento']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_evento']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/ev_ico.png' class='ffl-ico' alt='Evento' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case "2":
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " criou a playlist ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/playlists/".$v['id_playlist']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_playlist']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/pl_ico.png' class='ffl-ico' alt='Música' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case "31":
                  switch($v['tipo_recado'])
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
                         $dn = explode("-",$v['data_original']);
                         $recado_sintax = array("na notícia","portal/show/$dn[0]/$dn[1]");
                         break;                           
                  }
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " criou um recado $recado_sintax[0] ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/$recado_sintax[1]/".$v['identificacao_receptor']."/comentarios/#content-".$k['id_recado']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_receptor']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "<div class='ffl-recado-text'>";
                  $atualizacoes[$i]['escopo_box'].= $v['texto_recado'];
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;  
              case '32':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " recebeu um recado de ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/profile/".$v['identificacao_criador']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_criador']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "<div class='ffl-recado-text'>";
                  $atualizacoes[$i]['escopo_box'].= $v['texto_recado'];
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '4':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " confirmou participação no evento ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/site/evento/".$v['id_evento']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_evento']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/ev_ico.png' class='ffl-ico' alt='Evento' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '5':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " criou um curriculum. ";      
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Curriculum' />";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '6':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " atualizou seu curriculum. ";      
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Curriculum' />";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '7':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " avaliou a música ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/site/musica/".$v['id_musica']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_musica']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/mu_ico.png' class='ffl-ico' alt='Música' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '8':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " fechou contato com ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/profile/".$v['login_usuario2']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_usuario2']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              case '9':
                  $atualizacoes[$i]['escopo_box'] = "<div class='ffl-itens'>";
                  $atualizacoes[$i]['escopo_box'].= " está acompanhando ";
                  $atualizacoes[$i]['escopo_box'].= "<a href='$root/profile/".$v['login_usuario2']."' class='nochange2'>";
                  $atualizacoes[$i]['escopo_box'].= "<strong>".$v['nome_usuario2']."</strong>";       
                  $atualizacoes[$i]['escopo_box'].= "<img src='$root/imagens/site/note_ico.png' class='ffl-ico' alt='Recado' />";
                  $atualizacoes[$i]['escopo_box'].= "</a>";
                  $atualizacoes[$i]['escopo_box'].= "</div>";
                  break;
              
           }
       }
           
       return $atualizacoes;
   }
   
   public function loadFeed($first_feed,$is_feed)
   {
       /**
        *  Carrega as atualizações 
        *  @param int $page        *  
        *  @param bool $is_feed 
        * 
        */
       
       try
       {
           if($this->id == "")
               throw new Exception("Id inválido.");
           
           
           $this->atualizacao->getId($this->id);
           
           if($is_feed == 'false')
               $is_feed = false;
           else
           {
               $this->atualizacao->loadFeed();
               $is_feed = true;
           }           
           
           $feed_list = $this->atualizacao->loadAtualizacoes($first_feed,$is_feed);
           $atualizacoes = $this->loadBoxes($feed_list);
           
           $first_feed = array_pop($feed_list);
           $first_feed = $first_feed['id_feed'];
           echo json_encode(array("response" => 1, "feed" => $atualizacoes, "first_feed" => $first_feed));
           
       }
       catch(Exception $a)
       {
           echo json_encode(array("response" => 0, "error" => $a->getMessage()));
       }
       
   }
   
   public function loadNewest($last_id)
   {
       /**
        *  Verifica novos comentários baseando-se em um último ID.
        *  @param int $last_id;
        */
       
       try
       {
           if(!is_numeric($last_id))
               throw new Exception("ID inválido.");
           
           $this->atualizacao->getId($this->id);
           $this->atualizacao->loadFeed();
           $feed_list = $this->atualizacao->loadAtualizacoes(0,true,$last_id);
           $atualizacoes = $this->loadBoxes($feed_list);
           
           // Pega o id do último feed.
           $last_feed = $feed_list[0]["id_feed"];
           
           // Conta o número de feeds que apareceram.
           $count_feeds = count($feed_list);
           
           echo json_encode(array("response" => 1, "feeds" => $atualizacoes, "last_feed" => $last_feed, "count_feeds" => $count_feeds));
           
       }
       catch(Exception $a)
       {
           echo json_encode(array("response" => 0, "error" => $a->getMessage()));           
       }
   }
   
   public function addAcompanhante($user_id)
   {
       /**
        *  Adiciona um acompanhante para os feeds.
        *  @param int $user_id
        */
       
       try
       {
           $this->atualizacao->getId($this->id);
           $this->atualizacao->acompanharUser($user_id);
           echo json_encode(array("response" => 1));           
       }
       catch(Exception $a)
       {
           echo json_encode(array("response" => 0, "error" => $a->getMessage()));
       }
   }
   
   public function delAcompanhante($user_id)
   {
       /**
        *  Adiciona um acompanhante para os feeds.
        *  @param int $user_id
        */
       
       try
       {
           $this->atualizacao->getId($this->id);
           $this->atualizacao->deleteAcompanhar($user_id);
           echo json_encode(array("response" => 1));           
       }
       catch(Exception $a)
       {
           echo json_encode(array("response" => 0, "error" => $a->getMessage()));
       }
   }
   
}




?>