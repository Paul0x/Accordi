<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Validação                          //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class validate
{

   public $id;
   public $username;
   public $senha;
   public $email;
   public $tipo;
   public $erro;
   public $nome;
   private $sql;

   function __construct()
   {
      $this->sql = new conn();
   }

   function verificaLogin()
   {

      $this->sql->prepareselect("usuario", array("id", "senha"), array("login", "senha"), array($this->login, md5($this->senha)));
      $this->sql->executa();
      $resultado = $this->sql->fetch;
      if ($this->sql->rowcount == 1 && $resultado['senha_usuario'] === md5($this->senha))
      {
         $this->id = $resultado['id_usuario'];
         return true;
      }
      else
         return false;
   }

   function pegaInfo($id)
   {
      if ($id != "" && is_numeric($id))
      {
         if ($this->id == '')
            $this->id = $id;

         $this->sql->prepareselect("usuario", array("nome", "id", "email", "tipo"), "id", $this->id);
         $this->sql->executa();
         if ($this->sql->rowcount != 1)
            return false;
         $this->sql_array = $this->sql->fetch;
         $this->nome = $this->sql_array['nome_usuario'];
         $this->id = $this->sql_array['id_usuario'];
         $this->email = $this->sql_array['email_usuario'];
         $this->tipo = $this->sql_array['tipo_usuario'];
         return true;
      }
      else
      {
         return false;
      }
   }

   function pegaId($login)
   {

      $this->sql->prepareselect("usuario", "id", "login", $login);
      $this->sql->executa();
      if ($this->sql->rowcount != 1)
         return false;
      else
      {
         $id = $this->sql->fetch;
         $id = $id['id_usuario'];
      }
      return $id;
   }

   function validaSessao()
   {
      if ($this->verificaLogin() == true)
      {
         $_SESSION['login_usuario'] = $this->login;
         $_SESSION['id_usuario'] = $this->id;
         $_SESSION['tipo_usuario'] = $this->tipo;
         return true;
      }
      else
      {
         return false;
      }
   }

   function validaLogoff()
   {
      if (isset($_SESSION['id_usuario']))
      {
         unset($_SESSION['id_usuario']);
      }
      if (isset($_SESSION['login_usuario']))
      {
         unset($_SESSION['login_usuario']);
      }
      if (isset($_SESSION['tipo_usuario']))
      {
         unset($_SESSION['tipo_usuario']);
      }

      if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['login_usuario']) && !isset($_SESSION['tipo_usuario']))
      {
         return true;
      }
      else
         return false;
   }

   function recuperarPassword($code)
   {
      $resultado = "";

      $this->sql->prepareselect("usuario", "recovery", "email", $this->email);
      $a = $this->sql->executa();
      $this->sql_array = $this->sql->fetch;
      if ($this->email != NULL)
      {
         if ($this->sql->rowcount == 0)
         {
            $retorno = "<strong class='side-tip'>E-mail inexistente</strong>";
            return $retorno;
         }
         for ($i = 0; $i < 7; $i++)
         {
            $digito = rand(0, 9);
            $confirma.= $digito;
         }

         $this->sql->prepareupdate($confirma, "recovery", "usuario", $this->email, "email", "STR");
         $resultado = $this->sql->executa();
         if ($resultado == true)
         {
            $emailbody = "<b>Accordi - www.accordi.com.br - Recuperação de senha</b> <br /><br /><br /> Recebemos uma solicitação para realizar uma alteração de senha vinda do seu e-mail no Accordi.<br /><br /> Geramos um código para confirmar a sua solicitação, clique na URL abaixo(ou cole no navegador). <br /><br /> <a href='http://www.accordi.com.br/recovery/" . $confirma . "'>http://www.accordi.com.br/recovery/" . $confirma . "</a><br /><br /> Caso você não tenha solicitado a troca de senha ignore esse e-mail. <br /> Atenciosamente, equipe Accordi.";
            $retorno = "<strong class='side-tip-ok'>Mensagem de confirmação enviada com sucesso, verifique seu e-mail!</strong>";
         }
         else
         {
            $retorno = "<strong class='side-tip'>Não foi possível gerar o código de confirmação.</strong>";
            return $retorno;
         }
      }
      else
      {

         $campos = array("id", "recovery", "email", "login");
         $this->sql->prepareselect("usuario", $campos, "recovery", $code);
         $this->sql->executa();
         $this->sql_array = $this->sql->fetch;
         $this->id = $this->sql_array['id_usuario'];
         $this->email = $this->sql_array['email_usuario'];
         $this->username = $this->sql_array['login_usuario'];
         $strdigitos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
         if ($this->sql->rowcount == 1)
         {
            for ($i = 0; $i < 9; $i++)
            {
               $len = rand(0, strlen($strdigitos));
               $nsenha.= $strdigitos[$len];
            }

            $this->sql->prepareupdate(md5($nsenha), "senha", "usuario", $this->id, "id", "INT");
            $q1 = $this->sql->executa();
            if ($q1 == true)
            {
               echo "<script type='text/javascript'>setCookie(\"pw-change\",\"1\",60*60*24)</script>";
               $this->sql->prepareupdate("NULL", "recovery", "usuario", $this->id, "id");
               $this->sql->executa();
               $emailbody = "<b>Accordi - www.accordi.com.br - Nova Senha!</b> <br /><br /><br /> Parabéns! Uma nova senha foi gerada para sua conta no Accordi, você já pode logar com sua nova senha.<br />Lembramos que é extremamente necessário você alterar sua senha assim que efetuar logon. <br /><br /><i>Login:</i> " . $this->username . " <br /><i>Nova Senha:</i> " . $nsenha . " <br /> Atenciosamente, equipe Accordi.";
               $retorno = "<strong class='side-tip-ok'>Senha alterada - um e-mail foi enviado para: " . $this->email . "</strong>";
            }
            else
            {
               $retorno = "<strong class='side-tip'>Não foi possível realizar a recuperação.</strong>";
               return $retorno;
            }
         }
         else
         {
            $retorno = "<strong class='side-tip'>Código inválido.</strong>";
            return $retorno;
         }
      }
      date_default_timezone_set('America/Sao_Paulo');
      $mail = new phpmailer();
      $mail->IsSMTP();  // Ativa o envio via SMTP
      $mail->IsHTML(true);
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = "ssl";
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = '465';
      $mail->Username = 'accordi.contato@gmail.com';
      $mail->Password = 'brotherh00d';
      $mail->From = "accordi.contato@gmail.com";   // Email de quem está enviando
      $mail->FromName = "Accordi";   // Email de quem está enviando
      $mail->AddAddress($this->email);
      $mail->Subject = "Accordi - Recuperação de senha";
      $mail->Body = $emailbody;
      $mailtrue = $mail->Send();
      if ($mailtrue == false)
         $retorno = "<strong class='side-tip'>Falha no envio do e-mail: " . $mailtrue . "</strong>";

      return $retorno;
   }

   function cadastraUsuario($login, $senha, $nome, $email2, $tel, $logd, $bairro, $cidade, $estado, $website, $apelido, $sobrenome, $tipo)
   {
       
      $login = trim($login);
      $email2 = trim($email2);
      $nome = trim($nome);
      
      if($login == "" || $login != trim($login))
         throw new Exception("Login inválido.");
      
      if($email2 == "")
          throw new Exception("Email inválido.");
      
      if($tel == "")
          throw new Exception("Telefone inválido.");
      
      if($nome == "" )
          throw new Exception("Nome inválido.");
      
      if($tipo != 1 && $tipo != 2)
          throw new Exception("Tipo de conta indefinido.");
      
      // Valida o login
      
      $pattern_login = "/^[a-zA-z1-9][^\\\']+$/";
      if(!preg_match($pattern_login,$login))
      {
          throw new Exception("O login só pode conter letras e números.");
      }
      
      // Validação dos dados via REGEX
      $patterns[0] = "/^\([0-9]{2}\)[0-9]{4}-[0-9]{4}$/"; // Pattern do telefone
      $patterns[1] = "/^([0-9a-zA-Z]+([_.-]?[0-9a-zA-Z]+)*@[0-9a-zA-Z]+[0-9,a-z,A-Z,.,-]*(.){1}[a-zA-Z]{2,4})+$/"; // Pattern do e-mail
      if(!preg_match($patterns[0],$tel))
          throw new Exception("Telefone inválido");
      
      if(!preg_match($patterns[1],$email2))
          throw new Exception("Email inválido");
      
      
      
      try
      {
         $insert = new conn();
         $valores = array("NULL", $login, md5($senha), $tipo, $nome, $tel, $logd, $bairro, $cidade, $estado, $email2);
         $campos = array("id", "login", "senha", "tipo", "nome", "telefone1", "logradouro", "bairro", "cidade", "estado", "email");
         $bind = array("STR", "STR", "STR", "INT", "STR", "STR", "STR", "STR", "STR", "STR", "STR");
         $insert->prepareinsert("usuario", $valores, $campos, $bind);
         $insert->executa();
         $insert->prepareselect("usuario", "id", "login", $login);
         $insert->executa();
         $rsql2 = $insert->fetch;
         $mid = $rsql2['id_usuario'];
         $this->email = $email2;
         if ($tipo == 1)
         {
            $insert2 = new conn();
            $valores = array($mid, $sobrenome, $apelido);
            $campos = array("id", "sobrenome", "apelido");
            $bind = array("INT", "STR", "STR");
            $insert->prepareinsert("artista", $valores, $campos, $bind);
            $insert->executa();
         }
         elseif ($tipo == 2)
         {
            $insert2 = new conn();
            $valores = array($mid, $website);
            $campos = array("id","website");
            $bind = array("INT", "STR");
            $insert->prepareinsert("contratante", $valores, $campos, $bind);
            $insert->executa();
         }
      }
      catch (PDOException $a)
      {
         throw new Exception("Erro na query.");
      }

      if ($insert == true && $insert2 == true)
      { 
         $emailbody = "Parabéns, você acabou de efetuar um cadastro no site accordi!<br /> Esperamos que tire proveito de nosso site.<br />Bem vindo!";
         date_default_timezone_set('America/Sao_Paulo');
         $mail = new phpmailer();
         $mail->IsSMTP();  // Ativa o envio via SMTP
         $mail->IsHTML(true);
         $mail->SMTPAuth = true;
         $mail->SMTPSecure = "ssl";
         $mail->Host = 'smtp.gmail.com';
         $mail->Port = '465';
         $mail->Username = 'accordi.contato@gmail.com';
         $mail->Password = 'brotherh00d';
         $mail->From = "accordi.contato@gmail.com";   // Email de quem está enviando
         $mail->FromName = "Accordi";   // Email de quem está enviando
         $mail->AddAddress($this->email);
         $mail->Subject = "Accordi - Contato";
         $mail->Body = $emailbody;
        // $mailtrue = $mail->Send();
      }
      else
      {
         throw new Exception("Ocorreu um erro ao realizar o cadastro.");
      }
   }

   function ifExist($item, $campo, $table, $campo2="")
   {
      if ($campo2 == "")
         $campo2 = $campo;
      if ($item == "")
         return false;
      try
      {
         $this->sql = new conn;
         $this->sql->prepareselect($table, $campo, $campo2, $item);
         $this->sql->executa();
         if ($this->sql->rowcount == 0)
         {
            return true;
         }
         else
         {
            return false;
         }
      }
      catch (PDOException $a)
      {
         return false;
      }
   }

   function dadoPertence($item, $id, $campoitem, $campoid, $table)
   {
      try
      {

         $comparadores = array($campoid, $campoitem);
         $valores = array($id, $item);
         $this->sql->prepareselect($table, $campoid, $comparadores, $valores);
         $this->sql->executa();
         $rc = $this->sql->rowcount;
         if ($rc >= 1)
            return true;
         else
            return false;
      }
      catch (PDOException $a)
      {
         return false;
      }
   }

   function pegarMax($campo, $tabela)
   {
      try
      {
         $a = $this->sql->freeQuery("SHOW TABLE STATUS LIKE '$tabela'");
         if ($a != false)
         {
            return $a["Auto_increment"];
         }
         else
            return false;
      }
      catch (PDOException $a)
      {
         //echo $a->getMessage();
         unset($this->sql);
         return false;
      }
   }
   
   public function listValue($value, $table, $limit="")
   {
       $this->sql->prepareselect($table,$value,"","","same","","",PDO::FETCH_NUM,"all","",$limit);
       $this->sql->executa();
       $a = $this->sql->fetch;
       if($a == "") return false;
       else
       {
       foreach($a as $i => $v)
       {
           $a[$i] = ucwords(trim($v[0]));
       }
       $a = array_unique($a);
       sort($a);
       return $a;
       }
       
   }
   
   public function countUser($tipo)
   {
      $this->sql->prepareselect("usuario", "id", "tipo", $tipo, "same", "count");
      $this->sql->executa();
      $a = $this->sql->fetch;
      return $a['count(id_usuario)'];
   }
   
   public function mostrarAtualizacoes($id)
   {
       $dataf = new dataformat(); 
       if($id == "" || !is_numeric($id))
           return false;
       $a = $this->ifExist($id, "id", "usuario");
       if($a == true)
           return false;
       
       /*
        *  Pegamos o tipo do usuário
        */
       $this->sql->prepareselect("usuario","tipo","id",$id);
       $this->sql->executa();
       $tipo = $this->sql->fetch['tipo_usuario'];
       
       /*
        *  Pegamos as últimas 20 atualizações de cada -> Possívelmente vamos precisar dinamizar esses números.
        */
       if($tipo == 1)
       {
       $this->sql->prepareselect("musica", array("id","nome","data_insercao"), "id_artista", $id, "same","","", PDO::FETCH_NUM, "all", array("data_insercao","DESC"), array(0,20));
       $this->sql->executa();
       $atual = $this->sql->fetch;
       if($atual != "")
       {
       foreach ($atual as $k => $v)
       {
         $dataf->pegarData($v[2]);
         $un = $dataf->getUnix();
         $dt = $dataf->defineHorario();
         $lista[$un] = array("m",$v[0],$v[1],$dt);  
       }
       }
       }
       elseif($tipo == 2)
       {
       $this->sql->prepareselect("evento", array("id","nome","data_criacao"), "id_contratante", $id, "same","","", PDO::FETCH_NUM, "all", array("data_criacao","DESC"), array(0,20));
       $this->sql->executa();
       $atual = $this->sql->fetch;
       if($atual != "")
       {
       foreach ($atual as $k => $v)
       {
         $dataf->pegarData($v[2]);
         $un = $dataf->getUnix();
         $dt = $dataf->defineHorario();
         $lista[$un] = array("e",$v[0],$v[1],$dt); 
       }  
       }
       }
       $this->sql->prepareselect("recado", array("id_receptor","id_criador","data"), array("tipo","id_receptor"), array(0,$id), "same","","", PDO::FETCH_NUM, "all", array("data","DESC"), array(0,20),"");
       $this->sql->executa();
       $atual = $this->sql->fetch;
       if($atual != "")
       {
       foreach ($atual as $k => $v)
       {
         $u = new usuario($v[0]);
         $rn = $u->nome;
         $rl = $u->login;
         $u = new usuario($v[1]);
         $cn = $u->nome;
         $cl = $u->login;
         unset($u);
         $dataf->pegarData($v[2]);
         $un = $dataf->getUnix();
         $dt = $dataf->defineHorario();
         $lista[$un] = array("rr",$rn,$cn,$dt,$rl,$cl);  
       }
       }
       $this->sql->prepareselect("recado", array("id_receptor","id_criador","data","tipo"), array("id_criador"), array($id), "same","","", PDO::FETCH_NUM, "all", array("data","DESC"), array(0,20),"");
       $this->sql->executa();
       $atual = $this->sql->fetch;
       if($atual != "")
       {
       foreach ($atual as $k => $v)
       {
         if($v[3] != 3)
         {
         switch($v[3])
         {
             case 0:
                 $u = new usuario($v[0]);
                 $rn = $u->nome;
                 $rl = $u->login;
                 break;
             case 1:
                 $u = new musicas();
                 $u->infoMusica($v[0],"public");
                 $rn = $u->nome;
                 $rl = $u->id;
                 break;
             case 2:
                 $u = new eventos();
                 $u->id = $v[0];
                 $u->pegaInfo();
                 $rn = $u->nome;
                 $rl = $u->id;
                 break;
             case 4:
                 try
                 {
                 $u = new Portal();
                 $u->getId($v[0]);
                 $list = $u->pegaInfo();
                 $rn = $list[1];
                 $rl = $list[12];
                 }
                 catch(Exception $a)
                 {
                     
                 }
                 break;
         }
         $u = new usuario($v[1]);
         $cn = $u->nome;
         $cl = $u->login;
         unset($u);
         $dataf->pegarData($v[2]);
         $un = $dataf->getUnix();
         $dt = $dataf->defineHorario();
         $lista[$un] = array("rc$v[3]",$rn,$cn,$dt,$rl,$cl);
         }
       }
       }
       $this->sql->prepareselect("contato", array("id_artista","id_contratante","data_insercao"), array("id_contratante","id_artista","status"), array($id,$id,0), "same","","", PDO::FETCH_NUM, "all", array("data_insercao","DESC"), array(0,20),"",array("OR","AND"));
       $this->sql->executa();
       $atual = $this->sql->fetch;
       if($atual != "")
       {
       foreach ($atual as $k => $v)
       {
         $u = new usuario($v[0]);
         $an = $u->nome;
         $al = $u->login;
         $u = new usuario($v[1]);
         $cn = $u->nome;
         $cl = $u->login;
         unset($u);
         $dataf->pegarData($v[2]);
         $un = $dataf->getUnix();
         $dt = $dataf->defineHorario();
         $lista[$un] = array("c",$an,$cn,$dt,$al,$cl);  
       }
       }
       unset($dt);
       unset($un);
       unset($al);
       unset($an);
       unset($cn);
       unset($cl);
       unset($rn);
       unset($rl);
       unset($dataf);
       unset($atual);
       if($lista != "")
       {
       krsort($lista);
       return $lista;
       }
       else
           return false;
}

    public function do_post_request($url, $data, $optional_headers = null)
    {
      $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
                ));
      if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
      }
      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
      }
      return $response;
    }

    public function getLastUsers($limit)
    {
        /**
         *  Pega os últimos 5 usuários cadastrados no site.
         */

        if($limit == "" || !is_numeric($limit))
            return false;

        $this->sql->prepareselect("usuario", array("nome","imagem","login","id"), "", "", "same", "", "", NULL, "all", array("data_cadastro","DESC"), array("0",$limit));
        $this->sql->executa();
        return $this->sql->fetch;
    }
    
    public function validaRepeticao($nome)
    {
        /**
         *  Verifica se os caracteres criam uma ordem de repetição.
         *  @param string $nome
         *  @return bool
         */
        
        
        $nome = str_replace("/","",$nome);
        $nome = str_replace("[","",$nome);
        $nome = str_replace("]","",$nome);
        $nome = str_replace("^","",$nome);
        $nome = str_replace("$","",$nome);
        $nome = str_replace("?","",$nome);
        $nome = str_replace("+","",$nome);        
        $nome = str_replace("*","",$nome);        
        $nome = str_replace("{","",$nome);
        $nome = str_replace("}","",$nome);
        $nome = str_replace("\\","",$nome);
        $len = strlen($nome);
        $divi = 2;
        for($a = 0; $a < strlen($nome); $a++)
        {
            $substr = "";
            $divisao = number_format(($len/$divi),0);
            while($divisao == $divisao_old)
            {
               $divi+=1;
               $divisao = number_format(($len/$divi),0);              
            }
            if($divisao == 1)
            {
               $divi = strlen($nome);
            }
            $contador = 0;
            for($v = 0; $v < $divi; $v++)
            {
                for($h = 0; $h < $divisao; $h++)
                {
                    $substr[$v] .= ($nome[$contador+$h]);
                }
                $contador+=$h;          
            }
            for($i = 0; $i < count($substr); $i++)
            {
                $str = $substr[$i];
                $conta = count($substr);
                $pattern = "/($str){3}/";
                if(preg_match($pattern,$nome))
                {
                   return false;
                }
            }       
            if($divi >= strlen($nome))
                break;
            $divisao_old = $divisao;
            $divi = $divi+1;
        }
        
        return true;
    }
    



}

?>