<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: e-ajax.php - Requisições de AJAX por eventos.
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

class AJAXevent extends AJAX
{
    /*
     *  @id
     *  INT - [ ID do evento ]
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
         *  Pega o ID do usuário do quals erá pego as informações.
         *  Valor de retorno: BOOL
         */
        if(is_null($id))
            return false;
        else
            $this->id = $id;
            return true;
    }

    
    public function eventComment($tipo,$page)
    {
      /*
       *  Mostra 20 comentários no perfil do usuário.
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
                echo "<div class='comment-box-p' id='comentario-box-t-$v[0]'>";
                echo "<input type='hidden' class='cmt-id' value='$v[0]' />";
                echo "<div class='title-c'><a href='$root/profile/$v[5]' class='title-c-a'>$v[7]</a> - $v[3]";
                if($v[4] == $v[9])
                    echo "<span class='autor-badge'>Gerente do Evento</span>";
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
                if($ec != true && $_POST['mode2'] == 'ecomment' && $_POST['cid'] == $v[0]) echo "<p><span class='side-tip'>Falha ao alterar o comentário.</span></p>";
                echo "</div>";
                echo "</div>";
                echo "<div class='comment-footer'>";
                if($_SESSION['id_usuario'] == $v[9] || $_SESSION['id_usuario'] == $v[4])
                {
                   echo "<span class='del-comment' id='dc3-$v[0]'>Deletar</span>";
                   if($_SESSION['id_usuario'] == $v[4])
                   echo "<span class='edit-comment' id='ec3-$v[0]'>Editar</span>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            unset($array);
            return;   
    }
    
    public function eventsReloadComments($maxid,$idr)
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
        $new =  $c->loadNew($maxid,2,$idr);        
        if($new == 0)
            return;
        
        // Carrega os novos comentários
        $this->id = $idr;
        $comentarios = $this->showComment(2,0,$new);
        
        echo "<div id='reload-ajax'>";
        echo "<div id='last-id'>".$comentarios[0][0]."</div>";
        $this->templateComment($comentarios); 
        echo "</div>";        
        
    }
    
    public function deletaEvento($step)
    {
        /**
         *  Deleta um evento
         *  @param int $step
         */
        
        
        if($this->id == "" || !is_numeric($this->id))
            return;
        
        if($_SESSION['id_usuario'] == "")
            return;
        
        $evento = new eventos();
        $evento->id = $this->id;
        $a = $evento->pegaInfo();
        if($a == false)
        {
            // Não foi possível localizar o evento
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='content'>";
            echo "<div class='box-cabecalho'>Deletar Evento</div>";
            echo "<p><strong>Não foi possível localizar o evento</strong></p>";
            echo "</div>";
            echo "</div>";
            return;
        }
        
        switch($step)
        {
            case 0:
                echo "<div id='ajax-wrap'>";
                echo "<div id='response'>1</div>";
                echo "<div id='content'>";
                echo "<input type='hidden' id='evento-d-id' value='$evento->id' />";
                echo "<div class='box-cabecalho'>Deletar Evento</div>";
                echo "<p>Deseja deletar o evento <strong>$evento->nome</strong>?</p>";
                echo "<p>O evento atualmente está <strong> $evento->status_string </strong></p>";
                echo "<input type='button' value='Deletar' class='btn' id='dbuttonacao' />";
                echo "<span id='response-div'></span>";
                echo "</div>";
                echo "</div>";
                break;
            case 1:
                try
                {
                    $a = $evento->deletaEvento();
                    echo "<div class='box-cabecalho'>Deletar Evento</div>";
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='content'>";
                    echo "<div class='evento-info2-title'>O evento $e->nome foi deletado com sucesso.</div>";
                    echo "<div class='evento-info2-sub'><a href='".$_SESSION['root']."/eventos/' class='nochange2'>(Voltar)</a></div>";
                    echo "</div>";
                    echo "</div>";
                }
                catch(Exception $a)
                {
                    echo "<div id='ajax-wrap'>";
                    echo "<div id='response'>0</div>";
                    echo "<div id='content'>";
                    echo "<p>Parece que ocorreu um erro ao deletar o evento, tente novamente.</p>";
                    echo "<input type='hidden' id='evento-d-id' value='$evento->id' />";
                    echo "<input type='button' value='Deletar' class='btn' id='dbuttonacao' />";
                    echo "</div>";
                    echo "</div>";                    
                }
        }
        
    }
    
    public function deleteParticipante($participante)
    {
        /**
         *  Deleta um participante do evento.
         */
        
        if(!is_numeric($participante))
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='error'>Participante não é numérico</div>";
            echo "</div>";
            return;
        }
        
        if($this->id == "")
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='error'>Evento não declarado</div>";
            echo "</div>";
            return;
            
        }
        
        try
        {
            $e = new eventos();
            $e->id = $this->id;
            $e->deletaParticipante($participante);
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
    
    public function editarEvento()
    {
        /**
         *  Abre a aba para realizar a edição do evento.
         */
        
        $root = $_SESSION['root'];
        if($this->id == "")
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";            
            echo "<div id='content'><p>Não foi possível abrir a página para editar o evento.</p></div>";
            echo "</div>";
        }
        
        try
        {
            $e = new eventos();
            $e->id = $this->id;
            $e->pegaInfo();
        }
        catch(Exception $a)
        {
            throw new Exception($a->getMessage());
        }
        
        echo "<div id='ajax-wrap'>";
        echo "<div id='response'>1</div>";
        echo "<div id='content'>";
        // Início do layout
        echo "<div id='edit-evento-wrap'>";
        echo "<form method='post' action='$root/eventos' enctype='multipart/form-data' id='edita-evento-f' target='upimge'>";
        echo "<input type='hidden' id='data-info' name='data-info-e' value='$e->id' />";
        echo "<div class='box-cabecalho'>Editar</div>";
        echo "<div class='info'>Sinta-se a vontade para alterar as informações do seu evento.</div>";
        echo "<div class='event-box-add'>";
        echo "<h6>Descrição</h6>";
        echo "<ul class='evento-ul'>";
        echo "<li><label class='edit-ul-mini'>Nome</label>";
        echo "<input type='text' class='edit-text2' name='nome' value='$e->nome' id='nome' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Gênero:</label><input value='$e->genero' autocomplete='off' type='text' class='edit-text2' name='genero' id='genero-e' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Descrição</label></li>";
        echo "<li><textarea name='descricao' id='descricao' class='edit-text'>$e->descricao</textarea></li>";
        echo "<li><label class='edit-ul-mini'>Banner:</label></li>";
        echo "<li><input class='file-input' type='file' name='banner-e' id='banner-e-envia'/></li>";
        echo "<li><p class='smalltip2'>Max: 3mb - 600x108 - PNG,JPG,JPEG e GIF</p></li>";
        echo "<li><iframe name='upimge' id='upimge' style='display: none;'></iframe></li>";
        echo "<li><label class='edit-ul-mini'>Data</label></li><li>";
        echo "<div class='edit-ul-mini'>";
        echo "<input type='text' class='edit-text22' value='$e->dia' size='2' maxlength='2' name='n-dia' id='n-dia' />";
        echo "<select name='n-mes' class='edit-text' id='n-mes'>";
        
        echo "<option value='01' "; if($e->mes == '01') echo "selected='selected'"; echo ">Janeiro</option>";
        echo "<option value='02' "; if($e->mes == '02') echo "selected='selected'"; echo ">Fevereiro</option>";
        echo "<option value='03' "; if($e->mes == '03') echo "selected='selected'"; echo ">Março</option>";
        echo "<option value='04' "; if($e->mes == '04') echo "selected='selected'"; echo ">Abril</option>";
        echo "<option value='05' "; if($e->mes == '05') echo "selected='selected'"; echo ">Maio</option>";
        echo "<option value='06' "; if($e->mes == '06') echo "selected='selected'"; echo ">Junho</option>";
        echo "<option value='07' "; if($e->mes == '07') echo "selected='selected'"; echo ">Julho</option>";
        echo "<option value='08' "; if($e->mes == '08') echo "selected='selected'"; echo ">Agosto</option>";
        echo "<option value='09' "; if($e->mes == '09') echo "selected='selected'"; echo ">Setembro</option>";
        echo "<option value='10' "; if($e->mes == '10') echo "selected='selected'"; echo ">Outubro</option>";
        echo "<option value='11' "; if($e->mes == '11') echo "selected='selected'"; echo ">Novembro</option>";
        echo "<option value='12' "; if($e->mes == '12') echo "selected='selected'"; echo ">Dezembro</option>";     
        
        echo "</select>";
        echo "<select class='edit-text' name='n-ano' id='n-ano'>";
        $ano = date("Y");
        for($i = 0;$i <= 5; $i++)
        {
            $ano_now = $ano+$i;
            echo "<option value='$ano_now' ";
            if($e->ano == $ano_now)
               echo "selected='selected' ";
            echo ">";
            echo $ano_now;
            echo "</option>";
        }
        echo "</select>";
        echo "<span id='dia-error'></span>";
        echo "</li>";
        echo "<li><label class='edit-ul-mini'>Horário de início </label></li>";
        echo "<li>";
        echo "<div class='edit-ul-mini'>";
        echo "<select name='hora' id='e-hora'>";
        
        for ($cont = 0; $cont <= 23; $cont++)
        {
            if ($cont < 10)
                $cont = "0" . $cont;
            
            if ($e->horan == $cont)
                echo "<option value='$cont' selected='selected'>$cont</option>";
            else
                echo "<option value='$cont'>$cont</option>";
        }
        
        echo "</select>(horas) :";
        echo "<select name='minuto' id='e-minuto'>";
        
        for ($cont = 0; $cont <= 59; $cont++)
        {
            if ($cont < 10)
                $cont = "0" . $cont;
            
            if ($e->minuton == $cont)
                echo "<option value='$cont' selected='selected'>$cont</option>";
            else
                echo "<option value='$cont'>$cont</option>";
        }
        
        echo "</select>(minutos)";
        echo "</div>";
        echo "</li>";
        echo "</ul>";
        echo "</div>";
        echo "<div class='event-box-add' id='event-box-add2'>";
        echo "<h6>Localização</h6>";
        echo "<p class='smalltip2'>Digite um endereço válido para podermos gerar um mapa.</p>";
        echo "<ul  class='evento-ul'>";
        echo "<li><label class='edit-ul-mini'>Endereço</label>";
        echo "<input type='text' class='edit-text2' value='$e->logradouro' name='logradouro' id='logradouro' maxlength='90' /></li>";
        echo "<li><label class='edit-ul-mini'>Bairro</label>";
        echo "<input type='text' class='edit-text2' value='$e->bairro' name='bairro' id='bairro' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Cidade</label>";
        echo "<input type='text' class='edit-text2' value='$e->cidade' name='cidade' id='cidade' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Estado</label>";  
        echo "<select name='estado' id='estado' class='edit-text'>";
        echo "<option value='0'>Selecione o Estado</option>";
        echo "<option "; if ($e->estado == "ac") echo "selected='selected'"; echo " value='ac' id='ac' >Acre</option>";
        echo "<option "; if ($e->estado == "al") echo "selected='selected'"; echo " value='al' id='al' >Alagoas</option>";
        echo "<option "; if ($e->estado == "ap") echo "selected='selected'"; echo " value='ap' id='ap' >Amapá</option>";
        echo "<option "; if ($e->estado == "am") echo "selected='selected'"; echo " value='am' id='am' >Amazonas</option>";
        echo "<option "; if ($e->estado == "ba") echo "selected='selected'"; echo " value='ba' id='ba' >Bahia</option>";
        echo "<option "; if ($e->estado == "ce") echo "selected='selected'"; echo " value='ce' id='ce' >Ceará</option>";
        echo "<option "; if ($e->estado == "df") echo "selected='selected'"; echo " value='df' id='df' >Distrito Federal</option>";
        echo "<option "; if ($e->estado == "es") echo "selected='selected'"; echo " value='es' id='es' >Espirito Santo</option>";
        echo "<option "; if ($e->estado == "go") echo "selected='selected'"; echo " value='go' id='go' >Goiás</option>";
        echo "<option "; if ($e->estado == "ma") echo "selected='selected'"; echo " value='ma' id='ma' >Maranhão</option>";
        echo "<option "; if ($e->estado == "ms") echo "selected='selected'"; echo " value='ms' id='ms' >Mato Grosso do Sul</option>";
        echo "<option "; if ($e->estado == "mt") echo "selected='selected'"; echo " value='mt' id='mt' >Mato Grosso</option>";
        echo "<option "; if ($e->estado == "mg") echo "selected='selected'"; echo " value='mg' id='mg' >Minas Gerais</option>";
        echo "<option "; if ($e->estado == "pa") echo "selected='selected'"; echo " value='pa' id='pa' >Pará</option>";
        echo "<option "; if ($e->estado == "pb") echo "selected='selected'"; echo " value='pb' id='pb' >Paraíba</option>";
        echo "<option "; if ($e->estado == "pr") echo "selected='selected'"; echo " value='pr' id='pr' >Paraná</option>";
        echo "<option "; if ($e->estado == "pi") echo "selected='selected'"; echo " value='pi' id='pi' >Pernambuco</option>";
        echo "<option "; if ($e->estado == "rj") echo "selected='selected'"; echo " value='rj' id='rj' >Rio de Janeiro</option>";
        echo "<option "; if ($e->estado == "rn") echo "selected='selected'"; echo " value='rn' id='rn' >Rio Grande do Norte</option>";
        echo "<option "; if ($e->estado == "rs") echo "selected='selected'"; echo " value='rs' id='rs' >Rio Grande do Sul</option>";
        echo "<option "; if ($e->estado == "ro") echo "selected='selected'"; echo " value='ro' id='ro' >Rondônia</option>";
        echo "<option "; if ($e->estado == "rr") echo "selected='selected'"; echo " value='rr' id='rr' >Roraima</option>";
        echo "<option "; if ($e->estado == "sc") echo "selected='selected'"; echo " value='sc' id='sc' >Santa Catarina</option>";
        echo "<option "; if ($e->estado == "sp") echo "selected='selected'"; echo " value='sp' id='sp' >São Paulo</option>";
        echo "<option "; if ($e->estado == "se") echo "selected='selected'"; echo " value='se' id='se' >Sergipe</option>";
        echo "<option "; if ($e->estado == "to") echo "selected='selected'"; echo " value='to' id='to' >Tocantins</option>";    
        echo "</select></li>";
        echo "</ul>";
        echo "</div>";
        echo "<div class='event-box-add' id='event-box-add3'><h6>Participantes</h6>";
        echo "Adicione o login dos novos participantes, separados por ; (Ponto e vírgula).";
        echo "<ul  class='evento-ul' >";
        echo "<li><textarea  name='participantes' id='participantes-add' class='edit-text'></textarea></li>";
        echo "</ul>";
        echo "<div class='box-cabecalho'>Últimos seis participantes</div>";
        echo "<div class='lista-participantes-box'>";
        $a = $e->mostraMembros(1);
        if ($a != false)
        {
            echo "<ul class='wrap-lista-membros' id='".$e->id."'>";
            foreach ($a as $key => $user)
            {
                if($key == 6)
                    break;
                echo "<li><div class='thumb-box4' id='show-participantes-$user[3]'>";
                echo "<div class='delparticipante' title='Excluir' id='delparticipante-$user[3]-b'></div>";
                if ($user[1] != "0")
                    echo "<div class='thumb-img'><a href='$root/profile/$user[2]' ><img src='$root/imagens/profiles/$user[3]_thumb.$user[1]' class='thumb' alt='thumb' /></a></div> ";
                else
                    echo "<div class='thumb-img'><a href='$root/profile/$user[2]' ><img src='$root/imagens/profiles/noavatar_thumb.png' class='thumb' alt='thumb' /></a></div>";
                echo "<div>";
                echo "$user[0]";
                echo "</div></li>";
            }
            echo "<div class='clear'></div>";
            echo "</ul>";
        }
        echo "</div>";
        echo "<ul class='evento-ul'>";
        echo "<input type='hidden' id='e-edit-id' value='$e->id' />";
        echo "<li> <input type='submit' class='login-button-input' id='editbuttone' value='Editar' /></li>";
        echo "<li><span id='envia-error'></span></li>";
        echo "</ul>";
        echo "</form>";
        echo "</div>";      
        // Fim do layout
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    
    public function editaEvento($param)
    {
        /**
         *  Edita o conteúdo de um evento
         */
        
        try
        {            
            $e = new eventos();
            $e->id = $this->id;
            $e->pegaInfo();
            $e->editaEvento($param[0], $param[1], $param[2], $param[3], $param[4], $param[5], $param[6], $param[7], $param[8], $param[9], $param[10], $param[11], $param[12],$param[13]);            
            echo json_encode(array("response" => 1));
            
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => 0, "error" => $a->getMessage()));
        }
        
    }
    
    public function editaRede($rede,$valor)
    {
        /**
         *  Edita as informações de uma rede dentro do evento
         *  @param string rede
         *  @param string valor         *  
         */
        
        try
        {
            $e = new eventos();
            $e->id = $this->id;
            $resposta = $e->addRede($rede,$valor);
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>1</div>";
            echo "<div id='content'>$resposta</div>";
            echo "</div>";            
        }
        catch(Exception $a)
        {
            echo "<div id='ajax-wrap'>";
            echo "<div id='response'>0</div>";
            echo "<div id='content'></div>";
            echo "</div>";            
        }
    }
    
    public function criarEvento()
    {
        /**
         *  Abre o layout para criar um novo evento.
         */
        
        echo "<div id='ajax-wrap'>";
        echo "<div id='response'>1</div>";
        echo "<div id='content'>";
        echo "<form method='post' action=\"".$_SESSION['root']."/eventos\" enctype=\"multipart/form-data\" id='envia-evento' >";
        echo "<div class='box-cabecalho'>Adicionar</div>";
        echo "<div class='info'>Ao postar seus eventos no Accordi você ver as pessoas interessadas em visita-lo e mostrar os participantes que forem registrados no site.</div>";
        echo "<div class='event-box-add'>";
        echo "<h6>Descrição</h6>";
        echo "<ul class='evento-ul'>";
        echo "<li><label class='edit-ul-mini'>Nome</label>";
        echo "<input type='text' class='edit-text2' name='nome' id='nome' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Gênero:</label><input type='text' autocomplete='off' class='edit-text2' name='genero' id='genero-e' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Descrição</label></li>";
        echo "<li><textarea name='descricao' id='descricao' class='edit-text'></textarea></li>";
        echo "<li><label class='edit-ul-mini'>Banner:</label></li>";
        echo "<li><input class='file-input' type='file' name='banner-e' id='banner-e-envia'/></li>";
        echo "<li><p class='smalltip2'>Max: 3mb - 600x108 - PNG,JPG,JPEG e GIF</p></li>";
        echo "<li><iframe name='upimge' id='upimge' style='display: none;'></iframe></li>";
        echo "<li><label class='edit-ul-mini'>Data</label></li><li>";
        echo "<div class='edit-ul-mini'>";
        echo "<input type='text' class='edit-text22' size='2' maxlength='2' name='n-dia' id='n-dia' />";
        echo "<select name='n-mes' class='edit-text' id='n-mes'>";
        
        echo "<option value='01'>Janeiro</option>";
        echo "<option value='02'>Fevereiro</option>";
        echo "<option value='03'>Março</option>";
        echo "<option value='04'>Abril</option>";
        echo "<option value='05'>Maio</option>";
        echo "<option value='06'>Junho</option>";
        echo "<option value='07'>Julho</option>";
        echo "<option value='08'>Agosto</option>";
        echo "<option value='09'>Setembro</option>";
        echo "<option value='10'>Outubro</option>";
        echo "<option value='11'>Novembro</option>";
        echo "<option value='12'>Dezembro</option>";     
        
        echo "</select>";
        echo "<select class='edit-text' name='n-ano'>";
        $ano = date("Y");
        for($i = 0;$i <= 5; $i++)
        {
            $ano_now = $ano+$i;
            echo "<option value='$ano_now'>";
            echo $ano_now;
            echo "</option>";
        }
        echo "</select>";
        echo "<span id='dia-error'></span>";
        echo "</li>";
        echo "<li><label class='edit-ul-mini'>Horário de início </label></li>";
        echo "<li><div class='edit-ul-mini'>";
        echo "<select name='hora' id='e-hora'>";
        
        for ($cont = 0; $cont <= 23; $cont++)
        {
            if ($cont < 10)
                $cont = "0" . $cont;
            
            if ($evento->horan == $cont)
                echo "<option value='$cont' selected='selected'>$cont</option>";
            else
                echo "<option value='$cont'>$cont</option>";
        }
        
        echo "</select>(horas) :";
        echo "<select name='minuto' id='e-minuto'>";
        
        for ($cont = 0; $cont <= 59; $cont++)
        {
            if ($cont < 10)
                $cont = "0" . $cont;
            
            if ($evento->minuton == $cont)
                echo "<option value='$cont' selected='selected'>$cont</option>";
            else
                echo "<option value='$cont'>$cont</option>";
        }
        
        echo "</select>(minutos)</div>";
        echo "</li>";
        echo "</ul>";
        echo "</div>";
        echo "<div class='event-box-add' id='event-box-add2'>";
        echo "<h6>Localização</h6>";
        echo "<p class='smalltip2'>Digite um endereço válido para podermos gerar um mapa.</p>";
        echo "<ul  class='evento-ul'>";
        echo "<li><label class='edit-ul-mini'>Endereço</label>";
        echo "<input type='text' class='edit-text2' name='logradouro' id='logradouro' maxlength='90' /></li>";
        echo "<li><label class='edit-ul-mini'>Bairro</label>";
        echo "<input type='text' class='edit-text2' name='bairro' id='bairro' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Cidade</label>";
        echo "<input type='text' class='edit-text2' name='cidade' id='cidade' maxlength='25' /></li>";
        echo "<li><label class='edit-ul-mini'>Estado</label>";  
        echo "<select name='estado' id='estado' class='edit-text'>";
        echo "<option value='0'>Selecione o Estado</option>";
        echo "<option value='ac' id='ac' >Acre</option>";
        echo "<option value='al' id='al' >Alagoas</option>";
        echo "<option value='ap' id='ap' >Amapá</option>";
        echo "<option value='am' id='am' >Amazonas</option>";
        echo "<option value='ba' id='ba' >Bahia</option>";
        echo "<option value='ce' id='ce' >Ceará</option>";
        echo "<option value='df' id='df' >Distrito Federal</option>";
        echo "<option value='es' id='es' >Espirito Santo</option>";
        echo "<option value='go' id='go' >Goiás</option>";
        echo "<option value='ma' id='ma' >Maranhão</option>";
        echo "<option value='ms' id='ms' >Mato Grosso do Sul</option>";
        echo "<option value='mt' id='mt' >Mato Grosso</option>";
        echo "<option value='mg' id='mg' >Minas Gerais</option>";
        echo "<option value='pa' id='pa' >Pará</option>";
        echo "<option value='pb' id='pb' >Paraíba</option>";
        echo "<option value='pr' id='pr' >Paraná</option>";
        echo "<option value='pi' id='pi' >Pernambuco</option>";
        echo "<option value='rj' id='rj' >Rio de Janeiro</option>";
        echo "<option value='rn' id='rn' >Rio Grande do Norte</option>";
        echo "<option value='rs' id='rs' >Rio Grande do Sul</option>";
        echo "<option value='ro' id='ro' >Rondônia</option>";
        echo "<option value='rr' id='rr' >Roraima</option>";
        echo "<option value='sc' id='sc' >Santa Catarina</option>";
        echo "<option value='sp' id='sp' >São Paulo</option>";
        echo "<option value='se' id='se' >Sergipe</option>";
        echo "<option value='to' id='to' >Tocantins</option>";    
        echo "</select></li>";
        echo "</ul>";
        echo "</div>";
        echo "<div class='event-box-add' id='event-box-add3'><h6>Participantes</h6>";
        echo "Adicione o login dos novos participantes, separados por ; (Ponto e vírgula).";
        echo "<ul  class='evento-ul' >";
        echo "<li><textarea  name='participantes' id='participantes-add' class='edit-text'></textarea></li>";
        echo "</ul>";
        echo "<div class='clear'></div>";
        echo "<ul class='evento-ul'>";
        echo "<li> <input type='submit' class='btn' value='Adicionar' name='evento-add-btn' id='rbuttonacao' />";
        echo "<li><span id='envia-error'></span></li>";
        echo "</ul>";
        echo "</form>";
        echo "</div>";      
        echo "</div>";
        echo "</div>";
    }
    
    public function loadEventosParticipante($page)
    {
        /**
         *  Pega mais eventos relacionados.
         */
        
        try
        {
            $evento = new eventos();
            $eventos_participa = $evento->eventosAll(0,$_SESSION['id_usuario'],$page);
            echo json_encode(array("response" => 1,"itens" => $eventos_participa));            
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => 0,"error" => $a->getMessage()));            
        }
        
        
    }
    

}








?>