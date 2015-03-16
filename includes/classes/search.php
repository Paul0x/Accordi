<?php

if ($IN_ACCORDI != true) {
    exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Busca                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class buscar {

    public $tag;
    public $npesquisa;
    public $nresultados;
    public $tempoquery;

    /*
     *  Eventos
     */
    private $enome;
    private $egenero;
    private $ecidade;
    private $page;
    
    /*
     *  Música
     */
    private $mnome;
    private $mgenero;

    /*
     *  Conexões
     */
    private $conn;

    /*
     *  Pesquisa Artista/Contratantes
     */
    private $listaid;

    public function __construct() {
        $this->tag = "" . $_GET['s'] . "";
        $this->replace = $_GET['s'];
        $this->conn = new conn();
    }

    public function filtroEvento($nome, $cidade, $genero, $page) {
        $this->enome = $nome;
        $this->ecidade = $cidade;
        $this->egenero = $genero;
        $this->page = $page;
        $a = $this->pesquisaEvento();
        return $a;
    }
    
    public function filtroMusica($nome, $genero, $page) {
        $this->mnome = trim($nome);
        $this->mgenero = trim($genero);
        if(is_numeric($page))
            $this->page = $page;
        else
            $this->page = 0;
        try
        {
            $a = $this->pesquisaMusica();
            return $a;
        }
        catch(Exception $ex)
        {
           throw new Exception($ex->getMessage()); 
        }
    }
    
    private function pesquisaMusica()
    {
        /**
         * Realiza a pesquisa de uma música por nome e gênero.
         * @return array
         * 
         */
        
        if(!is_numeric($this->page))
        {
            $this->page = 0;
        }
                
        $desc = "nome";
        
        if($this->mgenero != "")
        {
            $valores[1] = $this->mgenero;
            $campos[1] = "genero";
            $desc = "genero";
            
        }
        
        
        if($this->mnome != "")
        {
            $valores[0] = $this->mnome;
            $campos[0] = "nome";
            $desc = "genero";
        }
        
        $campos[2] = "permissao";
        $valores[2] = "0";
                
        $limit = $this->page;
        
        $this->conn->prepareselect("musica", "id", $campos, $valores, array("like","like","="), "", "", PDO::FETCH_COLUMN, "all", array($desc,"ASC"), array($limit, "25"));
        $this->conn->executa();
        
        $lista_id = $this->conn->fetch;
        if(!is_array($lista_id))
            throw new Exception("Nenhum registro encontrado");
        
        $musica = new musicas();
        
        foreach($lista_id as $i => $v)
        {
            $a = $musica->infoMusica($v,"public");
            if($a != false)
            {
                $lista_musicas[] = array("id" => $musica->id,"nome" => $musica->nome,"genero" => $musica->genero,"duracao" => $musica->duracao, "artista" => $musica->artista);
            }
        }
        
        return $lista_musicas;
       
        
        
        
    }

    private function pesquisaEvento() {

        $v = Array();
        $idlist = Array();
        if ($this->ecidade != "") {
            $v[1] = $this->ecidade;
            $c[1] = "cidade";
            $con[0] = "AND";
        }
        if ($this->egenero != "") {
            $v[2] = $this->egenero;
            $c[2] = "genero";
            $con[1] = "AND";
        }
        if ($this->enome != "") {
            $v[0] = $this->enome;
            $c[0] = "nome";
            $con = "AND";
        }
        if ($this->page == "")
            $limit = 0;
        else
            $limit = $this->page;

        $this->conn->prepareselect("evento", "id", $c, $v, $modo = "like", "", "", PDO::FETCH_COLUMN, "all", array("status","ASC","data","ASC"), array($limit, "15"),"",$con);
        $this->conn->executa();
        $idlist = $this->conn->fetch;
        if ($idlist != "") {
            
            $evento = new eventos();
            foreach ($idlist as $i => $v) {
                $evento->id = $v;
                $evento->pegaInfo();
                $listasearch[] = array($evento->id, $evento->nome, $evento->hora, $evento->data, $evento->genero, $evento->imagem, $evento->cidade);
            }
        }
        return $listasearch;
    }

    function pesquisaPessoa($page, $tipo) {

        /*
         *    Páginação
         */

        if ($page == "" || !is_numeric($page) || $page<0)
            $page = 0;

        /*
         *   Organizando pesquisa por tipos 
         */

        $tipo = strtolower($tipo);
        if ($tipo != "" && $tipo != "artista" && $tipo != "contratante")
            $tipo = "";
        if ($tipo == "artista")
            $tipo = 1;
        if ($tipo == "contratante")
            $tipo = 2;

        /*
         *  Requisição de informação ao BD 
         */

        $ta = array_sum(explode(' ', microtime())); // Tempo antes da requisição
        $camposu = array("nome_usuario", "cidade_usuario", "estado_usuario");
        $valoresu = array($this->tag, $this->tag, $this->tag);
        $conectoresu = array("OR", "OR");
        if ($tipo == 1) {
            $conectoresu[2] = "OR";
            $conectoresu[3] = "AND";
            $camposu[3] = "sobrenome_artista";
            $campous[4] = "apelido_artista";
            $valoresu[3] = $this->tag;
            $valoresu[4] = $this->tag;
            $camposu[5] = "tipo_usuario";
            $valoresu[5] = 1;
            $join = array("INNER", "artista");
            $this->conn->compararCampos("id_usuario", "id_artista");
        }
        if ($tipo == 2) {
            $camposu[3] = "website_contratante";
            $camposu[4] = "tipo_usuario";
            $valoresu[3] = $this->tag;
            $valoresu[4] = 2;
            $conectoresu[2] = "OR";
            $conectoresu[3] = "AND";
            $join = array("INNER", "contratante");
            $this->conn->compararCampos("id_usuario", "id_contratante");
        }

        $page = $page * 14;
        $this->conn->prepareselect("usuario", "id", $camposu, $valoresu, "like", "", $join, PDO::FETCH_COLUMN, "all", "", array($page, 14), "", $conectoresu, 0);
        $this->conn->executa();
        $this->listaid = $this->conn->fetch;
        if($tipo == 2)
            $this->conn->compararCampos("id_usuario", "id_contratante");
        elseif($tipo == 1)
            $this->conn->compararCampos("id_usuario", "id_artista");
        $this->conn->prepareselect("usuario", "id", $camposu, $valoresu, "like", "count", $join, PDO::FETCH_COLUMN, "all", "", "", "", $conectoresu, 0);
        $this->conn->executa();
        $tpage = $this->conn->fetch[0];
        
        /*
         *  Fim da requisição
         */
        if ($this->listaid != "") {
            foreach ($this->listaid as $i => $v) {
                $u = new usuario($v);

                if ($u->tipo == 1)
                    $counttipo = $u->numeroMusicas();
                if ($u->tipo == 2)
                    $counttipo = $u->numeroEventos();
                $resultados[] = array($u->id, $u->nome, $u->sobrenome, $u->apelido, $u->cidade, $u->estado, $u->imagem, $counttipo, $u->login, $u->tipo);
            }
        }
        /* Algoritmo de Relevância */
        if ($resultados != "") {
            foreach ($resultados as $i => $v) {
                $resultados[$i] = array(1, $v); // Todos os resultados tem 1 ponto.
                if ($this->replace != "") {
                    foreach ($v as $in => $vl) {
                        $a = strpos($vl, $this->replace);
                        if ($a != false)
                            $resultados[$i][0]++;
                    }
                }
            }
            arsort($resultados);
        }
        $td = array_sum(explode(' ', microtime())); // Tempo depois da requisição
        $this->tempoquery = $td - $ta;
        $this->tempoquery = number_format($this->tempoquery, "3");
        $this->nresultados = $tpage;
        $this->npesquisa = $tpage / 14;
        return $resultados;
    }

}