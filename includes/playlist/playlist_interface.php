<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: portal.php - Interface do portal
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Mostra index do portal.
 *    Principais funções:
 *      - Exibe as últimas notícias do portal
 *      - Mostra os eventos próximos
 *
 * 
 *********************************************/
if(IN_ACCORDI != true)
    exit();

class playlist_interface
{
    
    /**
     *  ID da playlist
     */
    private $id;
    
    /**
     *  Chave da página
     */
    private $key;
    
    /**
     *  Classe Playlist
     */
    private $playlist;
    
    /**
     *  Classe Música
     */
    private $musica;
    
    /**
     *  Classe Usuário
     */
    private $user;
    
    /**
     *  
     *      Métodos
     * 
     */
    
    public function __construct($id,$key,$capa="")
    {
        // Pega as informações
        $this->id = $id;
        $this->key = $key;
        $this->user = $_SESSION['id_usuario'];        
        $this->playlist = new Playlist(); 
        $this->playlist->getUser($this->user);
        if($capa == "")
            $this->loadMain();
        else
        {
            if(is_numeric($capa))
                $this->id = $capa;
            $this->addCapa($_FILES['pc-imagem']);
        }
    }
    
    private function loadMain()
    {
        /**
         *  Carrega a página inicial de playlists
         *  
         */
        
        
        echo "<div class='ajax-box-playlist'></div>";
        echo "<div id='playlist-real-wrapper'>";
        echo "<div id='playlist-header-wrapper'>";
        // Carrega a lista de playlist
        $this->loadPlaylists();        
        echo "</div>";
        echo "<div id='playlist-body-wrapper'>";
        echo "<div id='playlist-listam-wrapper'>";
        if($this->id == "")
        {
            echo "<div class='playlist-no-playlist'>Nenhuma Playlist Selecionada</div>";
            $this->loadBody();
        }
        else
        {
            $this->loadPlaylist();
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    
    private function loadPlaylists()
    {
        /**
         *  Carrega a lista de playlists.
         *  
         */
        
        echo "<div id='lista-playlist-header'>";
        echo "<div id='lph-wrap'>";
        echo "<div class='lista-playlist-title'>Suas Playlists</div>";
        echo "<div id='seta-playlist-left'></div>";
        echo "<div id='lista-playlist-roll'>";
        echo "<div id='lista-playlist-rollwrap'>";
        echo "<div id='lp-b-rp'>";
        echo "<div class='playlist-box' id='addnpl'>";
        echo "<div class='playlist-box-left'></div>";
        echo "<div class='playlist-box-right'>";
        echo "<div class='playlist-box-image2'>Nova Playlist</div>";
        echo "</div>";
        echo "</div>";
        try
        {
            
            $a = $this->playlist->listaPlaylists(true);
            foreach($a as $i => $v)
            {
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
            }
            
        }
        catch(Exception $a)
        {
            
        }
        echo "</div>";
        echo "</div>";        
        echo "</div>";
        echo "<div id='seta-playlist-right'></div>"; 
        echo "<div class='clear'></div>";
        echo "</div>";
        echo "<div class='hide-playlist'>Esconder</div>";
        echo "</div>";
    }
    
    private function loadPlaylist()
    {
        /**
         *  Carrega as informações da playlist e lista as músicas da mesma
         */
        
        $root = $_SESSION['root'];
        try
        {
            $this->playlist->getId($this->id);
            $infos = $this->playlist->getInfo();            
            try
            {
                $musicas = $this->playlist->infoMusicas();
                $count_musicas = count($musicas);
                $duracao = $this->playlist->setDuracao();
            }
            catch(Exception $a)
            {
                $count_musicas = 0;                
            }
            $u = new usuario($infos[1]);
            echo "<input type='hidden' id='playlist-id' value='$infos[0]' />";
            
            // Carrega a playlist automáticamente, caso a key play seja passada
            if($this->key == 'play')
                    echo "<script type='text/javascript'>pl = new playlistClass(); pl.startPlaylist($infos[0]);</script>";
            
            
            echo "<div id='playlist-show-info'>";
            echo $infos[2]." - $infos[3]";
            echo "<p>$infos[4] execuções - Playlist criada por <a href='$root/profiles/$u->login' class='nochange2'>$u->nome</a>";
            if($infos[6] != "")
            {
                // Pega inforamções da playlist base
                try
                {
                    $base = $this->playlist->baseInfo();
                    echo " com base em <a href='$root/playlists/$base[0]' class='nochange2'>$base[1]</a>";
                }
                catch(Exception $a){ }
            }
            echo ".</p>";
            if($count_musicas != 0)
                echo "<div class='playlist-info-right'>$count_musicas músicas - $duracao</div>";
            if($u->id != $_SESSION['id_usuario'] && $_SESSION['id_usuario'] != "" && $infos[7] != "1")
            {
                echo "<div id='reply-playlist'>";
                echo "Essa é uma playlist pública, deseja replica-la? <div class='btn-reply-playlist' id='rp-pl-$infos[0]'></div>";
                echo "</div>";
            }
            elseif($u->id == $_SESSION['id_usuario'] && $_SESSION['id_usuario'] != "")
            {
                echo "<div id='reply-playlist'>";
                if($infos[7] == '0')
                    echo "<div id='playlist-privacy-1' class='playlist-privacy'>Playlist Pública (alterar)</div>";
                elseif($infos[7] == '1')
                    echo "<div id='playlist-privacy-0' class='playlist-privacy'>Playlist Privada (alterar)</div>";
                
                echo "<div id='op-menu'>";
                echo "<div id='opm-main'>Organizar Playlist</div>";
                echo "<div id='opm-list'>";
                echo "<div class='opm-item' id='opm-alfabeto'>Ordem Alfabética</div>";
                echo "<div class='opm-item' id='opm-genero'>Gênero</div>";
                echo "<div class='opm-item' id='opm-personalizada'>Personalizado</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                
            }
            
            echo "</div>";
            echo "<div id='playlist-music-list'>";
            if(is_array($musicas))
            {
                echo "<ul id='ul-music-list'>";
                foreach($musicas as $i => $v)
                {
                    echo "<li class='music-li-main' position='$v[6]' id='musica-lista-pl-$v[0]'>";
                    echo "<a href='#'><div class='start-playlist' id='start-m-$v[0]' title='Tocar Playlist'></div></a>";
                    echo "<div>";
                    echo "<div class='mlm-title'>$v[1]</div>";
                    echo "<div class='mlm-artista'>$v[2]</div>";
                    echo "<div class='mlm-genero'>$v[5]</div>";
                    echo "<div class='mlm-duracao'>$v[4]</div>";
                    echo "</div>";
                    if($infos[1] == $_SESSION['id_usuario'])
                    echo "<div class='mlm-delete' id='del-$v[0]' >Deletar</div>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            echo "</div>";
        }
        catch(Exception $a)
        {
            echo "<div class='playlist-no-playlist'>Playlist Inexistente :(</div>";
        }
        
        
    }
    
    private function addCapa($capa)
    {
        /**
         *  Adiciona a capa da playlist
         *  @param file $capa
         */
        
        if($this->id == "" || !is_numeric($this->id))
            return;
        
        try
        {
            $this->playlist->getId($this->id);
            $this->playlist->addCapa($capa);
            echo "<div id='response'>1</div>";
        }
        catch(Exception $a)
        {
            echo "<div id='response'>0</div>";
        }
        
        
        
    }
    
    private function loadBody()
    {
        /**
         *  Carrega o corpo da página
         */
        
        echo "<div id='show-playlists-wrap'>";
        try
        {
           // Carrega as playlists de, no máximo, 10 contatos.
           $playlists =  $this->playlist->loadFromContatos();
           echo "<div id='spc-title'>Saiba o que seus contatos estão ouvindo</div>";
           $this->playlist->getUser($_SESSION['id_usuario']);
           
           // Imprime essas playlists
           foreach($playlists as $i => $plays)
           {
               foreach($plays as $key => $v)
               {
                   echo "<div class='left' id='playlist-box-$v[0]'>";
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
               }
           }   
            
        }
        catch(Exception $a)        
        {
            
        }
        
        
        echo "</div>";
    }
    
        
    
}

?>