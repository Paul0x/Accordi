<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: music.php - Página privada para visualizar e listar músicas.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página privada para listar as músicas e mostrar informações sobre elas além de realizar a função de adicionar novas músicas.
 *    Principais funções:
 *      - Listar músicas.
 *      - Mostrar informações das músicas via requisição AJAX.
 *      - Realizar processo de adicionar músicas.
 * 
 * 
 * 
 *********************************************/
if (IN_ACCORDI != true)
{
   exit();
}



class musica_manage
{
    /**
     *  Classe de interface para gerenciar músicas.
     */
    
    private $musica;
    private $key;
    private $id;
    
    public function __construct($id,$ajax)
    {
        /**
         *  Classe construtora, chama os devidos métodos
         */
        
        $this->musica = new musicas();
        $this->id = $id;
        
        if(is_numeric($this->id))
        {
            // Pega informações da música
            $a = $this->musica->infoMusica($this->id,"private");
            if($a == false)
            {
                $this->notFound();
                return;
            }
        }
            
        if($this->id == 'add') { include("add.php"); }
        else $this->loadBody($ajax);
    }
    
    private function loadBody($ajax)
    {
        /**
         *  Carrega todo o escopo da página.
         * 
         */
        
        echo "<div class='ajax-box-edit'></div>";
        
        // Aciona o carregamento automático da página
        echo "<script type='text/javascript'>";
        echo "ms = new musicaClass();";
        echo "ms.autoLoadMusica();";
        echo "</script>";
        echo "<div id='music-edit-wrap'>";
        
        if($ajax != true)
        {
            // Caarrega Menu
            $this->loadMenu();
        }
        echo "<div id='me-content' class='default-box2'>";
        
        if($this->id == "" || !is_numeric($this->id))
            $this->loadGeral();
        else
            $this->infoMusica();
        
        echo "</div>";
        
        echo "<div class='clear'></div>";
        echo "</div>";
        
    }
    
    private function loadGeral(){}
    
    private function infoMusica()
    {
        /**
         *  Carrega informações da música 
         */
        
        if($this->id == "" || !is_numeric($this->id))
                return;
        
        $ms = $this->musica;
        $root = $_SESSION['root'];
        
        // Calcula avaliação
        $this->musica->calculaAvaliacao();
        
        echo "<input id='medit-id' type='hidden' value='$ms->id' />";
        echo "<div class='mec-header'>";
        echo "<div class='mec-title'>$ms->nome</div>";
        echo "<div class='mec-player'>";
        echo "<object type=\"application/x-shockwave-flash\" value=\"transparent\" data=\"$root/swf/music.swf?dir=$root/files/musicas/$ms->id\" width=\"257\" height=\"130\" >";
        echo "<param name='wmode' value='transparent' />";
        echo "<param name='movie' value='$root/swf/music.swf?dir=$root/files/musicas/$ms->id' />";
        echo "<embed src=\"$root/swf/music.swf?dir=$root/files/musicas/$ms->id\" type=\"application/x-shockwave-flash\"  width=\"257\" height=\"130\" wmode=\"transparent\" quality=\"high\"></embed>";
        echo "</object>";
        echo "</div>";
        echo "<div class='ms-edit-aba2' id='ms-edit-aba2-$ms->id'>Editar</div>";
        echo "<div class='ms-del-aba2' id='ms-del-aba2-$ms->id'>Excluir</div>";
        echo "</div>";
        echo "<div class='clear'></div>";
        
        echo "<div class='mec-left'>";
        
        echo "<div class='mec-general'>Informações</div>";
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Gênero</div>";
        echo "$ms->genero";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Duração</div>";
        echo "$ms->duracao";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Visualizações</div>";
        echo "$ms->view";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Classificação</div>";
        if($ms->classificacao == 0)
            echo "Livre";
        else
            echo "$ms->classificacao anos";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Avaliação Média</div>";
        echo "$ms->avaliacao sobre 5";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Número de Avaliações</div>";
        echo "$ms->navaliacao";
        echo "</div>";
        
        echo "<div class='mec-info'>";
        echo "<div class='meci-title'>Privacidade da Música</div>";
        echo "$ms->permissao";
        echo "</div>";
        
        echo "</div>";
        echo "<div class='mec-right'>";
        
        // Carrega o clipe da música no youtube (caso exissta)
        if($ms->clipe != "")
        {
            echo "<div class='mec-general'>Vídeo da Música</div>";
            echo "<div class='default-box3'>";
            echo "<object width=\"270\" height=\"220\">";
            echo "<param name='wmode' value='transparent' />";
            echo "<param name=\"movie\" value=\"http://www.youtube.com/v/".$ms->clipe."?fs=1\"></param>";
            echo "<embed src=\"http://www.youtube.com/v/".$ms->clipe."?fs=1\"
            type=\"application/x-shockwave-flash\"
            width=\"270\" height=\"220\" 
            allowfullscreen=\"true\" wmode=\"transparent\" quality=\"high\"></embed>";
            echo "</object>";
            echo "</div>";
        }
        
        
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "<div class='mec-general'>Letra</div>";
        echo "<div id='nobr-letras' class='hidden'>$ms->letra</div>";
        echo "<div class='mec-letras'>";
        echo "<div id='edit-letras' title='Clique para editar'>";
        if($ms->letra == "")
            echo "Clique aqui para adicionar uma letra na música.";
        else
            echo nl2br($ms->letra);
        echo "</div>";
        echo "</div>";
        
        
        
    }
    
    private function loadMenu()
    {
        /**
         *  Carrega o menu das músicas
         * 
         */
        
        echo "<div id='me-menu' class='default-box2'>";
        echo "<div class='gra_top1'>Minhas Músicas</div>";        
        
        echo "<a href='".$_SESSION['root']."/musicas/add' class='nochange2'><div class='mem-bigdiv' id='add-musica-link'>Adicionar Música</div></a>";
        
        
        // Carrega lista de músicas do usuário
        $lista = $this->musica->carregaMusicasArtista();
        if($lista != false)
        {
            foreach($this->musica->listamusicas as $i => $v)
            {
                echo "<div class='mem-bigdiv' id='nem-bigdiv-$v[0]'>";
                echo "<div class='mem-link' id='link-$v[0]'>";
                echo "<div class='mem-list-title'>$v[1]</div>";
                echo "<div class='mem-list-info'>$v[2] - $v[3]</div>";
                echo "</div>";
                echo "<div alt='Excluir' class='music-show-del' id='music-show-del-$v[0]' ></div>";
                echo "<div alt='Editar' class='music-show-info' id='music-show-info-$v[0]'></div>";
                echo "</div>";
            }
        }
        echo "</div>";
    }
    
    
    private function notFound()
    {
        // Inclui a página 404.php
        echo "<script type='text/javascript'>document.title = 'Accordi - Página indisponível';</script>";
        include("includes/site/404.php");
    }
    
    
    
    
}

