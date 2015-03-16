<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Playlist                           //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class Playlist
{
    
    // Atributos da Playlist
    private $id;
    private $duracao;
    private $lista_musicas;
    private $genero;
    private $nome;
    private $execucoes;
    private $uid;
    private $imagem;
    private $base;
    
    // Classes
    private $musicas;
    private $usuario;
    private $dataformat;
    private $conn;
    
    // Métodos (falhos né)
    
    public function __construct()
    {
        // Vamos apenas instanciar a classe de conexão aqui.
        $this->conn = new conn();        
    }
    
    public function getId($id)
    {
        /**
         *  Pega o ID da playlist.
         *  @param int $id
         *  @return bool
         */
        
        if($id == "" || !is_numeric($id))
            return false;        
        else
        {
            $this->id = $id;
            return true;
        }
        
    }
    
    public function getUser($id)
    {
        /**
         *  Pega o ID do usuário.
         *  @param int $id
         *  @return bool
         */
        
        if($id == "" || !is_numeric($id))
            return false;        
        else
        {
            $this->uid = $id;
            return true;
        }
        
    }
    
    public function listaPlaylists($info = false)
    {
        /**
         *  Lista as playlists do usuário. 
         *  @param bool $info
         */
        
        if($this->uid == "")
        {
            throw new Exception("ID do usuário inválido.");
        }
        
        // Lista o ID de todas as playlists do usuário.
        $this->conn->prepareselect("playlist","id","id_usuario",$this->uid,"same","","",PDO::FETCH_COLUMN, "all",array("id","DESC"));
        $this->conn->executa();
        
        if($this->conn->fetch == "")
        {
            throw new Exception("Nenhuma playlist encontrada.");
        }
        
        // Verifica se a requisição pede todas as informações da playlist
        if($info == true)
        {
            foreach($this->conn->fetch as $i => $v)
            {
                // Pega todas as informações da playlist.
                $this->id = $v;
                try
                {
                    $info_playlist[] = $this->getInfo(); 
                }
                catch(Exception $a)
                {
                   
                }
            }
        }
        else
        {
            $info_playlist = $this->conn->fetch;
        }
        
        return $info_playlist;
    }
    
    public function getInfo()
    {
        /**
         *  Pega informações sobre a playlist.
         */
        
        if($this->id == "")
        {
            throw new Exception("ID da playlist inválido.");
        }
        
        $info = array("id","id_usuario","nome","genero","execucoes","imagem","base","privacidade");
        $this->conn->prepareselect("playlist",$info,"id",$this->id);
        $this->conn->executa();
        
        if($this->conn->fetch == "")
        {
            throw new Exception("Playlist não encontrada.");
        }
        
        // Preenche os atributos da classe.
        $pl = $this->conn->fetch;
        $this->id = $pl[0];
        $this->uid = $pl[1];
        $this->nome = $pl[2];
        $this->genero = $pl[3];
        $this->execucoes = $pl[4];
        $this->imagem = $pl[5];
        $this->base = $pl[6];
        $this->privacidade = $pl[7];
        
        return $this->conn->fetch;
    }
    
    public function infoMusicas()
    {
        /**
         *  Pega informações de todas as músicas da playlist.
         */
        
        if($this->id == "")
        {
            throw new Exception("Nenhuma playlist encontrada.");
        }
        
        $this->conn->prepareselect("musicas_playlist",array("id_musica","posicao"),"id_playlist",$this->id,"same","","",NULL, "all",array("posicao","ASC"));
        $this->conn->executa();
        $this->lista_musicas = $this->conn->fetch;
        
        if($this->lista_musicas == "")
        {
            throw new Exception("A playlist não existe ou não possui nenhuma música.");
        }
        
        $this->musicas = new musicas();
        $this->duracao = 0;
        foreach($this->lista_musicas as $i => $v)
        {
            $this->musicas->infoMusica($v[0],"public");
            $musicas_info[] = array($this->musicas->id,$this->musicas->nome,$this->musicas->artista,$this->musicas->artistaid,$this->musicas->duracao,$this->musicas->genero,$v[1]);
            $duracao = explode(":",$this->musicas->duracao);
            
            if(count($duracao) == 3)
            {
                $time = strtotime("+$duracao[0] hours $duracao[1] minutes $duracao[2] seconds")-time();    
                $this->duracao+= $time;
            }
            elseif(count($duracao) == 2)
            {
                $time = strtotime("+$duracao[0] minutes $duracao[1] seconds")-time();
                $this->duracao+= $time; 
            }
        }
        if($this->duracao < 3600)
            $this->duracao = date("i:s",$this->duracao);
        else            
            $this->duracao = date("H:i:s",$this->duracao);
            
        return $musicas_info;
        
    }
    
    public function novaPlaylist($nome,$base="")
    {
        /**
         *  Adiciona uma nova playlist
         *  @param string nome
         *  @param int base
         */
        
            
        if($this->uid == "")
        {
            throw new Exception("Não existe usuário definido.");
        }
        
        if($base != "" && !is_numeric($base))
        {
            throw new Exception("Base inválida.");
        }
        
        $nome = trim($nome);
        
        if($nome == "" || strlen($nome) > 45)
        {
            throw new Exception("Nome inválido.");
        }
        
        $validate = new validate();
        $max = $validate->pegarMax('id', 'playlist');
        
        $this->conn->prepareinsert("playlist",array($this->uid,$nome,"Indefinido",'0',$base),array("id_usuario","nome","genero","imagem","base"),array("INT","STR","STR","STR","INT"));
        $a = $this->conn->executa();
        
        if($a != true)
        {
            throw new Exception("Falha ao criar playlist.");
        }
        
        return $max;
            
    }
    
    public function deletaPlaylist()
    {
        /**
         *  Deleta uma playlist.
         */
        
        if($this->id == "" || $this->uid == "")
        {
            throw new Exception("Informações necessárias inválidas");
        }
        
        $usuario = $this->uid;
                
        $this->getInfo();
        if($this->uid != $usuario)
        {
            throw new Exception("O usuário não tem permissão para deletar a playlist.");
        }
        
        $this->conn->preparedelete("playlist","id",$this->id);
        $a = $this->conn->executa();
        
        $this->conn->preparedelete("musicas_playlist","id_playlist",$this->id);
        $b = $this->conn->executa();
        
        
       unlink("./imagens/playlists/".$this->id.".$this->imagem");
        
        if($a != true || $b != true)
        {
            throw new Exception("Falha ao deletar playlist.");
        }
    }
    
    public function addMusica($id)
    {
        /**
         *  Adiciona uma música na playlist
         */
        
        if($this->id == "")
        {
            throw new Exception("Playlist não encontrada.");
        }
        
        if($id == "" || !is_numeric($id))
        {
            throw new Exception("Id da música inválido.");
        }
        
        $this->musica = new musicas();
        $a = $this->musica->infoMusica($id,"public");
        
        if($a == false)
        {
            throw new Exception("Música inexistente.");
        }
        
        if($this->musica->isAllowed() == false)
        {
            throw new Exception("Falha na permissão para adicionar a música ".$this->musica->nome.".");
        }
        
        if($this->verificaMusica($id))
        {
            throw new Exception("Música duplicada na playlist.");
        }
         
        try
        {
            $this->infoMusicas();
            $n_posicao = count($this->lista_musicas);
        }
        catch(Exception $a)
        {
            $n_posicao = 0;
        }
        
        $this->conn->prepareinsert("musicas_playlist", array($this->id,$id,$n_posicao), array("id_playlist","id_musica","posicao"), array("INT","INT","INT"));
        $q = $this->conn->executa();
        if($q != true)
        {
            throw new Exception("Não foi possível adicionar a música.");
        }       
        
        
        // Redefine os gêneros da playlist.
        $this->decideGenero();
        
        // Verifica a organização das posições na playlist
        $this->reorganizaPlaylist();
        
    }
    
    public function addCapa($capa)
    {
        /**
         *  Adiciona uma capa
         *  @param $capa
         * 
         */
        
        if($this->id == "")
            throw new Exception("Id inválido.");

        $img = new imagem();
        $capa = $img->pegarImagem($capa);
        
        if($capa != 'true')
        {
            throw new Exception("Imagem inválida. - $capa");
        }
        
        $img->dimensoesMaximas(75,75);
        $img->cropOn();
        $i = $img->verificarMaximo(3000000);
        
        if($i == false)
        {
            throw new Exception("Imagem muito grande.");
        }
        
        $img->generate("./imagens/playlists/" . $this->id);
        $this->imagem = $img->formatoImg();
        
        if($this->imagem != "")
        {
            $this->conn->prepareupdate($this->imagem, "imagem", "playlist", $this->id, "id", "STR");
            $this->conn->executa();
        }
        
    }
    
    public function verificaMusica($id)
    {
        /**
         *  Verifica se a música existe em determinada playlist.
         *  @param int $id
         *  @return bool 
         */
        
        if($this->id == "")
        {
            return false;
        }
        
        if($id == "" || !is_numeric($id))
        {
            return false;
        }
        
        $this->conn->prepareselect("musicas_playlist","id_musica",array("id_playlist","id_musica"),array($this->id,$id));
        $this->conn->executa();
        if($this->conn->fetch == "")
            return false;
        else
            return true;
    }
    
    public function deletaMusica($id)
    {
        /**
         *  Deleta uma música da playlist
         *  @param int $id
         */
        
        if($this->id == "")
        {
            throw new Exception("Nenhuma playlist selecionada.");
        }
        
        if($this->verificaMusica($id) == false)
        {
            throw new Exception("Música inexistente na playlist.");
        }
        
        $this->getInfo();
        
        // Verifica se o usuário que está deletando tem permissões.
        if($_SESSION['id_usuario'] != $this->uid)
            throw new Exception("Permissões inválidas.");
        
        $this->conn->preparedelete("musicas_playlist",array("id_musica","id_playlist"),array($id,$this->id));
        $a = $this->conn->executa();
        if($a != true)
        {
            throw new Exception("Falha ao deletar música.");
        }
        
        
    }
    
    public function setDuracao()
    {
        /**
         *  Envia a duração total da playlist.
         */
        
        return $this->duracao;
    }
    
    public function selecionaMusica($id,$aleatorio=false)
    {
        /**
         *  Seleciona música
         *  @param int $id [ ID da música ]
         *  @param int $aleatorio [ Se a execução é aleatória ]
         * 
         */
        
        if($this->id == "")
        {
            throw new Exception("Não existe playlist definida.");
        }
        // Classe música
        $musica = new musicas();

        // Variável para guardar a música atual
        $musica_atual;

        // Carrega as músicas da playlist
        $lista_musicas = $this->infoMusicas();

        // Verifica se existe uma música pré-definida
        if($id != "" && is_numeric($id))
        {
            // Verifica se a música existe na playlist
            $a = $this->verificaMusica($id);
            if($a == false)
                throw new Exception("Música não existe na playlist.");

            $musica_atual = $musica->infoMusica($id,"public");
            foreach($lista_musicas as $i => $v)
            {
                if($v[0] == $id)
                {
                    $key_musica = $i;
                    break;
                }
            }
            if($key_musica != (count($lista_musicas)-1))
                $proxima_musica = $lista_musicas[$key_musica+1];
            if($key_musica != 0)
                $musica_anterior = $lista_musicas[$key_musica-1];
        }
        else
        {
            // Uma música não foi definida, vamos continuar com algumas verficações           
            if($aleatorio == false)
            {
                $musica_atual = $musica->infoMusica($lista_musicas[0][0],"public");
                $proxima_musica = $lista_musicas[1];
                $musica_anterior = "";
            }
            else
            {
                $count  = count($lista_musicas)-1;
                $random = rand(0,$count);
                $musica_atual = $musica->infoMusica($lista_musicas[$random][0]);
                if($random != $count)
                    $proxima_musica = $lista_musicas[$random+1];
                if($random != 0)
                    $musica_anterior = $lista_musicas[$random-1];
            }   
            
        }

        // Verifica se a música existe
        if($musica_atual == false)
            throw new Exception("Música inexistente.");
        
        // Retorna as músicas
        $musica_atual = array($musica->id,$musica->nome,$musica->genero,$musica->artista,$musica->artistaid,$musica->duracao,$musica->letra);
        $musicas = array($musica_atual,$proxima_musica,$musica_anterior,$lista_musicas);
        return $musicas;

    }
    
    public function decideGenero()
    {
        /**
         *  Adiciona o gênero mais populoso na playlist como gênero principal
         */
        
        if($this->id == "")
        {
            throw new Exception("Id indefindo.");
        }
        
        $lista_musicas = $this->infoMusicas();
        $generos = array();
        foreach($lista_musicas as $i => $v)
        {
            $g = $v[5];
            $generos[$g]+= 1;  
        }
        
        arsort($generos);   
        foreach($generos as $i => $v)
        {
            $maior_genero = $i;
            break;
        }
        
        $this->conn->prepareupdate($maior_genero, "genero", "playlist", $this->id, "id", "STR");
        $this->conn->executa();
        
    }
    
    public function loadFromContatos()
    {
        /**
         *  Carrega 10 playlists aleatórias de contatos.
         * 
         */
        
        if($this->uid == "")
        {
            throw new Exception("Usuário não encontrado.");            
        }
        
        // Cria classe usuário
        $usuario = new usuario($this->uid);
        
        if($usuario == false)
        {
            throw new Exception("Usuário inválido.");
        }
        
        // Verifica os contatos do usuário
        $usr = $this->uid;
        try
        {
            $contatos = $usuario->carregaContatos();
         
            if($contatos == "")
            {
                throw new Exception("Nenhum contato encontrado.");
            }
            
            // Randomiza os contatos
            shuffle($contatos);
            
            
            foreach($contatos as $i => $v)
            {
                $this->getUser($v);
                $playlists[] = $this->listaPlaylists(true);
                if($i == 10)
                    break;              
            }
            
            shuffle($playlists);
            return $playlists;
            
        }
        catch(Exception $a)
        {
            throw new Exception("Impossível carregar contatos.");
            
        }
        
    }
    
    public function replicaPlaylist()
    {
        /**
         *  Replica uma playlists
         * 
         */
        
        if($this->id == "" || $this->uid == "")
            throw new Exception("Usuário ou playlist não definidos");
        
        try
        {
            $user = $this->uid;
            $infos = $this->getInfo();

            // Verifica se o usuário informado não é igual ao criador da playlist
            if($this->uid == $user)
            {
                throw new Exception("Você não pode replicar suas próprias playlists.");
            }
            
            if($this->privacidade == '1')
            {
                throw new Exception("Essa é uma playlist privada.");
            }

            // Pega a lista de músicas da playlist
            $musicas_old = $this->infoMusicas();

            // Adiciona uma nova playlist com o mesmo nome e adiciona a playlist base.
            $this->uid = $user;
            $old_id = $this->id;
            $imagem_old = $this->imagem;
            $novo_id = $this->novaPlaylist($this->nome,$old_id);
            $this->id = $novo_id;

            // Adiciona todas as músicas da playlist anterior na nova playlist
            if(is_array($musicas_old))
            {
                foreach($musicas_old as $i => $v)
                {
                    $this->addMusica($v[0]);
                }
            }
            
            // Copia a imagem da antiga playlist.
            if($this->imagem != '0')
            {
               copy("./imagens/playlists/".$old_id.".$imagem_old","./imagens/playlists/".$novo_id.".$imagem_old");
               $this->conn->prepareupdate($imagem_old, "imagem", "playlist", $novo_id, "id", "INT");
               $this->conn->executa();
            }
            return $novo_id;
            
            
            
        }
        catch(Exception $a)
        {
            throw new Exception($a->getMessage());
        }
        
    }
    
    public function baseInfo()
    {
        /**
         *  Pega as informações básicas de uma playlist base
         * 
         */
        
        if($this->base == "")
            throw new Exception("Playlist base não encontrada.");
        
        $this->conn->prepareselect("playlist","nome","id",$this->base);
        $this->conn->executa();
        if($this->conn->fetch == "")
        {
            // A playlist base provávelmente foi deletada
            throw new Exception("Playlist base inválida.");
        }
        else
        {
            return array($this->base,$this->conn->fetch[0]);
        }
        
    }
    
    public function updatePrivacidade($valor)
    {
        /**
         *  Atualiza a privacidade de uma playlist.
         *   0 - Playlist pública, replys são disponíveis para todos
         *   1 - Playlist privada, apenas o usuário pode visualizar a playlist
         * 
         */
        
        if($this->id == "")
        {
            throw new Exception("Playlist inválida.");
        }
        
        if($valor != '0' && $valor != '1')
        {
            throw new Exception("Valor inválido para a privacidade.");
        }
        
        $this->conn->prepareupdate($valor, "privacidade", "playlist", $this->id, "id", "INT");
        $a = $this->conn->executa();
        
        if($a == false)
            throw new Exception("Falha ao alterar privacidade.");
    }
    
    private function reorganizaPlaylist()
    {
        /**
         *  Reorganiza a playlist, procurando por músicas com uma posição duplicada
         * 
         */
        
        if($this->id == "")
             throw new Exception("Nenhuma playlist selecionada.");
        
        
        $this->conn->prepareselect("musicas_playlist",array("id","posicao"),"id_playlist",$this->id, "same", "", "", NULL, "all");
        $this->conn->executa();
        
        if($this->conn->fetch == "")
             return;
        
        foreach($this->conn->fetch as $i => $v)
        {
           $lista[$v[0]] = $v[1];      
        }
        
        $conta = array_count_values($lista);
        foreach($this->conn->fetch as $i => $v)
        {
           $indice = $v[1];
           while($conta[$indice] > 1)
           {
               $conta[$indice] -= 1;
               $indice++;
               $conta[$indice] +=1;
           }
           
           if($indice != $v[1])
           {
              $this->conn->prepareupdate($indice, "posicao", "musicas_playlist", $v[0], "id", "INT");
              $this->conn->executa();
           }
        }
        
    }
    
    public function sortPlaylist($tipo)
    {
        /**
         *  Organiza a playlist de uma maneira personalizada.
         *  @param int $tipo
         */
        
        if($this->id == "")
            throw new Exception("Playlist indefinida.");
        
        if($tipo != 1 && $tipo != 2)
            throw new Exception("Tipo de organização indefinido.");
        
        try
        {
            $infos = $this->infoMusicas();
        }
        catch(Exception $a)
        {
            throw new Exception("Nenhuma música encontrada.");            
        }
        
        if($tipo == 1)
        {
            // Ordenação por ordem alfabética
            foreach($infos as $i => $v)
            {
                $nova_lista[] = array(ucwords($v[1]),$v[0]);

            }
            
            // Organiza o Array por ordem alfabética
            sort($nova_lista);
        }
        
        elseif($tipo == 2)
        {
            // Ordenação por gênero
            foreach($infos as $i => $v)
            {
                $nova_lista[] = array($v[5],$v[0]);
            }
            
            // Junta os gêneros do array
            sort($nova_lista);
        }
        
        foreach($nova_lista as $i => $v)
        {
            // Agora pegamos os índices e alteramos as posições das músicas
            $this->conn->prepareupdate($i, "posicao", "musicas_playlist", array($this->id,$v[1]), array("id_playlist","id_musica"), "INT");
            $this->conn->executa();
            $musicas[] = $v[1];
        }
        
        // Retorna a lista com a nova organização das músicas.
        return $musicas;
        
    }
    
    public function ddPlaylist($lista)
    {
        /**
         *  Reorganiza a playlist de maneira personalizada.
         * 
         */
        
        if($this->id == "")
        {
            throw new Exception("ID inválido.");
        }
        
        if(!is_array($lista))
            throw new Exception ("Nenhuma música encontrada.");
        
        foreach($lista as $i => $v)
        {
            $id_musica = explode("-", $v);
            $this->conn->prepareupdate($i, "posicao", "musicas_playlist", array($this->id,$id_musica[3]), array("id_playlist","id_musica"), "INT");
            $this->conn->executa();
        }
        
    }
    
    
    
    
 
    
}

?>