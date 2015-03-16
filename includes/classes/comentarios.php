<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Eventos                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.

class comentarios
{
    
    /*
     *   Atributos privados
     */
        
    private $validate;
    private $conn;
    private $usuario;
    private $tipo;
    private $data;
    
    /*
     *  Atributos públicos
     *  Utilizados para interagir com o script.
     */
    
    public $id;
    public $cid;
    public $rid;
    public $commentarray;
    
    
    /**
     *   Breve explicação sobre os tipos de recado.
     *   0 = Recado no perfil. Onde id_receptor é o ID do usuário.
     *   1 = Recado em música. Onde id_receptor é o ID da musica.
     *   2 = Recado em eventos. Onde id_receptor é o ID do evento.
     *   3 = Recado no contrato. Onde id_receptor é o ID do contrato.
     *   4 = Recado na notícia. Onde id_receptor é o ID da notícia
     */
    
    
    public function __construct()
    {
        $this->conn = new conn();
        $this->validate = new validate();
    }
    
    public function bbCode($msg,$autobreakline=true,$noticiareplace=false)
    {
      // Vamos realizar a formatação necessária nos bbCodes.
      
      $msg = str_replace(array("\r\n","\n","\r"),'<br />',$msg); 
      
            
      // Replaces padrões de BBcode
      //
      // - Negrito
      // - Itálico
      // - Sublinhado
      // - Cor
      // - Lista
      
      $bb[0] = "/(\[b\])+(.*?)(\[\/b\])+/";
      $bb[1] = "/(\[i\]){1}(.*?)(\[\/i\]){1}/";
      $bb[2] = "/(\[u\]){1}(.*?)(\[\/u\]){1}/";
      $bb[3] = "/(\[color=){1}([a-zA-Z\']+)(\]){1}(.*?)(\[\/color\]){1}/";
      $bb[4] = "/(\[\*\])+(.*?)(\[\/\*\])+/";
      $replace[0] = "<strong>$2</strong>";
      $replace[1] = "<span class=italic>$2</span>";
      $replace[2] = "<span class=underline>$2</span>";
      $replace[3] = "<font color=$2>$4</font>";
      $replace[4] = "<li class='bbul'>$2</li>";      
      $msg = preg_replace($bb, $replace, $msg);
      $msg = str_replace("</li><br /><li class='bbul'>","</li><li class='bbul'>",$msg);            
      while(preg_match($bb[3],$msg) != 0)
      {
          $msg = preg_replace($bb[3], $replace[3], $msg);
      } 
      $bb = "";
      
      // Replaces de notícias
      // - Parágrafo
      // - Imagem
      // - Link
      //
      if($noticiareplace == true)
      {
          $bb[0] = "/(\[p\])+(.*?)(\[\/p\])+/";
          $bb[1] = "/(\[img src=){1}(.*?)(float=([a-zA-Z]*?))?( \/\]){1}/";
          $bb[2] = "/(\[a href=){1}(.*?)(\]){1}(.*?)(\[\/a\]){1}/";
          $bb[3] = "/(\[clear\])/";
          $replace[0] = "<p>$2</p>";   
          $replace[1] = "<img src=$2 alt=Imagem class=pt-img-$4 />";
          $replace[2] = "<a href=http://$2 class=link-blue>$4</a>";
          $replace[3] = "<div class=clear></div>";
          $msg = preg_replace($bb, $replace, $msg);
      }
      
      
      

      $codigo_acentos = array('&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&Ccedil;','&ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;',
                        '&egrave;','&eacute;','&ecirc;','&euml;',
                        '&Igrave;','&Iacute;','&Icirc;','&Iuml;',
                        '&igrave;','&iacute;','&icirc;','&iuml;',
                        '&Ntilde;',
                        '&ntilde;',
                        '&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;',
                        '&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;',
                        '&Ugrave;','&Uacute;','&Ucirc;','&Uuml;',
                        '&ugrave;','&uacute;','&ucirc;','&uuml;',
                        '&Yacute;',
                        '&yacute;','&yuml;',
                        '&ordf;',
                        '&ordm;','&quot;','&#039;');


      $acentos = array('À','Á','Â','Ã','Ä','Å','à','á','â','ã','ä','å','Ç', 'ç',
                       'È','É','Ê','Ë',
                       'è','é','ê','ë',
                       'Ì','Í','Î','Ï',
                       'ì','í','î','ï',
                       'Ñ',
                       'ñ',
                       'Ò','Ó','Ô','Õ','Ö',
                       'ò','ó','ô','õ','ö',
                       'Ù','Ú','Û','Ü',
                       'ù','ú','û','ü',
                       'Ý',
                       'ý','ÿ',
                       'ª',
                       'º',"\"","'");
      
      // Adiciona autobreak lines para os comentários
      $msg = str_replace($codigo_acentos, $acentos, $msg);
      if($autobreakline == true)
      {
          $r1 = "/([^ <>\t]{50})(.*?)/";
          $r2 = "$1<br />$2";
          $msg = preg_replace($r1,$r2,$msg);
      }
      $msg = str_replace($acentos, $codigo_acentos, $msg);
      
      return $msg;
    }
    
    public function novoComentario($idc,$idr,$tipo,$msg)
    {
       $a = ""; // Variável de verificação
       
       $msg = trim($msg);
       if($msg == "") // Não enviaremos comentários vazios.
           return false;
       
       if($idc == "" || $idr == "")
           return false;
       
       // Verifica o número de caracteres do comentário
           if(strlen($msg) > 250)
               return false;
       
       // Não é necessário fazer validação durante a inserção de dados, visto que a mesma já é completamente feita pela classe de conexão.      
       $this->conn->prepareinsert("recado", array($idc,$idr,$tipo,$msg), array("id_criador","id_receptor","tipo","texto"), array("INT","INT","INT","STR"));
       $a = $this->conn->executa();
       
       
       // Envia o recado para o facebook.
       if($a == true)
       {
           
       // Verifica se o comentário é dentro de um contato, temos que passar a notificação.
       if($tipo == 3)
       {
           $c = new contato();
           $c->getId($idr);
           $info = $c->pegarInfo();
           if($info[1] == $idc)
               $c->alterarNotificacao(3);
           else
               $c->alterarNotificacao(2);
           unset($c,$info);
       }       
       
           $u = new usuario($idc);
           
           if($u->facebook != "")
           {
           switch($tipo)
           {
           case 0:
               $u2 = new usuario($idr);
               $msgf = "
               Accordi - Social Plugin
               O usuário $u->nome criou um recado no perfil de $u2->nome -> $msg
               http://www.accordi.com.br/profile/$u2->login";
               break;
           case 1:
               $u2 = new musicas;
               $u2->infoMusica($idr,"public");
               $msgf = "
               Accordi - Social Plugin
               O usuário $u->nome criou um recado na música $u2->nome -> $msg
               http://www.accordi.com.br/site/musica/$idr";
           break;
           case 2:
               $u2 = new eventos;
               $u2->id = $idr;
               $u2->pegaInfo();
               $msgf = "
               Accordi - Social Plugin
               O usuário $u->nome criou um recado no evento $u2->nome -> $msg
               http://www.accordi.com.br/site/evento/$idr";
           break;
           case 4:
               $u2 = new Portal();
               $u2->getId($idr);
               $list = $u2->pegaInfo();
               $msgf = "
               Accordi - Social Plugin
               O usuário $u->nome criou um recado na notícia $list[1] -> $msg 
               http://www.accordi.com.br/portal/show/$list[12]";
           }
           $app = new snapps;
           $app->postOnWall($msgf);
           unset($app,$idc,$idr,$msgf,$tipo);
           }
           return true;
       }
       else
           return false;
    }
    
    public function listarComentario($rid,$tipo,$page=array(0,20))
    {
       if(!is_numeric($rid) || !is_numeric($tipo))
               return false;
       $this->rid = $rid;
       $this->tipo = $tipo;
       $this->conn->prepareselect("recado","id",array("id_receptor","tipo"),array($rid,$tipo),"same","","",PDO::FETCH_COLUMN,"all",array("id","DESC"),$page);
       $this->conn->executa();
       if($this->conn->fetch == "")
               return false;
       foreach($this->conn->fetch as $i => $v) 
       {
         $this->id = $v;  
         $lista[] = $this->publicarComentario();  
       }
       unset($rid,$tipo,$page);
       return $lista;
    }
    
    public function publicarComentario()
    {
        /*
         * Método para pegar todas as informações necessárias para exibir um comentário.
         * 
         */
        if($this->id == "" || !is_numeric($this->id))
                return false;
        $validarecado = $this->validate->ifExist($this->id,"id","recado");
        $this->conn->prepareselect("recado",array("id","id_criador","tipo","id_receptor","texto","data"),"id",$this->id);
        $this->conn->executa();
        $recado = $this->conn->fetch; // Atribuimos as informações recebidas da query ao array recado.
        
        if($this->conn->rowcount == 0)
                return false; // Id ou comentário inválidos.
        
        $this->usuario = new usuario($recado['id_criador_recado']);
        
        /* Verificamos se o Criador do recado é o dono da música/evento */
        if($recado['tipo_recado'] == 1)
        {
            $m = new musicas();
            $m->infoMusica($recado['id_receptor_recado'],"public");
            $owner = $m->aid;
            unset($m);
        }
        elseif($recado['tipo_recado'] == 2)
        {
            $e = new eventos();
            $e->id = $recado['id_receptor_recado'];
            $e->pegaInfo();
            $owner = $e->contratante;
            unset($e);
        }
        else
            $owner = $recado['id_receptor_recado'];
        
        // Formatamos o texto para nossos padrões de comentários.
        $rnobb = $recado['texto_recado'];
        $recado['texto_recado'] = $this->bbCode($recado['texto_recado']);
        
        $d = new dataformat();
        $d->pegarData($recado['data_recado']);
        $a = $d->defineHorario();
               
        // Agora vamos repassar tais informações via array e utiliza-las durante o script.
        $this->comentarioarray = array ($recado["id_recado"],$recado["id_receptor_recado"],$recado['texto_recado'],$a,$this->usuario->id,$this->usuario->login,$this->usuario->imagem,$this->usuario->nome,$rnobb,$owner);        
        unset($d,$a,$owner,$recado,$validarecado);
        return $this->comentarioarray;
    }
    
    public function deletarComentario()
    {
       if($this->id == "" || !is_numeric($this->id))
               return false;
       
       $comentario = $this->publicarComentario(); // Pegamos as informações do comentário.
       
       if($comentario == false)
           return false;
       
       if($_SESSION['id_usuario'] != $comentario[9] && $_SESSION['id_usuario'] != $comentario[4])
           return false; // Verificamos se os criadores são válidos.
       
       $this->conn->preparedelete("recado","id",$this->id);
       $a = $this->conn->executa();
       unset($comentario);
       return $a;
       
    }
    
    public function loadNew($id,$tipo,$rid)
    {
        /**
         *  Carrega os últimos comentários a partir de um id informado.
         *  @param id
         * 
         */
        
        if(!is_numeric($id) || !is_numeric($tipo) || !is_numeric($rid))
        {
            throw new Exception("O id que foi enviado é inválido.");
        }
        
        // Verifica o número de comentários que existem próximos ao id.
        
        
        $query = $this->conn->freeQuery("SELECT count(id_recado) FROM recado WHERE id_recado > $id AND id_receptor_recado = $rid AND tipo_recado = $tipo");
        
        // Retornamos o número de recados encontrados.
        return $query[0];
        
        
        
    }
    
    public function editarComentario($text)
    {
        
       if($this->id == "" || !is_numeric($this->id))
               return false;

       $comentario = $this->publicarComentario(); // Pegamos as informações do comentário.

       if($comentario == false)
           return false;
       
       if($text == "")
          return false;
       
       if($_SESSION['id_usuario'] != $comentario[4])
           return false; // Verificamos se os criadores são válidos.
       
       $this->conn->prepareupdate($text, "texto", "recado", $this->id, "id", "STR");
       $a = $this->conn->executa();
       unset($comentario,$text);
       return $a;
       
       
    }
}
?>