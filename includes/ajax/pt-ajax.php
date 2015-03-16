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

class AJAXportal extends AJAX
{
    /*
     *  @id
     *  INT - [ ID da notícia ]
     */    
    protected $id;
    
    /*
     *  @bid
     *  INT - [ ID do box ]
     */    
    protected $bid;
    
    /*
     *  @usuario
     *  OBJETO - [ Instância da classe usuário ]
     */
    private $usuario;
      
       
    public function getId($id)
    {
        /*
         *  Pega o ID da notícia do quals erá pego as informações.
         *  Valor de retorno: BOOL
         */
        if(is_null($id))
            return false;
        else
            $this->id = $id;
            return true;
    }
    
    public function getBid($id)
    {
        /*
         *  Pega o ID do box do quals erá pego as informações.
         *  Valor de retorno: BOOL
         */
        if(is_null($id))
            return false;
        else
            $this->bid = $id;
            return true;
    }
    
    public function novaNoticia()
    {
        /**
         *  Cria uma nova notícia.
         */
        
        $this->usuario = new usuario(); // A verificação no menu é feita via variáveis de sessão. Vamos utilizar isso aqui também.
        
        // Verifica se o usuário tem a permissão necessária para gerar o formulário.
        if($_SESSION['id_usuario'] == "" || $this->usuario->isAdmin() <= 0)
        {
            echo "<h3>Você não tem permissão para criar uma nova notícia.</h3>";
            return;
        }
        
        // Carrega o formulário para criação de notícias.
        echo "<div class='padding'>";
        echo "<form method='post' action='".$_SESSION['root']."/portal'  enctype=\"multipart/form-data\">";
        echo "<h3>Adicionar Nova Notícia</h3>";
        echo "<ul class='np-ul'>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Título</label>";
        echo "<input type='text' maxlength='50' name='ntc-titulo' class='np-txt' />";
        echo "<span class='np-ul'> // Insira o título da notícia</span>";
        echo "</li>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Resumo</label>";
        echo "<input type='text' maxlength='255' name='ntc-resumo' class='np-txt' />";
        echo "<span class='np-ul'> // Insira um resumo da notícia</span>";
        echo "</li>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Categoria</label>";
        echo "<input type='text' maxlength='50' name='ntc-categoria' class='np-txt' />";
        echo "</li>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Tags</label>";
        echo "<input type='text' maxlength='50' class='np-txt' name='ntc-tags' />";
        echo "<span class='np-ul'> // Separe as tag's por vírgula</span>";
        echo "</li>";   
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Imagem</label>";
        echo "<input type='file' name='ntc-img' />";
        echo "<span class='np-ul'> // Tamanho recomendado para a imagem: 600x150px.</span>";
        echo "</li>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'>Notícia</label>";
        echo "</li>";
        echo "<li class='np-ul'>";
        echo "<label class='np-ul'></label>";
        echo "<textarea name='ntc-texto' class='np-txt' ></textarea>";
        echo "</li>";
        echo "</ul>";
        echo "<input type='submit' class='login-button-input' name='ntc-add' value='Adicionar' />";
        echo "</form>";
        echo "</div>";        
        
    }
    
    public function deletarNoticia($id,$step)
    {
        /**
         *  Deleta uma notícia baseado em seu ID.
         */
        
        $portal = new portal();
        $portal->getId($id);
        try
        {
            $info = $portal->pegaInfo();
            switch($step)
            {
                case 0:
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='ajax-content'>";
                    echo "<div class='gra_top1'>Deletar Notícia</div>";
                    echo "<div class='padding-contato'>";
                    echo "<p>Você tem certeza que gostaria de deletar a notícia <strong>$info[1]</strong> permanentemente?</p>";
                    echo "<input type='button' id='noticia-delete2' class='btn' value='Deletar' />";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    break;
                case 1:
                    $portal->deletarNoticia();
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='ajax-content'>";
                    include("includes/wp-portal/portal.php");
                    $html = new portal_interface("lista","",true);
                    echo "</div>";
                    echo "</div>";
            }            
        }
        catch(Exception $a)
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='ajax-content'>";
            echo "<div class='gra_top1'>Erro</div>";
            echo "<div class='padding3'>Não foi possível deletar a notícia, verifique se você possui permissão ou tente novamente mais tarde.</div>";
            echo "</div>";
            echo "</div>";
        }
        
    }
    
    public function editarNoticia($id,$texto)
    {
        /**
         *  Edita a notícia de uma notícia, baseado em sua ID.
         *  @param int $id
         *  @param string $texto
         */
        
        $portal = new Portal();
        $portal->getId($id);
        try
        {            
            $portal->editarTexto($texto);            
            $info = $portal->pegaInfo();
            // Imprime os resultados da edição
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            echo "<div id='ajax-content'>$info[11]</div>";
            echo "</div>";
            
        }
        catch(Exception $a)
        {            
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='ajax-content'><div id='noticia-edit'></div></div>";
            echo "</div>";
        }
    }
    
     
    public function createBox($step,$tipo,$noticia)
    {
        /**
         *  Cria uma box a partir dos parametros estabelecidos
         *  @param int $step
         *  @param int $tipo
         *  @param int $noticia
         */
        
        
        switch($step)
        {
            case 1:
                echo "<div id='mp-wrap'>";
                echo "<div class='gra_top1'>Criação de Box</div>";
                echo "<div class='info'>As \"Box\" são caixas onde as notícias são exibidas na página principal do portal.";
                echo "<p>Para inserir coloque o nome da notícia desejada e o tipo de box que você deseja (veja os tipos).</p>";
                echo "</div>";                
                
                // Inicía formulário para adicionar as BOX
                echo "<div id='bx-cr-left'>";
                echo "<div class='bx-cr-tip'>Escolha um tipo de caixa</div>";
                
                // Mostramos os três tipos de 
                echo "<div class='box-choice' id='choicebx-1'><div id='box-mini1-cr' class='box-mini1-cr'>235px</div></div>";
                echo "<div class='box-choice' id='choicebx-2'><div id='box-mini2-cr' class='box-mini2-cr'>360px</div></div>";
                echo "<div class='box-choice' id='choicebx-3'><div id='box-mini3-cr' class='box-mini3-cr'>720px</div></div>";
                echo "<input type='hidden' id='bc-selected-box' value='' />";
                
                echo "</div>";
                echo "<div id='bx-cr-mid'>";
                echo "<div class='bx-cr-tip'>Escolha a notícia para a box</div>";
                
                echo "<div id='noticia-select-bx-wrap'>";
                echo "<div class='choice-tip'>Selecione a caixa antes de escolher a notícia.</div>";
                echo "</div>";
                echo "</div>";
                echo "<div id='bx-cr-right'>";
                echo "<div class='bx-cr-tip'>Visualize seu layout atual</div>";
                echo "<div id='noticia-layout-bx-wrap'>";
                $this->loadLayout();
                echo "</div>";
                
                echo "</div>";
                //
                
                echo "<div class='clear'></div>";
                echo "</div>";
                break;
            case 2:
                $portal = new Portal();
                try
                {
                    $portal->novaBox($tipo,$noticia);
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='ajax-content'>";
                    $this->loadLayout();
                    echo "</div></div>";
                }
                catch(Exception $ex)
                {
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>0</div>";
                    echo "<div id='ajax-content'>".$ex->getMessage()."</div>";
                    echo "</div>";                                       
                }
                break;
        }
    }
    
    public function loadLayout()
    {
        /** 
         *  Carrega o layout baseando-se no tipo de boxes existentes.
         */
        
        echo "<div id='layout-mini-cr'>";
        try
        {
            $portal = new Portal();
            foreach($portal->loadBox() as $i => $v)
            {
                try
                {
                    $portal->getId($v[1]);
                    $info = $portal->pegaInfo();
                    switch($v[2])
                    {

                            case 1: echo "<div class='box-mini-ex1' id='box-mini-$v[0]'><a href='#'><div class='delete-box-mini' id='dlb-$v[0]'></div></a>$info[1]</div>"; break;
                            case 2: echo "<div class='box-mini-ex2' id='box-mini-$v[0]'><a href='#'><div class='delete-box-mini' id='dlb-$v[0]'></div></a>$info[1]</div>"; break;
                            case 3: echo "<div class='box-mini-ex3' id='box-mini-$v[0]'><a href='#'><div class='delete-box-mini' id='dlb-$v[0]'></div></a>$info[1]</div>"; break;  
                    }                        
                }
                catch(Exception $ex)
                {
                    // Deleta a box caso ele não consiga pegar as informações da notícia
                    $portal->getBid($v[0]);
                    $portal->deleteBox();
                }
            }
            echo "<div class='clear'></div>";
        }
        catch(Exception $a)
        {
            
        }
        echo "</div>";
    }
    
    public function loadNoticias($page,$tipo)
    {
        /**
         *  Carrega as notícias em uma pequena box para o usuário selecionar 
         */
        
        try
        {
            $portal = new Portal();
            $lista = $portal->listaNoticias("",$page,1); // Pega a lista das últimas 20 notícias
            
            // Agora vamos alterar o layout só para verificar se estamos trocando de página ou exibindo o layout completo.
            switch($tipo)
            {
                case 0:
                    echo "<div class='box-layout-noticias'>";
                    echo "<div class='box-layout-noticias-header'>Notícias Disponíveis</div>";
                    echo "<div class='box-layout-list'>";
                    echo "<ul>";
                    foreach($lista as $i => $v)
                    {
                        echo "<li class='box-layout-list-li' id='list-noticia-$v[0]'>$v[1]</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                    // Adiciona o page UP caso o número de notícias seja superior a 20
                    if($portal->list_count > 20)
                    {
                        echo "<div class='box-layout-noticias-pup'>";
                        echo "<div id='bln-back'>Voltar</div><div id='bln-next'>Próximo</div>";
                        echo "</div>";
                        echo "<div class='clear'></div>";
                    }
                    echo "</div>";
                    echo "<input type='hidden' id='bxl-page' value='0' />";
                    break;
                case 1:
                    echo "<ul>";
                    foreach($lista as $i => $v)
                    {
                        echo "<li class='box-layout-list-li' id='list-noticia-$v[0]'>$v[1]</li>";
                    }
                    echo "</ul>";
                    break;
            }
            
        }
        catch(Exception $a)
        {
            echo "<div class='choice-tip'>Nenhuma notícia encontrada no momento</div>";
        }
        
    }
    
    public function deleteBox($id)
    {
        /**
         *  Interface para deletar box via AJAX
         *  @param $id
         */
        
        if($id == "" || !is_numeric($id))
            return;
        
        $portal = new Portal();
        try
        {
            $portal->getBid($id);
            $portal->deleteBox();
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
    
    
    public function loadCalendario($mes,$ano)
    {
        /**
         *  Carrega um calendário com os próximos eventos.
         *  @param int $mes
         */
        
        
        // Instancia as classes necessárias
        $evento = new eventos();
        $data_format = new dataformat();
        
        if(!is_numeric($mes) || !is_numeric($ano))
            return false;
        
        // Verifica se o mês é válido
        if($mes < 1 || $mes > 12)
            $mes = date("m");
        
        if(strlen($mes) == 1)
            $mes = "0".$mes;
        
         if($ano > date("Y")+10 || $ano < date("Y")-10)
           $ano = date("Y");
        
        // Lista os eventos do mês
        $eventos_mes = $evento->eventosMes(1,$mes,$ano);
        
        
        // Pega informações sobre a data
        $data_format->pegarData($ano."-".$mes."-01");
        $wd = $data_format->pegarWeekDay();
        $mes_nome = $data_format->nomeMes();
        $max_day = $data_format->pegarMaxMes($mes,$ano);
        $count_day = 1;
        $count_mes = $mes;
        if($mes < 10)
                $count_mes = $count_mes[1];
        
        // Imprime o calendário
        echo "<input type='hidden' value='".$count_mes."' id='mes-atual'>";   
        echo "<input type='hidden' value='".$ano."' id='ano-atual'>";
        echo "<div class='gra_top1'><span id='calendario-back2'><div id='seta_2'></div></span><strong>$mes_nome / $ano</strong><span id='calendario-next2'><div id='seta_1'></div></span></div>";        echo "<div class='calendario-space-t-small'>D</div>";
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
    }
    
    public function noticiaComment($tipo,$page)
    {
      /*
       *  Mostra 20 comentários da notícia.
       *  @tipo - INT [ Tipo de comentário que estamos buscando ]
       *  @page - INT [ Número de início para o limit dos comentários ]
       */ 
        
       $root = $_SESSION['root'];   
       if(is_null($this->id) || is_null($this->token) || !$this->ajax_true())
           return false;
       $c = $this->showComment($tipo,$page);
       
            if($c != "")
            {
              $this->templateComment($c);  
            }
       
       unset($c);   
    }
    
    
    protected function templateComment($array)
    {
      /*
       *  Template do comentário para exibição
       *  @array - Array [ Informações dos comentários ]
       */
            $root = $_SESSION['root'];
            echo "<div id='ajax-wrap'>";
            echo "<div id='comment-count'>".count($array)."</div>";
            echo "<div id='comment-list'>";
            foreach($array as $i => $v)
            {
                echo "<div class='comment-box-p-portal' id='comentario-box-t-$v[0]'>";
                echo "<input type='hidden' class='cmt-id' value='$v[0]' />";
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
            echo "</div>";
            echo "</div>";
            unset($array);
            return;   
    }
    
    public function portalReloadComments($maxid,$idr)
    {
        /**
         *  Carrega novos comentários
         *  @param int $maxid
         *  @param int $idr
         *  @param int $tipo 
         */
        
        if(!is_numeric($maxid) || !is_numeric($idr))
            return;
        
        // Procura por novos comentários
        $c = new comentarios();
        $new =  $c->loadNew($maxid,4,$idr);        
        if($new == 0)
            return;
        
        // Carrega os novos comentários
        $this->id = $idr;
        $comentarios = $this->showComment(4,0,$new);
        
        echo "<div id='reload-ajax'>";
        echo "<div id='last-id'>".$comentarios[0][0]."</div>";
        $this->templateComment($comentarios); 
        echo "</div>";        
        
    }
    
    
    

}








?>