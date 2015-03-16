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
class eventos
{

   public $id;
   public $nome;
   public $data;
   public $hora;
   public $genero;
   public $descricao;
   public $logradouro;
   public $bairro;
   public $cidade;
   public $estado;
   public $contratante;
   public $youtube;
   public $facebook;
   public $twitter;
   public $datan;
   public $mes;
   public $horan;
   public $minuton;
   public $dia;
   public $imagem;
   private $conn;
   public $root;

   public function __construct()
   {
      $this->conn = new conn();
      date_default_timezone_set('America/Sao_Paulo');
      if (dirname($_SERVER["PHP_SELF"]) == DIRECTORY_SEPARATOR)
      {
         $this->root = "";
      }
      else
      {
         $this->root = dirname($_SERVER["PHP_SELF"]);
      }
   }

   public function listaEventos($id="")
   {
      if ($id == "")
         $id = $_SESSION['id_usuario'];
      if ($id == "" && !isset($_SESSION['id_usuario']))
         return false;
      try
      {
         $this->conn->prepareselect("evento", array("id", "nome", "data", "hora", "id_contratante", "cidade","genero","banner","estado","status"), "id_contratante", $id, "same", "", "", null, "all", array("id", "DESC"));
         $this->conn->executa();
      }
      catch (PDOException $a)
      {
         return false;
      }
      $result = $this->conn->fetch;
      if ($this->conn->rowcount == 0)
         return false;
      for ($i = 0; $i < count($result); $i++)
      {
         $this->id = $result[$i]['id_evento'];
         $this->nome = $result[$i]['nome_evento'];
         $this->data = $this->dataBr($result[$i]['data_evento']);
         $this->hora = substr($result[$i]['hora_evento'],0,-3);
         $this->cidade = $result[$i]['cidade_evento'];
         $this->genero = $result[$i]['genero_evento'];
         $this->estado = $result[$i]['estado_evento'];
         $this->imagem = $result[$i]['banner_evento'];
         $this->status = $result[$i]['status_evento'];
         $eventos[] = array($this->id, $this->nome, $this->data, $this->hora, $this->cidade, $this->genero,$this->imagem,$this->escolherEstado(),$this->status);
      }
      return $eventos;
   }

   public function pegaInfo()
   {
      if ($this->id == "" || !is_numeric($this->id))
      {
         return false;
      }
      try
      {
         $lista = array("id","nome","data","hora","id_contratante","descricao","logradouro","bairro","cidade","estado","twitter","facebook","youtube","imagem","orkut","view","genero","status");
         $this->conn->prepareselect("evento", "", "id", $this->id);
         $this->conn->executa();
      }
      catch (PDOException $a)
      {
         return false;
      }
      if ($this->conn->rowcount == 0)
      {
         return false;
      }
      $result = $this->conn->fetch;
      $this->id = $result['id_evento'];
      $this->nome = $result['nome_evento'];
      $this->data = $this->dataBr($result['data_evento']);
      $this->datan = $result['data_evento'];
      $this->hora = substr($result['hora_evento'],0,-3);
      $this->contratante = $result['id_contratante_evento'];
      $this->descricao = $result['descricao_evento'];
      $this->logradouro = $result['logradouro_evento'];
      $this->bairro = $result['bairro_evento'];
      $this->cidade = $result['cidade_evento'];
      $this->estado = $result['estado_evento'];
      $this->twitter = $result['twitter_evento'];
      $this->facebook = $result['facebook_evento'];
      $this->youtube = $result['youtube_evento'];
      $this->lastfm = $result['lastfm_evento'];
      $this->imagem = $result['banner_evento'];
      $this->genero = $result['genero_evento'];
      $this->view = $result['view_evento'];
      $this->status = $result['status_evento'];
      if($this->status == 'a')
          $this->status_string = "Aberto";
      else
          $this->status_string = "Fechado";
      $a = explode(":", $this->hora);
      $this->horan = $a[0];
      $this->minuton = $a[1];
      return true;
   }
   
   public function adicionaView()
   {
       /**
        * Adiciona uma visualização para a notícia.
        *  
        */
       
       if($this->id == "" || $this->view == "")
               return;
       
       $this->conn->prepareupdate($this->view+1, "view", "evento", $this->id, "id", "INT");
       $this->conn->executa();
       
       
   }

   public function mostraBanner()
   {
      if ($this->id == "")
         return false;
      if ($this->imagem == "0")
         echo "<img class='banner-evento' src='$this->root/imagens/evento/nobanner.png' alt='Banner' />";
      else
      {
         echo "<img class='banner-evento' src='$this->root/imagens/evento/$this->id.$this->imagem' alt='Banner' />";
         return true;
      }
   }

   public function mostraMembros($tipo,$page=0)
   {
      if ($this->id == "" || !is_numeric($this->id))
         return false;
      
      $page = $page*50;
      
      
      $this->conn->prepareselect("membros_evento", "id_usuario", array("id_evento", "tipo"), array($this->id, $tipo), "same", "", "", PDO::FETCH_COLUMN, "all",array("id","DESC"),array($page,50));
      $this->conn->executa();
      $a = $this->conn->fetch;
      if ($a == "")
         return false;
      foreach ($a as $key => $membros)
      {
          $usuario = new usuario($membros);
          $p[] = array($usuario->nome, $usuario->imagem, $usuario->login, $usuario->id);
      }
      return $p;
   }

   public function dataBr($dataus)
   {
      $databr = explode("-", $dataus);
      $this->dia = $databr[2];
      $this->mes = $databr[1];
      $this->ano = $databr[0];
      $databr = $databr[2] . "/" . $databr[1] . "/" . $databr[0];
      if ($dataus == date("Y") . "-" . date("m") . "-" . date("d"))
         $databr = "Hoje";
      return $databr;
   }

   public function escolherEstado()
   {
      switch ($this->estado)
      {
         case "ac":
            return "Acre";
            break;
         case "al":
            return "Alagoas";
            break;
         case "ap":
            return "Amapá";
            break;
         case "am":
            return "Amazonas";
            break;
         case "ba":
            return "Bahia";
            break;
         case "ce":
            return "Ceará";
            break;
         case "df":
            return "Distrito Federal";
            break;
         case "es":
            return "Espirito Santo";
            break;
         case "go":
            return "Goiás";
            break;
         case "ma":
            return "Maranhão";
            break;
         case "ms":
            return "Mato Grosso do Sul";
            break;
         case "mt":
            return "Mato Grosso";
            break;
         case "mg":
            return "Minas Gerais";
            break;
         case "pa":
            return "Pará";
            break;
         case "pb":
            return "Paraíba";
            break;
         case "pr":
            return "Paraná";
            break;
         case "pe":
            return "Pernambuco";
            break;
         case "pi":
            return "Piauí";
            break;
         case "rj":
            return "Rio de Janeiro";
            break;
         case "rn":
            return "Rio Grande do Norte";
            break;
         case "rs":
            return "Rio Grande do Sul";
            break;
         case "ro":
            return "Rondônia";
            break;
         case "rr":
            return "Roraima";
            break;
         case "sc":
            return "Santa Catarina";
            break;
         case "sp":
            return "São Paulo";
            break;
         case "se":
            return "Sergitpe";
            break;
         case "to":
            return "Tocantins";
            break;
         default:
            return null;
            break;
      }
   }
   
   public function manageVisitante($tipo)
   {
       if($this->id == "")
               return false;
       if(!is_numeric($this->id) || !is_numeric($_SESSION['id_usuario']))
               return false;
       $v = new validate();
       $ex = $v->ifExist(array($_SESSION['id_usuario'],$this->id),array("id_usuario","id_evento"),"membros_evento");
       $this->pegaInfo();
       
       // Adiciona
       if($tipo == 0)
       {
           if($this->status == 'f')
               return false;
           if($ex == false)
               return false;
           unset($ex);
           unset($v);
           $this->conn->prepareinsert("membros_evento", array($this->id,$_SESSION['id_usuario'],0), array("id_evento","id_usuario","tipo"), array("INT","INT","INT"));
           $a = $this->conn->executa();
           if($a == true)
               return true;
           else
               return false;
       }
       
       // Deleta
       elseif($tipo == 1)
       {
           if($ex == true)
               return false;
           unset($ex);
           unset($v);
           $this->conn->preparedelete("membros_evento",array("id_evento","id_usuario","tipo"),array($this->id,$_SESSION['id_usuario'],0));
           $a = $this->conn->executa();
           if($a == true)
               return true;
           else
               return false;    
       }
    }
    

   public function addRede($rede,$valor)
   {
      /**
       *  Edita as informações de uma rede.
       *  @param $rede
       *  @param $valor
       *  @return string
       */
       
      
      if($this->id == "" || !is_numeric($this->id))
          throw new Exception("Id do evento inválido.");
       
      if($rede != 'twitter' && $rede != 'facebook' && $rede != "lastfm" && $rede != "youtube")
          throw new Exception("Rede inválida.");
      
            
      $this->conn->prepareupdate($valor, $rede, "evento", $this->id, "id", "STR");
      $a = $this->conn->executa();
      
      if($a != true)
          throw new Exception("Falha ao alterar");
      
      $this->conn->prepareselect("evento",$rede,"id",$this->id);
      $this->conn->executa();
      
      if($this->conn->fetch == "")
          throw new Exception("Erro ao selecionar valores");
      
      return $this->conn->fetch[0];
      
      
      
   }

   public function orderByProximos()
   {
      $data = explode("-", $this->datan);
      $a = mktime(0, 0, 0, $data[1], $data[2], $data[0]);
      $b = time();
      return $a - $b;
   }

   public function eventosCidade()
   {
      // Mostra os eventos pela cidade do usuário (Apenas Logado)
      $usuario = new usuario($_SESSION['id_usuario']);
      $this->conn->prepareselect("evento", "id", array("cidade","status"), array($usuario->cidade,'a'), "same", "", "", PDO::FETCH_COLUMN, "all");
      $this->conn->executa();
      $a = $this->conn->fetch;
      if ($a[0] != "")
      {
         foreach ($a as $k => $in)
         {
               $evento = new eventos();
               $evento->id = $in;
               $evento->pegaInfo();
               $data = $evento->orderByProximos();
               if ($lista[$data] != "")
               {
                  $a = rand(1, 94302);
                  $data = $data + $a;
               }
               $lista[$data] = array($evento->id, $evento->nome, $evento->cidade, $evento->escolherEstado(), $evento->data,$evento->imagem,$evento->hora);
         }
         $a = ksort($lista);
         return $lista;
      }
      return false;
   }

   public function deletaParticipante($user)
   {
       /**
        *  Deleta participante de um evento.
        *  @param int $user 
        */
       
       
      if ($user == "" || !is_numeric($user) || $this->id == "")
         throw new Exception("Usuário inválido.");
      
      $this->conn->prepareselect("membros_evento","id",array("id_usuario", "id_evento","tipo"), array($user, $this->id,1));
      $this->conn->executa();
      if($this->conn->fetch == "")
      {
          throw new Exception("Usuário não encontrado no evento.");          
      }  
      
      $this->conn->preparedelete("membros_evento", array("id_usuario", "id_evento","tipo"), array($user, $this->id,1));
      $a = $this->conn->executa();
      if ($a == false)
      {
          throw new Exception("Não foi possível deletar o usuário.");
      }
   }

   public function editaEvento($nome, $mes, $dia, $hora, $minuto, $descricao, $logradouro, $bairro, $cidade, $estado, $banner, $participantes,$genero,$ano)
   {       
      /**
       * Edita um evento
       *  
       */
       
      $this->pegaInfo();
      
      if($this->status == 'f')
      {
          throw new Exception("Você não pode editar um evento que já passou.");
      }
      $nome = trim($nome);
      $descricao = trim($descricao);
      $bairro = trim($bairro);
      $cidade = trim($cidade);
      $logradouro = trim($logradouro);
      $genero = trim($genero);
      if ($nome == "" || !is_numeric($dia) || !is_numeric($hora) || !is_numeric($minuto) || !is_numeric($ano) || $logradouro == "" || $bairro == "" || $cidade == "" || $estado == "" || $genero == "")
      {
         throw new Exception("Você inseriu informações inválidas");
      }
      
      /**
       *  Realiza as validações da DATA
       *  
       */
      
      // Verifica se o evento é anterior a data atual
      $data = $ano . "-" . $mes . "-" . $dia;
      $horario = $hora . ":" . $minuto . ":" . "00";
      
      // Verifica se a data do evento é válida      
      $data_format = new dataformat();
      if($data_format->validaData($data) == false)
      {
              throw new Exception("Data inválida.");
      }
      
      
      
      $c = true;

      if ($this->id == "" || !is_numeric($this->id))
         throw new Exception("Id do evento inválido.");
      
      $validate = new validate();
      
      // Verifica se o nome e o sobrenome não são repetitivos
      if($validate->validaRepeticao($nome) == false || $validate->validaRepeticao($genero) == false || $validate->validaRepeticao($cidade) == false)
      {
          throw new Exception("Os valores contem muitas repetições e não podem ser adicionados.");
      }
      
      if ($nome != $this->nome)
         $this->nome = $nome;
      if ($mes != $this->mes)
         $this->mes = $mes;
      if ($dia != $this->dia)
         $this->dia = $dia;
      if ($hora != $this->hora)
         $this->hora = $hora;
      if ($minuto != $this->minuto)
         $this->minuto = $minuto;
      if ($descricao != $this->descricao)
         $this->descricao = $descricao;
      if ($logradouro != $this->logradouro)
         $this->logradouro = $logradouro;
      if ($bairro != $this->bairro)
         $this->bairro = $bairro;
      if ($cidade != $this->cidade)
         $this->cidade = $cidade;
      if ($estado != $this->estado)
         $this->estado = $estado;
      if ($genero != $this->genero)
         $this->genero = $genero;
      if($banner == "" && $banner != "jpg" && $banner != "png" && $banner != "gif")
      {
         $banner = $this->imagem;
      }
      
      $valores = array(ucwords($this->nome), $data, $horario, $this->descricao, $this->logradouro, $this->bairro, $this->cidade, $this->estado, $banner, $_SESSION['id_usuario'], $this->genero);
      $campos = array("nome", "data", "hora", "descricao", "logradouro", "bairro", "cidade", "estado", "banner", "id_contratante","genero");
      $bind = array("STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR","STR", "INT","STR");
      $this->conn->prepareupdate($valores, $campos, "evento", $this->id, "id", $bind);
      $b = $this->conn->executa();
      
      if($b != true)
          throw new Exception("Falha ao inserir eventos.");
      
      if ($participantes != "")
      {
         $aparticipantes = explode(';', $participantes);
         $aparticipantes = array_unique($aparticipantes);
         foreach ($aparticipantes as $key => $login)
         {
            $this->conn->prepareselect("usuario", "id", "login", $login);
            $this->conn->executa();
            $a = $this->conn->fetch;
            $v = new validate();
            $existe = $v->ifExist(array($this->id,$a['id_usuario']), array("id_evento","id_usuario"), "membros_evento");
            if($existe == true){
            $this->conn->prepareinsert("membros_evento", array($this->id, $a['id_usuario'], "1"), array("id_evento", "id_usuario", "tipo"), array("INT", "INT", "INT"));
            $c = $this->conn->executa();}
         }         
         
         if($c != true)
             throw new Exception("Falha ao inserir participantes.");
      }

   }

   public function createBanner($banner,$id="")
   {

      $h = new validate();
      $max = $h->pegarMax('id', 'evento');
      if($id != "" && is_numeric($id))
         $max = $id;
      
         $img = new imagem();
         $avt = $img->pegarImagem($banner);
         if($avt == "true"){
         $img->dimensoesMaximas(588, 100);
         $img->redimensionaOn();
         $i = $img->verificarMaximo(3000000);
         if($i == false) return "A imagem que você enviou é maior que 3MB.";
         $img->generate("./imagens/evento/" . $max);
         $this->imagem = $img->formatoImg();
         $img = null;
         $img = new imagem();
         $thumb = $img->pegarImagem($banner);
         $img->dimensoesMaximas(60, 60);
         $img->cropOn();
         $generate = $img->generate("./imagens/evento/" . $max."_thumb");
         
         if($thumb == "true" && $generate == true){
             return true;
         }
         }
         else return $avt;
   }

   public function adicionaEvento($nome, $dia, $mes, $hora, $minuto, $descricao, $logradouro, $bairro, $cidade, $estado, $banner, $participantes,$genero,$ano)
   {
    
      $nome = trim($nome);
      $descricao = trim($descricao);
      $bairro = trim($bairro);
      $cidade = trim($cidade);
      $logradouro = trim($logradouro);
      $genero = trim($genero);
    
      if ($nome == "" || !is_numeric($dia) || !is_numeric($hora) || !is_numeric($minuto) || !is_numeric($ano) || $logradouro == "" || $bairro == "" || $cidade == "" || $estado == "" || $genero == "")
      {
         return "Você inseriu informações inválidas, tente novamente";
      }      
      
      if(strlen($dia) == 1)
          $dia = "0".$dia;
      
      $h = new validate();
      
      /**
       *  Realiza as validações da DATA
       *  
       */
      
      // Verifica se o evento é anterior a data atual
      $data = $ano . "-" . $mes . "-" . $dia;
      $horario = $hora . ":" . $minuto . ":" . "00";
      
      // Verifica se a data do evento é válida      
      $data_format = new dataformat();
      if($data_format->validaData($data) == false)
              return "Data inválida.";
         
      // Adiciona o banner
      $max = $h->pegarMax('id', 'evento');
      if ($banner['name'] != "")
      {
          
         $b = $this->createBanner($banner);
         if ($b != "true")
            return $b;
         
      }
      else
      {
         $this->imagem = "0";
      };

      // Verifica se não existem repetições
      if($h->validaRepeticao($nome) == false || $h->validaRepeticao($genero) == false || $h->validaRepeticao($cidade) == false)
      {
          return("Os valores contem muitas repetições e não podem ser adicionados.");
      }
      
      $valores = array(ucwords($nome), $data, $horario, $descricao, $logradouro, $bairro, $cidade, $estado,$this->imagem, $_SESSION['id_usuario'],$genero);
      $campos = array("nome", "data", "hora", "descricao", "logradouro", "bairro", "cidade", "estado", "banner", "id_contratante","genero");
      $bind = array("STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "INT","STR");
      $this->conn->prepareinsert("evento", $valores, $campos, $bind);
      $a = $this->conn->executa();
      if ($participantes != "")
      {
         $aparticipantes = explode(';', $participantes);
         $aparticipantes = array_unique($aparticipantes);
         foreach ($aparticipantes as $key => $login)
         {
            $this->conn->prepareselect("usuario", "id", "login", $login);
            $this->conn->executa();
            $a = $this->conn->fetch;
            $this->conn->prepareinsert("membros_evento", array($max, $a['id_usuario'], "1"), array("id_evento", "id_usuario", "tipo"), array("INT", "INT", "INT"));
            $c = $this->conn->executa();
         }
      }
      if ($a == true)
      {
          $u = new usuario();
          if($u->facebook != "")
          {
              $app = new snapps;
              $msgf = "O usuário $u->nome acabou de criar o evento $nome no Accordi 
                       Participe agora mesmo: http://www.accordi.com.br/alpha/site/evento/$max";
              $app->postOnWall($msgf);
          }
          return true;
      }
      else
         return "Ocorreu um erro durante a inserção do evento, ele pode não ter sido adicionado corretamente.";
   }

   public function deletaEvento()
   {
      /**
       *  Deleta Evento
       */
       
      if ($this->id == "" || !is_numeric($this->id))
      {
         throw new Exception("Id inválido");
      }
      
      // Verifica se o usuário que está deletando é o criador do evento
      if($this->contratante != $_SESSION['id_usuario'])
      {
          throw new Exception("Permissão inválida.");
      }
      
      $this->conn->preparedelete("membros_evento","id_evento",$this->id);
      $q2 = $this->conn->executa();      
      if($q2 != true)
          throw new Exception("Ocorreu um erro ao deletar evento. 2");
      
      $this->conn->preparedelete("recado",array("tipo","id_receptor"),array("2",$this->id));
      $q3 = $this->conn->executa();
      if($q3 != true)
          throw new Exception("Ocorreu um erro ao deletar evento. 3");
      
      $this->conn->preparedelete("evento", "id", $this->id);
      $q1 = $this->conn->executa();
      if($q1 != true)
          throw new Exception("Ocorreu um erro ao deletar evento. 1");
      
      if($this->imagem != "0")
      {
          unlink("./imagens/evento/".$this->id."_thumb.".$this->imagem);
          unlink("./imagens/evento/".$this->id.".".$this->imagem);
      }
      
      if($q1 != true || $q2 != true || $q3 != true)
          throw new Exception("Ocorreu um erro ao deletar evento.");
      
   }
   
   public function contaMembros($tipo)
   {
       /**
        *  Conta os membros que existem em determinado evento.
        *  @param int $tipo
        *  @return int $c
        */
       if(!is_numeric($tipo) || $this->id == "" || !is_numeric($this->id))
               return false;
       $this->conn->prepareselect("membros_evento","id",array("id_evento","tipo"),array($this->id,$tipo),"same","count");
       $this->conn->executa();
       $c = $this->conn->fetch['count(id_membros_evento)'];
       return $c;
   }
   
   public function eventosMes($tipo,$mes=null,$ano=null)
   {
       /**
        *  Retorna os eventos de determinado mês
        *  @param int $mes
        *  @param int $tipo
        *  @return array $dia_mes_evento
        */
       if($_SESSION['id_usuario'] == "" && $tipo != 1)
           return false;
       
       if($mes == null)
           $ma =  date("m");
       if($ano == null)
           $ano = date("Y");
       else
       {
           if($mes > 12 || $mes < 1 || !is_numeric($mes))
               return false;
           
           $ma = $mes;
       }
       if(!is_numeric($ano))
           return false;
       
       $ya = $ano;
       $comp = array("year(data_evento)","month(data_evento)");
       $val = array($ya,$ma);
       
       if($tipo != 1)
       {
           $comp[2] = "id_usuario_membros_evento";
           $join = array("INNER","membros_evento");
           $val[2] = $_SESSION['id_usuario'];
           $this->conn->compararCampos("id_evento_membros_evento","id_evento");
       }
       
       $this->conn->prepareselect("evento", array("nome_evento","data_evento","hora_evento","id_evento","day(data_evento)"),$comp,$val,"same","",$join,NULL,"all",array("data_evento","DESC"),"","","AND",2);
       
       $this->conn->executa();
       if($this->conn->fetch == "")
               return false;
       foreach($this->conn->fetch as $k => $v)
       {
           $v[2] = substr($v[2],0,5);
           $dia_mes_evento[$v[4]][] = array($v[0],$v[1],$v[2],$v[3]);
       }
       return $dia_mes_evento;
   }
   
   public function eventosAll($tipo,$user,$page)
   {
       /**
        *  Verifica a lista de eventos que o usuário participa
        *  @param int $tipo
        *  @param int $user
        *  @param int $page
        * 
        */
       
       if($user == "" || !is_numeric($user))
           throw new Exception("Usuário inválido.");
       
       if($tipo != 0 && $tipo != 1)
           throw new Exception("Tipo de participação inválida.");
       
       if($page == "" || !is_numeric($page))
           $page = 0;

       $this->conn->prepareselect("membros_evento","id_evento",array("id_usuario_membros_evento","tipo_membros_evento","status_evento"),array($user,$tipo,'a'),"same","",array("INNER",'evento'),PDO::FETCH_COLUMN,"all",array("data_evento","ASC"),array($page,"10"),"","AND",0);
       $this->conn->compararCampos("id_evento_membros_evento","id_evento");
       $this->conn->executa();
       if($this->conn->fetch == "")
           throw new Exception("O usuário não participa de nenhum evento.");
       
       foreach($this->conn->fetch as $i => $v)
       {
          $this->id = $v;
          $this->pegaInfo();
          $eventos[] = array("id" => $this->id,"nome" => $this->nome,"data" => $this->data);   
       }
       
       
       return $eventos;
       
   }

}

?>