<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: msg.php - Sistema de mensagens dos contato
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Sistema para visualizar todas as mensagens do usuário com seus contatos.
 *    Principais funções:
 *      - Carrega as mensagens do usuário
 *      - Gerencia mensagens do usuário
 * 
 *********************************************/

if (IN_ACCORDI != true) {
    exit();
}

class contatos_inbox
{
    private $contato;
    private $lista;
    private $nome;
    private $c_id;
    private $key;
    private $tipo;
    
    
    public function __construct()
    {
        /**
         *  Método construtor
         *  Detectamos a URL e verificamos se o parametro msg foi passado pelo contato.
         */
        if(URL_3 == "")
            $url = URL_2;
        else
            $url = URL_3;
        
        /**
         *  Pegamos a Key para utilizar de referência
         */
        if($url != "")
            $this->key = $url;
        if($_GET['key'] != "")
            $this->key = $_GET['key'];
        
        /**
         *  Iniciamos nosso processo
         */
        $this->contato = new contato();
        $this->switchStatus();
    }
    
    private function switchStatus()
    {
        /*
         * Define quais mensagens vamos pegar de acordo com a URL.
         */
        switch($this->key)
        {
            case "atuantes": $this->tipo = 0; $this->nome = "atuantes"; $this->pegarLista(); break;
            case "fechados": $this->tipo = 1; $this->nome = "fechados"; $this->pegarLista(); break;
            case "enviados": $this->tipo = 2; $this->nome = "enviados"; $this->pegarLista(); break;
            case "recebidos": $this->tipo = 3; $this->nome = "recebidos"; $this->pegarLista(); break;
            case "show": $this->showContato(); break;
            default: $this->tipo = 0; $this->nome = "atuantes"; $this->pegarLista(); break;
        }
    }
    
    private function pegarLista()
    {
        /*
         * Pega a requisição da lista de mensagens e formata as informações adequadamente.
         */
        
        $this->contato->getUser($_SESSION['id_usuario'],$_SESSION['tipo_usuario']);
        $this->lista = $this->contato->listar_contatos($this->tipo);
        $this->showLista();
    }
    
    private function showLista()
    {
        /**
         * Mostra a lista em HTML
         */
        echo "<div class='default-box2' id='lista-contato-wrap'>";
        echo "<div class='gra_top1'>Contatos $this->nome</div>";
        switch($this->nome)
        {
            case "atuantes":
                echo "<div class='info'>Os contatos abaixo foram aprovados ainda estão em validade com a data de expiração.</div>";
               break;
            case "fechados":
                echo "<div class='info'>Os contatos abaixo são os expirados e recusados por você.</div>";
                break;
            case "enviados":
                if($_SESSION['tipo_usuario'] == 1)
                    echo "<div class='info'>Veja os contratantes para quem você já enviou seu curriculum.</div>";
                else
                    echo "<div class='info'>Veja os artistas para quem você já enviou um pedido de contato.</div>";
                break;
            case "recebidos":
                if($_SESSION['tipo_usuario'] == 1)
                    echo "<div class='info'>Veja todos os pedidos de contato que você recebeu.</div>";
                else
                    echo "<div class='info'>Veja os curriculums que os artistas enviaram para você.</div>";
                break;
        }
        echo "<ul>";
        echo "<li class='title-list-contato-msg'>";
        echo "<div class='title-contato-list' id='tcl-assunto'>Assunto</div>";
        echo "<div class='title-contato-list'>Remetente</div>";
        echo "<div class='title-contato-list'>Receptor</div>";
        echo "<div class='title-contato-list'>Data</div>";
        echo "<div class='title-contato-list2'>Status</div>";
        echo "</li>";
        
        // Imprimir mensagens
        if($this->lista != false)
        {
            foreach($this->lista as $i => $v)
            {
                echo "<li class='tcl-list'>";
                echo "<a href='".$_SESSION['root']."/contato/inbox/show&id=$v[3]'>";
                echo "<div class='text-contato-list-a'>$v[0]</div>";
                echo "<div class='text-contato-list'>$v[5]</div>";
                echo "<div class='text-contato-list'>$v[4]</div>";
                echo "<div class='text-contato-list'>$v[1]</div>";
                echo "<div class='text-contato-list2'>$v[2]</div>";
                echo "</a>";
                echo "<li>";
            }
        }
        else
            echo "<li class='tcl-list'><div class='text-contato-list-error'>Nenhum contato encontrado nesta categoria.</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    private function showContato()
    {
        /**
        *
        * @return void
        */
        $this->contato->getId($_GET['id']);
        $registro = $this->contato->pegarInfo();
        if($registro == "")
            echo "<div class='warn-super'>O contato não foi encontrado</div>";
        elseif($registro[1] != $_SESSION['id_usuario'] && $registro[2] != $_SESSION['id_usuario'])
            echo "<div class='warn-super'>Você não tem permissão para visualizar esse contato</div>";
        else
        {
        /**
         *  Conteúdo do $registro
         *  0 - id
         *  1 - id_remetente
         *  2 - id_receptor
         *  3 - assunto
         *  4 - data
         *  5 - valor
         *  6 - descricao
         *  7 - id_artista
         *  8 - id-contratante
         *  9 - termos
         *  10 - status
         *  11 - data_insercao
         *  12 - nome_remetente
         *  13 - nome_receptor
         *  14 - nome_artista
         *  15 - nome_contratante
         *  16 - status_string
         *  17 - artista_login
         *  18 - contratante_login
         *  19 - is_read
         * 
         */
        $this->c_id = $registro[0];
        
        /**
         *  Alteramos o status do contrato para lido após comentário.
         */
        if(($registro[19] == 1 || $registro[19] == 3 || $registro[19] == 5) && $_SESSION['id_usuario'] == $registro[2])
            $this->contato->alterarNotificacao(0);
        elseif(($registro[19] == 2 || $registro[19] == 4) && $_SESSION['id_usuario'] == $registro[1])
            $this->contato->alterarNotificacao(0);
        
        $this->contato->getUser($_SESSION['id_usuario']);
        $a = $this->contato->countNotificacoes();
        echo "<script type='text/javascript'>b = new commentsClass(); b.reloadComments();</script>";
        echo "<div class='opc-box'></div>";
        echo "<div class='ajax-box-edit'></div>";
        echo "<div id='bci-main-wrap'>";
        echo "<div class='default-box2' id='bci-main'>";
        echo "<input type='hidden' value='$registro[0]' id='contato-id'>";
        echo "<div class='gra_top1'>Informações do Contato</div>";
       //if($_SESSION['tipo_usuario'] == 2 && $registro[10] == 2 && $registro[2] == $_SESSION['id_usuario'])
            //$this->carregaCurriculum();
        if($registro[10] == 2 && $registro[2] == $_SESSION['id_usuario'])
            echo "<div class='bci-aceita'>Você recebeu esse pedido de contato de <strong>$registro[12]</strong>, <strong>$registro[11]</strong>.
            <div class='btn-c-green' id = 'btn-c-green'>Aprovar</div><div class='btn-c-red' id = 'btn-c-red'>Recusar</div></div>";
        echo "<div class='body-contato-info'>";
        echo "<ul class='bci-ul'>";
        echo "<li class='bci-title'>Informações Gerais</li>";
        echo "<li class='bci-list'><label class='bci-label'>Assunto</label> $registro[3]</li>";
        if($registro[12] == $registro[14])
        {
            echo "<li class='bci-list'><label class='bci-label'>Remetente</label> <a href='".$_SESSION['root']."/profile/$registro[17]' class='link-blue' >$registro[12]</a> <span class='color-blue'>(Artista)</span></li>";
            echo "<li class='bci-list'><label class='bci-label'>Receptor</label> <a href='".$_SESSION['root']."/profile/$registro[18]' class='link-blue'>$registro[13]</a> <span class='color-red'>(Contratante)</span></li>";
        }
        else
        {
            echo "<li class='bci-list'><label class='bci-label'>Remetente</label><a href='".$_SESSION['root']."/profile/$registro[18]' class='link-blue'> $registro[12]</a> <span class='color-red'>(Contratante)</span></li>";
            echo "<li class='bci-list'><label class='bci-label'>Receptor</label> <a href='".$_SESSION['root']."/profile/$registro[17]' class='link-blue'>$registro[13]</a> <span class='color-blue'>(Artista)</span></li>";
        }
        echo "</ul>";
        echo "<ul class='bci-ul'>";
        echo "<li class='bci-title'>Informações do Contato</li>";
        echo "<li class='bci-list'><label class='bci-label'>Data de Expiração</label> $registro[4]</li>";
        echo "<li class='bci-list'><label class='bci-label'>Valor Do Contato</label> $registro[5]</li>";
        echo "<li class='bci-list'><label class='bci-label'>Status</label><span id = 'status-change'> $registro[16]</span></li>";
        echo "</ul>";
        echo "<div class='clear'></div>";
        echo "</div>";
        echo "<div class='bci-bar'><div id='bci-termos'>Termos Utilizados</div><div id='bci-descri'>Descrição</div>";
        if($registro[10] != 1 && $registro[10] != 2)
        echo "<div id='bci-cancel'>Cancelar</div>";
        echo "<div class='clear'></div></div>";
        echo "</div>";
        echo "<div id='bci-hide-content'>";
        echo "<div class='hidden' id='hide-termos'>";
        echo "<div class='gra_top1'>Termos</div><div class='info'>Segue abaixo os termos nos quais o contratate estabeleceu o contato.</div>";
        echo "<div class='bci-content-hide'>".($registro[9])."</div></div>";
        echo "<div class='hidden' id='hide-descricao'>";
        echo "<div class='gra_top1'>Descrição</div><div class='info'>Descrição geral do contato.</div>";
        echo "<div class='bci-content-hide'>".nl2br($registro[6])."</div></div>";
        echo "</div>";
        echo "</div>";
        if($registro[10] != 1)
        {
        echo "<div class='default-box2' id='mci-main'>";
        echo "<div class='gra_top1'>Mensagens</div>";
        echo "<div class='info3'>As mensagens postadas aqui não podem ser editadas ou deletadas</div>";
        $this->recadoManage();
        echo "</div>";
        }
        }
    }
    
    private function recadoManage()
    {
        /**
         * Mostra os recados do contato.
         */
        $recados = new comentarios();
        $msg = $recados->listarComentario($this->c_id,3);
        echo "<div class='mci-post' id='bx-cmt'>";
        echo "<textarea id='text-comment-n' class='mci-txt'></textarea>";
        echo "<input type='button' class='btn' id='comment-button4' value='Escrever' /><span id='c-error'></span>";
        echo "</div>";
        if($msg != "")
        {
            foreach($msg as $i => $v)
            {
                echo "<div class='mci-msg-box' id='comentario-box-t-$v[0]'>";
                echo "<div class='mci-msg-header'>$v[7] - $v[3]</div>";
                echo "<div class='mci-msg-img'>";
                if ($v[6] != "0")
                echo "<div class='thumb-img'><a href='".$_SESSION['root']."/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='".$_SESSION['root']."/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                else
                echo "<div class='thumb-img'><a href='".$_SESSION['root']."/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
                echo "</div>";
                echo "<div class='mci-msg-content'>";
                echo "<div id='content-$v[0]'>$v[2]</div>";
                echo "</div>";
                echo "</div>";                
            }
            echo "<div id='results-comments-more'><div id='results-c-m0'></div></div>";
            if(count($msg) >= 20)
            {
                echo "<div class='more-comments' id='more-comments'>Mais</div>";   
            }
            echo "<input type='hidden' id='comentario-page' value='0' />";
            echo "<input type='hidden' id='comentario-count' value='".count($msg)."' />";
            echo "<input type='hidden' id='ultimo-comentario' value='".$msg[0][0]."' />";
        }

    }
}

?>