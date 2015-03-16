<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: profile.php - Pegar informações relaciondas com o perfil.
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

class AJAXmusic extends AJAX
{
    /*
     *  @id
     *  INT - [ ID da música ]
     */    
    protected $id;
    
    /*
     *  @usuario
     *  OBJETO - [ Instância da classe usuário ]
     */
    private $usuario;
      
    /*
     *  @evento
     *  OBJETO - [ Instância da classe evento ]
     */
    private $evento;
    
    /*
     *  @musica
     *  OBJETO - [ Instância da classe musica ]
     */
    private $musica;
  
        
    public function getId($id)
    {
        /*
         *  Pega o ID da música da qual será pego as informações.
         *  Valor de retorno: BOOL
         */
        if(is_null($id) || !is_numeric($id))
            return false;
        else
            $this->id = $id;
            return true;
    }
    
    public function musicComment($tipo,$page)
    {
      /*
       *  Mostra 20 comentários na página da música.
       *  @tipo - INT [ Tipo de comentário que estamos buscando ]
       *  @page - INT [ Número de início para o limit dos comentários ]
       */ 
       $root = $_SESSION['root'];   
       if(is_null($this->id) || is_null($this->token) || !$this->ajax_true())
           return false;
       $c = $this->showComment($tipo,$page);
       
       /*
        *  Template do comentário para exibição
        */
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
                echo "<div class='comment-box-p2' id='comentario-box-t-$v[0]'>";
                echo "<input type='hidden' class='cmt-id' value='$v[0]' />";
                echo "<div class='title-c'><a href='$root/profile/$v[5]' class='title-c-a'>$v[7]</a> - $v[3]";
                if ($v[4] == $v[9])
                echo "<span class='autor-badge'>Autor da Música</span>";
                echo "</div>";
                echo "<div class='box-img-c'>";
                if ($v[6] != "0")
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                else
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
                echo "</div>";
                echo "<div class='comment-content2'>";
                echo "<input type='hidden' id='content-ajax-$v[0]' value='$v[8]' />";
                echo "<div id='content-$v[0]'>$v[2]";
                if ($ec != true && $_POST['mode2'] == 'ecomment' && $_POST['cid'] == $v[0])
                echo "<p><span class='side-tip'>Falha ao alterar o comentário.</span></p>";
                echo "</div>";
                echo "</div>";
                echo "<div class='comment-footer3'>";
                if ($_SESSION['id_usuario'] == $v[9] || $_SESSION['id_usuario'] == $v[4])
                {
                echo "<span class='del-comment' id='dc2-$v[0]'>Deletar</span>";
                if ($_SESSION['id_usuario'] == $v[4])
                echo "<span class='edit-comment' id='ec2-$v[0]'>Editar</span>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            unset($array);
            return;   
    }
    
    public function musicReloadComments($maxid,$idr)
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
        $new =  $c->loadNew($maxid,1,$idr);        
        if($new == 0)
            return;
        
        // Carrega os novos comentários
        $this->id = $idr;
        $comentarios = $this->showComment(1,0,$new);
        
        echo "<div id='reload-ajax'>";
        echo "<div id='last-id'>".$comentarios[0][0]."</div>";
        $this->templateComment($comentarios); 
        echo "</div>";        
        
    }
    
    public function loadPage($tipo)
    {
        /**
         *   Carrega parte da página de música via AJAX.
         */
        
        include("includes/music/public2.php");
        echo "<div id='ajax-wrap'>";
        echo "<div id='music-sub-right'>";
        $html = new musica_interface($this->id,$tipo,true);   
        echo "</div>";
        echo "</div>";
    }
    
    public function loadInfo()
    {
        /**
         *  Carrega a página de informações da música.
         * 
         */
               
        include("includes/music/music2.php");
        echo "<div id='ajax-wrap'>";
        echo "<div id='ajax-content'>";
        $html = new musica_manage($this->id,true);
        echo "</div>";
        echo "</div>";
        
    }
    
    public function editMusica($step,$nome,$genero,$classificacao,$privacidade,$clipe)
    {
        /**
         *  Edita uma música
         */
        
        if($step == 0)
        {
            $this->loadEdit();
            return;
        }
        elseif($step == 1)
        {
            // Edita a música
            try
            {
                $musica = new musicas();
                $musica->id = $this->id;
                $musica->editaMusica($nome, $genero, $classificacao, $privacidade, $clipe);
                echo json_encode(array("response" => "1"));
            }
            catch(Exception $a)
            {
                echo json_encode(array("response" => "0", "error" => $a->getMessage()));
                
            }
        }
    }
    
    public function loadEdit()
    {
        /**
         *  Carrega a interface para editar a música.
         * 
         */
        
        $musica = new musicas();
        $a = $musica->infoMusica($this->id,"private");
        if($a == false)
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='form-content'>";
            echo "<div class='padding2'>Não foi possível carregar a página para edição da música.</div>";
            echo "</div>";
            echo "</div>";
        }
        else
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1/div>";
            echo "<div id='form-content'>";
            echo "<form id='edit-musica-$musica->id' class='form-edit' method='post' action=''>";
            echo "<div class='box-cabecalho'>Editar Música</div>";
            echo "<ul class='edit-ul'>";
            
            echo "<li>";
            echo "<label class='edit-ul' for='titulo'>Título</label>";
            echo "<input type='text' class='edit-text' name='titulo' id='titulo' maxlength='20' value='$musica->nome' />";
            echo "<span id='nome-error'></span>";
            echo "</li>";
            
            echo "<li>";
            echo "<label class='edit-ul' for='genero'>Genero</label>";
            echo "<input type='text' class='edit-text' name='genero' id='genero' maxlength='15' value='$musica->genero' />";
            echo "</li>";
            
            echo "<li>";
            echo "<label class='edit-ul'>Classificação</label>";
            echo "<select class='edit-text' name='classificacao' id='classificacao'>";
            echo "<option "; if($musica->classificacao == '0'){ echo "selected='selected'"; } echo" value='0'>Livre</option>\n";
            echo "<option "; if($musica->classificacao == '10'){ echo "selected='selected'"; } echo" value='10'>Maiores de 10 anos</option>\n";
            echo "<option "; if($musica->classificacao == '14'){ echo "selected='selected'"; } echo" value='14'>Maiores de 14 anos</option>\n";
            echo "<option "; if($musica->classificacao == '16'){ echo "selected='selected'"; } echo" value='16'>Maiores de 16 anos</option>\n";
            echo "<option "; if($musica->classificacao == '18'){ echo "selected='selected'"; } echo" value='18'>Maiores de 18 anos</option>\n";
            echo "<option "; if($musica->classificacao == '21'){ echo "selected='selected'"; } echo" value='21'>Maiores de 21 anos</option>\n";
            echo "</select>";
            echo "</li>";
            
            echo "<li>";
            echo "<label class='edit-ul'>Clipe</label>";
            echo "<input type='text' class='edit-text' name='clipe' id='clipe' maxlength='11' value='$musica->clipe' />";
            echo "</li>";
            
            echo "</ul>";
            
            echo "<div class='mid-edita'>";
            echo "<p class='private1'>Acesso</p>";
            echo "<p class='private2'>Quem pode acessar essa música?</p>";
            echo "<ul class='edit-ul2'>";
            echo "<li>";
            echo "<label class='edit-ul2' >Todos</label>";
            echo "<input type='radio'  name='p-musica' id='p-musica-1' value='0' ";
            if ($musica->permissaon == "0")
                echo "checked='checked'";
            echo "/>";
            echo "<label class='edit-ul2' >Contatos</label>";
            echo "<input type='radio'  name='p-musica' id='p-musica-2' value='1' ";
            if ($musica->permissaon == "1")
                echo "checked='checked'";
            echo "/>";
            echo "</li>";
            echo "</ul>";
            echo "</div>";   
            echo "<input type='button'  id='music-btn-editar' class='btn' value='Atualizar' /><input type='button' class='btn' id='ajax-close' value='Fechar' />";
            echo "<div id='form-error' class='ms-edit-log'></div>";
            echo "</form>";
            
            echo "</div>";
            echo "</div>";
            
        }
        
    }
    
    public function musicDelete($step)
    {
        /**
         *  Deleta uma música
         * 
         */
        
        if($step == '0')
        {
            $musica = new musicas();
            $a = $musica->infoMusica($this->id,"private");
            if($a == false)
            {
                echo "<div id='ajax-wrap'>";
                echo "<div id='response'>0</div>";
                echo "</div>";
            }
            else
            {
                echo "<div id='ajax-wrap'>";
                echo "<div id='response'>1</div>";
                echo "<div id='form-content'>";
                echo "<form id='deleta-musica-$musica->id' class='form-edit' method='post' action=''>";
                echo "<div class='box-cabecalho'>Deletar Música</div>";
                echo "<p>Você tem certeza que deseja deletar a música: <strong>$musica->nome</strong></p>";
                echo "<input type='button'  id='music-btn-deletar'  class='btn' value='Confirmar' />";
                echo "<input type='button' class='btn' id='ajax-close' value='Fechar' />";
                echo "<span id='form-error'></span>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        }
        else
        {
            try
            {
                $musica = new musicas();
                $musica->id = $this->id;
                $musica->deletaMusica();
                echo "<div id='ajax-wrap'>";
                echo "<div id='response'>1</div>";
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
    }
    
    public function editLetra($letras)
    {
        /**
         *  Edita a letra das músicas
         */
        
        try
        {
            $musica = new musicas();
            $musica->id = $this->id;
            $nletra =  $musica->editaLetras($letras);            
            $nletra2 = nl2br($nletra);
            
            if($nletra2 == "")
            {
                $nletra2 = "Clique aqui para adicionar uma letra na música.";
            } 
            
            
            // Adiciona a classe de edição sobre a letra
            $nletra2 = "<div id='edit-letras' title='Clique para editar'>$nletra2</div>";
            
            echo json_encode(array("response" => "1", "letra" => $nletra2, "letranobr" => $nletra));
            
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => "0", "error" => $a->getMessage()));
        }
        
    }
    
    public function pesquisaMusica($nome,$genero,$page)
    {
        /**
         *  Carrega informações sobre músicas para serem pesquisas
         *  @param string $nome
         *  @param string $genero
         *  @param int $page
         * 
         */
        
        if(!is_numeric($page))
        {
            return;
        }
        
        try
        {
           $busca = new buscar();
           $musicas = $busca->filtroMusica($nome,$genero,$page);
           echo json_encode($musicas);
        }
        catch(Exception $a)
        {
            
        }
    }
    

}








?>