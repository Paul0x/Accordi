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

class portal_interface
{
    
    /**
     *  @private string key
     */
    private $key;
    
    /**
     *  @private int key2
     */
    private $key2;
    
    /**
     *  @private object portal
     */
    private $portal;
    
    public function __construct($key1,$key2,$is_ajax=false)
    {
       // Aloca as variáveis e inicia o carregamento da página.
       $this->key = $key1;
       $this->is_ajax = $is_ajax;
       $this->key2 = $key2;
       $this->portal = new Portal();
       
       /* Verifica se a notícia existe */
       if($this->key == "show")
           $a = $this->getNoticiaId();
       $this->loadBody();
       
    }
    
    private function getNoticiaId()
    {
        /**
         *  Traduz a string do notícia e verifica se ela existe.
         */
        
          $a = $this->portal->getIdByUrl($this->key2);
          if($a == true)
          {
              $this->key2 = $this->portal->setId();
              return true;
          }
          
    }
    
    private function loadBody()
    {
        /**
         *  Carrega todo o escopo da página.
         */
        
        // Verifica se o usuário é administrador.
        if($_SESSION['id_usuario'] != "")
        {
            $u = new usuario();
            $this->admin = $u->isAdmin();
            if($this->admin > 0 && $this->is_ajax != true)
                    $this->menuAdmin();
            // Carrega a aba de administrador caso o usuário tenha essa permissão.
        }
        
        echo "<div class='opc-box'></div>";
        echo "<div class='ajax-box-edit'></div>";
        echo "<div id='portal-full-wrap'>";
        echo "<div id='portal-noticias-wrap'>";
        
        // Verifica se vamos mostrar uma notícia ou não.
        switch($this->key)
        {
            case "show": $this->mostraNoticia(); break;
            case "list": $this->listaNoticias(0); break;
            case "listn": $this->listaNoticias(1); break;
            default: $this->mainPage(); break;
        }
        echo "</div>";
        echo "<div id='portal-right-wrap'>";
            $this->loadRight();
        
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "</div>";
        $this->loadRodape();
        echo "<div class='clear'></div>";        
    }
    
    private function listaNoticias($tipo)
    {
       /**
        *  Lista as notícias baseando-se na sua categoria.
        *  @param int $tipo
        * 
        *  - Tipo 0: Pesquisa por categoria
        *  - Tipo 1: Pesquisa por título
        */
       
        try
        {
            $this->key2 = explode("/",$this->key2);
            if($this->key2[0] == "all")
                $this->key2[0] = "";
            $lista = $this->portal->listaNoticias($this->key2[0],$this->key2[1],$tipo);
            echo "<div class='left'>";
            if($this->key2[0] != "")
                switch($tipo)
                {
                    case 0: echo "<div class='noticia-title'>Categoria: ".$this->key2[0]."</div>"; break;
                    case 1: echo "<div class='noticia-title'>Título: ".$this->key2[0]."</div>"; break;
                }
            else
                echo "<div class='noticia-title'>Últimas notícias</div>";
            echo "</div>";
            $this->searchBox();
            $root = $_SESSION['root'];
            foreach($lista as $i => $v)
            {
                echo "<a href='$root/portal/show$v[12]'><div class='noticia-list-ul'>";
                echo "<div class='noticia-list-ul-title'>$v[1]</div>";
                echo "<span class='noticia-list-ul'>$v[2]</span>";
                echo "<p class='noticia-list-ul'>$v[8]</p>";
                echo "</div></a>";                
            }
            echo "<div class='clear'></div>";
            
            // Faz o sistema de páginação
            $pages = ceil($this->portal->list_count/20);
            if($this->key2[0] == "")
                    $this->key2[0] = "all";
            for($i = 0; $i < $pages; $i++)
            {
                if($tipo == 0) $t = "list";
                else $t = "listn";
                echo "<a href='$root/portal/$t/".$this->key2[0]."/$i'><div class='noticia-list-p'>".($i)."</div></a>";
            }
        }
        catch(Exception $a)
        {
            $this->notFound();
        }
        
    }
    
    private function notFound()
    {
        /**
         *  Mensagem exibida ao não encontrar as coisas [:-D
         */
        
        echo "<div class='pr-search-fail'>";
        echo "Não foi possível encontrar uma notícia com o as chaves específicadas. (Tente novamente no campo ao lado)";
        echo "</div>";
        $this->searchBox();
    }
    
    private function mainPage()
    {
        /**
         *  Carrega a main page do Portal
         *
         */        
        echo "<div id='pm-sc-wrap'>";
        echo "<div class='pm-destaques'>Destaques</div>";
        // Formulário de pesquisa
        $this->searchBox();
        echo "</div>";
        echo "<div id='pm-pn-wrap'>";
        // Div contendo as box com as principais notícias.
        $this->loadBox();
        echo "</div>";
        echo "<div id='pm-un-wrap'>";
        // Lista as últimas 10 notícias e um link para a página de listagem.
        $this->load10();
        echo "</div>";
        echo "<div id='pm-ma-wrap'>";
        echo "<div class='gra_top2'>Mais Acessadas</div>";
        // Div contendo a lista das mais acessasdas
        $this->loadMaisVisitados();
        
        echo "</div>";
    }
    
    private function loadRight()
    {
        /**
         *  Carrega o menu direito do portal
         
        */
        
        echo "<a href='".$_SESSION['root']."/portal'><div id='main-portal-banner'></div></a>";
        echo "<div id='pr-pe-wrap'>";
        echo "<div class='pr-right-title'>Próximos Eventos</div>";
        $this->loadCalendario();
        echo "</div>";
        echo "<div id='pr-mm-wrap'>";
        echo "<div class='pr-right-title'>Músicas da Semana</div>";
        $this->topMusicas();
        echo "</div>";
        echo "<div id='pr-ta-wrap'>";
        echo "<div class='pr-right-title'>Melhores Artistas</div>";
        $this->topUsuario(0);
        echo "</div>";
        echo "<div id='pr-tc-wrap'>";
        echo "<div class='pr-right-title'>Melhores Contratantes</div>";
        $this->topUsuario(1);
        echo "</div>";
        echo "<div id='pr-tc-wrap'>";
        echo "<div class='pr-right-title'>Nosso Twitter</div>";
        $this->loadTwitter();
        echo "</div>";
    }
    
    private function mostraNoticia()
    {
        /**
         *  Mostra uma determinada notícia
         */
        
        if($this->portal->setId() == 0) // Verifica se existe uma notícia para exibir.
            $this->portal->getId($this->key2);
        
        try
        {
            $root = $_SESSION['root'];            
            $info = $this->portal->pegaInfo();
            $this->portal->addView();
            $u = new usuario($info[4]);            
            // Carrega a notícia sem BBcode aplicado para caso o usuário possa editar.
            if($this->admin > 0)
                echo "<div class='hidden' id='noticia-edit-nobb' >$info[10]</div>";    
            echo "<input type='hidden' id='noticia-id' value='$info[0]' />";            
            echo "<script type='text/javascript'>b = new commentsClass(); b.reloadComments();</script>";
            echo "<div class='big-categoria'>";
            echo "<div class='left'><a href='$root/portal/' class='big-categoria'>Portal</a></div>";
            if($info[3] != "")
                echo " <div class='big-seta'></div><div class='left'> <a href='$root/portal/list/$info[3]' class='big-categoria'>$info[3]</a></div>";
            echo "</div>";
            $this->searchBox();
            echo "<div class='padding'>";
            
            if($info[7] != "")
                echo "<img src='$root/imagens/noticias/$info[7]' title='$info[1]' class='banner-noticia' >";
            echo "<div class='cabecalho-wrap'>";            
            if($this->admin > 0)
                echo "<a href='#'><div class='noticia-delete' id='noticia-delete'>Deletar</div></a>";
            echo "<div class='noticia-title'>$info[1]</div>";
            echo "<div class='noticia-subtitle'>$info[2]</div>";
            echo "<div class='noticia-creditos'>Por: $u->nome - criado em $info[8] ";
            if($info[9] != "")
                echo "(editado em $info[9])";
            if($info[6] == 1) $visu = "ão";
            else $visu = "ões";
            echo " - $info[6] visualizaç$visu.";
            echo "</div>";
            echo "</div>";
            echo "<div class='noticia-content' >";
            if($this->admin > 0)
                echo "<div id='noticia-edit' class='pointer' title='Clique para editar' >";
            echo $info[11];
            echo "<div class='clear'></div>";
            if($this->admin > 0)
                echo "</div>";
            echo "<div class='clear'></div>";
            echo "</div>";
            echo "<div class='tags-wrapper'>";
            try
            {
                echo "<strong>Tags:</strong> ";
                $count = count($info[5])-1;
                foreach($info[5] as $i => $v)
                {
                    echo "<span class='tag-list'>$v</span>";
                    if($i != $count)
                        echo ",";
                }
            }
            catch(Exception $a)
            {
                echo "Nenhuma tag encontrada.";
            }
            echo "</div>";
            echo "<div id='comentarios-noticias-wrap'>";
            
            /**
             *  Vamos carregar os comentários aqui.
             */
            
            $this->loadComentarios();
            
            
            
            
            
            echo "</div>";
            echo "<div class='clear'></div>";
            echo "</div>";
            
        }
        catch(Exception $ex)
        {
            $this->notFound();
        }
        
        
    }
    
    private function menuAdmin()
    {
        /**
         *  Carrega barra do menu para adicionar novas notícias.
         */
        
        echo "<input type='button' class='btn' value='Criar Notícia' id='btn-nova-noticia' />";
        echo "<input type='button' class='btn' value='Gerenciar' id='btn-gerencia-portal' />";
        
        // Verifica se o formulário para adicionar notícias foi inserido.
        if(isset($_POST['ntc-add']))
        {
            // O formulário para adicionar notícias foi acionado.
            try
            {
                $this->portal->criarNoticias($_POST['ntc-titulo'],$_POST['ntc-resumo'],$_POST['ntc-categoria'],$_SESSION['id_usuario'],$_POST['ntc-tags'],$_FILES['ntc-img'],$_POST['ntc-texto']);
                echo "<span class='ntc-ok'>Notícia adiciona.</span>";
                $this->key = "show";
            }
            catch(Exception $a)
            {
                echo "<span class='ntc-error'>Erro ao inserir notícia. - ".$a->getMessage()." </span>";
            }
        }
        
    }
    
    private function searchBox()
    {
        /**
         *  Aba de pesquisa.
         */
        
        echo "<div id='pr-search-wrap'>";
        echo "<div id='pr-search-button'>Buscar</div>";
        echo "<input type='text' id='pr-search-input'/>";
        echo "</div>";
        echo "<div class='clear'></div>";
    }
    
    private function loadRodape()
    {
        /**
         *  Lista as categorias existentes na parte inferior da tela
         * 
         */
        
        try
        {
            $categorias = $this->portal->getCategoria();
            
            if(is_array($categorias))
            {
                $count = count($categorias);
                if($count > 8)
                    $count = $count/4;
                $count = number_format($count,0);
            }
            else
                $count = 1;            
            echo "<div id='pr-rodape-wrap'>";
            echo "<div id='pr-rodape-title'>Categorias</div>";
            $contador = 0;
            echo "<ul class='pr-rodape-ul'>";
            foreach($categorias as $i => $v)
            {
                if($contador > $count)
                {
                    echo "</ul><ul class='pr-rodape-ul'>";
                    $contador = 0;
                }
                echo "<a href='".$_SESSION['root']."/portal/list/$v[0]'><li class='pr-rodape-ul'>$v[0]</li></a>";
                $contador++;                
            }
            echo "</ul>";
            echo "<div class='clear'></div>";
            echo "</div>";
        }
        catch(Exception $a)
        {
            
        }
        
    }
    
    private function loadBox()
    {
        /**
         * Carrega as notícis principais do site
         *  
         */
        
        try
        {
            $box = $this->portal->loadBox();
            $root = $_SESSION['root'];
            $count_list = 0;
            echo "<ul><li class='bnotícia-li'>";
            foreach($box as $i => $v)
            {
                $this->portal->getId($v[1]);
                $infos = $this->portal->pegaInfo();
                
                // Vamos fazer algumas análises afim de mostrar a notícia corretamente
                if(strlen($infos[2]) > 150)
                    $class_resumo = "portal-resumosmall$v[2]";
                else
                {   
                    $class_resumo = "portal-resumo".$v[2];
                }
                
                  
                switch($v[2])
                {
                    case 1:                        
                        echo "<div class='portal-box1'>";
                        echo "<a href='$root/portal/show$infos[12]' class='ptr-link'>";
                        if($v[3] != "")
                            echo "<div class='portal-box-image'><img src='$root/imagens/noticias/$v[3]' alt='Imagem' /></div>";
                        echo $infos[1];                        
                        echo "</a>";
                        echo "<div class='$class_resumo'>$infos[2]</div>";
                        echo "</div>";
                        break;
                    case 2:
                        echo "<div class='portal-box2'>";
                        echo "<a href='$root/portal/show/$infos[12]' class='ptr-link'>";
                        if($v[3] != "")
                            echo "<div class='portal-box-image'><img src='$root/imagens/noticias/$v[3]' alt='Imagem' /></div>";
                        echo $infos[1];
                        echo "</a>";
                        echo "<div class='$class_resumo'>$infos[2]</div>";
                        echo "</div>";
                        break;
                    case 3:
                        echo "<div class='portal-box3'>";
                        echo "<a href='$root/portal/show/$infos[12]' class='ptr-link'>";
                        if($v[3] != "")
                            echo "<div class='portal-box-image'><img src='$root/imagens/noticias/$v[3]' alt='Imagem' /></div>";
                        echo $infos[1];           
                        echo "</a>";             
                        echo "<div class='$class_resumo'>$infos[2]</div>";
                        echo "</div>";
                        break;
                }
                $count_list+= $v[2];
                if($count_list >= 3)
                {
                    echo "</li><li class='bnotícia-li'>";
                    $count_list = 0; 
                }
                
            }
            echo "</li></ul>";
            echo "<div class='clear'></div>";
            echo "<div class='clear-left'></div>";
        }
        catch(Exception $a)
        {
            
        }
    }
    
    private function load10()
    {
        /**
         *  Carrega as últimas 10 notícias postadas.
         */
        
        
        echo "<div id='pm-cd-wrap'>";
        echo "<div class='gra_top2'>Últimas 10</div>";        
        echo "<ul>";
        try
        {
            $lista = $this->portal->listaNoticias("",0,1);
            foreach($lista as $i => $v)
            {
                echo "<a href='".$_SESSION['root']."/portal/show$v[12]'>";
                echo "<li class='noticia-main-list'>";
                if($v[7] != "")
                {
                    echo "<div class='nml-image'>";
                    echo "<img src='".$_SESSION['root']."/imagens/noticias/$v[13]' alt='$v[1]' class='thumb' />";
                    echo "</div>";
                }
                echo "<div class='nml-text'>";
                echo $v[1];
                echo "</div>";
                echo "</li>";
                echo "</a>";
                if($i == 9)
                    break;
            }
            
        }
        catch(Exception $a)
        {
            echo "<li class='noticia-main-list'>Nenhuma notícia encontrada</li>";
        }
        echo "</ul>";       
        echo "</div>";
        if(count($lista) > 10)
            echo "<a href='".$_SESSION['root']."/portal/listn'><div class='gra_top2'>Visualizar mais notícias</div></a>";
        
    }
    
    private function loadCalendario()
    {
        /**
         *  Carrega um calendário com os próximos eventos.
         */
        
        
        // Instancia as classes necessárias
        $evento = new eventos();
        $data_format = new dataformat();
        
        // Lista os eventos do mês
        $eventos_mes = $evento->eventosMes(1);
        
        // Pega informações sobre a data
        $data_format->pegarData(date("Y")."-".date("m")."-01");
        $wd = $data_format->pegarWeekDay();
        $mes_nome = $data_format->nomeMes();
        $max_day = $data_format->pegarMaxMes(date("m"));
        $count_day = 1;
        $count_mes = date("m");
        if(strpos($count_mes,"0") === 0)
                $count_mes = $count_mes[1];
        
        // Imprime o calendário
        echo "<div class='default-box2' id='c-box'>";
        echo "<input type='hidden' value='".$count_mes."' id='mes-atual'>";   
        echo "<input type='hidden' value='".date("Y")."' id='ano-atual'>";
        echo "<div class='gra_top1'><span id='calendario-back2'><div id='seta_2'></div></span><strong>$mes_nome / ".date("Y")."</strong><span id='calendario-next2'><div id='seta_1'></div></span></div>";        echo "<div class='calendario-space-t-small'>D</div>";
        echo "<div class='calendario-space-t-small'>S</div>";
        echo "<div class='calendario-space-t-small'>T</div>";
        echo "<div class='calendario-space-t-small'>Q</div>";
        echo "<div class='calendario-space-t-small'>Q</div>";
        echo "<div class='calendario-space-t-small'>S</div>";
        echo "<div class='calendario-space-t-small'>S</div>";
        for($row_mes=0;$row_mes<=5;$row_mes++)
        {
            for($col_mes=0;$col_mes<=6;$col_mes++)
            {
               if($row_mes == 0 && $col_mes < $wd[0])
                   echo "<div class='calendario-space-w-small'></div>";
               else
               {
                   if($eventos_mes[$count_day] != "")
                   echo "<div class='calendario-space-n-small' id='c-tt-ajaxsmall-$count_day'>$count_day</div>";
                   else
                   echo "<div class='calendario-space-small'>$count_day</div>";
                   $count_day++;
                   if($count_day > $max_day)
                       break;
               }
            }
            if($count_day > $max_day)
            break;
        }
        unset($i,$v);
        if($eventos_mes != "")
        {
            foreach($eventos_mes as $i => $v)
            {
                echo "<div class='hidden' id='hide-e-c-$i'>";
                echo "<ul>";
                    echo "<li><div class='gra_top1'><div class='gra-evento'>Eventos do dia</div></div>";
                    foreach($v as $iv => $vv)
                    {
                        echo "<li class='padding-list'><a href='".$_SESSION['root']."/site/evento/$vv[3]' class='link-blue'>$vv[0]</a> ás $vv[2]</li>";
                    }
                echo "</ul>";    
                echo"</div>";
            }
        }        
        echo "</div>";       
    }
    
    private function loadMaisVisitados()
    {
        /**
         *  Carrega as 10 notícias mais visitadas dos últimos 5 dias.
         */
        
        echo "<ul>";
        try
        {
            $lista = $this->portal->pegarMaisVisualizadas();
            foreach($lista as $i => $v)
            {
                echo "<a href='".$_SESSION['root']."/portal/show$v[12]'>";
                echo "<li class='noticia-main-list'>";
                if($v[7] != "")
                {
                    echo "<div class='nml-image'>";
                    echo "<img src='".$_SESSION['root']."/imagens/noticias/$v[13]' alt='$v[1]' class='thumb' />";
                    echo "</div>";
                }
                echo "<div class='nml-text'>";
                echo $v[1];
                echo "</div>";
                echo "</li>";
                echo "</a>";
                if($i == 9)
                    break;
            }
        }
        catch(Exception $a)
        {
            echo "<li class='noticia-main-list'>Nenhuma notícia encontrada</li>";
        }
        echo "</ul>";
    }
    
    private function topMusicas()
    {
        /**
         *  Carrega as 5 músicas mais visitadas da semana.
         */
        
        try
        {
            $musica = new musicas();
            $a = $musica->topWeek();
            
            echo "<ul>";
            foreach($a as $i => $v)
            {
                echo "<a href='".$_SESSION['root']."/site/musica/$v[0]'><li class='musica-portal-lista'>$v[1] <p class='small'> ( $v[3] visualizações na semana )</p></li></a>";
            }
            echo "</ul>";
            
        }
        catch(Exception $a)
        {
            echo "Nenhuma música encontrada.";
        }
    }
    
    private function loadComentarios()
    {
        /**
         *  Carrega os comentários na página de notícias.
         */
        
        $comentarios = new comentarios();
        $root = $_SESSION['root'];
        
        // Adiciona uma box para adicionar os comentários.
        if($_SESSION['id_usuario'] != "")
        {
            echo "<div class='gra_top1'>Postar Comentário</div>";
            echo "<div id='bx-cmt'>";
            echo "<textarea class='c-textarea4' id='text-comment-n'></textarea>";
            echo "<p><input type='button' value='Comentar' id='comment-button' class='btn' /><span id='c-error'>";
            echo "</span></p></div>";
        }
        
        // Carrega os comentários da notícia
        
        $lista_comentarios = $comentarios->listarComentario($this->portal->setId(),4);
        if($lista_comentarios != "")
        {
            foreach ($lista_comentarios as $i => $v)
            {
                
                echo "<div class='comment-box-p-portal' id='comentario-box-t-$v[0]'>";
                echo "<div class='title-c-portal'><a href='$root/profile/$v[5]'>$v[7]</a> - $v[3]</div>";
                echo "<div class='box-img-c'>";
                if ($v[6] != "0")
                    echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                else
                    echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
                echo "</div>";
                echo "<div class='comment-content-portal'>";
                echo "<input type='hidden' id='content-ajax-$v[0]' value='$v[8]' />";
                echo "<div id='content-$v[0]'>$v[2]";
                if ($ec != true && $_POST['mode2'] == 'ecomment' && $_POST['cid'] == $v[0])
                    echo "<p><span class='side-tip'>Falha ao alterar o comentário.</span></p>";
                echo "</div>";
                echo "</div>";
                echo "<div class='comment-footer-portal'>";
                if ($_SESSION['id_usuario'] == $v[1] || $_SESSION['id_usuario'] == $v[4])
                {
                    echo "<span class='del-comment2' id='dc-$v[0]'>Deletar</span>";
                    if ($_SESSION['id_usuario'] == $v[4])
                        echo "<span class='edit-comment2' id='ec-$v[0]-0'>Editar</span>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "<div id='results-comments-more'><div id='results-c-m0'></div></div>";
            if(count($lista_comentarios) >= 20)
                echo "<div class='more-comments2' id='more-comments'>Mais</div>";
            echo "<input type='hidden' id='comentario-page' value='0' />";
            echo "<input type='hidden' id='comentario-count' value='".count($lista_comentarios)."' />";
            echo "<input type='hidden' id='ultimo-comentario' value='".$lista_comentarios[0][0]."' />";
            
        }
        echo "<div class='clear'></div>";
    }
    
    private function topUsuario($tipo)
    {
        /**
         *  Mostra informações dos três melhores usuários no accordi.
         */
        
        $ranking = new ranking();
        $root = $_SESSION['root'];
        switch($tipo)
        {
            case 0:
                $r = $ranking->rankingArtista();
                if($r === true)
                {
                    foreach($ranking->rartista as $i => $v)
                    {
                        $u = new usuario($v[3]);
                        echo "<div class='thumb-box-portal'>";
                        echo "<div class='left'>";
                        if($u->imagem != '0')
                            echo "<img class='thumb' src='$root/imagens/profiles/".$u->id."_thumb.$u->imagem' alt='$u->nome' />";
                        else
                            echo "<img class='thumb' src='$root/imagens/profiles/noavatar_thumb.png' alt='$u->nome' />";
                        echo "</div>";
                        echo "<div class='left'>";
                        echo "<span class='nome-portal'>$u->nome</span>";
                        echo "<p><a href='$root/profile/$u->login' class='link-portal'>(Ver Perfil)</a></p>";
                        echo "</div>";
                        echo "</div>";
                        
                        
                        if($i == 2)
                            break;
                    }
                }
                break;
            case 1:
                $r = $ranking->rankingContratante();
                if($r === true)
                {
                    foreach($ranking->rcontratante as $i => $v)
                    {
                        $u = new usuario($v[3]);
                        echo "<div class='thumb-box-portal'>";
                        echo "<div class='left'>";
                        if($u->imagem != '0')
                            echo "<img class='thumb' src='$root/imagens/profiles/".$u->id."_thumb.$u->imagem' alt='$u->nome' />";
                        else
                            echo "<img class='thumb' src='$root/imagens/profiles/noavatar_thumb.png' alt='$u->nome' />";
                        echo "</div>";
                        echo "<div class='left'>";
                        echo "<span class='nome-portal'>$u->nome</span>";
                        echo "<p><a href='$root/profile/$u->login' class='link-portal'>(Ver Perfil)</a></p>";
                        if($u->website != "")
                                echo "<p><a href='http://$u->website' class='link-portal'>(Ver Website)</a></p>";
                        echo "</div>";
                        echo "</div>";
                        
                        
                        if($i == 2)
                            break;
                    }
                }
                break;
        }
    }
    
    private function loadTwitter()
    {
        /**
         *  Carrega informações do twitter
         */
        
        echo "<script src=\"http://widgets.twimg.com/j/2/widget.js\"></script>";
        echo "<script>
               new TWTR.Widget({
                 version: 3,
                 type: 'profile',
                 rpp: 3,
                 interval: 6000,
                 width: 210,
                 height: 190,
                   theme: {
                   shell: {
                   background: '#0e90d0',
                   color: '#ffffff'
                   },
                   tweets: {
                   background: '#fff',
                   color: '#3298d3',
                   links: '#036095'
                   }
                 },
                 features: {
                   scrollbar: false,
                   loop: false,
                   live: true,
                   hashtags: true,
                   timestamp: true,
                   avatars: false,
                   behavior: 'all'
                 }
               }).render().setUser('devaccordi').start();
               </script>";
    }
    
}

?>