<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Atualizações                       //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class Atualizacoes
{
    
    private $conn;
    private $id;
    private $feed_list;
    
    public function __construct()
    {
        /**
         *  Constrói a classe e habilita a conexão com o banco de dados.
         */
        
        $this->conn = new conn();
    }
    
    public function getId($id)
    {
        /**
         *  Pega o ID do usuário
         *  @param  int $id
         *  
         */
        
        if($id == "" || !is_numeric($id))
        {
            return false;
        }
        $this->id = $id;
        return true;
    }
    
    
    public function loadFeed()
    {
        /**
         *  Carrega os usuários que serão puchados para o feed.
         */
        
        if($this->id == "")
            throw new Exception("Usuário não informado.");
        
        $user = new usuario($this->id);
        
        if($user == false)
            throw new Exception("Usuário inexistente.");
        
        // Pega 25 contatos do usuário que foram mais ativos nessa semana.        
        $data_dias = $this->conn->freeQuery("SELECT DATE_ADD(NOW(), INTERVAL -3 DAY)");
        $data_atual = date("Y/m/d H:i:s");
        
        // Verifica qual contato será pesquisa
        if($user->tipo == 1)
        {
            $pesquisa = array("id_contratante","id_artista");
        }
        else
        {
            $pesquisa = array("id_artista","id_contratante");
        }
        
        
        // Pega os valores
        $userbase = $this->conn->freeQuery("SELECT id_usuario_feed FROM feed INNER JOIN contato WHERE id_usuario_feed = $pesquisa[0]_contato AND $pesquisa[1]_contato = $user->id AND status_contato = 0 AND date_feed BETWEEN ('$data_dias[0]') AND ('$data_atual') GROUP BY id_usuario_feed ORDER BY count( id_feed ) DESC LIMIT 0,25",true);
        $userbase_subscribe = $this->conn->freeQuery("SELECT id_usuario_feed FROM feed INNER JOIN acompanhar WHERE id_usuario_feed = id_acompanhado_acompanhar AND id_usuario_acompanhar = $user->id AND date_feed BETWEEN ('$data_dias[0]') AND ('$data_atual') GROUP BY id_usuario_feed ORDER BY count( id_feed) DESC LIMIT 0,100",true);
        foreach($userbase as $i => $v)
        {
            $this->feed_list[] = $v[0];
        }
        foreach($userbase_subscribe as $i => $v)
        {
            $this->feed_list[] = $v[0];
        }
        if(is_array($this->feed_list))
            $this->feed_list = array_unique($this->feed_list);
    }
    
    
    public function loadAtualizacoes($first_id = 0,$is_feed = false, $last_id = 0)
    {
        /**
         *  Carrega o feed de um usuário.
         *  
         *   @param int $page
         *   @param bool $is_feed
         *   @param int $last_id
         * 
         *   Segue a lista atualizada dos feeds disponíveis (13/11/2011):
         *      [0] => musica
         *      [1] => evento
         *      [2] => playlist
         *      [31] => recado (criador)
         *      [32] => recado (receptor)
         *      [4] => participação no evento
         *      [5] => adição curriculum
         *      [6] => update curriculum
         *      [7] => avaliação música
         *      [8] => aceitação contato
         * 
         *  OBS: Outros itens podem ser adicionados via trigger no database.
         *  OBS2: Os feeds serão carregados de 20 em 20 para evitar sobrecarga de processamento.
         * 
         */
        
        
        if($this->id == "")
            throw new Exception("Usuário não informado.");
        
        // Carrega as últimas atualizações do usuário
        if($is_feed == false && is_numeric($this->id))
        {
            $info_usuario = "id_usuario_feed = $this->id";
            $usuario_object = new usuario($this->id);
            
            if($usuario_object == false)
                throw new Exception("Usuário inválido.");
        }        
        elseif($is_feed == true && is_array($this->feed_list))
        {
            // Adiciona todos os usuários na comparação
            $info_usuario = "( ";
            foreach($this->feed_list as $i => $v)
            {
                if($i != 0)
                    $info_usuario.= " OR ";
                if(is_numeric($v))
                    $info_usuario.= "id_usuario_feed = $v";
            }            
            $info_usuario.= " )";
        }
        else
        {
            throw new Exception("Usuário não designado.");
        }
        
        // Verifica a página
        if(!is_numeric($page))
            $page = 0;
        
        // Verifica o último ID
        if(is_numeric($last_id) && $last_id != 0)
        {
            // O usuário especificou um último ID de feed, então só vamos pegar feeds com ID maior que o dele.
            $big_feed = " AND id_feed > $last_id";            
        }
        elseif($first_id != 0)
        {
            $big_feed= " AND id_feed < $first_id";
        }
        
             
        // Seleciona os últimos 20 feeds
        $query_string = "SELECT tipo_feed,id_item_feed,id_usuario_feed,date_feed,subtipo_feed,id_feed FROM feed WHERE $info_usuario";
        $query_string.= $big_feed;
        $query_string.= " ORDER BY date_feed DESC ";
        if($last_id == 0)
            $query_string.= " LIMIT 0,20";

        $lista_feed = $this->conn->freeQuery($query_string,true);
        
        if(!is_array($lista_feed))
            throw new Exception("Nenhuma atualização encontrada.");
        
        // Instancia a classe para formatar data
        $this->data_format = new dataformat();
        
        // Instancia a classe para formatar bbcode
        $this->comentarios = new comentarios();
        
        
        
        // Percorre todo o array para aumentar o número de informações que temos.
        foreach($lista_feed as $i => $v)
        {
            // Primeiro pega informações sobre o usuário que criou o
            if(!$is_feed)
                $atualizacoes[$i] = array("tipo_feed" => $v[0],
                                          "nome_usuario" => $usuario_object->nome,
                                          "login_usuario" => $usuario_object->login,
                                          "imagem_usuario" => $usuario_object->imagem,
                                          "id_usuario" => $usuario_object->id);
            elseif(is_numeric($v[2]))
            {
                // Pega informações do usuário informado e verifica se o id é numérico.
                $u_info = $this->conn->freeQuery("SELECT nome_usuario, login_usuario, imagem_usuario FROM usuario WHERE id_usuario = $v[2]");
                if($u_info != "")
                    $atualizacoes[$i] = array("tipo_feed" => $v[0], 
                                              "nome_usuario" => $u_info[0], 
                                              "login_usuario" => $u_info[1], 
                                              "imagem_usuario" => $u_info[2],
                                              "id_usuario" => $v[2]);
            }
            else
            {
                throw new Exception("Ocorreu um erro ao capturar o feed de um usuário.");
            }
            
            // Adiciona o ID do feed
            $atualizacoes[$i]['id_feed'] = $v[5];
            
            // Verifica o tipo de atualização e realiza as devidas querys
            // $lista_id
            //  [0] => tipo_feed
            //  [1] => id_item_feed
            //  [2] => id_usuario_feed
            //  [3] => date_feed
            //  [4] => subtipo_feed
            if(!is_numeric($v[1]))
                throw new Exception("Ocorreu um erro ao capturar uma informação de feed.");
            
            try
            {
            switch($v[0])
            {
                case 0:
                case 7:
                    $item = $this->conn->freeQuery("SELECT nome_musica FROM musica WHERE id_musica = $v[1]");
                    if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    $atualizacoes[$i]["nome_musica"] = $item[0];
                    $atualizacoes[$i]["id_musica"] = $v[1];
                    break;
                case 1:
                    $item = $this->conn->freeQuery("SELECT nome_evento FROM evento WHERE id_evento = $v[1]");
                    if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    $atualizacoes[$i]["nome_evento"] = $item[0];
                    $atualizacoes[$i]["id_evento"] = $v[1];
                    break;
                case 2:
                    $item = $this->conn->freeQuery("SELECT nome_playlist FROM playlist WHERE id_playlist = $v[1]");
                    if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    $atualizacoes[$i]["nome_playlist"] = $item[0];
                    $atualizacoes[$i]["id_playlist"] = $v[1];                    
                    break;
                case 31:
                    // Verifica o tipo de recado e pega as devidas informações.
                    switch($v[4])
                    {
                        case 0:
                            $item = $this->conn->freeQuery("SELECT nome_usuario,login_usuario,texto_recado FROM recado INNER JOIN usuario WHERE id_receptor_recado = id_usuario AND id_recado = $v[1]");                            
                            break;
                        case 1:
                            $item = $this->conn->freeQuery("SELECT nome_musica,id_musica,texto_recado FROM recado INNER JOIN musica WHERE id_receptor_recado = id_musica AND id_recado = $v[1]");
                            break;
                        case 2:
                            $item = $this->conn->freeQuery("SELECT nome_evento,id_evento,texto_recado FROM recado INNER JOIN evento WHERE id_receptor_recado = id_evento AND id_recado = $v[1]");
                            break;
                        case 4:
                            $item = $this->conn->freeQuery("SELECT titulo_noticia,titulo_url_noticia,texto_recado FROM recado INNER JOIN noticia WHERE id_receptor_recado = id_noticia AND id_recado = $v[1]");
                            break;      
                    }
                    if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    
                    $atualizacoes[$i]["nome_receptor"] = $item[0];
                    $atualizacoes[$i]["identificacao_receptor"] = $item[1];
                    $atualizacoes[$i]["texto_recado"] = $this->comentarios->bbCode($item[2]);
                    $atualizacoes[$i]["tipo_recado"] = $v[4]; 
                    $atualizacoes[$i]["id_recado"] = $v[1];
                    break;
                case 4:
                    $item = $this->conn->freeQuery("SELECT nome_evento,tipo_membros_evento FROM evento INNER JOIN membros_evento WHERE id_evento = id_evento_membros_evento AND id_evento = $v[1]");
                    if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    $atualizacoes[$i]["nome_evento"] = $item[0];
                    $atualizacoes[$i]["id_evento"] = $v[1];
                    $atualizacoes[$i]["tipo_participacao"] = $item[1];
                    break;
                case 8:
                    switch($v[4])
                    {
                        case 1:
                            $query_resumo = "id_remetente_contato";
                            break;
                        case 2:
                            $query_resumo = "id_receptor_contato";
                            break;                    
                    } 
                   $item = $this->conn->freeQuery("SELECT nome_usuario,login_usuario FROM contato INNER JOIN usuario WHERE id_usuario = $query_resumo AND id_contato = $v[1]");
                   if(!is_array($item))
                        throw new Exception("Item inexistente.");
                    $atualizacoes[$i]["nome_usuario2"] = $item[0];
                    $atualizacoes[$i]["login_usuario2"] = $item[1];
                    break;
                case 9:
                    $item = $this->conn->freeQuery("SELECT nome_usuario,login_usuario FROM usuario WHERE id_usuario = $v[1]");
                    $atualizacoes[$i]["nome_usuario2"] = $item[0];
                    $atualizacoes[$i]["login_usuario"] = $item[1];
                    break;
                   
            }
            
            // Formata a data
            $atualizacoes[$i]["data_original"] = $v[3];
            $this->data_format->pegarData($v[3]);
            $atualizacoes[$i]["data_formatada"] = $this->data_format->defineHorario();
            
            }
            catch(Exception $a)
            {
                $atualizacoes[$i] = array("error" => $a->getMessage());
            }            
        }
        
        
        if(!is_array($atualizacoes))
            throw new Exception("Atualizações indisponíveis.");
        
        return $atualizacoes;
    }

    /**
     *  Funções referentes ao acompanhamento de usuários.
     *  
     *  OBS: O acompanhamento permite que usuários com o mesmo tipo de conta consigam assinar feeds.
     */
    
    
    public function isAcompanhado($user_id)
    {
        /**
         *  Verifica se o usuário informado já é acompanhado pelo usuário padrão.
         *  @param int $user_id
         *  @return bool
         */
        
        if($this->id == "")
        {
            throw new Exception("Seu ID é inválido.");
        }
        
        if($user_id == "" || !is_numeric($user_id))
        {
            throw new Exception("ID do usuário é inválido.");
        }
        
        $this->conn->prepareselect("acompanhar","id",array("id_usuario","id_acompanhado"),array($this->id,$user_id));
        $this->conn->executa();
        
        if($this->conn->fetch == "")
            return false;
        else
            return true;
        
    }
    
    public function acompanharUser($user_id)
    {
        /**
         *  Adiciona um usuário para ser acompanhado.
         *  @param int $user_id
         */
        
        if($this->id == "")
        {
            throw new Exception("Seu ID é inválido.");
        }
        
        if($user_id == "" || !is_numeric($user_id))
        {
            throw new Exception("ID do usuário é inválido.");
        }
        
        $user = new usuario($user_id);
        if($user == false)
        {
            throw new Exception("O usuário informado não existe.");
        }
        
        try
        {
            if($this->isAcompanhado($user_id) == true)
            {
                throw new Exception("O usuário já está sendo acompanhado.");
            }
        }
        catch(Exception $a)
        {
            throw new Exception($a->getMessage());
        }
        
        
        $this->conn->prepareinsert("acompanhar", array($this->id,$user_id), array("id_usuario","id_acompanhado"), array("INT","INT"));
        $a = $this->conn->executa();
        
        if($a == false)
            throw new Exception ("Falha ao acompanhar usuário");
    }
    
    public function deleteAcompanhar($user_id)
    {
        /**
         *  Cancela o acompanhamento de um usuário 
         *  @param int $user_id
         */
        
        if($this->id == "")
        {
            throw new Exception("Seu ID é inválido.");
        }
        
        if($user_id == "" || !is_numeric($user_id))
        {
            throw new Exception("ID do usuário é inválido.");
        }
        
        if($this->isAcompanhado($user_id) == false)
        {
            throw new Exception("Usuário não é acompanhado.");
        }
        
        $this->conn->preparedelete("acompanhar",array("id_usuario","id_acompanhado"),array($this->id,$user_id));
        $a = $this->conn->executa();
        if($a == false)
            throw new Exception("Não foi possível excluir o acompanhado.");
        
        
    }
    
}

?>