<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Portal                  //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class Portal
{
  
   // Propriedades das notícias
   private $id;
   private $titulo;
   private $resumo;
   private $categoria;
   private $autor;
   private $tags;
   private $visualizacoes;
   private $imagem;
   private $data;
   private $data_edicao;
   private $titulo_url;
   public  $list_count;
   
   // Propriedades das box
   private $bid;
   private $btipo;
   
   // Propriedades gerais
   private $conn;   
   public $errorlog;
   
   
   // Métodos
   
   public function __construct()
   {
       /**
        * Instancia a classe de conexão com o bd.
        */
       
       $this->conn = new conn();
   }
   
   public function getId($id)
   {
       /**
        *  Pega o ID de uma notícia
        *  @param id
        *  @return bool
        */
       
       if($id != "" && is_numeric($id))
       {
           $this->id = $id;
           return true;
       }
       
       else
       {
           return false;
       }       
   }
   
   public function getBid($id)
   {
       /**
        *  Pega o ID de uma box
        *  @param id
        *  @return bool 
        */
       
       if($id != "" && is_numeric($id))
       {
           $this->bid = $id;
           return true;
       }
       else
       {
           return false;
       }       
   }
   
   public function setId()
   {
       /**
        *  Envia o ID de uma notícia.
        *  @return int
        */
       
       if($this->id == "")
           return 0;
       else
           return $this->id;
   }
   
   public function getAutor($id)
   {
       /**
        *  Pega o autor de uma notícia
        *  @param id
        *  @return bool
        */
       
       if($id != "" && is_numeric($id))
       {
           $this->autor = $autor;
           return true;
       }
       
       else
       {
           return false;
       }
   }
   
   public function pegaInfo()
   {
       /**
        *  Pega todas as informações de uma notícia
        */
       
       if($this->id == "")
       {
           $this->errorlog[] = "Id inválido";
           throw new Exception("Id inválido");
       }
       
       $campos = array("id","titulo","resumo","categoria","autor","tags","visualizacoes","imagem","data","data_edicao","texto","titulo_url");
       $this->conn->prepareselect("noticia",$campos,"id",$this->id);
       $this->conn->executa();
       
       if($this->conn->fetch == "")
       {
           $this->errorlog[] = "Nenhuma notícia encontrada";
           throw new Exception("Nenhuma notícia encontrada.");
       }
       
       
       // Passa as informações da notícia para as propriedades.
       $r = $this->conn->fetch;
       $this->id = $r[0];
       $this->titulo = $r[1];
       $this->resumo = $r[2];
       $this->categoria = $r[3];
       $this->autor = $r[4];
       $this->tags = $r[5];
       $this->visualizacoes = $r[6];
       $this->imagem = $r[7];
       $this->data = $r[8];
       $this->data_edicao = $r[9];
       $this->texto = $r[10];
       $this->titulo_url = $r[11];
       
       if($this->imagem != '0')
       {
           $thumb = explode(".",$this->imagem);
           $thumb = $thumb[0]."_thumb.".$thumb[1];
       }
       
       // Retorna as informações.
       
       $this->getTags();
       
       $c = new comentarios();
       $texto_bb = $c->bbCode($r[10],false,true);
       
       $dataformat = new dataformat(); // Classe para formatar datas.
       
       // Formata a primeira data
       $dataformat->pegarData($this->data);
       $data_return = $dataformat->defineHorario(true,false);
       
       // Formata a segunda data
       if($this->data_edicao != "")
       {
           $dataformat->pegarData($this->data_edicao);       
           $data_edicao_return = $dataformat->defineHorario(true,false);
       }
       
       $url = explode("-",$this->data);
       $url = "/".$url[0]."/".$url[1]."/".$this->titulo_url;
       
       $array = array( $this->id,
                       $this->titulo,
                       $this->resumo,
                       $this->categoria,
                       $this->autor,
                       $this->tags,
                       $this->visualizacoes,
                       $this->imagem,
                       $data_return,
                       $data_edicao_return,
                       $this->texto,
                       $texto_bb,
                       $url,
                       $thumb);
       return $array; // Retorna as informações com as notícias.
       
   }
   
   private function replaceUrlTitle()
   {
       /**
        * Transforma o título em uma string compatível com URLs
        */
       
       if($this->titulo == "")
       {
           $this->errorlog[] = "Título inexistente.";
           throw new Exception("Titulo inexistente");
       }

       // Lista de caracteres indesejáveis
       $search = array(".",",","/","\\","!","@","#","$","¨","&","*","?","(",")","+","-",":",":","\"","'",">","<","{","}","[","]");
       $titulo_url = str_replace($search,"",$this->titulo);
       
       // Passa tudo para minúsculo
       $titulo_url = strtolower($titulo_url);
       
       // Retira acentos
       $search = array("à","è","é","ò","ó","í","ì","ã","õ","ê","â","ô","û","ú","ù");
       $replch = array("a","e","e","o","o","i","i","a","o","e","a","o","u","u","u");
       str_replace($search,$rplch,$titulo_url);
       
       // Troca os espaços por hífen
       $titulo_url = str_replace(" ","-",$titulo_url);
       
       return $titulo_url;
   }
   
   public function listaNoticias($tag,$page,$tipo)
   {
       /**
        *  Lista um certo número de notícias de acordo com a categoria.
        *  @param string $tag
        *  @param int $page
        *  @param int $tipo
        *  @return motherfuckinarray
        */
       
      
       // A página vem primeiro caso a categoria não seja citada
       if($tag == "all")
           $tag = "";
       
       $compara = array("","","same"); // Criamos um array de comparadores que só será preenchido caso uma categoria seja especificada, poupando comparação inútil.
       if($tag != "")
       {
           if($tipo == 0)
            $compara = array("categoria",$tag,"same");
           elseif($tipo == 1)
            $compara = array("titulo",$tag,"like");   
       }
       $limit = $page*20;
       
       // Conta o número de registros sobre aquele resultado.
       $this->conn->prepareselect("noticia","id",$compara[0],$compara[1],$compara[2],"count");
       $this->conn->executa();
       $this->list_count = $this->conn->fetch[0];
       if($limit > $this->list_count)
       {
           $this->errorlog[] = "Não foi possível encontrar resultados.";
           throw new Exception("Não foi possível encontrar resultados.");
       }
       
       // Mostra os resultados apontados
       $this->conn->prepareselect("noticia","id",$compara[0],$compara[1],$compara[2], "", "", PDO::FETCH_COLUMN, "all", array("id","DESC"), array($limit,20));
       $this->conn->executa();
       
       if($this->conn->fetch != "")
       {
           $lista = array();
           foreach($this->conn->fetch as $i => $v)
           {
               // Pega o ID da notícia e joga na classe.
               $this->getId($v);
               $lista[] = $this->pegaInfo();
           }
           return $lista;
       }
       else
       {
           throw new Exception("Nenhnuma notícia foi encontrada ",231); // Lançamos uma excessão caso não exista nenhuma notícia ^^v
       }
   }
   
   public function criarNoticias($titulo,$resumo,$categoria,$autor,$tags,$imagem,$texto)
   {
       /**
        *  Realiza a criação de uma nova notícia
        *  @param string $titulo
        *  @param string $resumo
        *  @param string $categoria
        *  @param int $autor
        *  @param string $tags
        *  @param file $imagem
        *  @param string $texto
        */
       
       
        if($titulo == "" || $autor == "" || $texto == "")
        {
            $this->errorlog[] = "Não é possível cadastrar notícias com os campos vazios.";
            throw new Exception("Campos vazios");
        }
        
        if(!is_numeric($autor))
        {
            $this->errorlog[] = "Autor inválido.";
            throw new Exception("Autor inválido.");
        }
        
        if(strlen($categoria) > 50 || strlen($titulo) > 80 || strlen($resumo) > 255)
        {
            $this->errorlog[] = "Parametros excedendo o número de caracteres.";
            throw new Exception("Parametros excedendo o número de caracteres.");
        }
        
        /* Verifica se o usuário possui permissões para inserir as notícias */
        $usuario = new usuario($autor);
        if($usuario->isAdmin() <= 0)
        {
            $this->errorlog[] = "O usuário não tem permissão para criar uma notícia.";
            throw new Exception("Usuário sem permissão para criar notícia");
        }
        
        // Verifica se o título não possui caracteres indesejáveis
        $padrao = "/^[^\/\\\'\";:-]*$/";
        if(preg_match($padrao, $titulo) == false)
        {
            /* O título possui caracteres que não são permitidos */
            $this->errorlog[] = "Título inválido.";
            //throw new Exception("Título inválido.");
        }
        
        if(preg_match($padrao, $categoria) == false)
        {
            /* O título possui caracteres que não são permitidos */
            $this->errorlog[] = "Categoria inválido.";
            throw new Exception("Categoria inválido.");
        }
        
        // Precisamos realizar uma verificação no título para não ocorrer duplicidade (Visto que vamos utiliza-lo como referência de URL)
        $titulo = trim($titulo); // Primeiro vamos tirar espaços desnecessários do título.
        $mes = date("m");
        $ano = date("Y");
        $this->titulo = $titulo;
        try
        {
            $titulo_url = $this->replaceUrlTitle();
        }
        catch(Exception $a)
        {
            throw new Exception("Falha ao gerar título.");
        }
        
        if($this->getIdByUrl($ano."/".$mes."/".$titulo_url) == true)
        {
            $this->errorlog[] = "Já existe uma notícia com esse nome.";
            throw new Exception("Já existe uma notícia com esse nome.");
        }
        
        try
        {
            $this->manageImagens($imagem,4);
            $this->manageImagens($imagem);
        }
        catch(Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
        
        $h = new validate();
        $max = $h->pegarMax('id', 'noticia');
        /* Pega o ID da notícia a ser criada  */
        $this->id = $max;
        
        $campos = array("titulo","resumo","categoria","autor","tags","imagem","texto","titulo_url");
        $valores = array($titulo,$resumo,$categoria,$autor,$tags,$this->imagem,$texto,$titulo_url);
        $bind = array("STR","STR","STR","INT","STR","STR","STR","STR");
        $this->conn->prepareinsert("noticia", $valores, $campos, $bind);
        $a = $this->conn->executa();
        if($a ==  false)
        {
            $this->errorlog[] = "Não foi possível inserar a notícia desejada.";
            throw new Exception("Não foi possível inserir a notícia desejada.");
        }       
   }
   
   public function editarTexto($texto)
   {
       /**
        *  Edita apenas o texto da notícia.
        *  @param string $texto
        */
       
       if($this->id == "")
       {
           $this->errorlog[] = "Você não possui ID definido.";
           throw new Exception("Você não possui ID definido.");
       }
       
       // Retira o excesso de espaços
       $texto = trim($texto);
       
       if($texto == "")
       {
           $this->errorlog[] = "Você não pode deixar a notícia em branco.";
           throw new Exception("Você nõa pode deixar a notícia em branco.");
       }
       
       if($this->verificaPermissao($_SESSION['id_usuario']) == false)
       {
           $this->errorlog[] = "Você não tem permissão para editar essa notícia.";
           throw new Exception("Você não tem permissão para editar essa notícia.");
       }
       
       $this->conn->prepareupdate($texto, "texto", "noticia", $this->id, "id", "STR");
       $a = $this->conn->executa();
       if($a != true)
       {
           $this->errorlog[] = "Ocorreu um erro ao editar a notícia.";
           throw new Exception("Ocorreu um erro ao editar a notícia.");
       }
       
       
   }
   
   
   private function manageImagens($imagem,$tipo=0)
   {
       /**
        *   Método para gerenciar as imagens das notícias.
        *   @param file $imagem
        */
       
       // Instancia a classe para gerenciamento de imagem.
       $img_classe = new imagem();
       
       if($imagem['name'] == "")
       {
           // Verifica se a imagem não é um parametro nulo.
           $this->errorlog[] = "Nenhuma imagem foi utilizada, teoricamente isso não é um erro mas vamos guardar o alerta.";
           $this->nome = "";
           return;
       }
       
       // Quando vamos criar as box nós não recebemos as imagens via método post, e sim via URL.
       // Neste caso  temos que abrir a imagem e conseguir as variáveis de trabalho.
       if($tipo != 0 && $tipo != 4)
       {           
           $a = $img_classe->pegarSavedImagem($imagem);  
       }
       else
       {
           $a = $img_classe->pegarImagem($imagem);
       }
       if($a != 'true')
       {
           $this->errorlog[] = "A imagem que você tentou adicionar não é válida.";
           throw new Exception("Imagem inválida.");
       }
       
       /* Pega o nome da imagem em questão, retorna uma excessão caso o nome dela seja nulo. */
       $nome = $img_classe->setNome();
       if($nome == "")
       {
           $this->errorlog[] = "A imagem possui um nome inválido.";
           throw new Exception("A imagem possui um nome inválido.");
       }
       
       $nome = md5($nome); // Geramos um md5 com o nome do arquivo.
       // OBS: Caso 2 arquivos com o mesmo nome sejam criados a classe de imagens vai gerar outro nome.
       
       switch($tipo)
       {
           case 0: $img_classe->dimensoesMaximas(690,250); break; // As imagens que passarem das dimensões máximas serão redimensionadas
           case 1: $img_classe->dimensoesMaximas(170,140); break; // Box tipo 1
           case 2: $img_classe->dimensoesMaximas(150,120); break; // Box tipo 2
           case 3: $img_classe->dimensoesMaximas(160,120); break; // Box tipo 3
           case 4: $img_classe->dimensoesMaximas(70,50); break; // Thumb
       }
       
       if($tipo == 0)
           $img_classe->redimensionaOn();
       else
       {
           $img_classe->cropOn();
       }
       if($tipo == 4)
           $img_classe->generate("./imagens/noticias/".$nome."_thumb",true); // Gera a imagem no diretório de notícias.
       else
           $img_classe->generate("./imagens/noticias/".$nome,true); // Gera a imagem no diretório de notícias.
       $this->imagem = $img_classe->setNome().".".$img_classe->formatoImg();      
   }
   
   public function deletarNoticia()
   {
       /**
        *  Deleta uma notícia baseando-se no ID.
        */
       
       if($this->id == "")
       {
           $this->errorlog[] = "Não existe ID para deletar.";
           throw new Exception("Não existe ID para deletar.");
       }      
       
       // Pega informações da notícia que vai ser deletada.
       $this->pegaInfo();
       
       // Deleta a imagem da notícia
       if($this->imagem != "")
            unlink("./imagens/noticias/".$this->imagem);
       
       if($this->verificaPermissao($_SESSION['id_usuario']) == false)
       {
           $this->errorlog[] = "O usuário não tem permissão para deletar essa notícia.";
           throw new Exception("O usuário não tem permissão para deletar essa notícia.");
       }
          
       $this->conn->preparedelete("noticia","id",$this->id);
       $this->conn->preparedelete("recado",array("id","tipo"),array($this->id,4));
       $a = $this->conn->executa();
       if($a == false)
       {
           $this->errorlog[] = "Falha ao deletar notícia.";
           throw new Exception("Falha ao deletar notícia.");
       }
       
   }
   
   private function verificaPermissao($editor)
   {
       if($this->autor != $editor)
       {
           /* O autor é diferente do editor, vamos verificar se o editor possui as permissões necessárias para editar a notícia do autor. */
           $usuario = new usuario($editor);
           $usuario2 = new usuario($this->autor);
           if($usuario2->isAdmin() > $usuario->isAdmin())
           {
               return false;
           }           
       }
       return true;
   }
   
   public function getIdByUrl($url)
   {
       /**
        *  Pega o ID de acordo com a URL informada
        *  @param string $url
        *  @return bool
        */
       
       $url = explode("/",$url);
       
       if(!is_array($url) || count($url) != 3)
       {
           $this->errorlog[] = "A url não oferece todos os parametros necessários.";
           return false;
       }
       
       if(!is_numeric($url[0]) || !is_numeric($url[1]))
       {
           $this->errorlog[] = "Os primeiros dois parâmetros necessitam ser numéricos, afinal são datas. ( p1 $url[0] / p2 $url[1] )";
           return false;
       }
       
       // Troca os hífens por espaço.       
       $this->conn->prepareselect("noticia", "id_noticia", array("year(data_noticia)","month(data_noticia)","titulo_url_noticia"), array($url[0],$url[1],$url[2]), "same", "", "", NULL, "", "", "", "", "AND", 2);
       $this->conn->executa();
       if($this->conn->fetch == "")
       {
           $this->errorlog[] = "Não foi encontrado nenhum registro.";
           return false;
       }
       else
       {
           $this->id = $this->conn->fetch[0];
           return true;
       }
   }
   
   public function getCategoria($categoria="")
   {
       /**
        *  Pega o nome de uma categoria específica ou lista o nome de todas.
        *  @param string $categoria
        *  @return array
        */
       
       $compara = array("","");
       if($categoria != "")
       {
           $compara = array("categoria",$categoria);
       }
       $this->conn->prepareselect("noticia","categoria",$compara[0],$compara[1], "same", "", "", PDO::FETCH_NUM, "all", array("categoria","ASC"));
       $this->conn->executa();
       // Não foi encontrado nenhum registro de categoria
       if($this->conn->fetch == "")
       {
           $this->errorlog[] = "Não foi encontrado nenhum tipo de categoria.";
           throw new Exception("Não foi encontrado nenhum tipo de categoria.");
       }
       
       return $this->conn->fetch;       
       
   }
   
   private function getTags()
   {
       /**
        * Pega a lista de tag's para a notícia.
        */
       
       if($this->tags == "")
       {
           $this->errorlog[] = "Nehnuma tag encontrada.";
           throw new Exception("Nenhuma tag encontrada");
       }
       
       $this->tags = explode(",",$this->tags);
       
   }
   
   public function addView()
   {
       /**
        *  Aumenta em 1 o número de visualizações da notícia.
        */
       
       if($this->id == "")
               return; // Para a adição caso o ID não exista.
       
       $this->conn->prepareupdate($this->visualizacoes+1, "visualizacoes", "noticia", $this->id, "id", "INT");
       $this->conn->executa();
   }
   
   public function pegarMaisVisualizadas()
   {
       /**
        *  Reune uma lista das 10 notícias mais visualizadas dos últimos 5 dias.
        */
       
       try
       {
           date_default_timezone_set("America/Sao_Paulo");
           $data = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-5, date('Y')));
           $query = "SELECT id_noticia,data_noticia,visualizacoes_noticia FROM noticia WHERE data_noticia >= $data"; // Verifica as datas de 5 dias atrás
           $fetch = $this->conn->freeQuery($query,true);
           $lista_noticias = array();
           
           $data_format = new dataformat();
           
           if($fetch[0] == "")
           {
               throw new Exception("Nenhuma notícia encontrada.");
           }
           
           foreach($fetch as $i => $v)
           {
               $data_format->pegarData($v[1]);
               $unix = time() - $data_format->getUnix();               
               $media = $v[2]/$unix;              
               $lista_noticias[] = array($media,$v[0]);
           }
           
           if(is_array($lista_noticias))
               rsort($lista_noticias);
           
           // Agora pegamos as 10 primeiras notícias
           foreach($lista_noticias as $i => $v)
           {
               $this->getId($v[1]);
               $lista_info[] = $this->pegaInfo();
               
               if($i == 9)
                   break;
           }           
           return $lista_info;
       }
       catch(Exception $a)
       {
           throw new Exception("Nenhuma notícia encontrada.");
       }
       
       
   }
   
   
   /**
    *
    * 
    * 
    *         Início dos métodos relacionados com as Box
    *       - Criar novas box
    *       - Mostrar as box atuais
    *       - Deletar box
    * 
    * 
    */
   
   
   public function novaBox($tipo,$noticia)
   {
       /**
        *  Cria uma nova box para ficar nas notícias principais.
        *  @param int $tipo
        *  @param int $noticia
        */
       
       if($tipo > 3 || $tipo < 1)
       {
           // Ficou padronizado que as box serão definidas em 3 tipos
           // 1o tipo - 355px;
           // 2o tipo - 500px;
           // 3o tipo - 720px;
           $this->errorlog[] = "Tipo de box é inválida.";
           throw new Exception("Tipo de box é inválida.");
       }
       
       /**
        *  Pega informações das box e conta para ver se não excede o tamanho máximo.
        */
       try
       {
           $box_list = $this->loadBox();
           $box_count = 0;
           foreach($box_list as $i => $v)
           {
               $box_count+= $v[2];
           }
       }
       catch(Exception $a)
       {
          $box_count = 0;
       }
       
       if($box_count > 18)
       {
           $this->errorlog[] = "Número máximo de box excedido.";
           throw new Exception("Número máximo de box excedido.");
       }
       
       try
       {
           // Verifica se a notícia existe.
           $this->getId($noticia);
           $this->pegaInfo();
       }
       catch(Exception $ex)
       {
           // Passa uma exceção para frente caso não exista notícia alguma.
           throw new Exception($ex->getMessage());
       }
       
       // Gera as imagens que serão utilizadas pela box.
       if($this->imagem != "")
           $this->manageImagens("./imagens/noticias/".$this->imagem,$tipo);
       
       // Adiciona uma nova box com a notícia.
       $valores = array($noticia,$tipo,$this->imagem);
       $campos = array("noticia","tipo","imagem");
       $bind = array("INT","INT","STR");
       $this->conn->prepareinsert("box", $valores, $campos, $bind);
       $a = $this->conn->executa();
       if($a != true)
       {
           $this->errorlog[] = "Erro ao adicionar a box.";
           throw new Exception("Erro ao adicionar a box.");           
       }       
   }
   
   public function loadBox()
   {
       /**
        *  Carrega todas as box do portal.
        *  @return array
        */
       
       $this->conn->prepareselect("box",array("id","noticia","tipo","imagem"),"","", "same", "", "", PDO::FETCH_NUM, "all", array("id","ASC"));
       $this->conn->executa();
       
       if($this->conn->fetch == "")
       {
           $this->errorlog[] = "Nenhuma box encontrada.";
           throw new Exception("Nenhuma box encontrada");
       }
       
       return $this->conn->fetch;
   }
   
   public function deleteBox()
   {
       /**
        *  Deleta uma box baseando-se em seu ID
        */
       
       if($this->bid == "")
       {
           $this->errorlog[] = "ID da box não especificado.";
           throw new Exception("ID da box não especificado.");
       }
       
       // Pega a imagem da box e deleta
       $this->conn->prepareselect("box","imagem","id",$this->bid);
       $this->conn->executa();
       if($this->conn->fetch != "")
            unlink("./imagens/noticias/".$this->conn->fetch[0]);
       
       $this->conn->preparedelete("box", "id", $this->bid);
       $a = $this->conn->executa();
       if($a != true)
       {
           $this->errorlog[] = "Falha ao deletar box.";
           throw new Exception("Ocorreu uma falha ao deletar a box.");
       }
           
       
   }
    
}

?>