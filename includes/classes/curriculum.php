<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Curriculum                               //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class curriculum
{
    private $id;
    private $aid;
    private $nome;
    private $sexo;
    private $idade;
    private $ramo;
    private $grupo_musical;
    private $area;
    private $img;
    public $lista_musicas;
    public $lista_contatos;
    public $lista_referencias;
    public $campo_editado;
    private $conn;
    
    // Log de erros
    public $errorlog;
    
    public function __construct()
    {
        $this->conn = new conn();
    }
    
    public function getUser($aid)
    {
        /**
         *  Pega o ID do artista para utilizar em consultas
         *  @param int $aid
         */
        
        if($aid != "" && is_numeric($aid))
            $this->aid = $aid;            
    }
    
    public function getId($id)
    {
        /**
         *  Pega o ID do curriculum para utilizar em consultas
         *  @param int $id
         */
        
        if($id != "" && is_numeric($id))
            $this->id = $id;            
    }
    
    public function validaCurriculum()
    {
        if($this->aid == "")
        {
            $this->errorlolg[] = "Não é possúivel validar o curriculum sem o id.";
            return false;
        }
        
        $this->conn->prepareselect("curriculum","id","id_artista",$this->aid);
        $this->conn->executa();
        
        if($this->conn->fetch == "")
            return false;
        else
            $this->id = $this->conn->fetch[0];
            return true;   
    }
    
    
    
    public function loadInfo()
    {
        /**
         *  Carrega informações do curriculum.
         *  
         */
        
        if($this->aid == "" && $this->id == "")
        {
            $this->errorlog[] = "Não é possível pegar informação do curriculum sem um parametro de consulta.";
            return false;
        }
        
        else if($this->id == "")
        {
            $a = $this->validaCurriculum($this->aid);
            if($a == false)
            {
                $this->errolog[] = "Curriculum inexistente.";
                return false;
            }
        }
        
        $campos = array("id","id_artista","nome","nascimento","sexo","ramo","instrumento","area","img","grupo_musical");
        $this->conn->prepareselect("curriculum",$campos,"id",$this->id);
        $this->conn->executa();
        $array = $this->conn->fetch;
        
        /**
         *  Carrega informações nos atributos das classes.
         */
        $this->id = $array['id_curriculum'];
        $this->aid = $array['id_artista_curriculum'];
        $this->nome = $array['nome_curriculum'];
        $this->idade = $array['nascimento_curriculum'];
        $this->sexo = $array['sexo_curriculum'];
        $this->ramo = $array['ramo_curriculum'];
        $this->instrumento = $array['instrumento_curriculum'];
        $this->area = $array['area_curriculum'];
        $this->img = $array['img_curriculum'];
        $this->grupo_musical = $array['grupo_musical_curriculum'];
        
        /**
         *  Vamos usar a classe Usuário para formatar a data e o sexo (visto que os métodos já existem).
         */
        $usuario = new usuario();
        $usuario->nascimento = $this->idade;
        $usuario->sexo = $this->sexo;
        if($this->idade != "")
            $this->idade = $usuario->calcularIdade();
        $this->sexo = $usuario->calcularSexo();
        
        /**
         *  Carrega as listas.
         */
        
        $this->pegarLista(0);
        $this->pegarLista(1);
        $this->pegarLista(2);
        $this->pegarLista(3);
        
        // Retorna um array com todas as informações.
        $infos = array(
            'id' => $this->id,
            'uid' => $this->aid,
            'nome' => $this->nome,
            'idade' => $this->idade,
            'sexo' => $this->sexo,
            'ramo' => $this->ramo,
            'instrumento' => $this->instrumento,
            'area' => $this->area,
            'img' => $this->img,
            'grupo_musical' => $this->grupo_musical
            );
        return $infos;
        
    }
    
    public function pegarLista($tipo)
    {
        /**
         *  Pega as listas, determinadas pelo tipo, passados por parametros.
         *  @param int $tipo
         */
        
        if($tipo < 0 || $tipo > 3)
        {
            $this->errorlog[] = "Você inseriu uma lista inválida.";
            return false;
        }
        
        if($this->id == "")
        {
            $this->errolog[0] = "Você não pode pegar a lista sem passar um parametro de identificação.";
            return false;
        }
        
        switch($tipo)
        {
            case 0: $lista = 'musicas'; break;
            case 1: $lista = 'contatos'; break;
            case 2: $lista = 'referencias'; break;
            case 3: $lista = 'tcontatos'; break;
        }
        
        
        $this->conn->prepareselect("curriculum","lista_$lista","id",$this->id);
        $this->conn->executa();
        if($this->conn->fetch[0] != "")
        {
        $a = $this->conn->fetch[0];
        $a = explode(",",$a);
        }
        
        switch($tipo)
        {
            case 0: $this->lista_musicas = $a; 
                    return true;
                    break;
            case 1: $this->lista_contatos = $a; 
                    return true;
                    break;
            case 2: $this->lista_referencias = $a;
                    return true;
                    break;
            case 3: $this->lista_tcontatos = $a;
                    return true;
                    break;
        }
        return false;
    }
    
    public function newCurriculum()
    {
        /**
         *  Cria um novo curriculum
         */
        
        if($this->aid == "")
        {
            $this->errorlog[] = "Não é possível criar um curriculum sem um usuário definido.";
            return false;
        }
        
        $a = $this->validaCurriculum();
        
        if($a == true)
        {
            $this->errorlog[] = "Curriculum já existe.";
            return false;
        }
        
        $u = new usuario($this->aid);
        $valores = array($this->aid,$u->nome." ".$u->sobrenome,$u->nascimento,$u->sexo,"prf");
        $bind = array("INT","STR","INT","STR","STR");
        $campos = array("id_artista","nome","nascimento","sexo","img");
        $this->conn->prepareinsert("curriculum", $valores, $campos, $bind);
        $u = $this->conn->executa();
        if($u != true)
        {
            $this->errorlog[] = "Ocorreu um erro ao adicionar o curriculum. / ".array_pop($this->conn->error);
            return false;
        }
        return true;
    }
    
    public function editCampo($campo,$valor)
    {
        /**
         *  Edita campo passado por parâmetros, a validação é feita via switch.
         *  @param string $campo
         *  @param string $valor
         *  @return bool
         */
        
        if($this->id == "" || !is_numeric($this->id))
        {
            $this->errorlog[] = "Você não inseriu um parâmetro de identificação ou seu parâmetro é inválido.";
            return false;
        }
        
        switch($campo)
        {
            case 'sexo':
                if($valor != 'Masculino' && $valor != 'Feminino' && $valor != 'Não Informar')
                {
                    $this->errorlog[] = "Sexo inválido";
                    return false;
                }
                switch($valor)
                {
                    case "Masculino": $valor = 'm'; break;
                    case "Feminino": $valor = 'f'; break;
                    case "Não Informar": $valor = 'i'; break;
                }
                $campo_sql = "sexo";
                $valor_sql = $valor;
                break;
            case 'area':
                $campo_sql = "ramo";
                $valor_sql = $valor;
                break;
            case 'instrumento':
                $campo_sql = "instrumento";
                $valor_sql = $valor;
                break;
            case 'regiao':
                $campo_sql = "area";
                $valor_sql = $valor;
                break;
            case 'grupo_musical':
                $campo_sql = "grupo_musical";
                $valor_sql = $valor;
                break;
            default:
                $this->errorlog[] = "Você inseriu um campo inválido, ele não existe. :(";
                return false;
                break;               
        }
        
        if($campo_sql != "" && $this->errorlog == "")
        {
            $this->conn->prepareupdate($valor_sql, $campo_sql, "curriculum", $this->id, "id", "STR");
            $a = $this->conn->executa();
            
            /**
             *  Atribui valor ao novo campo para utilizar de retorno.
             */
            $this->conn->prepareselect("curriculum",$campo_sql,"id",$this->id);
            $this->conn->executa();
            
            if($this->conn->fetch != "" && $a == true)
            {
                // Consertamos apenas o sexo
                if($campo == "sexo")
                {
                    switch($this->conn->fetch[0])
                    {
                        case 'i': $this->conn->fetch[0] = "Não Informado"; break;
                        case 'm': $this->conn->fetch[0] = "Masculino"; break;
                        case 'f': $this->conn->fetch[0] = "Feminino"; break;
                    }
                }
                $this->campo_editado = $this->conn->fetch[0];
                return true;
            }                
            else
            {
                $this->errorlog[] = "Ocorreu um erro ao editar o campo.  ".$this->conn->query;
                return false;
            }
        }
    }

    
    public function showImagem()
    {
        if($this->img == "")
        {
            $this->errorlog[] = "Impossívei mostrar uma imagem inexistente";
            return false;
        }
        
        if($this->aid == "")
        {
            $this->errorlog[] = "Impossível mostrar uma imagem sem um usuário definido";
        }
        
        if($this->img == "prf")
        {
            $u = new usuario();
            echo $u->mostrarImagem();
            return;
        }
        else
        {
           echo "<img src='".$_SESSION['root']."/imagens/curriculum/$this->aid.$this->img alt='Imagem' />";
            
        }
    }
    
    public function deleteCurriculum()
    {
        if($this->id == "")
        {
            $this->errorlog[] = "Não é possível deletar um curriculum sem especificar seu ID";
            return false;
        }
        $a = $this->loadInfo();
        if($_SESSION['id_usuario'] != $this->aid)
        {
            $this->errorlog[] = "Você não pode deletar um curriculum que não é seu... por sinal, como você chegou aqui?";
            return false;
        }
        
        $this->conn->preparedelete("curriculum","id",$this->id);
        $a = $this->conn->executa();
        if($a == true)
        {
            return true;
        }
        else
        {
            $this->errorlog[] = "Falha ao deletar curriculum";
            return false;
        }
    }
    
    public function listaAddItem($tipo,$item)
    {
        /**
         *  Adiciona um item na lista.
         *  @param int $tipo
         *  @param int $item
         *  @return bool
         */
        
        
        // Óbviamente, verificamos se o temos um curriculum para atualizar a lista.
        if($this->id == "")
        {
            $this->errorlog[] = "Não é possível adicionar listas em curriculums vázios.";
            return false;
        }
        
        // Verificamos se o tipo é numérico.
        if(!is_numeric($tipo) || $item == "")
        {
            $this->errorlog[] = "Dados inválidos.";
            return false;
        }
        
        // Retiramos as vírgulas
        $item = str_replace(",","-",$item);
        
        // Verificamos se o ID da música ou referência é válido para verificação.
        if(($tipo == 0 || $tipo == 2) && !is_numeric($item))
        {
            $this->errorlog[] = "O id de uma música/referência precisa ser numérico.";
            return false;
        }
        
        // Verifica se o contato enviado é um array, afinal ele também precisa receber o tipo de contato.
        if(!is_array($item) && $tipo == 1)
        {
            $this->errorlog[] = "O item deve ser enviado como array quando o tipo for 1.";
            return false;
        }
        
        // Adicinamos o tipo do contato, caso essa função receba tipo 1
        if($tipo == 1)
        {
            // Vamos verificar se nenhum dos dois campos está vazio, afinal a verificação anterior não funciona com arrays.
            if($item[0] == "" || $item[1] == "")
            {
                $this->errorlog[] = "Dados inválidos";
                return false;
            }
            $this->listaAddItem(3,$item[1]);
            $item = $item[0];
        }
        
        if($tipo == 0)
        {
            // Verificamos se a música existe e pertence ao usuário logado.
            // A função infoMusica retorna false caso o usuário logado não seja o dono da música ou caso ela não exista.
            
            $m = new musicas();
            $m = $m->infoMusica($item);
            if($m == false)
            {
                $this->errorlog[] = "A música que você tentou adicionar não é válida.";
                return false;
            }
        }
        
        
        // Pega a lista e verifica se ela é verdadeira
        $a = $this->pegarLista($tipo);
        if($a == false)
        {
            $this->errorlog[] = "Lista inválida";
            return false;
        }
        
        
        /**
         *  Pega informações da lista que queremos pegar.
         */
        switch($tipo)
        {
           case 0: $a = $this->lista_musicas; $campo = "musicas"; break;
           case 1: $a = $this->lista_contatos; $campo = "contatos"; break;
           case 2: $a = $this->lista_referencias; $campo = "referencias"; break;
           case 3: $a = $this->lista_tcontatos; $campo = "tcontatos"; break;
        }
        
        // Verifica se o count tem mais de 6 itens.
        if(is_array($a) && count($a) >= 6)
        {
            $this->errorlog[] = "Número de itens ná lista chegou ao máximo.";
            return false;
        }
        
        // Validamos se o item já existe na lista, caso exista não iremos adiciona-lo.
        if(is_array($a))
        {
            if(in_array($item,$a))
            {
                $this->errorlog[] = "Não é possível adicionar dois itens iguais na mesma lista.";
                return false;         
            }
        }
        
        // Todos os dados foram válidados, vamos gerar a string da lista e atualizar o banco de dados. 
        if($a == "")
            // Não existem outros itens na lista, logo $a retorna valor vazio, então adicionamos apenas o item 1
            $string = $item;
        else
        {
            $string = "";
            // Caso contrário $a retorna um array, vamos fazer um foreach para reorganizar a lista em forma de string e realizar o update.
            foreach($a as $i => $v)
            {
                if($i != 0)
                   $string.=",";
                $string.="$v";
            }
            // Adicionamos a cereja do bolo no final da string.
            $string.=",$item";
        }
        
        // Realizamos a atualização no banco de dados e tudo sai melhor do que o esperado.
        $this->conn->prepareupdate($string, "lista_".$campo, "curriculum", $this->id, "id","STR");
        $a = $this->conn->executa();
        if($a == true)
            return true;
        else
        {
            print_r($this->conn->error);
            $this->errorlog[] = "Erro ao atualizar lista";
            return false;
        }
    }
    
    public function listaDelItem($tipo,$pos)
    {
        /**
         *  Deleta um item da lista
         *  @param int $tipo
         *  @param int $pos
         */
        
        if($this->id == "")
        {
            $this->errorlog[] = "Não é possível verificar as listas do curriculum sem um ID.";
            return false;
        }
        
        // Pegamos a lista
        $a = $this->pegarLista($tipo);
        if($a == false)
        {
            $this->errorlog[] = "Lista inválida";
        }
        
        // Escolhemos o tipo de lista.
        switch($tipo)
        {
            case 0: $lista = $this->lista_musicas; $campo = "musicas"; break;
            case 1: $lista = $this->lista_contatos; $campo = "contatos"; break;
            case 2: $lista = $this->lista_referencias; $campo = "referencias"; break;
            case 3: $lista = $this->lista_tcontatos; $campo = "tcontatos"; break;
        }
        
        
        // Verificamos se a posição existe na lista desejada.
        $count = count($lista)-1;
        if($count < $pos)
        {
            $this->errorlog[] = "Não existe a posição na lista que você estava procurando.";
            return false;            
        }
        
        // Agora passamos a lista por um foreach e removemos o índice desejado
        $string = "";
        foreach($lista as $i => $v)
        {
           if($i != $pos)
               $string.= ",$v";
        }
        $string = substr($string,1);
        // Agora fazemos o update no banco de dados com os itens da lista alterados
        $this->conn->prepareupdate($string, "lista_".$campo, "curriculum", $this->id, "id");
        $a = $this->conn->executa();
        if($a == true)
        {
            if($tipo == 1)
                $this->listaDelItem(3,$pos);
            return true;
        }
        else
        {
            $this->conn->errorlog[] = "Não foi possível deletar o item da lista.";
            return false;
        }
            
        
    }
    
    public function showListaMusicas()
    {
        /**
         *  Pega informações das músicas e retorna em um array para serem exibidos na tela
         *  @return
         */
        
        // Instancia a classe musicas.
        $m = new musicas();
        
        if($this->lista_musicas == "" || !is_array($this->lista_musicas))
        {
            $this->errorlog[] = "Você não tem nenhuma música para listar.";
            return;
        }
        
        foreach($this->lista_musicas as $i => $v)
        {
            // Coleta informações da música e joga dentro dos atributos do objeto.
            $a = $m->infoMusica($v,"public");
            if($a == false)
            {
                $this->listaDelItem(0,$i);  // A música é inexistente, vamos retira-la da lista.
            }
            else
            {
            $lista_array[] = array($m->nome,$m->duracao,$m->genero,$m->view,$m->id);
            }
        }
        
        return $lista_array;
    }
    
    public function listaMusicas()
    {
        /**
         *  Lista as músicas do artista retirando as que já estão listadas.
         *  
         */
        
        if($this->aid == "")
        {
            $this->errorlog[] = "Não temos um artista para pegar músicas.";
            return false;
        }
        
        // Instancia a classe músicas;
        $m = new musicas();
        $a = $m->carregaMusicasArtista();
        if($a != true)
        {
            $this->errorlog[] = "Impossível carregar as músicas do artista.";
            return false;
        }
        
        foreach($m->listamusicas as $i => $v)
        {
            // Percorre as músicas do artista e retira as que já estão listadas
            
            if($this->lista_musicas != "")
            {
                if(!in_array($v[0],$this->lista_musicas))
                {
                    $lista_array[] = array($v[0],$v[1],$v[2]);
                }
            }
            else
                $lista_array[] = array($v[0],$v[1],$v[2]);
                
        }
        
        return $lista_array;
        
    }
    
    public function listaReferencias()
    {
        /**
         *  Lista os contatos do artista para ele os adicionar como referência em seu curriculum.
         */
        
        if($this->aid == "")
        {
            $this->errorlog[] = "Você precisa ter o ID do artista antes de iniciar a busca.";
            return false;
        }
        
        echo $lista;
        $this->conn->prepareselect("contato", "id_contratante", array("id_artista","status"), array($this->aid,0), $modo="same", "", "", PDO::FETCH_COLUMN, "all");
        $this->conn->executa();
        $lista = $this->conn->fetch;
        if($lista != "")
        {            
            $lista = array_unique($lista);
            foreach($lista as $i => $v)
            {
                if(is_array($this->lista_referencias))
                {
                    if(!in_array($v,$this->lista_referencias))
                    {
                        $u = new usuario($v);
                        $lista_array[] = array($u->id,$u->nome);
                    }
                }
                else
                {
                    $u = new usuario($v);
                    $lista_array[] = array($u->id,$u->nome);                    
                }
            } 
        }
        return $lista_array;
    }
    
    public function showListaContatos()
    {
        /**
         *  Lista as formas de contato utilizadas pelo artista.
         *  @return array
         */
        
        if($this->id == "")
        {
            $this->errorlog[] = "Você precisa selecionar um curriculum para mostrar os contatos.";
            return false;
        }
        
        // Percorre a lista e organiza os dados
        if($this->lista_contatos != "" && count($this->lista_contatos) == count($this->lista_tcontatos))
        {
            foreach($this->lista_contatos as $i => $v)
            {
                $valor = ucwords($this->lista_tcontatos[$i]);
                $contatos_array[] = array($valor,$v);
            }
        }    
        
        return $contatos_array;
    }
    
    public function showListaReferencias()
    {
        if($this->id == "")
        {
            $this->errorlog[] = "Você precisa de um contato definido para conseguir listar as referências.";
            return false;
        }
        
        // Percorre a lista de ID das referências.
        // Vamos pegar as informações do contratante na classe do usuário.
        if($this->lista_referencias != "")
        {
            foreach($this->lista_referencias as $i => $v)
            {
                $u = new usuario($v);
                $u->pegarGostoMusical($v,2);
                $lista_referencias[] = array($u->nome,$u->imagem,$u->tipomusical,$u->id,$u->login,$u->numeroContatos());                
            }
        }
        return $lista_referencias;
    }
    public function atualizaCurriculum()
    {
        /**
         *  Atualiza o curriculum com as informações atuais do artista;
         *  OBS: Basicamente ele atualiza o nome/sexo/data de nascimento do usuário.
         */
        
        if($this->id == "")
        {
            $this->errorlog[] = "Você não pode editar um curriculum da puta que pariu.";
            return false;
        }
        
        if($_SESSSION['id_usuario'] != $this->aid)
        {
            $this->errorlog[] = "Você não pode atualizar o curriculum sem estar logado no perfil do usuário.";
            return false;
        }
        
        //
        // Pega as informações do perfil e joga para dentro do perfil.
        //
        $u = new usuario();
        $infos = array($u->nome." ".$u->sobrenome,$u->nascimento,$u->sexo);
        $campos = array("nome","nascimento","sexo");
        $this->conn->prepareupdate($infos, $campos, "curriculum", $this->id, "id",array("STR","STR","STR"));
        $a = $this->conn->executa();
        if($a == true)
            return true;
        else
        {
            $this->errorlog[] = "Não foi possível atualizar o perfil";
            return false;
        }
    }
    
    
    
}