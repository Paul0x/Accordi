<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Usuário                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class usuario
{

   public $id;
   public $tipo;
   public $login;
   public $senha;
   public $nome;
   public $telefone1;
   public $telefone2;
   public $youtube;
   public $twitter;
   public $facebook;
   public $orkut;
   public $imagem;
   public $logradouro;
   public $bairro;
   public $cidade;
   public $estado;
   public $email;
   public $pemail;
   public $pende;
   public $root;
   // Artista
   public $sobrenome;
   public $apelido;
   public $sexo;
   public $nascimento;
   public $cpf;
   public $sobre;
   public $tipomusical;
   public $pidade;
   public $pnome;
   public $dia;
   public $mes;
   public $ano;
   public $view;
   private $conn;
   // Contratante
   public $website;
   public $cadastro;
   public $descricao;

   // TimeZone


   public function __construct($id="")
   {

      $this->conn = new conn();
      
      if ((!isset($_SESSION['tipo_usuario']) || !isset($_SESSION['id_usuario'])) && $id == "")
         return false;
      
      if ($id == "" || !is_numeric($id))
         $id = $_SESSION['id_usuario'];
      
      
      $fields = array("id","login","senha","tipo","nome","telefone1","telefone2","youtube","twitter","facebook","orkut","imagem","logradouro","bairro","cidade","estado","email","privacidade_email","privacidade_endereco","view","count_imagem");
      $this->conn->prepareselect("usuario", $fields, "id", $id, "same", PDO::FETCH_ASSOC);
      $this->conn->executa();
      if($this->conn->rowcount == 0)
              return false;
      $resultado = $this->conn->fetch;
      $this->id = $resultado['id_usuario'];
      $this->login = $resultado['login_usuario'];
      $this->senha = $resultado['senha_usuario'];
      $this->tipo = $resultado['tipo_usuario'];
      $this->nome = $resultado['nome_usuario'];
      $this->telefone1 = $resultado['telefone1_usuario'];
      $this->telefone2 = $resultado['telefone2_usuario'];
      $this->youtube = $resultado['youtube_usuario'];
      $this->twitter = $resultado['twitter_usuario'];
      $this->facebook = $resultado['facebook_usuario'];
      $this->orkut = $resultado['orkut_usuario'];
      $this->imagem = $resultado['imagem_usuario'];
      
      if($this->imagem != "0")
              $this->imagem = $this->imagem."?v".$resultado['count_imagem_usuario'];
      
      $this->logradouro = $resultado['logradouro_usuario'];
      $this->bairro = $resultado['bairro_usuario'];
      $this->cidade = $resultado['cidade_usuario'];
      $this->estado = $resultado['estado_usuario'];
      if($this->estado == "")
              $this->estado = 0;
      $this->email = $resultado['email_usuario'];
      $this->pemail = $resultado['privacidade_email_usuario'];
      $this->pende = $resultado['privacidade_endereco_usuario'];
      $this->view = $resultado['view_usuario'];
      date_default_timezone_set('America/Sao_Paulo');
      if (dirname($_SERVER["PHP_SELF"]) == DIRECTORY_SEPARATOR)
      {
         $this->root = "";
      }
      else
      {
         $this->root = dirname($_SERVER["PHP_SELF"]);
      }

      if ($this->tipo == 1)
      {

         $this->conn->prepareselect("artista", "", "id", $id);
         $this->conn->executa();
         $resultado = $this->conn->fetch;
         $this->sobrenome = $resultado['sobrenome_artista'];
         $this->apelido = $resultado['apelido_artista'];
         $this->sexo = $resultado['sexo_artista'];
         $this->nascimento = $resultado['data_nascimento_artista'];
         $this->cpf = $resultado['cpf_artista'];
         $this->sobre = $resultado['sobre_artista'];
         $this->pidade = $resultado['privacidade_idade_artista'];
         $this->pnome = $resultado['privacidade_exiben_artista'];
         $brnascimento = explode("-", $this->nascimento);
         $brdia = $brnascimento[2];
         $brmes = $brnascimento[1];
         $brano = $brnascimento[0];
         $this->dia = $brdia;
         $this->mes = $brmes;
         $this->ano = $brano;
         
         // Permissões do artista
         if($_SESSION['id_usuario'] != $this->id)
         {
             if(($this->pidade == 2 && !$this->isContato()) || $this->pidade == 1)
             {
                 $this->dia = "";
                 $this->mes = "";
                 $this->ano = "";
                 $this->nascimento = "";
             }
         }
         
      }
      elseif ($this->tipo == 2)
      {

         $this->conn->prepareselect("contratante", "", "id", $id);
         $this->conn->executa();
         $resultado = $this->conn->fetch;
         $this->cadastro = $resultado['cadastro_contratante'];
         $this->website = $resultado['website_contratante'];
         $this->descricao = $resultado['resumo_contratante'];
      }
      
      // Permissões gerais
      if($_SESSION['id_usuario'] != $this->id)
      {
          // Verifica o e-mail
          if(($this->pemail == 2 && !$this->isContato()) || $this->pemail == 1)
          {
              $this->email = "";
          }
          
          // Verifica o endereço
          if(($this->pende == 2 && !$this->isContato()) || $this->pende == 1)
          {
              $this->logradouro = "";
              $this->bairro = "";
              $this->estado = "0";
              $this->cidade = "";
          }
          
          
      }
 
      
      
      return true;
   }

   public function nascimentoBr()
   {

      $nascimento = $this->dia . "/" . $this->mes . "/" . $this->ano;
      return $nascimento;
   }

   public function calcularIdade()
   {

      if ($this->tipo == 1 && $this->nascimento != NULL)
      {
         $brnascimento = explode("-", $this->nascimento);
         $brdia = $brnascimento[2];
         $brmes = $brnascimento[1];
         $brano = $brnascimento[0];
         list ($dia, $mes, $ano) = explode("/", date("d/m/Y"));

         $idade = $ano - $brano;
         if ($brmes > $mes || ($brmes == $mes && $brdia >= $dia))
         {
            $idade = $idade - 1;
         }
         return $idade;
      }

      return false;
   }
   
   public function pegarGostoMusical($id = "",$tipo = "")
   {
     if($id == "")
         $id = $this->id;
     
     if($tipo == "")
         $tipo = $this->tipo;
     if($id == "" || !is_numeric($id))
             return false;
     if($tipo == 1)
     {
        
        $this->conn->prepareselect("musica",array("genero_musica","count(genero_musica)"),"id_artista_musica",$id,"same","","",NULL,"all",array("count(genero_musica)",DESC),"","genero_musica","AND",2);
        $this->conn->executa();
        $generos = $this->conn->fetch;
        if($this->conn->fetch != "")
             $this->tipomusical = $generos[0][0];
        return $this->tipomusical;
     
     }
     elseif($tipo == 2)
     {
        // Selecionamos os ultimos 5 artistas 
        $this->conn->prepareselect("contato","id_artista","id_contratante",$this->id,"same","","",PDO::FETCH_COLUMN,"all","",array("0","5"));
        $this->conn->executa();
        
        if($this->conn->fetch == "")
                return false;
        
        foreach($this->conn->fetch as $i => $v)
        {            
           $g = $this->pegarGostoMusical($v,1);
           if($g != "")
               $gosto[] = $g;
        }
        if(is_array($gosto))
        {
        sort($gosto);
        $gosto = array_count_values($gosto);
        foreach($gosto as $i => $v)
        {
            $max = $i;
        }
                
        if($max != "")
            $this->tipomusical = $max;
        else
            $this->tipomusical = "Indefinido";
        return true;
        }
        
        
     }
   }
   
   public function usuariosRelacionados()
   {
       /*
        *  Relaciona usuários por informações do perfil.
        */
       $comparadores = array("nome","cidade","bairro");
       $valores = array($this->nome,$this->cidade,$this->bairro);
       $cn = array("OR","OR");
       $this->conn->prepareselect("usuario","id",$comparadores,$valores,"like","","",PDO::FETCH_COLUMN,"all","",array("0","50"),"",$cn);
       $this->conn->executa();
       if($this->conn->fetch == "")
               $this->conn->fetch = array ();
       $us = $this->conn->fetch;
       
       /*
        *  Relaciona usuários por eventos que os usuários participam.
        */
       $this->conn->prepareselect("membros_evento","id_evento","id_usuario",$this->id,"same","","",PDO::FETCH_COLUMN,"all","",array("0","10"));
       $this->conn->executa();
       if($this->conn->fetch != "")
       {
           $a = $this->conn->fetch;
           foreach($a as $k => $e)
           {
             $this->conn->prepareselect("membros_evento","id_usuario","id_evento",$e,"same","","",PDO::FETCH_COLUMN,"all","",array("0","30")); 
             $this->conn->executa();
             $us = array_merge($us,$this->conn->fetch);
           }
       }
       
       /*
        *  Relaciona usuários por contatos anteriores.
        */
       switch($this->tipo)
       {
           case 1: $campos = "id_artista"; $campos2 = "id_contratante"; break;
           case 2: $campos = "id_contratante"; $campos2 = "id_artista"; break;
       }
       
       $this->conn->prepareselect("contato",$campos2,$campos,$this->id,"same","","",PDO::FETCH_COLUMN,"all","",array("0","30"));
       $this->conn->executa();
       if($this->conn->fetch != "")
       {
       $us = array_merge($us,$this->conn->fetch); // Adiciona os ultimos 30 contratantes relacionados.
       $contratantes = array_unique($this->conn->fetch);
       /*
        *  Relaciona com outros possíveis membros do contratantes.
        *  OBS: Isso só vai acontecer caso tenha menos de 100 usuários relacionados.
        */
       
       $us = array_unique($us); // Unificamos as id's para impedir overflow da memória
       $count = count($us); // Contamos quantos usuários tem no array.
       
       if($count < 100)
       {
       foreach($contratantes as $i => $v)
       {
           $this->conn->prepareselect("contato",$campos,$campos2,$v,"same","","",PDO::FETCH_COLUMN,"all","",array("0","20"));
           $this->conn->executa();
           if($this->conn->fetch != "")
           $us = array_merge($us,$this->conn->fetch); // Passamos a lista de ID para o usuário.
       }
       }
       
       }
       /*
        *  Deixa os usuários unicos e finaliza o processo.
        */
       $us = array_unique($us);
       shuffle($us);
       foreach($us as $k => $v)
       {
           if($v != $this->id)
           {
           $u = new usuario($v);
           $u->pegarGostoMusical($u->id,$u->tipo);
           $uinfo[] = array($u->nome,$u->login,$u->imagem,$u->id,$u->sobrenome,$u->tipo,$u->tipomusical,$u->cidade);
           }
       }
       unset($us);
       unset($u);
       unset($i);
       unset($v);
       return $uinfo;
   }
   
   public function valorAval($musica)
   {
       if(!is_numeric($musica) || $musica == "" || $this->id == "" || !is_numeric($this->id))
               return false;
       $this->conn->prepareselect("avaliacao", "valor", array("id_usuario","id_musica"), array($this->id,$musica));
       $this->conn->executa();
       if($this->conn->rowcount == 0)
           return 0;
       else return $this->conn->fetch[0];
   }

   public function nascimentoAno()
   {
      $ano = date("Y");
      $ano = $ano - 16;
      for ($i = 0; $i <= 100; $i++)
      {
         if ($this->ano == $ano)
            echo "<option value='" . $ano . "' selected='selected'>" . $ano . "</option>";
         else
            echo "<option value='" . $ano . "'>" . $ano . "</option>";
         $ano = $ano - 1;
      }
   }

   public function calcularSexo()
   {
      if ($this->tipo == 1)
      {
         $a = strtolower($this->sexo);
         switch ($a)
         {
            case 'm':
               $sexotext = "Masculino";
               break;
            case 'f':
               $sexotext = "Feminino";
               break;
            default:
               $sexotext = "Não Informado";
               break;
         }
         return $sexotext;
      }
   }

   public function msg_error($erro)
   {
      echo $erro;
      return true;
   }
   
   public function numeroEventos()
   {
      if($this->tipo == 1)
              return false;
      $this->conn->prepareselect("evento", "id", "id_contratante", $this->id, "same", "count");
      $this->conn->executa();
      $a = $this->conn->fetch;
      if($this->conn->fetch == "")
          $a['count(id_evento)'] = '0';
      return $a['count(id_evento)'];
   }

   public function numeroMusicas()
   {
      if($this->tipo == 2)
              return false;
      $this->conn->prepareselect("musica", "id", "id_artista", $this->id, "same", "count");
      $this->conn->executa();
      $a = $this->conn->fetch;
      if($this->conn->fetch == "")
          $a['count(id_musica)'] = '0';
      return $a['count(id_musica)'];
   }

   public function numeroContatos()
   {

      switch($this->tipo)
      {
          case 1: $v = "id_artista"; break;
          case 2: $v = "id_contratante"; break;
      }
      
      $this->conn->prepareselect("contato", "id", array($v,"status"), array($this->id,'0'), "same", "count");
      $this->conn->executa();
      $a = $this->conn->fetch;
      if($this->conn->fetch == "")
          $a['count(id_contato)'] = '0';
      return $a['count(id_contato)'];
   }

   public function mostrarImagem()
   {
      if ($this->imagem == '0')
      {
         return "<img src='" . $this->root . "/imagens/profiles/noavatar.png' class='avatar' alt='Nenhum Avatar Selecionado' border='0' />";
      }
      else
      {
         return "<img src='" . $this->root . "/imagens/profiles/" . $this->id . "." . $this->imagem . "' alt='avatar' class='avatar' />";
      }
   }

   public function editarSenha($senha,$osenha)
   {
      if(md5($osenha) != $this->senha)
           return false;
      
      if(strlen($this->senha) < 6)
      {
          return "Senha muito pequena.";
      }
      
      if($this->senha != "" && trim($this->senha) != "")
            $this->senha = md5($senha);
      

      $this->conn->prepareupdate($this->senha, "senha", "usuario", $this->id, "id", "INT");
      $this->conn->executa();
      if ($this->conn == true)
         return true;
      else
         return "Falha na alteração da senha.";
   }

   public function escolherEstado()
   {
      switch (strtolower($this->estado))
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

   public function upFb($id)
   {
       if($this->id == "")
           return false;
       if($id == "delete")
          $this->conn->prepareupdate("","facebook","usuario",$this->id,"id");
       else
          $this->conn->prepareupdate($id,"facebook","usuario",$this->id,"id","STR");
       $this->conn->executa();
           return true;
   }
   public function viewCount()
   { 
     $this->conn->prepareupdate($this->view+1, "view", "usuario", $this->id, "id", "INT");
     $this->conn->executa();
   }
   public function editarProfile($aba, $nome, $email, $avatar, $telefone1, $telefone2, $sobrenome, $pseudonimo, $sexo, $dia, $mes, $ano, $sobre, $descricao, $website, $youtube, $twitter, $facebook, $orkut, $logd, $bairro, $cidade, $estado, $pemail, $pende, $pnome, $pidade)
   {
      
      $validate = new validate();
      $nome = trim($nome);
      $email = trim($email);
      $sobrenome = trim($sobrenome);
      $pseudonimo = trim($pseudonimo);
      if ($aba == "pessoal" && (is_null($nome) || is_null($telefone1) || is_null($email)))
         return "Dados inválidos";
      if($aba == "redes" && (strlen($facebook) != 15 && strlen($facebook) != 0))
         return "ID do Facebook inválido";
      if(strlen($descricao) > 600)
         return "Coloque uma descrição de até 600 caracteres.";
      if(strlen($sobre) > 600)
         return "Coloque uma descrição de até 600 caracteres.";
      
     
      
      $website = str_replace("http://", "", $website);
      $nasci = $ano . "-" . $mes . "-" . $dia;
      if ($nasci != $this->nascimento && $ano != "" && $dia != "" & $mes != "")
      {
         $this->nascimento = $nasci;
         $this->dia = $dia;
         $this->mes = $mes;
         $this->ano = $ano;
         $data_format = new dataformat();
         if($data_format->validaData($nasci) == false)
                 return "Data de nascimento inválida.";
      }
      
      // Valida os telefones
      $pattern = "/^\([0-9]{2}\)[0-9]{4}\-[0-9]{4}$/";
      if(!preg_match($pattern,$telefone1) && $aba == "pessoal")
          return "Telefone inválido.";
      
      // Verifica se o nome e o sobrenome não são repetitivos
      if($validate->validaRepeticao($nome) == false)
      {
          return "Insira um nome com menos repetições.";
      }
      
      if($validate->validaRepeticao($sobrenome) == false)
      {
          return "Insira um sobrenome com menos repetições.";
      }
      
      if($validate->validaRepeticao($apelido) == false)
      {
          return "Insira um apelido com menos repetições.";
      }
      

      
      
      
      if ($nome != $this->nome && $nome != "")
         $this->nome = $nome;
      if ($email != $this->email && $email != "")
         $this->email = $email;
      if ($telefone1 != $this->telefone1 && $telefone1 != "")
         $this->telefone1 = $telefone1;
      if ($telefone2 != $this->telefone2 && $aba == "pessoal")
         $this->telefone2 = $telefone2;
      if ($sobrenome != $this->sobrenome && $aba == "pessoal")
         $this->sobrenome = $sobrenome;
      if ($pseudonimo != $this->apelido && $aba == "pessoal")
         $this->apelido = $pseudonimo;
      if ($sexo != $this->sexo && $sexo != "")
         $this->sexo = $sexo;
      if ($sobre != $this->sobre && $aba == "pessoal")
         $this->sobre = $sobre;
      if ($descricao != $this->descricao && $aba == "pessoal")
         $this->descricao = $descricao;
      if ($website != $this->website && $aba == "pessoal")
         $this->website = $website;
      if ($youtube != $this->youtube && $aba == "redes")
         $this->youtube = $youtube;
      if ($twitter != $this->twitter && $aba == "redes")
         $this->twitter = $twitter;
      if ($facebook != $this->facebook && $aba == "redes")
         $this->facebook = $facebook;
      if ($orkut != $this->orkut && $aba == "redes")
         $this->orkut = $orkut;
      if ($logd != $this->logradouro && $aba == "local")
         $this->logradouro = $logd;
      if ($bairro != $this->bairro && $aba == "local")
         $this->bairro = $bairro;
      if ($cidade != $this->cidade && $aba == "local")
         $this->cidade = $cidade;
      if ($estado != $this->estado && $aba == "local")
         $this->estado = $estado;
      if ($pemail != $this->pemail && $pemail != "")
         $this->pemail = $pemail;
      if ($pende != $this->pende && $pende != "")
         $this->pende = $pende;
      if ($pnome != $this->pnome && $pnome != "")
         $this->pnome = $pnome;
      if ($pidade != $this->pidade && $pidade != "")
         $this->pidade = $pidade;
      
      if ($avatar['name'] != NULL)
      {
         $img = new imagem();
         $avt = $img->pegarImagem($avatar);
         if($avt == "true"){
         $img->dimensoesMaximas(150, 150);
         $img->redimensionaOn();
         $i = $img->verificarMaximo(3000000);
         if($i == false) return "A imagem que você enviou é maior que 3MB.";
         $img->generate("./imagens/profiles/" . $this->id);
         $this->imagem = $img->formatoImg();
         $img = null;
         $img = new imagem();
         $thumb = $img->pegarImagem($avatar);
         $img->dimensoesMaximas(57, 57);
         $img->cropOn();
         $img->generate("./imagens/profiles/" . $this->id."_thumb");
         if($thumb == "true"){}
         $thumb = $img->pegarImagem($avatar);
         $img->dimensoesMaximas(93, 93);
         $img->cropOn();
         $img->generate("./imagens/profiles/" . $this->id."_thumb2");
         if($thumb == "true"){}
         $this->updateImagemNum();
         
         }
         else return $avt;
                  
      }

      $valores = array($this->nome, $this->telefone1, $this->telefone2, $this->youtube, $this->twitter, $this->imagem, $this->facebook, $this->orkut, $this->logradouro, $this->bairro, $this->cidade, $this->estado, $this->email, $this->pemail, $this->pende);
      $campos = array("nome", "telefone1", "telefone2", "youtube", "twitter", "imagem", "facebook", "orkut", "logradouro", "bairro", "cidade", "estado", "email", "privacidade_email", "privacidade_endereco");
      $bind = array("STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "STR", "INT", "INT");
      $this->conn->prepareupdate($valores, $campos, "usuario", $this->id, "id", $bind);
      $this->conn->executa();

      if ($this->tipo == 1)
      {
         $valores = array($this->sobrenome, $this->apelido, $this->sexo, $this->nascimento, $this->sobre, $this->pidade, $this->pnome);
         $campos = array("sobrenome", "apelido", "sexo", "data_nascimento", "sobre", "privacidade_idade", "privacidade_exiben");
         $bind = array("STR", "STR", "STR", "STR", "STR", "INT", "INT");
         $this->conn->prepareupdate($valores, $campos, "artista", $this->id, "id", $bind);
         $this->conn->executa();
      }
      elseif ($this->tipo == 2)
      {
         $valores = array($this->website, $this->descricao);
         $campos = array("website", "resumo");
         $bind = array("STR", "STR");
         $this->conn->prepareupdate($valores, $campos, "contratante", $this->id, "id", $bind);
         $this->conn->executa();
      }

      if ($this->conn->error == "")
      {
         return true;
      }
      else
      {
          return array_pop($this->conn->error);
      }
   }
   
   public function getTermos()
   {
       /**
        *  Pega os termos atuais do contratante
        *  @return Array
        */
       
       if($this->tipo == 1)
               return false;
       
       $this->conn->prepareselect("contratante","termos","id",$this->id);
       $this->conn->executa();
       
       // Formata os termos para aceitar BBcode
       $c = new comentarios();
       $termosbb = $c->bbCode($this->conn->fetch[0],false);
       $termos = array($termosbb,$this->conn->fetch[0]);       
       return $termos;
       
   }
   
   public function getExigencias()
   {
       /**
        *  Pega as exigências do contratante
        *  @return Array
        */
       
       if($this->tipo == 1)
               return false;
       
       $this->conn->prepareselect("exigencias",array("idade_min_artista","cidade","estado","pagamento","descricao"),"id_contratante",$this->id);
       $a = $this->conn->executa();
       $exigencias = $this->conn->fetch;
       
       // Formata o valor da exigência.
       $exigencias[7] = "R$".$exigencias[3];
       
       // Valida o estado
       $es = $this->estado;
       $this->estado = $exigencias[2];
       $exigencias[5] = $this->escolherEstado();
       $this->estado = $es;
       
       // Coloca todas as exgiências com letra maiusculas.
       foreach($exigencias as $i => $v)
       {
           $exigencias[$i] = ucwords($v);
       }
       
       // Adiciona breakline na descrição da exigência.
       $exigencias[6] = nl2br($exigencias[4]);
       
       if($a == false)
           return false;
       return $exigencias;
   }
   
   
   public function editaTermos($termos){
       /**
        *  Edita os termos do contratante
        *  @param string $termos
        *  @return bool
        */
       if($this->id == null)
           return false;
       if($this->tipo != 2)
           return false;
       $termos = trim($termos);
       try {
           $this->conn->prepareupdate($termos, "termos", "contratante", $this->id, "id", "STR");
           $a = $this->conn->executa();
           if($a == false)
           return false;
       return true;
       }
       catch (PDOExeption $ex)
       {
           return false;           
       }
       
   }
   
   private function verificaExigencias()
   {
       /**
        * Verifica se as exigências existem para o contratante, caso não existam ele cria.
        * @return bool
        */
       
       if($this->id == "")
               return false;
       
       $this->conn->prepareselect("exigencias","id","id_contratante",$this->id);
       $this->conn->executa();
       
       if($this->conn->fetch == "")
       {
           /**
            * O sistema não reconheceu as exigências, será necessário criar uma.
            */
           
           $this->conn->prepareinsert("exigencias", $this->id, "id_contratante", "INT");
           $this->conn->executa();
       }
       return true;
   }
   
   public function editaExigencias($idade,$cidade,$estado,$pagamento,$descricao)
   {
      /**
       *  Edita as exigências do contratante
       *  @param int $idade
       *  @param string $cidade
       *  @param string $estado
       *  @param double $pagamento
       *  @param string $descricao
       * 
       */
      
      // Verifica a existência das exigências
      $this->verificaExigencias();
      
      if(strlen($idade) > 2 || strlen($cidade) > 22 || strlen($estado) > 2 || strlen($pagamento) > 9)
      {
          // Verificamos se os parametros não excedem o limite de caracteres.
          $this->errorlog[] = "Os dados que você enviou são inválidos.";
          return false;
      }
      
      if(!is_numeric($idade) && $idade != "")
      {
          // Verificamos se a idade não é numérica e não é nula.
          $this->errorlog[] = "A idade que você passou é inválida.";
          return false;
      }
      
      // Retiramos o R$ do pagamento ( caxo exista )
      $pagamento = str_replace("R$","",$pagamento);
      $pagamento = str_replace(",",".",$pagamento);
      
      if(!is_numeric($pagamento) && $pagamento != "")
      {
          $this->errorlog[] = "Pagamento inválido.";
          return false;
      }
      
      $valores = array($idade,$cidade,$estado,$pagamento,$descricao);
      $campos = array("idade_min_artista","cidade","estado","pagamento","descricao");
      $bind = array("INT","STR","STR","INT","STR");
      $this->conn->prepareupdate($valores, $campos, "exigencias", $this->id, "id_contratante",$bind);
      $a = $this->conn->executa();
      if($a == true)
      return true;
      else
      {
          $this->errorlog[] = "Não foi possível editar sua exigência.";
          return false;
      }
       
   }
   
   public function isAdmin()
   {
       /**
        *  Verifica se o usuário é um administrador e retorna sua permissão em formato numérico.
        *  @return int
        */
       
       $tipo = 0;
       if($this->id == "" || !is_numeric($this->id))
       {
           $this->errorlog[] = "Não é possível verificar se o usuário é administrador sem possuir um ID.";
           return $tipo;
       }
       
       $this->conn->prepareselect("admin","permissao","id_usuario",$this->id);
       $this->conn->executa(); // Faz a verificação se existe um registro na tabela de administradores.
       
       if($this->conn->fetch == "")
       {
           $this->errorlog[] = "Nenhum registro encontrado, o usuário não é um administrador.";
           return $tipo;
       }
       else
       {
           switch($this->conn->fetch[0])
           {
               case 'a': $tipo = 3; break;
               case 'm': $tipo = 2; break;
               case 's': $tipo = 1; break;
           }
           return $tipo;
       }
       
       
   }
   
   public function carregaContatos()
   {
       /**
        *  Carrega todos os usuários que fazem contato com o usuário atual.
        * 
        */
       
       if($this->id == "" || !is_numeric($this->id))
           throw new Exception("Usuário inválido.");
       
       if($this->tipo == 1)
           $campos = array("id_contratante","id_artista");
       else
           $campos = array("id_artista","id_contratante");
       
       $this->conn->prepareselect("contato","$campos[0]",array($campos[1],"status"),array($this->id,0),"same","","",PDO::FETCH_COLUMN,"all");
       $this->conn->executa();
       if($this->conn->fetch == "")
       {
           throw new Exception("Nenhum contato encontrado.");
       }
       
       // Retira contatos duplicados.
       $contatos = array_unique($this->conn->fetch);
       return $contatos;
   }
   
   public function loadContatos()
   {
       /**
        *  Lista informações de todos os contatos do usuário atual.
        * 
        */
       
       if($this->id == "" || !is_numeric($this->id))
               throw new Exception("Usuário inválido.");
       
       try
       {
           $contatos = $this->carregaContatos();
           foreach($contatos as $i => $v)
           {
               $u2 = new usuario($v);
               $contato_info[] = array($u2->nome,$u2->login,$u2->imagem,$u2->id);
           }
           
           return $contato_info;
       }
       catch(Exception $a)
       {
           throw new Exception($a->getMessage());
       }
       
       
   }
   
   public function isContato($id = "")
   {
       /**
        *  Verifica se o usuário informado é contato do usuário instanciado.
        *  @param int $id
        *  @return bool
        */
       
       if($id == "" || !is_numeric($id))
       {
           $id = $_SESSION['id_usuario'];
       }
       
       if($_SESSION['id_usuario'] == "")
           return false;
       
       $v = new validate();
       $item = array($id,$this->id);
       
       if($this->tipo == 1)
           $campos = array("id_contratante","id_artista");
       elseif($this->tipo == 2)
           $campos = array("id_artista","id_contratante");
       
       $existe_contato = $v->ifExist($item,$campos,"contato");
       
       if($existe_contato == true)
           return false;
       else
           return true; 
   }
   
   private function updateImagemNum()
   {
       /**
        *  Altera a versão da imagem do usuário.
        */
       
       if($this->id == "" || !is_numeric($this->id))
               return false;
       
       $this->conn->freeQuery("UPDATE usuario SET count_imagem_usuario = count_imagem_usuario+1 WHERE id_usuario = $this->id",false,false);
       return true;
   }

}