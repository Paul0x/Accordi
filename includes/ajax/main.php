<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: main.php - Realizar a chamada das funções AJAX.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página utilizada para redirecionar as chamadas AJAX realizadas pelo JAVASCRIPT.
 *    Principais funções:
 *      - Processar as chamadas realizadas via AJAX.
 *      - Enviar para o javascript as requisições recebidas.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

if($_POST['majax'] != true && is_null($_POST['token']))
    header("location: /accordi"); // Não ocorreu nenhuma requisição ajax so why u here? :{


/*
 *  Rotacionador de chamadas :p
 */
 switch($url[1])
 {
     case "main": include("main-ajax.php"); $mode = "main"; break;
     case "profile": include("p-ajax.php"); $mode = "profile"; break;
     case "events": include("e-ajax.php"); $mode= "evento"; break;
     case "music": include("m-ajax.php"); $mode = "musica"; break;
     case "contato": include("c-ajax.php"); $mode = "contato"; break;
     case "curriculum": include("cu-ajax.php"); $mode = "curriculum"; break;
     case "portal": include("pt-ajax.php"); $mode = "portal"; break;
     case "autocomplete": include("ac-ajax.php"); $mode= "autocomplete"; break;
     case "playlist": include("pl-ajax.php"); $mode='playlist'; break;
     case "validate": include("vl-ajax.php"); $mode='validate'; break;
     case "feed": include("at-ajax.php"); $mode="feed"; break;
 }
 
 
 /*
  *  Main
  */
  if($mode == "main")
  {
      $u_ajax = new AJAX;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "delete_comment": $u_ajax->deleteComment($_POST['id_c']); break;
          case "edit_comment": $u_ajax->editComment($_POST['id_c'],$_POST['text_c']); break;
      }
  }
 
 
 /*
  *  Profile
  */
  if($mode == "profile")
  {
      $u_ajax = new AJAXprofile;
      $u_ajax->getId($_POST['id_u']);
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "profile_info": $u_ajax->loadProfileInfo(); break;
          case "comment_request": $u_ajax->profileComment(0,$_POST['page']); break;
          case "add_comment": $u_ajax->addComment($_POST['id_r'],$_POST['text_c'],$_POST['tipo']); break;
          case "eventos_mes": $u_ajax->switchCalendario($_POST['mes'],$_POST['ano']); break;
          case "changeaba_profile": $u_ajax->loadProfileTabs($_POST['aba'],$_POST['id']); break;
          case "reload_comment": $u_ajax->profileReloadComments($_POST['max_id'],$_POST['rid']); break;
      }
  }
      
 /*
  *  Evento
  */
  if($mode == "evento")
  {
      $u_ajax = new AJAXevent;
      $u_ajax->getId($_POST['id_u']);
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "comment_request": $u_ajax->eventComment(2,$_POST['page']); break;
          case "add_comment": $u_ajax->addComment($_POST['id_r'],$_POST['text_c'],$_POST['tipo']); break;
          case "reload_comment": $u_ajax->eventsReloadComments($_POST['max_id'],$_POST['rid']); break;
          case "delete_evento": $u_ajax->getId($_POST['id']); $u_ajax->deletaEvento($_POST['step']); break;
          case "delete_participante": $u_ajax->getId($_POST['id_e']); $u_ajax->deleteParticipante($_POST['id_p']); break;
          case "load_edit": $u_ajax->getId($_POST['id']); $u_ajax->editarEvento(); break;          
          case "load_create": $u_ajax->criarEvento(); break;
          case "edit_evento": $u_ajax->getId($_POST['id']); $u_ajax->editaEvento($_POST['param']); break;
          case "edit_rede": $u_ajax->getId($_POST['id']); $u_ajax->editaRede($_POST['rede'],$_POST['valor']); break;
          case "update_eventosvisitados": $u_ajax->loadEventosParticipante($_POST['page']); break;
      }
  }

 /*
  *  Música
  */
  if($mode == "musica")
  {
      $u_ajax = new AJAXmusic;
      $u_ajax->getId($_POST['id_u']);
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "comment_request": $u_ajax->musicComment(1,$_POST['page']); break;
          case "add_comment": $u_ajax->addComment($_POST['id_r'],$_POST['text_c'],$_POST['tipo']); break;
          case "reload_page": $u_ajax->getId($_POST['id']); $u_ajax->loadPage($_POST['mode']); break;
          case "reload_comment": $u_ajax->musicReloadComments($_POST['max_id'],$_POST['rid']); break;
          case "carrega_info": $u_ajax->getId($_POST['id']); $u_ajax->loadInfo(); break;
          case "edit_musica": $u_ajax->getId($_POST['id']); $u_ajax->editMusica($_POST['step'],$_POST['nome'],$_POST['genero'],$_POST['classificacao'],$_POST['privacidade'],$_POST['clipe']); break;
          case "edit_letras": $u_ajax->getId($_POST['id']); $u_ajax->editLetra($_POST['letras']); break;
          case "delete_musica": $u_ajax->getId($_POST['id']); $u_ajax->musicDelete($_POST['step']); break;
          case "pesquisa_musica": $u_ajax->pesquisaMusica($_POST['nome'],$_POST['genero'],$_POST['page']); break;
      }
  }  
  
  
 /*
  *  Contato
  */
  if($mode == "contato")
  {
      $u_ajax = new AJAXcontato;
      $u_ajax->getId($_POST['id_u']);
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "comment_request": $u_ajax->contatoComment(3,$_POST['page']); break;
          case "contato_start": $u_ajax->createContato($_POST['step'],$_POST['id_r'],$_POST['data'],$_POST['valor'],$_POST['descricao'],$_POST['assunto']); break;
          case "add_comment": $u_ajax->addComment($_POST['id_r'],$_POST['text_c'],$_POST['tipo']); break;
          case "update_status": $u_ajax->updateStatus($_POST['id'], $_POST['status']); break;
          case "update_termos": $u_ajax->updateTermos($_POST['termos']); break;
          case "show_notificacoes": $u_ajax->mostraNotificacoes(); break;
          case "update_exigencias": $u_ajax->updateExigencias($_POST['idade'],$_POST['cidade'],$_POST['estado'],$_POST['pagamento'],$_POST['descricao']); break;
          case "contato_close": $u_ajax->closeContato($_POST['id_r'],$_POST['step']); break;
          case "reload_comment": $u_ajax->contatoReloadComments($_POST['max_id'],$_POST['rid']); break;
      }
  } 
  
  
 /*
  *  Curriculum
  */
  if($mode == "curriculum")
  {
      $u_ajax = new AJAXcurriculum;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "new_curriculum": $u_ajax->newCurriculum(); break;
          case "edit_campo": $u_ajax->editCampo($_POST['campo'],$_POST['valor'],$_POST['id_c']); break;
          case "delete_curriculum": $u_ajax->deleteCurriculum($_POST['id_c']); break;
          case "additemlista_curriculum": $u_ajax->getId($_POST['id_c']); $u_ajax->addItemLista($_POST['tipo'],$_POST['item']); break;
          case "delitemlista_curriculum": $u_ajax->getId($_POST['id_c']); $u_ajax->delItemLista($_POST['tipo'],$_POST['pos']); break;
          case "atualiza_curriculum": $u_ajax->getId($_POST['id_c']); $u_ajax->atualizaCurriculum(); break;
          case "loadinsert_curriculum": $u_ajax->getId($_POST['id_c']); $u_ajax->loadInsert($_POST['tipo']); break;
      }
  }  
  
 /*
  *  Portal
  */
  if($mode == "portal")
  {
      $u_ajax = new AJAXportal;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "new_noticia": $u_ajax->novaNoticia(); break;
          case "manage_portal": $u_ajax->managePortal(); break;
          case "deleta_noticia": $u_ajax->deletarNoticia($_POST['id'],$_POST['step']); break;
          case "edit_noticia": $u_ajax->editarNoticia($_POST['id'],$_POST['texto']); break;
          case "create_box": $u_ajax->createBox($_POST['step'],$_POST['tipo'],$_POST['noticia']); break;
          case "delete_box": $u_ajax->deleteBox($_POST['id']);
          case "bcr_page": $u_ajax->loadNoticias($_POST['page'],1); break;
          case "bcr_loadnoticia": $u_ajax->loadNoticias(0,0); break;
          case "eventos_mes": $u_ajax->loadCalendario($_POST['mes'],$_POST['ano']); break;
          case "add_comment": $u_ajax->addComment($_POST['id_r'],$_POST['text_c'],$_POST['tipo']); break;
          case "comment_request": $u_ajax->getId($_POST['id_u']); $u_ajax->noticiaComment(4,$_POST['page']); break;
          case "reload_comment": $u_ajax->portalReloadComments($_POST['max_id'],$_POST['rid']); break;
      }
  }
  
 /**
  *  Autocomplete
  */ 
  if($mode == "autocomplete")
  {
      $u_ajax = new AJAXautocomplete;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "auto_complete": $u_ajax->autoComplete($_POST['id'],$_POST['valor']); break;
      }    
  }
 
 /**
  *  Playlist
  */ 
  if($mode == "playlist")
  {
      $u_ajax = new AJAXplaylist;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "delete_playlist": $u_ajax->getId($_POST['id']); $u_ajax->deletePlaylist(); break;
          case "delete_musica": $u_ajax->getId($_POST['id_p']); $u_ajax->deleteMusica($_POST['id_m']); break;
          case "add_musica": $u_ajax->addMusica($_POST['step'],$_POST['id_m'],$_POST['id_p']); break;
          case "add_playlist": $u_ajax->addPlaylist($_POST['step'],$_POST['nome'],$_POST['id_p'],$_POST['id_m']); break;
          case "start_playlist": $u_ajax->getId($_POST['id_p']); $u_ajax->startPlaylist($_POST['id_m']); break;
          case "trocar_musica": $u_ajax->getId($_POST['id_p']); $u_ajax->trocarMusica($_POST['id_m'],$_POST['aleatorio']); break;
          case "playlist_reply": $u_ajax->replicarPlaylist($_POST['id_p']); break;
          case "playlist_privacidade": $u_ajax->getId($_POST['id_p']); $u_ajax->alterarPrivacidade($_POST['privacidade']); break;
          case "sort_playlist": $u_ajax->getId($_POST['id_p']); $u_ajax->organizarPlaylist($_POST['tipo']); break;
          case "show_musicas_tip": $u_ajax->getId($_POST['id']); $u_ajax->ajaxListMusicas(); break;
          case "ddsort_playlist": $u_ajax->getId($_POST['id_p']); $u_ajax->ddPlaylist($_POST['positions']);  break;
      }    
  }

 /**
  *  Validate
  */ 
  if($mode == "validate")
  {
      $u_ajax = new AJAXvalidate;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "cadastra_usuario": $u_ajax->cadastrarUsuario($_POST['login'],$_POST['senha'],$_POST['nome'],$_POST['email'],$_POST['tel'],$_POST['logd'],$_POST['bairro'],$_POST['cidade'],$_POST['estado'],$_POST['website'],$_POST['apelido'],$_POST['sobrenome'],$_POST['tipo']); break;
          case "valida_email": $u_ajax->validaEmail($_POST['email'],$_POST['id']); break;
          case "valida_login": $u_ajax->validaLogin($_POST['login']); break;
          case "valida_senha": $u_ajax->validaSenha($_POST['senha']); break;
      }    
  }
  
 /**
  *  Feed
  */ 
  if($mode == "feed")
  {
      $u_ajax = new AJAXfeeds;
      $u_ajax->getToken($_POST['token']);
      switch($_POST['action'])
      {
          case "load_feed": $u_ajax->getId($_POST['id_u']); $u_ajax->loadFeed($_POST['first_feed'],$_POST['is_feed']); break;
          case "load_newest": $u_ajax->getId($_POST['id_u']); $u_ajax->loadNewest($_POST['last_id']); break;
          case "acompanhar_user": $u_ajax->getId($_SESSION['id_usuario']); $u_ajax->addAcompanhante($_POST['user_id']); break;
          case "desacompanhar_user": $u_ajax->getId($_SESSION['id_usuario']); $u_ajax->delAcompanhante($_POST['user_id']); break;
      }    
  }
  

?>
