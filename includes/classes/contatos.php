<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Contato                               //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class contato
{
   private $id;
   private $idu;
   private $status;
   private $data;
   private $valor;
   private $descricao;
   private $termos;
   private $tipo;
   private $conn;
   public $errorlog;
   
   public function __construct() 
   {
       $this->conn = new conn();
   }
   
   public function getUser($usuario)
   {
      /**
       *
       * @param INT $usuario
       * @return VOID
       */
      $this->idu = $usuario;
   }
   
   public function getId($id)
   {
       /**
        *
        * @param INT $id
        * @return VOID
        */
       if(is_numeric($id))
       $this->id = $id;
   }
   public function getStatus($status)
    {
        if(is_numeric($status))
            $this->status = $status;
            return true;
    }
   private function statusToString($status)
   {
       /**
        *
        * @param INT $status
        * @return STRING
        */
       switch($status)
       {
           case 0: $s_string = "<span class='color-green'>Atuante</span>"; break;
           case 1: $s_string = "<span class='color-red'>Fechado</span>"; break;
           case 2: $s_string = "<span class='color-blue'>Aberto</span>"; break;
       }
       return $s_string;
   }
   
   public function listar_contatos($status,$tipo="")
   {
       /**
        *
        * @param INT $status
        * @param INT $tipo
        * @return ARRAY ou FALSE
        */
       
       $this->status = $status;
       if(!is_numeric($tipo) && $tipo != "")
           return false;
       
       if($tipo != "")
       {
           switch($tipo)
           {
               case 1: $c1 = "id_artista"; break;
               case 2: $c1 = "id_contratante"; break;
           }
       }
       
       switch($this->status)
       {
           case 0: $comparadores = array("id_receptor","id_remetente","status"); $valores = array($this->idu,$this->idu,$this->status); $conectores = array("OR","AND"); break;
           case 1: $comparadores = array("id_receptor","id_remetente","status"); $valores = array($this->idu,$this->idu,$this->status); $conectores = array("OR","AND"); break;
           case 2: $comparadores = array("id_remetente","status"); $valores = array($this->idu,2); $conectores = "AND"; break;
           case 3: $comparadores = array("id_receptor","status"); $valores = array($this->idu,2); $conectores = "AND"; break;
           case 4: $comparadores = array($c1,"status"); $valores = array($this->idu,0); $conectores = "AND"; break;
       }
       $this->conn->prepareselect("contato", array("assunto","data_insercao","status","id","id_receptor","id_remetente"), $comparadores, $valores, "same", "", "", NULL, "all",array("data","DESC"),"","",$conectores);
       $this->conn->executa();
       if($this->conn->fetch != "")
       {
        foreach($this->conn->fetch as $i => $v)
        {
            $u = new usuario($v[5]);
            $this->conn->fetch[$i][5] = $u->nome;
            unset($u);
            $u = new usuario($v[4]);
            $this->conn->fetch[$i][4] = $u->nome;
            unset($u);
            $data = new dataformat();
            $data->pegarData($v[1]);
            $this->conn->fetch[$i][1] = $data->defineHorario();
            unset($data);
            $this->conn->fetch[$i][2] = $this->statusToString($v[2]);
        }
        return $this->conn->fetch;
       }
       else
           return false;
   }
   
   public function pegarInfo()
   {
       /**
        *
        * Pegar informações de um contatos
        * @return ARRAY ou FALSE
        *  0 - id
        *  1 - id_remetente
        *  2 - id_receptor
        *  3 - assunto
        *  4 - data
        *  5 - valor
        *  6 - descricao
        *  7 - id_artista
        *  8 - id-contratante
        *  9 - termos
        *  10 - status
        *  11 - data_insercao
        *  12 - nome_remetente
        *  13 - nome_receptor
        *  14 - nome_artista
        *  15 - nome_contratante
        *  16 - status_string
        *  17 - artista_login
        *  18 - contratante_login
        *  19 - is_read
        */
       if(is_null($this->id))
           return false;
       
       // Realiza a query
       $valores = array("id","id_remetente","id_receptor","assunto","data","valor","descricao","id_artista","id_contratante","termos","status","data_insercao","is_read");
       $this->conn->prepareselect("contato",$valores,"id",$this->id);
       $this->conn->executa();
       
       $registro = $this->conn->fetch;
       $registro[19] = $registro[12];
       
       // Pega o nome dos usuários
       $u = new usuario($registro[1]);
       $registro[12] = $u->nome;
       $l1 = $u->login;
       unset($u);
       
       $u = new usuario($registro[2]);
       $registro[13] = $u->nome;
       $l2 = $u->login;
       unset($u);
       
       // Formata Valor
       
       if($registro[5] == "")
           $registro[5] = "Indefinido";
       else
           $registro[5] = "R$ $registro[5]";
       // Atribuimos os nomes para os artistas e contratantes sem precisar instanciar outro objeto usuário, pois o contato é entre dois usuários.
       // e obrigatóriamente o remetente e receptor tem que ser ou artista ou contratante.
       if($registro[1] == $registro[7])
       {
           $registro[14] = $registro[12];
           $registro[17] = $l1;
           $registro[15] = $registro[13];
           $registro[18] = $l2;
       }
       else
       {
           $registro[14] = $registro[13];
           $registro[17] = $l2;
           $registro[15] = $registro[12];
           $registro[18] = $l1;
       }
       
       // Formata datas
       $data = new dataformat;
       $data->pegarData($registro[4]);
       $registro[4] = $data->formatData();
       $data->pegarData($registro[11]);
       $registro[11] = $data->formatData();
       
       // Formata o status
       $registro[16] = $this->statusToString($registro[10]);
       
       // Formatar termos
       $cm = new comentarios();
       $registro[9] = $cm->bbCode($registro[9]);
       unset($cm);
       
       return $registro;   
   }
   
   public function createContato($assunto,$id_r,$data,$valor,$descricao)
   {
       /**
        *  function createContato
        *  @param STRING $assunto
        *  @param INT $id_r
        *  @param ARRAY $data
        *  @param DOUBLE $valor
        *  @param STRING $descricao
        *  @return BOOL
        */
       
        trim($assunto);
        trim($descricao);
        
        if(is_null($assunto) || $assunto == "")
        {
            $this->errorlog[] = "Adicione um assunto";
            return false;
        }
        if(is_null($id_r) || $assunto == "")
        {
            $this->errorlog[] = "Usuário não encontrado";
            return false;
        }
        if(is_null($data) || $assunto == "")
        {
            $this->errorlog[] = "Data inválida";
            return false;
        }
        if(strlen($assunto) > 22 || $assunto == "")
        {
            $this->errorlog[] = "Dados inválidos";
            return false;
        }
        
        // Validamos a Data
        $data_f = new dataformat();
        $vd = $data_f->validaData($data,true);
        if($vd == false)
        {
            $this->errorlog[] = "Data inválida";
            return false;    
        }
        // Validamos o valor
        $valor = str_replace(",",".",$valor);
        $valor = str_replace("R$","",$valor);
        if(!is_numeric($valor) && $valor != "")
        {
            $this->errorlog[] = "Valor inválido";
            return false;
        }
        
        // Pegamos os termos atuais do contratante
        
        if($_SESSION['id_usuario'] == "")
        {
            $this->errorlog[] = "Usuário inválido, você não está logado";
            return false;
        }
        if($_SESSION['tipo_usuario'] == 2)
        {
            $u = new usuario();
            $id_artista = $id_r;
            $id_contratante = $u->id;
        }
        else
        {
            $u = new usuario($id_r);
            $id_artista = $_SESSION['id_usuario'];
            $id_contratante = $id_r;
            
            // O receptor cumpre o papel de contratante, vamos verificar se o artista bateu as exigências mínimas.
            $exigencias = $u->getExigencias();
            $u2 = new usuario();
            if($u2->calcularIdade() < $exigencias[0])
            {
                $this->errorlog[] = "exigencia0";
                return false; // O artista é mais novo do que as exigências
            }
            if($u2->cidade != $exigencias[1] && $exigencias[1] != "")
            {
                $this->errorlog[] = "exigencia1";
                return false; // O artista não mora na cidade exigida
            }
            if($u2->estado != strtolower($exigencias[2]) && $exigencias[2] != "")
            {
                $this->errorlog[] = "exigencia2";;
                return false; // O artista não mora no estado exigido
            }
            if($valor < $exigencias[3])
            {
                $this->errorlog[] = "exigencia3";
                return false; // O valor do contato é muito baixo
            }
            
        }        
        $termos = $u->getTermos();
        $termos = $termos[1];
        unset($u,$vd,$data_f);
        
        // Realiza a criação
        $valores = array($id_r,$_SESSION['id_usuario'],$assunto,$data,$descricao,$id_artista,$id_contratante,$termos,2,$valor);
        $campos = array("id_receptor","id_remetente","assunto","data","descricao","id_artista","id_contratante","termos","status","valor");
        $bind = array("INT","INT","STR","STR","STR","INT","INT","STR","INT","INT");
        $this->conn->prepareinsert("contato", $valores, $campos, $bind);
        $a = $this->conn->executa();
        
        if($a == true)
        {
            return true;
        }
        else
        {
            $this->errorlog[] = "Falha na criação do contato";
            return false;
        }
       
   }
   
   public function alterarStatus()
   {
       /**
        *  Altera o status do contato para.
        */
       
       if($this->id == "" || !is_numeric($this->id))
           return false;
       
       if($this->status == "" || !is_numeric($this->id))
           return false;
       
       // Pega as informações do contato em questão.
       $infos = $this->pegarInfo();
       
       if($infos[10] == 1)
       {
           $this->errorlog[] = "Não é possível alterar o status de contatos já fechados.";
           return false; // Verificamos se o contato está fechado.
       }
       
       if($infos[10] == 0 && $this->status == 2 )
       {
           $this->errorlog[] = "Voce não pode pegar um contato aprovado e coloca-lo como aberto.";
           return false; // Verificamos se o contato é atuante e o status está ordenando que ele seja aberto denovo.
       }
       
       if($this->status == 1)
       {
           if($_SESSION['id_usuario'] == $infos[2])
               $this->alterarNotificacao(4);
           else
               $this->alterarNotificacao(5);
       }
       
       try
       {
           $this->conn->prepareupdate($this->status, "status", "contato", $this->id, "id", "INT");
           $this->conn->executa();
           return true;
       }
       catch(PDOException $a)
       {
           $this->errorlog[] = "Falha ao atualizar o status.";
           return false;
       }
   }
   
   public function alterarNotificacao($at)
   {
       /**
        *  Altera quem vai receber a notificação do contrato.
        *  
        *  Lista de notificações:
        *  0 - Não existem notificações.
        *  1 - Receptor recebe notificação de criação.
        *  2 - Remetente recebe notificação de atualização.
        *  3 - Receptor recebe notificação de atualização.
        *  4 - Remetente recebe notificação de fechamento.
        *  5 - Receptor recebe notificação de cancelamento.
        *  @param int $at
        *  @return bool
        */
       
        if($at < 0 || $at > 5)
            return false;
        
        if($this->id == "")
            return false;
        
        // Vamos apenas realizar a query para atualizar esse contato.
        
        try
        {
            $this->conn->prepareupdate($at,"is_read","contato",$this->id,"id","INT");
            $a = $this->conn->executa();
            if($a == true)
                return "true";
            else
                return false;
        }
        catch(PDOException $a)
        {
           return false; 
        }        
   }
   
   public function pegarNotificacoes()
   {
       /**
        *  Pega uma lista de contratos com as notificações que o usuário precisa receber.
        */
       
       /**
        *  Lista de informações
        *  1. is_read
        *  2. id
        *  3. assunto
        *  4. data
        *  5. id_artista
        *  6. id_contratante
        */
       
       $notificacoes = array();
       
       if($this->idu == "")
           return false;
       
       // Pegamos as notificações do receptor
       $this->conn->prepareselect("contato", array("is_read","id","assunto","data_insercao","id_artista","id_contratante"), array("is_read","is_read","is_read","id_receptor"), array("1","3","5",$this->idu), "same", "", "", NULL, "all", "", "", "", array("OR","OR","AND"));
       $this->conn->executa();
       if(is_array($this->conn->fetch))
       $notificacoes = $this->conn->fetch;
       
       // Pegamos as notificações do remetente
       $this->conn->prepareselect("contato", array("is_read","id","assunto","data_insercao","id_artista","id_contratante"), array("is_read","is_read","id_remetente"), array("4","2",$this->idu), "same", "", "", NULL, "all", "", "", "", array("OR","AND"));
       $this->conn->executa();
       if(is_array($this->conn->fetch))
       $notificacoes = array_merge($notificacoes,$this->conn->fetch);
       
       $this->c_notificacoes = count($noticicacoes);
       
       // Retornamos as notificações
       return $notificacoes;
   }
   
   public function countNotificacoes()
   {
      /**
       *  Conta quantas novas notificações o usuário tem em seus contatos.
       */ 
       
      if($this->idu == "")
          return 0;
       
      if($this->c_notificacoes != "")
          return $this->c_notificacoes;
      
      $this->conn->prepareselect("contato", "id", array("is_read","is_read","is_read","id_receptor"), array("1","3","5",$this->idu), "same", "count", "", NULL, "", "", "", "", array("OR","OR","AND"));
      $this->conn->executa();
      $s1 = $this->conn->fetch[0];
      
      $this->conn->prepareselect("contato", "id", array("is_read","is_read","id_remetente"), array("4","2",$this->idu), "same", "count", "", NULL, "", "", "", "", array("OR","AND"));
      $this->conn->executa();
      $s2 = $this->conn->fetch[0];
      
      $s1+=$s2; 
      return $s1;
   }
   
   public function countContatos()
   {
       /**
        *  Conta o número de contratos existentes.
        */
       
       if($this->idu == "")
       {
           $this->errorlog[] = "Não podemos contar os contatos de um usuário nulo.";
           return false;
       }
       
       $u = new usuario($this->idu);       
       
       if($u->tipo == 1)
           $t = 'id_artista_contato';
       else
           $t = 'id_contratante_contato';
       
       unset($u);
       $this->conn->prepareselect("contato", array('status_contato','count(id_contato)'), $t, $this->idu, "same", "", "", PDO::FETCH_NUM, "all", "", "", "status_contato", "AND",2);
       $this->conn->executa();
       
       if($this->conn->fetch == "")
       {
           $count = array("atuantes" => 0, "fechados" => 0, "abertos" => 0);
           return $count;
       }
       
       foreach($this->conn->fetch as $i => $v)
       {
           if($v[1] == "") $v[1] = 0;
           switch($v[0])
           {
               case 0:
                   $count['atuantes'] = $v[1];
                   break;
               case 1:
                   $count['fechados'] = $v[1];
                   break;
               case 2:
                   $count['abertos'] = $v[1];
                   break;
           }
       }
       
       return $count;
       
   }


}