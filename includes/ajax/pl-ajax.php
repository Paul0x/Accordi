<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: pt-ajax.php - Pegar requisições ajax para o portal.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Enviar o código HTML para o servidor pós requisição AJAX.
 *    Principais funções:
 *      - Processar as chamadas realizadas via AJAX.
 *      - Enviar para o javascript as requisições recebidas.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

require("main-ajax.php");

class AJAXplaylist extends AJAX
{
    
    /**
     *  ID da playlist
     */
    private $id;
    
    /**
     *  ID do usuário
     */
    private $uid;
    
    /**
     *  Classe da playlist
     */
    private $playlist; 
    
    public function __construct()
    {
        $this->playlist = new Playlist();
    }
    
    public function getId($id)
    {
        /**
         *  Pega o ID da playlist para trabalhar as informações
         *  @return BOOL
         */
        if(is_null($id))
            return false;
        else
            $this->id = $id;
            return true;
    }
    
    public function getUser($id)
    {
        /**
         *  Pega o ID do usuário da playlist.
         *  @return BOOL
         */
        if(is_null($id))
            return false;
        else
            $this->uid = $id;
            return true;
    }
    
    public function deletePlaylist()
    {
        try
        {
            $this->playlist->getId($this->id);
            $this->playlist->getUser($_SESSION['id_usuario']);
            $this->playlist->deletaPlaylist();
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            echo "</div>";
        }
        catch(Exception $a)
        {            
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "</div>";            
        }
    }
    
    public function deleteMusica($id)
    {
        /**
         *  Deleta uma música da playlist 
         *  @param int $id (id da música)
         */
        
        try
        {
            $this->playlist->getId($this->id);
            $this->playlist->deletaMusica($id);
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            echo "</div>";
        }
        catch(Exception $a)
        {            
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "</div>";            
        }
    }
    
    public function addMusica($step,$id,$playlist)
    {
        /**
         *  Adiciona uma música na playlist
         *  @param int $step
         *  @param int $id
         *  @param int $playlist
         * 
         */
        
        switch($step)
        {
            case 0:
                if(is_numeric($id))
                    echo "<input type='hidden' value='$id' id='music-id-add-playlist' />";
                echo "<div class='gra_top1'>Adicionar Música - Playlist</div>";
                try 
                {
                    $this->playlist->getUser($_SESSION['id_usuario']);
                    $pl = $this->playlist->listaPlaylists(true);
                    echo "<div class='info'>";
                    echo "<p>Selecione a playlist na lista abaixo</p>";    
                    echo "</div>";
                    echo "<div class='amp-left'>";
                    echo "<strong>Selecione a playlist</strong>";
                    echo "</div>";
                    echo "<div class='amp-right'>";
                    echo "Suas Playlists";
                    echo "<div class='amp-roll'>";
                    echo "<ul>";
                    foreach($pl as $i => $v)
                    {
                        echo "<li class='amp-list-playlist' id='ms-$v[0]'>$v[2]</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                    echo "</div>";
                }
                catch(Exception $a)
                {
                    echo "<div class='amp-all'>";
                    echo "<strong>Você ainda não criou nenhuma playlist.</strong>";
                    echo "<div class='btn-add-playlist-new' >Criar Playlist</div>";
                    echo "<p></p>";
                    echo "</div>";
                }
                break;
            case 1:
                try
                {
                    $this->playlist->getId($playlist);
                    $infos = $this->playlist->getInfo();
                    $m = new musicas();
                    $musica = $m->infoMusica($id,"public");
                    $existe = $this->playlist->verificaMusica($id);
                    if($existe == true)
                    {
                        echo "<span class='amp-response-false'>A música já existe na playlist <strong>$infos[2]</strong>.</span>";
                        return;
                    }
                    if($musica == false)
                    {
                        echo "<span class='amp-response-false'>A música que você está tentando adicionar é inválida.</span>"; 
                        return;
                    }
                    else
                    {
                        echo "<span class='amp-response-false'>Adicionar <strong>$m->nome</strong> na playlist <strong>$infos[2]</strong></span>"; 
                        echo "<div class='btn-add-ms-confirma' id='pladd-$infos[0]'>Adicionar</div>";
                    }
                }
                catch(Exception $a)
                {
                   echo "<span class='amp-response-false'>Ocorreu um erro, tente novamente.</span>";
                }
                break;
            case 2:
                try
                {
                    $this->playlist->getId($playlist);
                    $infos = $this->playlist->getInfo();
                    $this->playlist->addMusica($id);
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='content'>";
                    echo "<strong>Música Adicionada</strong>";
                    echo "<p><a href='".$_SESSION['root']."/playlists/$infos[0]/play' class='nochange2'>Ouvir playlist</a></p>";
                    echo "</div>";
                    echo "</div>";
                    
                }
                catch(Exception $a)
                {                    
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>0</div>";
                    echo "<div id='content'>";
                    echo $a->getMessage();
                    echo "</div>";
                    echo "</div>";
                    
                }               
                break;
        }
    }
    
    public function addPlaylist($step,$nome="",$id="",$musid="")
    {
        /**
         *  Adiciona uma nova playlist
         *  @param int $step
         *  @param int $id
         *  @param int $musid
         * 
         */
        
        switch($step)
        {
            case 0:
                echo "<div class='pc-ajax-wrap'>";
                
                // Step 1
                echo "<div class='pc-ajax-step1'>";
                echo "<div class='info'>Adicione uma playlist- 1º Passo</div>";
                echo "<div class='pc-left'>";
                echo "<div class='gra_top1'>Informações</div>";
                echo "<div class='pc-label'>Escolha um nome para a playlist</div>";
                echo "<input type='text' id='pc-nome' class='pc-input' />";
                echo "<input type='button' class='btn' value='Próximo' id='pc-input-stp1' />";
                echo "</div>";
                echo "<div class='pc-right'>";
                echo "<div class='gra_top2'>Com uma playlist você pode</div>";
                echo "<ul>";
                echo "<li class='pc-list'>Reunir suas músicas preferidas do <strong>Accordi</strong>.</li>";
                echo "<li class='pc-list'>Publicar seus gostos para outros usuários.<li>";
                echo "<li class='pc-list'>Ter um acervo musical completo auxiliando você nos contatos.<li>";
                echo "</ul>";
                echo "</div>";
                echo "</div>";
                
                // Step 2
                echo "<div class='pc-ajax-step2'>";
                echo "<div class='info'>Adicione uma playlis - 2º Passo</div>";
                echo "<div class='pc-left2'>";
                echo "<div class='gra_top1'>Informações</div>";
                echo "<div class='pc-label'>Adicione uma imagem para a capa.</div>";
                echo "<form method='post' action='".$_SESSION['root']."/playlists' enctype='multipart/form-data' id='musica-add-image' target='music-add-image'>";
                echo "<input type='file' name='pc-imagem' id='pc-imagem' class='pc-input2' />";
                echo "<input type='submit' class='btn' value='Finalizar' id='pc-input-stp2' />";
                echo "<input type='hidden' name='id-play-tobanner' id='id-play-tobanner' value='$id' />";                    
                echo "</form>";
                echo "</div>";
                echo "<div class='pc-right2'>";
                echo "<div class='gra_top2'>Com uma playlist você pode</div>";
                echo "<ul>";
                echo "<li class='pc-list'>Reunir suas músicas preferidas do <strong>Accordi</strong>.</li>";
                echo "<li class='pc-list'>Publicar seus gostos para outros usuários.<li>";
                echo "<li class='pc-list'>Ter um acervo musical completo auxiliando você nos contatos.<li>";
                echo "</ul>";
                echo "</div>";                
                echo "<iframe name='music-add-image' id='musica-add-frame' style='display: none;'></iframe>";
                echo "</div>";     
                
                echo "</div>";
                break;
            case 1:
                try
                {
                   $this->playlist->getUser($_SESSION['id_usuario']);
                   $id_p = $this->playlist->novaPlaylist($nome);
                   echo "<div id='ajax-wrap'>";
                   echo "<div id='response'>1</div>";
                   echo "<div id='pl-id'>$id_p</div>";                   
                   echo "</div>";
                }
                catch(Exception $a)
                {
                   echo "<div id='ajax-wrap'>";
                   echo "<div id='response'>0</div>";
                   echo "<div id='pl-id'>$id_p</div>";                   
                   echo "</div>";
                }
                
                break;
            
                
        }
    }
    
    public function startPlaylist($id_m)
    {
        /**
         *  Inícia o processo de caregamento da playlist, carregando o layout principal.
         */
        
        $root = $_SESSION['root'];
        
        try
        {
            
            $this->playlist->getId($this->id);
            $musicas = $this->playlist->selecionaMusica($id_m);
            $lista_musicas = $musicas[3]; // Lista as músicas da playlist.
            $musica_atual = $musicas[0]; // Passa as informações da música atual.
            $proxima_musica = $musicas[1]; // Passa as informações da próxima música.
            $musica_anterior = $musicas[2]; // Passa as informações da música anterior.
            $info_playlist = $this->playlist->getInfo();
            
            // Agora só basta carregar o layout
            echo "<div id='ajax-playlist-wrap'>";
            echo "<div id='response'>1</div>";
            echo "<div id='musica'>$musica_atual[0]</div>";
            echo "<div id='content'>";
            echo "<input type='hidden' id='playlist-id' value='$this->id' />";
            echo "<div id='playlist-content-wrap'>";
            echo "<div id='playlist-content-left'>";
            echo "<div class='default-box2'>";
            echo "<div class='gra_top1'>Letra</div>";
            echo "<div id='playlist-content-letras'>";
            echo nl2br($musica_atual[6]);            
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<div id='playlist-content-right'>";
            echo "<div id='playlist-content-player' class='default-box2'>";
            
            // Carrega o player

            echo "<object type=\"application/x-shockwave-flash\" value=\"transparent\" data=\"$root/swf/music.swf?dir=$root/files/musicas/$musica_atual[0]&autoplay=true\" width=\"239\" height=\"150\" >";
            echo "<param name='wmode' value='transparent' />";
            echo "<param name='movie' value='$root/swf/music.swf?dir=$root/files/musicas/$musica_atual[0]&autoplay=true' />";
            echo "<param name=\"allowScriptAccess\" value=\"always\"/>";
            echo "<embed allowScriptAccess=\"always\" src=\"$root/swf/music.swf?dir=$root/files/musicas/$musica_atual[0]&autoplay=true\" type=\"application/x-shockwave-flash\"  width=\"239\" height=\"150\" wmode=\"transparent\" quality=\"high\"></embed>";
            echo "</object>";
            
            echo "</div>";
            
            echo "<div id='playlist-content-lista' class='default-box2'>";
            echo "<div class='gra_top2'>Músicas na Playlist</div>";
            if($lista_musicas != "")
            {
                foreach($lista_musicas as $i => $v)
                {
                    if($v[0] == $musica_atual[0])
                        echo "<div class='playlist-content-listamusicas-selected' id='$v[0]-pcl' title='$v[5]'>$v[1] ($v[4])</div>"; 
                    else                    
                        echo "<div class='playlist-content-listamusicas' id='$v[0]-pcl' title='$v[5]'>$v[1] ($v[4])</div>"; 
                }
            }
            echo "</div>";
            echo "</div>";
            echo "<div class='clear'></div>";           
            echo "</div>";
            echo "<div class='clear'></div>";
            echo "</div>";
            echo "<div id='barra'>";
            echo "<div id='playlist-bottom-bar'>";
            echo "<input type='hidden' id='nextm-ipt' value='$proxima_musica[0]'/>";
            echo "<input type='hidden' id='backm-ipt' value='$musica_anterior[0]'/>";
            echo "<div id='pbar-back'>";
            echo "Anterior";
            echo "<div class='pbar-small' id='amus-nome'>$musica_anterior[1]</div>";
            echo "</div>";  
            
            echo "<div id='pbar-content1'>";
            echo "<strong>$info_playlist[2]</strong>";
            echo "<p>Gênero $info_playlist[3] - ";
            echo "Execuções $info_playlist[4]</p>";
            echo "<div class='pbar-config'><input type='checkbox' id='is-aleatorio' /><label for='is-aleatorio'>Aleatório</label></div>";
            echo "</div>";
            
            echo "<div id='pbar-content2'>";
            echo "<div class='pbar-mtitle'>$musica_atual[1]</div>";
            echo "<div class='pbar-info'>Gênero <strong class='ma-genero'>$musica_atual[2]</strong> do artista <strong class='ma-artista'>$musica_atual[3]</strong></div>";
            echo "</div>";
            echo "<div id='pbar-next'>";
            echo "Próxima";
            echo "<div class='pbar-small' id='pmus-nome'>$proxima_musica[1]</div>";
            echo "</div>";
            
            echo "</div>";
            echo "</div>";
            echo "</div>";
        
        }
        catch(Exception $a)
        {
            echo "<div id='ajax-playlist-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='content'>";
            echo "</div>";
            echo "</div>";
            
        }
    }
    
    public function trocarMusica($id_m,$aleatorio)
    {
        /**
         *  Realiza a troca de música.
         *  @param int $id_m
         */
        
        if($this->id == "")
            throw new Exception("Playlist inválida.");
        
        $root = $_SESSION['root'];
        
        try
        {
            if($aleatorio == 1)
                $aleatorio = true;
            else
                $aleatorio = false;
            
            $this->playlist->getId($this->id);
            $musicas = $this->playlist->selecionaMusica($id_m,$aleatorio);
            $musica_atual = $musicas[0]; // Passa as informações da música atual.
            $proxima_musica = $musicas[1]; // Passa as informações da próxima música.
            $musica_anterior = $musicas[2]; // Passa as informações da música anterior.
            
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            
            echo "<div id='musica'>";
            echo $musica_atual[0];
            echo "</div>";
            
            echo "<div id='letra'>";
            echo nl2br($musica_atual[6]);            
            echo "</div>";
            
            echo "<div id='proximo'>";
            if($proxima_musica != "")
            {
                $proxima_musica = array( "id" => $proxima_musica[0], "nome" => "($proxima_musica[1])" );
                echo json_encode($proxima_musica);            
            }
            else
            {
                echo json_encode(array("id" => "", "nome" => ""));
            }
            echo "</div>";
            
            echo "<div id='anterior'>";
            if($musica_anterior != "")
            {
                $musica_anterior = array( "id" => $musica_anterior[0], "nome" => "($musica_anterior[1])" );
                echo json_encode($musica_anterior);            
            }
            else
            {
                echo json_encode(array("id" => "", "nome" => ""));
            }
            echo "</div>";
            
            echo "<div id='atual'>";
            $musica_atual = array( "nome" => $musica_atual[1], "genero" => $musica_atual[2], "artista" => $musica_atual[3]);
            echo json_encode($musica_atual);
            echo "</div>";
            
            echo "</div>";
            
        }
        catch(Exception $a)
        {
            
        }
        
        
    }
    
    public function replicarPlaylist($id)
    {
        /**
         *  Replica uma playlist
         * 
         */
        
        try
        {
            $this->playlist->getId($id);
            $this->playlist->getUser($_SESSION['id_usuario']);
            $this->playlist->replicaPlaylist();
            $v = $this->playlist->getInfo();
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            echo "<div id='box'>";
            
            echo "<div class='left' id='playlist-box-$v[0]'>";
            echo "<div class='del-playlist1' id='del-$v[0]' ></div>";
            echo "<a href='".$_SESSION['root']."/playlists/$v[0]'>";
            echo "<div class='playlist-box'>";
            echo "<div class='playlist-gra-top'></div>";
            echo "<div class='playlist-box-left'></div>";
            echo "<div class='playlist-box-right'>";
            echo "<div class='playlist-box-image'>";
            if($v[5] != '0')
                echo "<img src='".$_SESSION['root']."/imagens/playlists/$v[0].$v[5]' alt='$v[0]' />";
            echo "</div>";
            echo "<div class='playlist-box-title'>$v[2]</div>";
            echo "</div>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
            
            echo "</div>";
            echo "</div>";
            
        }
        catch(Exception $a)
        {            
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='error'>".$a->getMessage()."</div>";
            echo "</div>";
            
        }
        
        
    }
    
    public function alterarPrivacidade($valor)
    {
        /**
         *  Altera a privacidade de uma playlist.
         * 
         */
        
        try
        {
            $this->playlist->getId($this->id);
            $this->playlist->updatePrivacidade($valor);
            echo json_encode(array("response" => "1"));
            
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => "0","error" => $a->getMessage()));            
        }
        
    }
    
    public function organizarPlaylist($tipo)
    {
        /**
         *  Organiza a playlist por ordem alfabética ou por gênero.s
         *  @páram int $tipo
         */
        
        try
        {
            $this->playlist->getId($this->id);
            $lista = $this->playlist->sortPlaylist($tipo);
            echo json_encode(array("response" => 1, "lista" => $lista));
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => 0,"error" => $a->getMessage()));
        }
    }
    
    public function ddPlaylist($lista)
    {
        /**
         *  Organiza a playlist de acordo com a resposta do sort.
         */
        
        try
        {
            $this->playlist->getId($this->id);
            $lista = $this->playlist->ddPlaylist($lista);
            echo json_encode(array("response" => 1));            
        }
        catch(Exception $a)
        {            
            echo json_encode(array("response" => 0, "error" => $a->getMessage()));
        }
    }
    
    public function ajaxListMusicas()
    {
        /**
         *  Lista as músicas da playlist para exibir no TT.
         *  
         */
        
        try
        {
            $this->playlist->getId($this->id);
            $musicas = $this->playlist->infoMusicas();
            foreach($musicas as $i => $v)
            {
                $musicas_list[] = array("nome" => $v[1]);
            }
            echo json_encode(array("response" => 1, "musicas" => $musicas_list));
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => 0, "error" => $a->getMessage()));
        }
        
        
    }

}








?>