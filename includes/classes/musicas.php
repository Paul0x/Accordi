<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Ranking                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.

require("getid3.php");
class musicas
{

   public $id;
   public $nome;
   public $genero;
   public $duracao;
   public $classificacao;
   public $letra;
   public $artista;
   public $clipe;
   public $permissao;
   public $permissaon;
   public $listamusicas;
   public $avaliacao;
   public $aid;
   public $view;
   public $navaliacao;
   private $conn;

   function __construct()
   {
      $this->conn = new conn();
   }

   public function carregaMusicasArtista()
   {
      if ($_SESSION['id_usuario'] == NULL && $this->artista == "")
      {
         return false;
      }
      if ($this->artista == "" || !is_numeric($this->artista))
         $this->artista = $_SESSION['id_usuario'];
      $this->conn->prepareselect("musica", "", "id_artista", $this->artista, "same", "", "", null, "all", array("id", "DESC", "genero", "ASC"));
      $this->conn->executa();
      $resultado = $this->conn->fetch;
      if($this->conn->rowcount == 0) return false;
      for ($i = 0; $i < count($resultado); $i++)
      {
         $c = $this->dC($resultado[$i]['classificacao_musica']);
         $p = $this->dP($resultado[$i]['permissao_musica']);
         $this->listamusicas[] = array($resultado[$i]['id_musica'], $resultado[$i]['nome_musica'], $resultado[$i]['genero_musica'], $resultado[$i]['duracao_musica'], $c, $resultado[$i]['letra_musica'], $resultado[$i]['id_artista_musica'], $p, $resultado[$i]['data_insercao_musica']);
      }
      if ($this->listamusicas != null)
      {
         return true;
      }
      else
      {
         return false;
      }
   }

   public function viewCount()
   { 
     $this->conn->prepareupdate($this->view_week+1, "view_week", "musica", $this->id, "id", "INT");
     $this->conn->executa();
   }
   
   public function musicaRelacionadas()
   {
       $this->conn->prepareselect("musica","id",array("id_artista","id"),array($this->aid,$this->id),array("=","!="),"","",PDO::FETCH_COLUMN,"all","",array("0","10"));
       $this->conn->executa();
       if($this->conn->fetch == null)
               $this->conn->fetch = array ();
       $us = $this->conn->fetch;
       $this->conn->prepareselect("musica","id",array("genero","id"),array($this->genero,$this->id),array("=","!="),"","",PDO::FETCH_COLUMN,"all","",array("0","20"));
       $this->conn->executa();
       if($this->conn->fetch == null)
               $this->conn->fetch = array ();
       $us = array_merge($us,$this->conn->fetch);
       shuffle($us);
       $us = array_unique($us);
       if($us[0] == "")
           throw new Exception("Nenhuma música relacionada.");
       foreach($us as $i => $v)
       {
           $m = new musicas();
           $m->infoMusica($v,"public");
           $lista[] = array($m->nome,$m->genero,$m->artista,$m->id);           
       }
       unset($m);
       return $lista;
   }
   public function dP($p)
   {
      if ($p == 0)
      {
         $a = "Pública";
      }
      elseif ($p == 1)
      {
         $a = "Apenas Contatos";
      }
      else
      {
         $a = "Indefinida";
      }
      return $a;
   }
   
   public function dC($c)
   {
       if($c == '0')
       {
         return "Livre";   
       }
       else
       {
         return "$c anos";
       }    
   }
   
   public function avaliarMusica($id,$valor)
   {
      if(!is_numeric($id) || $id == "" || $_SESSION['id_usuario'] == null)
          return false;
      if($valor > 5 || $valor < 0)
          return false;
      $v = new validate();
      $existe = $v->ifExist(array($_SESSION['id_usuario'],$id), array("id_usuario","id_musica"), "avaliacao");
      unset($v);
      if($existe == false){
          $this->conn->prepareupdate($valor, "valor", "avaliacao",array($_SESSION['id_usuario'],$id), array("id_usuario","id_musica"));
          }
      else
      {
      $campos = array("id_usuario","valor","id_musica");
      $valores = array($_SESSION['id_usuario'],$valor,$id);
      $this->conn->prepareinsert("avaliacao",$valores,$campos);
      }
      $this->conn->executa();
      return true;
   }
   

   public function infoMusica($id,$mode="private")
   {
      if(!is_numeric($id))
      {
          return false;
      }
      $this->conn->prepareselect("musica", "", "id", $id);
      $this->conn->executa();
      $resultado = $this->conn->fetch;
      if ($this->conn->fetch == "")
      {
         return false;
      }
      if ($_SESSION['id_usuario'] != $resultado['id_artista_musica'] && $mode == "private")
      {
         return false;
      }
      $this->id = $id;
      $this->nome = $resultado['nome_musica'];
      $this->genero = $resultado['genero_musica'];
      $this->duracao = $resultado['duracao_musica'];
      $this->classificacao = $resultado['classificacao_musica'];
      $this->letra = ($resultado['letra_musica']);
      $artista = new validate();
      $artista->pegaInfo($resultado['id_artista_musica']);
      $this->aid = $resultado['id_artista_musica'];
      $this->artista = $artista->nome;
      $this->idartista = $resultado['id_artista_musica'];
      $this->permissao = $this->dP($resultado['permissao_musica']);
      $this->permissaon = $resultado['permissao_musica'];
      $this->clipe = $resultado['clipe_musica'];
      $this->view = $resultado['view_musica'] + $resultado['view_week_musica'];
      $this->view_week = $resultado['view_week_musica'];
      $this->timestamp = $resultado['data_insercao_musica'];
      return true;
   }

   public function editaMusica($nome, $genero, $classificacao, $permissao, $clipe)
   {
      /**
       *  Edita uma música
       *  @param $nome
       *  @param $genero
       *  @param $classificacao
       *  @param $permissao
       *  @param clipe
       * 
       */
       
      if($this->id == "" || !is_numeric($this->id))
      {
          throw new Exception("Música inválida.");
      }
      
      if($this->infoMusica($this->id,"private") == false)
      {
          throw new Exception("Você não tem permissão para editar a música.");
      }
      
      $nome = trim($nome);
      $genero = trim($genero);
      if ($nome == "" || $genero == "")
      {
         throw new Exception("Dados incompetos");
      }
      
      if(!is_numeric($permissao))
      {
          throw new Exception("Permissão inválida.");
      }
      
      if(!is_numeric($classificacao))
      {
          throw new Exception("Classificação inválida.");
      }
      
      
      if (($nome) != $this->nome)
         $this->nome = ($nome);
      if (($genero) != $this->genero)
         $this->genero = ($genero);
      if (($classificacao) != $this->classificacao)
         $this->classificacao = ($classificacao);
      if (($clipe) != $this->clipe)
         $this->clipe = ($clipe);
      if (($permissao) != $this->permissaon)
         $this->permissaon = ($permissao);
      $valores = array($this->nome, $this->genero, $this->classificacao, $this->permissaon, $this->clipe);
      $campos = array("nome", "genero", "classificacao", "permissao", "clipe");
      $bind = array("STR", "STR", "INT", "INT", "STR");
      $this->conn->prepareupdate($valores, $campos, "musica", $this->id, "id", $bind);
      $sql = $this->conn->executa();
      if ($sql != true)
      {
          throw new Exception("Falha ao editar música.");
      }
   }

   public function deletaMusica()
   {
      /**
       *  Deleta a musica existente de acordo com o ID.
       */
       
      if($this->id == "" || !is_numeric($this->id))
      {
          throw new Exception("Nenhuma música selecionada");
      }
      
      if($this->infoMusica($this->id,"private") == false)
      {
          throw new Exception("Permissão inválida ou música inexistente.");
      }
      
      
      $this->conn->preparedelete("musicas_playlist", "id_musica", $this->id);
      $a4 = $this->conn->executa();
      
      if($a4 != true)
      {
          throw new Exception("Falha ao deletar música");
      }
            
      $this->conn->preparedelete("recado",array("tipo","id_receptor"),array("1",$this->id));
      $a3 = $this->conn->executa();
      
      if($a3 != true)
      {
          throw new Exception("Falha ao deletar música");
      }
      
      $this->conn->preparedelete("musica", "id", $this->id);
      $a1 = $this->conn->executa();
      
      if($a1 != true)
      {
          throw new Exception("Falha ao deletar música");
      }
      
      $this->conn->preparedelete("avaliacao", "id_musica", $this->id);
      $a2 = $this->conn->executa();
      
      if($a2 != true)
      {
          throw new Exception("Falha ao deletar música");
      }
      
      unlink("./files/musicas/".$this->id.".mp3");
      
      
      
      
   }

   public function calculaAvaliacao()
   {
      if ($this->id == "" || !is_numeric($this->id))
      {
         return false;
      }
      $this->conn->prepareselect("avaliacao", "id", "id_musica", $this->id, "same", "count");
      $this->conn->executa();
      $total = $this->conn->fetch['count(id_avaliacao)'];
      $this->conn->prepareselect("avaliacao", "valor", "id_musica", $this->id, "same", "sum");
      $this->conn->executa();
      $soma = $this->conn->fetch['sum(valor_avaliacao)'];
      if ($total != NULL && $soma != 0)
      {
         $media = $soma / $total;
         $this->navaliacao = $total;
         $this->avaliacao = $media;
         return $this->avaliacao;
      }
      else
      {
         $this->navaliacao = 0;
         $this->avaliacao = 0;
         return $this->avaliacao;
      }
   }

   public function addMusica($nome, $genero, $classificacao, $letra, $permissao, $mp3, $clipe)
   {
      /**
       *  Adiciona uma música.
       */
       
      // Verifica se nenhum dos campos obrigatórios são nulos. 
      if ($nome == "" || $genero == "" || $permissao == "")
      {
         $erro = "Dados incompletos ou inválidos";
         return $erro;
      }
      
      if(!is_numeric($classificacao))
      {
          $erro = "Classificação inválida";
          return $erro;
      }
      
      // Verifica o tamanho dos campos
      if(strlen($nome) > 25 || strlen($genero) > 15)
      {
          $erro = "Dados inválidos.";
          return $erro;
      }
      
      if($classificacao == "")
          $classificacao = 0;
      
  
      if ($mp3['name'] == "" || ( $mp3['type'] != "audio/mpeg3" && $mp3['type'] != "audio/mpeg"  && $mp3['type'] != "audio/mp3"))
      {
         $erro = "Adicione um arquivo mp3 válido. ".$mp3['type'];
         return $erro;
      }
      if ($mp3['size'] > 15000000)
      {
         $erro = "Adicione um arquivo mp3 com tamanho menor que 15mb.";
         return $erro;
      }
      
      
      // Adicionamos a música para o diretório
      $v = new validate();
      $sql2 = $v->pegarMax("id", "musica");
      $loca = move_uploaded_file($mp3['tmp_name'], "./files/musicas/" . $sql2 . ".mp3");
      
      // Inicia a utilização da classe getid3 para conseguir informações sobre a música.
      $this->getID3 = new getID3;
      $this->getID3->option_md5_data        = true;
      $this->getID3->option_md5_data_source = true;
      $this->getID3->encoding               = 'UTF-8';
      
      // Analiza o arquivo utilizando a id3
      $this->info = $this->getID3->analyze("./files/musicas/".$sql2.".mp3");
      if(!isset($this->info['error']))
      {
          // Nenhum erro encontrado, vamos pegar a duração da música.
          $duracao = $this->info['playtime_string'];
      }
      else
      {
          $duracao = "0:00";
      }
 
      
      $valores = array($nome, $genero, $duracao, $classificacao, $letra, $_SESSION['id_usuario'], $permissao, $clipe);
      $campos = array("nome", "genero", "duracao", "classificacao", "letra", "id_artista", "permissao", "clipe");
      $bind = array("STR", "STR", "STR", "INT", "STR", "INT", "INT", "STR");
      $this->conn->prepareinsert("musica", $valores, $campos, $bind);
      $sql = $this->conn->executa();
      $loca = move_uploaded_file($mp3['tmp_name'], "./files/musicas/" . $sql2 . ".mp3");
      if ($sql == true && $loca = true)
      {
          $u = new usuario();
          if($u->facebook != "")
          {
              $app = new snapps;
              $msgf = "O usuário $u->nome acabou de criar a música $nome no Accordi 
                       Escute agora mesmo: http://www.accordi.com.br/alpha/site/musica/$sql2";
              $app->postOnWall($msgf);
          }
         return "true";
      }
      else
         return "Falha na criação";
   }
   
   public function isAllowed()
   {
       /**
        *  Verifica se o usuário que está tentando acessar a musica possui contato ativo com o artista.
        */
       
       if($this->id == "")
       {
           $this->errorlog[] = "Não existe musica para verificar";
           return false;
       }
       
       if($this->permissaon == '0')
               return true;
       
       if($_SESSION['id_usuario'] == "")
       {
           $this->errorlog[] = "Não existe usuário logado.";
           return false;
       }
       
       if($_SESSION['id_usuario'] == $this->idartista)
               return true;
       
       
       $this->conn->prepareselect("contato","id_contratante",array("id_artista","status"),array($this->idartista,"0"), "same", "", "", PDO::FETCH_COLUMN, "all");
       $this->conn->executa();
       // Verifica se existe um registro com o id do usuário.
       if($this->conn->fetch != "")
       {
           foreach($this->conn->fetch as $i => $v)
           {
               if($v == $_SESSION['id_usuario'])
                   return true; // Existe um contato ^^v
           }
       }
       else
       {
           $this->errorlog[] = "Não existe um contato.";
       }
       return false;       
   }
   
   public function topWeek()
   {
       /**
        *  Carrega as 5 músicas mais vistas da semana.
        */
       
       try
       {
           $this->conn->prepareselect("musica",array("id","nome","id_artista","view_week"),"","", "same", "", "", PDO::FETCH_NUM, "all",array("view_week","DESC"),array(0,5));
           $this->conn->executa();
           
           if($this->conn->fetch == "")
                   throw new Exception("Nenhuma música encontrada.");
           
           return $this->conn->fetch;
           
       }
       catch(Exception $a)
       {
           throw new Exception("Nenhuma música encontrada.");
       }
   }
   
   public function editaLetras($letras)
   {
       /**
        *  Edita as letras de uma música
        *  @param INT $letras
        * 
        */
       
       if($this->id == "")
       {
           throw new Exception("Música inválida.");
       }
              
       if($this->infoMusica($this->id,"private") == false)
       {
           throw new Exception("Você não tem permissão para editar a letra.");
       }
       
       $letras = trim($letras);
       
       $this->conn->prepareupdate($letras, "letra", "musica", $this->id, "id", "STR");
       $a = $this->conn->executa();       
       
       if($a != true)
       {
           throw new Exception("Falha ao trocar letra.");
       }
       
       // Realiza a pesquisa das letras denovo
       $this->conn->prepareselect("musica","letra","id",$this->id);
       $this->conn->executa();
       
       // Retorna a letra
       return $this->conn->fetch[0];
       
   }
   
   public function musicasRelacionadas($usuario)
   {
       /**
        *  Encontra músicas relacionadas com o gosto do usuário
        *  @param int $usuario
        * 
        */
       
       if(!is_numeric($usuario))
           throw new Exception("Usuário inválido.");
       
       $u = new usuario($usuario);
       
       if($u == false)
           throw new Exception("Usuário inexistente.");
       
       $gosto_musical = $u->pegarGostoMusical();
       
       if($u->tipomusical == 'Indefinido' || $gosto_musical == false)
           throw new Exception("Gosto indefinido");
       
       $gosto_musical = $u->tipomusical;
       
       // Conta o número de músicas que existem com o gênero do usuário
       $this->conn->prepareselect("musica", "id", "genero", $gosto_musical, "like", "count");
       $this->conn->executa();
       $conta = $this->conn->fetch[0];
       if($conta <= 0 || $this->conn->fetch == "")
       {
           throw new Exception("Nenhuma música encontrada. como gosto.");
       }
       
       if($conta >= 10)
       {
           $conta -= 10;
           $count_start = rand(0,$conta);
       }
       else
       {
           $count_start = 0;
       }     
       
       // Verifica as músicas mais visualizadas com o gosto musical do usuário.
       $this->conn->prepareselect("musica", "id", "genero", $gosto_musical, "like", "", "", PDO::FETCH_COLUMN, "all", array("view","DESC"), array($count_start,"10"));
       $this->conn->executa();
       
       shuffle($this->conn->fetch);
       foreach($this->conn->fetch as $i => $v)
       {
           $this->infoMusica($v,"public");
           $lista_musicas[] = array("id" => $this->id,"nome" => $this->nome,"genero" => $this->genero,"duracao" => $this->duracao);
       }
       
       return $lista_musicas;
       
       
       
       
   }

}
