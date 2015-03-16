<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: events.php - Página privada de eventos
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Página particular do evento, onde o usuário pode realizar alterações nos eventos e visualizar os visitantes/participantes recentes.
 *    Principais funções:
 *      - Listar e visualizar os eventos do membro.
 *      - Mostrar informações do evento.
 *      - Realizar funções de edição ao evento.
 * 
 * 
 *********************************************/
if (IN_ACCORDI != true)
{
   exit();
}

class manage_eventos
{
    private $key;
    private $id;
    private $evento;
    
    // Classe construtora da interface
    public function __construct($id,$key,$add=false)
    {
               
        // Instancia classe Evento
        $this->evento = new eventos();
        $this->id = $id;
        $this->key = $key;
        
        // Verifica se estamos tentando adicionar um novo evento
        if($add != false)
        {
            $apost = $this->evento->adicionaEvento($_POST['nome'], $_POST['n-dia'], $_POST['n-mes'], $_POST['hora'], $_POST['minuto'], $_POST['descricao'], $_POST['logradouro'], $_POST['bairro'], $_POST['cidade'], $_POST['estado'], $_FILES['banner-e'], $_POST['participantes'], $_POST['genero'],$_POST['n-ano']);
            if($apost != 'true')
                echo "<div class='error-create-evento'>$apost</div>";
            
        }
        
        // Verifica se o servidor enviou o BANNER
        if($_FILES['banner-e'])
        {
            $a = $this->evento->createBanner($_FILES['banner-e'],$_POST['data-info-e']);
        }
        
        if($id != "")
        {
            // Verifica se o ID existe
            if(!is_numeric($id))
                return;
        
            $this->evento->id = $id;
            $infos = $this->evento->pegaInfo();        
            if($infos == false)
            {
                include("includes/site/404.php");
                return;
            }
        }
        
        $this->loadBody();
    }
    
    private function loadBody()
    {
        /**
         *  Carrega o escopo da página com todas as características básicas do layout.
         */
        
        echo "<div class='opc-box'></div>";
        echo "<div class='ajax-box-edit'></div>";
        echo "<div id='evento-main-wrap'>";
        echo "<div id='evento-menu-left' class='default-box2'>";
        
        $this->loadMenu();
        
        echo "</div>";
        echo "<div id='evento-content-wrap' class='default-box2'>";
        
        $this->loadContent();
        
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "</div>";
    }
    
    private function loadMenu()
    {
        /**
         *   Carrega uma lista com os eventos e a opção de criar novo evento.
         */
        
        $lista = $this->evento->listaEventos();
        
        echo "<div class='gra_top1'>Menu</div>";
        echo "<div class='evento-menu-link' id='create-ev'>Criar Evento</div>";
        echo "<a href='".$_SESSION['root']."/eventos/'><div class='evento-menu-link'>Listar Eventos</div></a>";
        
        if(is_array($lista))
        {
            echo "<div class='main-list-eventos'>Últimos Eventos</div>";
            $count = 0;
            foreach($lista as $i => $v)
            {
                $count++;
                echo "<div id='side-list-$v[0]'><a href='".$_SESSION['root']."/eventos/$v[0]'><div class='list-eventos-menu' title='$v[5]'>$v[1]</div></a></div>";
                if($count == 4)
                    break;
            }
            
            echo "<div class='main-list-eventos'>Calendário</div>";
        }
        
    }
    
    private function loadContent()
    {
        /**
         *  Carrega o conteúdo da página
         */
        
        $root = $_SESSION['root'];
        if($this->id == "")
           $this->listaEventos();
        else
        {
            // Carrega o header do conteúdo
            switch($this->key)
            {
                case 'visitantes': $classes = array(3,3,2,3); break;
                case 'atracoes': $classes = array(3,2,3,3); break;
                case 'mapa': $classes = array(3,3,3,2); break;
                default: $classes = array(2,3,3,3); break;
            }
            
            
            echo "<div id='evento-info-main-wrap'>";
            echo "<a href='$root/eventos/$this->id'><div class='gra_top$classes[0]' id='menu-e-informacoes'>Informações</div></a>";
            echo "<a href='$root/eventos/$this->id/atracoes'><div class='gra_top$classes[1]' id='menu-e-participantes'>Atrações</div></a>";
            echo "<a href='$root/eventos/$this->id/visitantes'><div class='gra_top$classes[2]' id='menu-e-visitantes'>Visitantes</div></a>";
            echo "<a href='$root/eventos/$this->id/mapa'><div class='gra_top$classes[3]' id='menu-e-mapa'>Mapa</div></a>";
            
            // Verifica a chave da url para mostrar o conteúdo
            switch($this->key)
            {
                case 'atracoes':
                    $this->loadMembros(1);
                    break;
                case 'visitantes':
                    $this->loadMembros(0);
                    break;
                case 'mapa':
                    $this->loadMapa();
                    break;
                default:
                    $this->infoEvento();
                    break;
            }
            
            echo "</div>";
        }       
    }
    
    private function listaEventos()
    {
        /**
         *  Lista os eventos do usuário 
         */
        
        $root = $_SESSION['root'];
        echo "<div class='gra_top1'>Seus Eventos</div>";
        echo "<div class='info'>Abaixo fica a lista dos seus eventos atuais.</div>";
        $lista = $this->evento->listaEventos();
        if(is_array($lista))
        {
            /**
             *  Imrpimimos o layout dos eventos dentro de caixas.
             */
            
            foreach($lista as $i => $v)
            {
                echo "<div class='lista-box-eventos-wrap' id='boxev-$v[0]'>";
                echo "<a href='$root/eventos/$v[0]' >";
                echo "<div class='lista-box-eventos'>";
                echo "<div class='left'>";
                if($v[6] == '0')
                    echo "<img src='$root/imagens/evento/nobanner_thumb.png' class='thumb' alt='$v[1]' />";
                else
                    echo "<img src='$root/imagens/evento/$v[0]_thumb.$v[6]' class='thumb' alt='$v[1]' />";
                echo "</div>";
                echo "<div class='left-evento-info'>";
                echo "<div class='evento-title'>";
                echo $v[1];
                if($v[8] == 'f')
                    echo "<span class='color-red'> (Encerrado)</span>";
                echo "</div>";
                echo "<div class='evento-escopo'>$v[5]</div>";
                echo "<div class='evento-escopo'>$v[2] às $v[3]</div>";
                echo "<div class='evento-escopo'>$v[4]</div>";
                echo "<div class='evento-escopo'>$v[7]</div>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
                echo "<img src='$root/imagens/site/excluir.png' alt='Excluir' id='devento2-$v[0]' evento='$v[0]' class='e-show-del2' title='Deletar Evento' />";
                echo "</div>";
            }
        }
    }
    
    private function infoEvento()
    {
        /**
         *  Carrega as informações gerais do evento.  
         */
        
        $e = $this->evento;
        $e->id = $this->id;
        $e->pegaInfo();
        echo "<input type='hidden' value='$e->id' id='event-id' />";
        echo "<div id='evento-info2-header'>";
        echo "<div id='devento-$e->id' class='e-show-del'>Deletar</div>";
        echo "<div id='editevento-$e->id' class='e-show-edit'>Editar Evento</div>";
        echo "</div>";
        echo "<div id='evento-info2-content'>";
        echo "<div class='evento-info2-title'>$e->nome</div>";
        echo "<div class='evento-info2-sub'><a href='".$_SESSION['root']."/site/evento/$e->id' class='nochange2' target='new'>(Visualizar Página)</a></div>";
        echo "<div class='evento-info2-content1'>";
        echo "<ul class='evento-info2-list'>";
        echo "<li class='evento-info2-list' ><strong>Informações Gerais</strong></li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Gênero</label>$e->genero</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Status</label>";
        if($e->status == 'a')
            echo "Aberto";
        else
            echo "Fechado";
        echo "</li>";  
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Horário</label>$e->data às $e->hora</li>";        
        echo "<li class='evento-info2-list' ><strong>Localização</strong></li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Logradouro</label>$e->logradouro</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Bairro</label>$e->bairro</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Cidade</label>$e->cidade</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Logradouro</label>".$e->escolherEstado()."</li>";        
        echo "<li class='evento-info2-list' ><strong>Estatísticas</strong></li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Número de visitantes</label>".$e->contaMembros(0)."</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Número de atrações</label>".$e->contaMembros(1)."</li>";
        echo "<li class='evento-info2-list' ><label class='evento-info2-label'>Visualizações</label>$e->view</li>";
        // Mostra a imagem
        if($e->imagem != "0")
        {
            echo "<li class='evento-info2-list'><strong>Imagem</strong></li>";
            echo "<li class='evento-info2-list'><img src='".$_SESSION['root']."/imagens/evento/".$e->id.".$e->imagem' alt='$e->nome' class='banner-evento' /></li>";
        }
        
        
        echo "</ul>";
        echo "</div>";
        echo "<div class='evento-info2-content2'>";
        // Pega informações sobre as redes
        if($e->youtube == "")
             $e->youtube = "<span class='italic'>editar</span>";   
        if($e->facebook == "")
             $e->facebook = "<span class='italic'>editar</span>";
        if($e->twitter == "")             
             $e->twitter = "<span class='italic'>editar</span>";   
        if($e->lastfm == "")             
             $e->lastfm = "<span class='italic'>editar</span>";   
        
        echo "<strong class='color-gray'>Seu evento nas redes</strong>";
        echo "<ul>";
        echo "<li><div class='redes-evento-list'>Twitter</div><div id='twitter-ev-wrap'><div id='twitter-ev-insert' class='edita-redes-ev' title='Clique para editar'>$e->twitter</div></div></li>";
        echo "<li><div class='redes-evento-list'>Facebook</div><div id='facebook-ev-wrap'><div id='facebook-ev-insert' class='edita-redes-ev' title='Clique para editar'>$e->facebook</div></div></li>";
        echo "<li><div class='redes-evento-list'>LastFM</div></li><div id='lastfm-ev-wrap'><div id='lastfm-ev-insert' class='edita-redes-ev' title='Clique para editar'>$e->lastfm</div></div></li>";
        echo "<li><div class='redes-evento-list'>YouTube</div></li><div id='youtube-ev-wrap'><div id='youtube-ev-insert' class='edita-redes-ev' title='Clique para editar'>$e->youtube</div></div></li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        
    }
    
    private function loadMembros($tipo)
    {
        /**
         *  Carrega todos os participantes de determinado evento.
         *  @param int $tipo
         */
        
        $this->evento->id = $this->id;
        $this->evento->pegaInfo();
        $lista = $this->evento->mostraMembros($tipo);
        $root = $_SESSION['root'];
        
        // Ordem de listagem do Array
        //
        // [0] => Nome
        // [1] => Imagem
        // [2] => Login
        // [3] => Id
               
        if(is_array($lista))
        {
            echo "<div class='evento-info2-title'>".$this->evento->nome."</div>";
            switch($tipo)
            {
                case 0: echo "<div class='evento-list-introduction'>Visitantes do seu evento</div>"; break;
                case 1: echo "<div class='evento-list-introduction'>Lista de atrações cadastradas</div>"; break;
            }
            
            
            echo "<div class='wrap-lista-membros' id='".$this->evento->id."'>";
            foreach($lista as $i => $v)
            {
                echo "<div class='thumb-box' id='show-participantes-$v[3]'>";
                if($tipo == 1)
                    echo "<div class='delparticipante' title='Excluir' id='delparticipante-$v[3]-b'></div>";
                echo "<a href='$root/profile/$v[2]' >";
                if($v[1] == "0")
                    echo "<img src='$root/imagens/profiles/noavatar_thumb.png' alt='$v[0]' class='thumb' />";
                else
                    echo "<img src='$root/imagens/profiles/$v[3]_thumb.$v[1]' alt='$v[0]' class='thumb' />";
                echo "</a>";
                echo "<div>";
                echo "<span class='color-blue'>$v[0]</span>";
                echo "</div>";
                echo "</div>";
            }
            echo "<div class='clear'></div>";
            echo "</div>";
        }
        else
        {
            echo "<div class='evento-info2-title'>".$this->evento->nome."</div>";
            switch($tipo)
            {
                case 0: echo "<div class='evento-list-introduction'>Não existem visitantes confirmados neste evento.</div>"; break;
                case 1: echo "<div class='evento-list-introduction'>Você não cadastrou nenhuma atração para esse evento.</div>"; break;
            }
        }
        
    }
    
    private function loadMapa()
    {
        /**
         *  Carrega o mapa do Evento
         * 
         */
        
        $this->evento->id = $this->id;
        $this->evento->pegaInfo();
        
        echo "<div class='evento-info2-title'>".$this->evento->nome."</div>";
        echo "<div class='evento-list-introduction'>Visualize o mapa para chegar ao local do seu evento.</div>";
        echo "<input type='hidden' id='adress' value='".$this->evento->logradouro." ".$this->evento->bairro.", ".$this->evento->cidade." ," . $this->evento->escolherEstado() . "' />";
        echo "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>";
        echo "<div id=\"mapevento\"></div>";
        
    }
}
