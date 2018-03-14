<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Conexão MySQL                      //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
// v1.1 - Aplicado melhorias na função do select.
// v1.2 - Reforçada proteção contra SQL injection
// v1.3 - Adicionado função para adicionar vários tipos de verificação em um select.

class conn
{

   private $tabela;
   private $id;
   private $campos;
   private $valores;
   private $comparadores;
   private $tipo;
   private $valoresc;
   private $bind;
   private $modo;
   private $conector;
   private $orderby;
   private $groupby;
   private $fetchtipo;
   private $fetchtipo2;
   private $op;
   private $puttable;
   private $join;
   private $pdo;
   private $ccompare;
   public $fetch;
   public $rowcount;

   function conecta()
   {
      
      try
      {
          try
          {
              $this->pdo = new PDO("mysql:host=localhost;dbname=mydb;charset=utf8", "usr", "123456"); // Driver de conexão.
          }
          catch(Exception $a)
          {
               }
      $this->pdo->exec("SET NAMES utf8");
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Vamos trabalhar com tratamento de erros.
      }
      catch(PDOException $A)
      {
        $this->error[] = "Impossível conectar com o banco de dados.";  
        header("location: ".$_SESSION['root']."/down");
        exit();
      }
   }

   function __destruct()
   {
      $this->pdo = null; // Matamos a conexão PDO 
   }

   public function executa()
   {
      /*
       * Query pública para executar as funções após preparo.
       */
      $this->conecta();
      switch ($this->tipo)
      {
         case "update":
            $a = $this->updatequery();
            break;
         case "delete":
            $a = $this->deletequery();
            break;
         case "insert":
            $a = $this->insertquery();
            break;
         case "select":
            $a = $this->selectquery();
            break;
         default:
            return false;
            break;
      }
      $this->limpaCache();
      if ($a)
      {
         if ($a == true)
            return true;
         else
            return false;
      }
      return false;
   }

   public function escapeString($string)
   {
      // Não é recomendável usar o quote e utilizar o comando bind_param após isso.
      if (is_numeric($string))
         return $string;
      if ($string == "NULL")
         return $string;
      $string = htmlentities($string, ENT_NOQUOTES, "UTF-8");
     return $string;
   }

   public function htmlEscape($string)
   {
      $string_old = $string;
      $string = html_entity_decode($string,ENT_QUOTES,"UTF-8");  
      $string = strip_tags($string);
      return $string;
   }

   public function escapeFetchString($fetch)
   {
      if ($this->fetchtipo2 == "all")
      {
         foreach ($fetch as $i => $v)
         {
            foreach ($fetch as $i2 => $v2)
            {
               if(!is_numeric($v2)) 
               $fetch[$i][$i2] = htmlentities($fetch[$i][$i2],ENT_QUOTES,"UTF-8");
            }
         }
      }
      else
      {
         foreach ($fetch as $i => $v)
         {
            if(!is_numeric($v))
            $fetch[$i] = htmlentities($fetch[$i],ENT_QUOTES,"UTF-8");
         }
      }
      return $fetch;
   }

   private function limpaCache()
   {
      unset($this->tabela);
      unset($this->id);
      unset($this->campos);
      unset($this->valores);
      unset($this->comparadores);
      unset($this->tipo);
      unset($this->valoresc);
      unset($this->bind);
      unset($this->modo);
      unset($this->orderby);
      unset($this->groupby);
      unset($this->fetchtipo);
      unset($this->fetchtipo2);
      unset($this->op);
      unset($this->join);
      unset($this->conector);
      unset($this->pdo);
      unset($this->puttable);
      unset($this->ccompare);
      //public $fetch;
      //public $rowcount;
   }

   public function prepareupdate($valores, $campos, $tabela, $valoresc, $comparadores="", $bind="")
   {
      if ($campos == "" || $tabela == "" || $valoresc == "")
      {
         $this->error[] = "Você não pode deixar campos obrigatórios vazios - $campos - $tabela - $valoresc";
         return false;
      }
      $this->valores = $valores;
      $this->campos = $campos;
      $this->tabela = $tabela;
      $this->comparadores = $comparadores;
      $this->valoresc = $valoresc;
      $this->bind = $bind;
      if ($this->bind != "")
         $this->getParam();
      $this->tipo = "update";
      return true;
   }

   public function prepareinsert($tabela, $valores, $campos="", $bind="")
   {
      /*
       * Caso o parâmetro campos não seja passado ele vai tentar adicionar todos os campos.
       */

      if ($valores == "")
      {
         $this->error[] = "Você não pode deixar campos obrigatórios vazios";
         return false;
      }
      $this->tabela = $tabela;
      $this->valores = $valores;
      $this->campos = $campos;
      $this->bind = $bind;
      if ($this->bind != "")
         $this->getParam();
      $this->tipo = "insert";
   }

   public function prepareselect($tabela, $campos="", $comparadores="", $valores="", $modo="same", $op="", $join="", $fetchtipo=NULL, $fetchtipo2="", $orderby="", $limit="", $groupby="", $conector="AND",$puttable=1)
   {
      /*
       * Ok, o único campo para pesquisar é a tabela, caso o campos fica vazio ele realizará pesquisa de todos os campos da tabela.
       * OBS: Isso não é recomendável, devido á perde de desempenho na query.
       */
      if ($tabela == "")
      {
         $this->error[] = "Você não pode deixar campos obrigatórios vazios";
         return false;
      }
      if ($campos == "")
      {
         $campos = "*";
      }
      $this->op = $op;
      $this->join = $join;
      $this->tabela = $tabela;
      $this->campos = $campos;
      $this->comparadores = $comparadores;
      $this->valores = $valores;
      // Para utilização do LIKE
      if ($modo == "same")
         $this->modo = "=";
      elseif ($modo == "like")
         $this->modo = "like";
      //
      if(is_array($modo))
      {
          $this->modo = Array();
          foreach($modo as $i => $v)
          {
              $this->modo[] = $v;
          }
      }
      $this->orderby = $orderby;
      $this->fetchtipo = $fetchtipo;
      $this->fetchtipo2 = $fetchtipo2;
      $this->groupby = $groupby;
      $this->limit = $limit;
      $this->puttable = $puttable;
      $this->conector = $conector;
      $this->tipo = "select";
      return true;
   }

   public function preparedelete($tabela, $campo="", $valor="")
   {
      /*
       *  Você pode deletar tanto por valor quanto por ID, sendo assim os parâmetros campo e valor são opcionais.
       *
       */
      if ($campo == "" || $tabela == "")
      {
         $this->error[] = "Você não pode deixar campos obrigatórios vazios";
         return false;
      }

      $this->tabela = $tabela;
      $this->campos = $campo;
      $this->valores = $valor;
      $this->tipo = "delete";
      return true;
   }
   
   public function compararCampos($campo1,$campo2)
   {
       if($campo1 == "" || $campo2 == "")
           return false;
       $this->ccompare[] = $campo1;
       $this->ccompare[] = $campo2;
   }

   private function getParam()
   {
      if (!is_array($this->bind))
         switch ($this->bind)
         {
            case "STR":
               $this->bind = PDO::PARAM_STR;
               break;
            case "INT":
               $this->bind = PDO::PARAM_INT;
               break;
            case "NULL":
               $this->bind = PDO::PARAM_NULL;
               break;
         }
      else
      {
         foreach ($this->bind as $i => $v)
         {
            switch ($v)
            {
               case "STR":
                  $this->bind[$i] = PDO::PARAM_STR;
                  break;
               case "INT":
                  $this->bind[$i] = PDO::PARAM_INT;
                  break;
               case "NULL":
                  $this->bind[$i] = PDO::PARAM_NULL;
                  break;
            }
         }
      }
   }

   private function deletequery()
   {
      if ($this->error != "" || $this->tabela == "" || $this->campos == "")
      {
         $this->error[] = "Você não possui os dados necessários para executar a query.";
         return false;
      }
      $tabela = $this->tabela;
      $campo = $this->campos;
      $valor = $this->valores;
      $cstring = "DELETE FROM $tabela WHERE ";
      if(is_array($campo))
      {
         foreach($campo as $i => $v)
         {
            $cstring.= $v ."_".$tabela." = ".$this->escapeString($valor[$i])."";
            if($i < count($campo)-1) $cstring.= " AND ";
         }
      }
      else
         $cstring.= $campo . "_" . $tabela . " = ".$this->escapeString($valor)."";
      try
      {
         $sql = $this->pdo->prepare($cstring);
         $sql->execute();
         return true;
      }
      
      catch (PDOException $a)
      {
         $this->error[] = "Ocorreu um erro na execução da query/classe PDO.";
         return false;
      }
      catch (Exception $a)
      {
         $this->error[] = "Um erro estranho ocorreu durante a tentativa de realizar a ação.";
         return false;
      }
   }

   private function updatequery()
   {
      if ($this->error != "" || $this->tabela == "" || $this->valoresc == "" || $this->comparadores == "")
      {
         $this->error[] = "Você não possui os dados necessários para executar a query.";
         return false;
      }

      if (count($this->valoresc) != count($this->comparadores))
      {
         $this->error[] = "Seus valores não combinam com seus comparadores";
         return false;
      }

      $valores = $this->valores;
      if (!is_array($valores))
         $valores = $this->htmlEscape($valores);
      else
      {
    
         foreach ($valores as $i => $v)
         {
            $valores[$i] = $this->htmlEscape($v);
         }
      }
      if (!is_array($this->comparadores))
      {
         $valoresc = $this->escapeString($this->valoresc);
         $valoresc = $this->pdo->quote($valoresc);
      }
      elseif(is_array($this->valoresc))
      {
         foreach($this->valoresc as $i => $v)
         {
             $v_n = $this->escapeString($v);
             $valoresc[] = $v_n;
         }
      }
      $campos = $this->campos;
      $comparadores = $this->comparadores;
      if ($comparadores == "")
         $comparadores = "id";
      $acount = count($c); // Pega o número de posições no arrái.
      if (count($valores) != count($campos))
      {

         $this->error[] = "Seus número de valores difere do número de campos.";
         return false; // Precisamos ter o mesmo número de campos e valores, correto?
      }
      if (count($this->valores) != count($this->bind))
      {

         $this->error[] = "Seu número de parametros não bate com o número de argumentos";
         return false;
      }

      $cstring = "UPDATE $this->tabela SET "; // Aqui começa a criação da string de conexão.
      for ($i = 0; $i < count($campos); $i++)
      {
         if (!is_array($campos))
            $cstring.= $campos . "_" . $this->tabela . " = ? ";
         else
            $cstring.= $campos[$i] . "_" . $this->tabela . " = ? ";
         if ($i < count($campos) - 1)
            $cstring.= ",";
      }
      $cstring.= "WHERE "; 
      if (!is_array($comparadores))
         $cstring.= " `" . $comparadores . "_" . $this->tabela . "` = $valoresc";
      else
      {
         $count = count($comparadores); 
         foreach ($comparadores as $i => $v)
         {
            if ($i == $count-1)
               $cstring.= $v . "_" . $this->tabela . " = $valoresc[$i]";
            elseif ($i == $count-2)
               $cstring.= $v . "_" . $this->tabela . " = $valoresc[$i] AND ";
            else
               $cstring.= $v . "_" . $this->tabela . " = $valoresc[$i] ,";
         }
      }
      $this->query = $cstring;
      try
      {
         $sql = $this->pdo->prepare($cstring);
         if (!is_array($valores))
         {
            if ($this->bind == "")
               $this->bind = PDO::PARAM_STR;
            $sql->bindParam(1, $valores, $this->bind);
         }
         else
         {
            foreach ($valores as $i => $v)
            {
               $p = $i + 1;
               if ($this->bind == "")
                  $this->bind[$i] == PDO::PARAM_STR;
               $sql->bindParam($p, $valores[$i], $this->bind[$i]);
            }
         }
         $sql->execute();
         return true;
      }
      catch (PDOException $a)
      {
         $this->error[] = "Ocorreu um erro na execução da query/classe PDO. - Update";
         $this->error[] = $a->getMessage();
         return false;
      }
      catch (Exception $a)
      {
         $this->error[] = "Um erro estranho ocorreu durante a tentativa de realizar a ação.";
         return false;
      }
   }

   private function selectquery()
   {
      if ($this->tabela == "" || $this->campos == "")
      {
         $this->error[] = "Você não possui os dados necessários para executar a query.";
         return false;
      }
      if ($this->comparadores != "" && $this->valores == "" && is_array($this->comparadores))
      {
         foreach ($this->comparadores as $i => $k)
         {
            $this->valores[$i] = "NULL";
         }
      }

      $campos = $this->campos;
      $tabela = $this->tabela;
      $comparadores = $this->comparadores;
      $valores = $this->valores;
      $modo = $this->modo;
      $groupby = $this->groupby;
      $orderby = $this->orderby;
      $fetchtipo = $this->fetchtipo;
      $fetchtipo2 = $this->fetchtipo2;
      $limit = $this->limit;
      $cn = $this->conector;

      // Realiza a verificação dos valores, para proteger possíveis exploits na query sql.
      if (is_array($valores))
      {
         foreach ($valores as $i => $v)
         {
            if (!is_numeric($v))
               $valores[$i] = $this->escapeString($valores[$i]);
            if($this->modo == 'like' || $this->modo[$i] == 'like')
               $valores[$i] = "%".$valores[$i]."%";
            if(!is_numeric($valores[$i]))
                $valores[$i] = $this->pdo->quote($valores[$i]);
         }
      }
      else
      {
         if (!is_numeric($valores))
            $valores = $this->escapeString($valores);
         if($this->modo == 'like' || $this->modo[$i] == 'like')
            $valores = "%".$valores."%";
         if(!is_numeric($valores))
                $valores = $this->pdo->quote($valores);
      }

      
      // Início da formação da query.
      $cstring = "SELECT ";
      
      // Verifica se todos os campos precisam ser pesquisados.
      if ($campos == "*") $cstring.= "*";
      else
      {
         // Percorre todos os campos
         for ($i = 0; $i < count($campos); $i++)
         {
            
             if(!is_array($campos))
                 $campo_atual = $campos;
             else
                 $campo_atual = $campos[$i];
            
            // Verifica como deve ser formada a string do campo. 
            switch($this->puttable)
            {
                case 0:
                case 1:
                    // Adiciona a tabela atual dentro da query.
                    $campo_atual = $campo_atual."_".$this->tabela;
                    break;
                case 2:
                    // O usuário é livre para adicionar quaisquer verificações.
                    $campo_atual = $campo_atual;
                    break;
            }
            
            if($this->op == "count" || $this->op == "sum")
            {
                $campo_atual = $this->op."(".$campo_atual.")";
            }
            
            $cstring.= "$campo_atual";
            
            if ($i < count($campos) - 1)
               $cstring.= ", ";
         }
      }
      
      // Realiza a parte dos joins na tabela
      $cstring .= " FROM " . $tabela;
      
      if ($this->join != "" && is_array($this->join))
      {
         $cstring.= " " . $this->join[0] . " JOIN ".$this->join[1];
      }
      
      if ($comparadores != "")
      {
         // Agora vamos mecher com os comparadores.
         $cstring.= " WHERE ";
         
         // Se o comparador não for um array apenas jogamos ele dentro da string.        
         if (!is_array($comparadores))
         {
            if($this->puttable != 1)
                $cstring.= " `".$comparadores."` $modo $valores";
            else
                $cstring.= " `" . $comparadores . "_" . $tabela . "` $modo $valores";
         }
         else
         {
             // Os comparadores são arrays, será necessário adicionar as verificações n query.
             $c = 0;
             $conectores_last = 0;
            

             // Verifica se é necessário aplicar aspas antes da query
             if(is_array($cn))
             {
                 $ct = count($cn)-1;
                 if($cn[$conectores_last] != $cn[$ct])
                     $cstring.= "(";
             }
             foreach ($comparadores as $i => $v)
             {
                // Verificamos se será necessário unificar a tabela
                switch($this->puttable)
                {
                    case 1:
                        $comparador_atual = $v."_".$tabela;
                        break;
                    default:
                        $comparador_atual = $v;
                        break;
                }
                
                // Verifica se o modo não é um array
                if(is_array($modo))
                {
                    $modo_atual = $modo[$i];
                }
                else
                    $modo_atual = $modo;
                
                $cstring.= " $comparador_atual $modo_atual $valores[$i]";
                
                // Verificamos se está na hora de fechar as aspas
                if(is_array($cn))
                {
                    if($cn[$c] != $cn[$conectores_last] && $cn[$c] != "")
                    {
                        $cstring.= ")";
                        $conectores_last = $c;
                        $cstring.= " $cn[$i]";
                        if($cn[$conectores_last] != $cn[$ct] && $c != (count($comparadores)-1))
                        {
                            $cstring.= " (";
                        }
                    }
                    else
                        $cstring.= " $cn[$i]";
                }
                elseif($c != (count($comparadores)-1))
                    $cstring.= " $cn ";
                $c++;
             }
         }
         
      }
      
      // Agora, caso necessário, colocamos a comparação entre tabelas.
      if($this->ccompare != "")
              $cstring.= " AND ".$this->ccompare[0]." = ".$this->ccompare[1];
      
      // Agrupamos os resultados
      if ($groupby != "")
      {
         $cstring.= " GROUP BY $groupby ";
      }
      
      // Ordenamos os resultados, caso o orderby seja um array divisível por 2
      if ($orderby != "" && count($orderby)%2 == 0)
      {
         $count_orderby = count($orderby);
         $cstring.= " ORDER BY";
         for($i = 0; $i < $count_orderby; $i=$i+2)
         {
             if($i != 0)
                 $cstring.= ", ";
             if($this->puttable == 1)
                 $orderby[$i] = $orderby[$i]."_".$tabela;
             $cstring.= " ". $orderby[$i]. " ". $orderby[$i+1];
         }
      }
      
      if ($limit != "")
      {
         if(!is_array($limit) && is_numeric($limit)) 
             $cstring.= " LIMIT $limit";
         if (is_array($limit) && is_numeric($limit[0]) && is_numeric($limit[1]))
             $cstring.= " LIMIT $limit[0],$limit[1]";
      }
      
      
      // Realiza os encodings necessários para proteger a query SQL.
      //$cstring = html_entity_decode($cstring, ENT_NOQUOTES, "UTF-8");         
      $codigo_acentos = array('&amp;Agrave;','&amp;Aacute;','&amp;Acirc;','&amp;Atilde;','&amp;Auml;','&amp;Aring;','&amp;agrave;','&amp;aacute;','&amp;acirc;','&amp;atilde;','&amp;auml;','&amp;aring;','&amp;Ccedil;','&amp;ccedil;','&amp;Egrave;','&amp;Eacute;','&amp;Ecirc;','&amp;Euml;',
                        '&amp;egrave;','&amp;eacute;','&amp;ecirc;','&amp;euml;',
                        '&amp;Igrave;','&amp;Iacute;','&amp;Icirc;','&amp;Iuml;',
                        '&amp;igrave;','&amp;iacute;','&amp;icirc;','&amp;iuml;',
                        '&amp;Ntilde;',
                        '&amp;ntilde;',
                        '&amp;Ograve;','&amp;Oacute;','&amp;Ocirc;','&amp;Otilde;','&amp;Ouml;',
                        '&amp;ograve;','&amp;oacute;','&amp;ocirc;','&amp;otilde;','&amp;ouml;',
                        '&amp;Ugrave;','&amp;Uacute;','&amp;Ucirc;','&amp;Uuml;',
                        '&amp;ugrave;','&amp;uacute;','&amp;ucirc;','&amp;uuml;',
                        '&amp;Yacute;',
                        '&amp;yacute;','&amp;yuml;',
                        '&amp;ordf;',
                        '&amp;ordm;');
      
      $codigo_acentos2 = array('&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&Ccedil;','&ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;',
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
                        '&ordm;');


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
                       'º');
      
      $cstring = str_replace($codigo_acentos,$acentos,$cstring);
      $cstring = str_replace($codigo_acentos2,$acentos,$cstring);
      
      $this->query = $cstring;
      try
      {
         if(is_null($this->error))
         {
         $sql = $this->pdo->query($cstring);
         if ($fetchtipo2 == "all")
            $a = $sql->fetchAll($fetchtipo);
         else
            $a = $sql->fetch($fetchtipo);
         $this->rowcount = $sql->rowCount();
         if ($sql->rowCount() == 0)
         {
            $this->fetch = "";
            return false;
         }
         $this->fetch = $this->escapeFetchString($a);
         return true;
         }
         return false;         
      }
      catch (PDOException $a)
      {
         $this->error[] = "Ocorreu um erro na execução da query/classe PDO. - Select";
         return false;
      }
      catch (Exception $a)
      {
         $this->error[] = "Um erro estranho ocorreu durante a tentativa de realizar a ação.";
         return false;
      }
   }
   
   public function freeQuery($query,$all=false,$fetch=true)
   {
      $this->conecta();
      // É um pouco perigoso utilizar isso. Use apenas para situações extremas ou onde não exista interferência direta do usuário.
      $a = $this->pdo->query($query);
      $this->limpaCache();
      if($fetch == false)
          return;
      if($all == false)
          $fr = $a->fetch();
      else
          $fr = $a->fetchAll();
      return $fr;
   }

   private function insertquery()
   {
      if ($this->valores == "" || $this->tabela == "")
      {
         $this->error[] = "Você não possui informações necessárias para realizar a query";
         return false;
      }
      if (count($this->valores) != count($this->bind) && $this->bind != "")
      {
         $this->error[] = "Seu número de parametros não bate com o número de argumentos";
         return false;
      }
      $tabela = $this->tabela;
      $valores = $this->valores;
      $campos = $this->campos;
      $bind = $this->bind;

      if (!is_array($valores))
         $valores = $this->htmlEscape($valores);
      else
      {
    
         foreach ($valores as $i => $v)
         {
            $valores[$i] = $this->htmlEscape($v);
         }
      }
      $cstring = "INSERT INTO $tabela ";
      if ($campos != "")
      {
         $cstring.= "( ";
         if (!is_array($campos))
            $cstring.= "" . $campos . "_" . $tabela . ")";
         else
         {
            foreach ($campos as $i => $v)
            {
               if ($i == count($campos) - 1)
                  $cstring.= "" . $v . "_" . $tabela . " )";
               else
                  $cstring.= "" . $v . "_" . $tabela . ", ";
            }
         }
      }
      $cstring.= " VALUES( ";
      if (!is_array($valores))
         $cstring.= "? )";
      else
      {
         foreach ($valores as $i => $v)
         {
            if ($i == count($valores) - 1)
               $cstring.= "? )";
            else
               $cstring.= "? , ";
         }
      }
      try
      {
         $sql = $this->pdo->prepare($cstring);
         if (is_array($valores))
         {
            foreach ($valores as $i => $v)
            {
               $p = $i + 1;
               if ($this->bind == "")
                  $this->bind[$i] = PDO::PARAM_STR;
               $sql->bindParam($p, $valores[$i], $this->bind[$i]);
            }
         }
         else
         {
            $sql->bindParam(1, $valores, $this->bind);
         }
         $sql->execute();
         return true;
      }
      catch (PDOException $a)
      { 
         $this->error[] = "Ocorreu um erro na execução da query/classe PDO - Insert. - ".$a->getMessage();
         return false;
      }
      catch (Exception $a)
      {
         $this->error[] = "Um erro estranho ocorreu durante a tentativa de realizar a ação.";
         return false;
      }
   }

}
