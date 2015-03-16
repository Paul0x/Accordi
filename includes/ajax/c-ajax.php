<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: c-ajax.php - Requisições AJAX dos contatos;
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

class AJAXcontato extends AJAX
{
    /*
     *  @id
     *  INT - [ ID do contato ]
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
    
    private $contato;
        
    public function getId($id)
    {
        /**
         *  Pega o ID do usuário do quals erá pego as informações.
         *  @param INT $id
         *  @return bool
         */
        if(is_null($id))
            return false;
        else
            $this->id = $id;
            return true;
    }
   
    
    public function contatoComment($tipo,$page)
    {
      /**
       *  Mostra 20 comentários no contato.
       *  @param INT $tipo
       *  @param INT $page
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
      /**
       *  Template do comentário para exibição
       *  @param array $array
       */
            $root = $_SESSION['root'];
            echo "<div id='ajax-wrap'>";
            echo "<div id='comment-count'>".count($array)."</div>";
            echo "<div id='comment-list'>";
            foreach($array as $i => $v)
            {
                echo "<div class='mci-msg-box' id='comentario-box-t-$v[0]'>";
                echo "<input type='hidden' class='cmt-id' value='$v[0]' />";
                echo "<div class='mci-msg-header'>$v[7] - $v[3]</div>";
                echo "<div class='mci-msg-img'>";
                if ($v[6] != "0")
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='".$_SESSION['root']."/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                else
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
                echo "</div>";
                echo "<div class='mci-msg-content'>";
                echo "<div id='content-$v[0]'>$v[2]</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            unset($array);
            return;   
    }
    
    public function createContato($step,$id,$data="",$valor="",$descricao="",$assunto="")
    {
        /**
         *  Realiza a criação do contato.
         *  @param INT $step
         *  @param INT $id
         *  @param INT $data
         *  @param DOUBLE $valor
         *  @param STRING $descricao
         *  @param STRING $assunto
         */
        
        date_default_timezone_set('America/Sao_Paulo');
        if(!is_numeric($id) || !is_numeric($step))
            return false;
        if($step > 5 || $step < 0 )
            return false;
        
        switch($step)
        {
            case 0:
                $u = new usuario($id);
                switch($_SESSION['tipo_usuario'])
                {
                    case 1: $coisa = "curriculum"; break;
                    case 2: $coisa = "pedido de contato"; break;
                }
                echo "<div class='gra_top2'>Formulário de Contato</div>";
                echo "<div class='contato-box-content'>
                     <p>O sistema de contato do Accordi foi planejado para facilitar o contato profissional entre artistas e contratantes. Nele, ambas as partes podem mostrar seus termos e informações necessárias para realizar o contato formal.</p> 
                     <p>É extremamente recomendável que antes de realizar um contato no Accordi você leia os <strong>Termos de Contato</strong>.</p>
                     <div class='contato-box-content'><p>Você deseja realizar um $coisa para o usuário(a) <strong class='color-green'>$u->nome $u->sobrenome</strong> ?</p>
                     <input type='button' id='contato-next-step-1' class='login-button-input' value='Sim' /><input type='button' id='contato-close' class='btn' value='Não' />   
                     </div>
                     </div>";
                unset($u);
                break;
            case 1:
                $u = new usuario($id);
                switch($u->sexo)
                { 
                    case "m":
                        $termos = array("o","usuário");
                        break;
                    case "f":
                        $termos = array("a","usuária");
                        break;
                    default:
                        $termos = array("o(a)","usuario(a)");
                        break;
                }
                switch($u->tipo)
                {
                    case 1: $tipo = array("artista","curriculum"); break;
                    case 2: $tipo = array("contratatante","termos"); break;
                }
                echo "<div class='gra_top2'>Requerimento de Contato - 1ª Etapa</div>";
                echo "<div class='contato-box-content'><strong class='color-green'>$u->nome</strong> é um $tipo[0] e você pode visualizar seu $tipo[1] para obter maiores informações sobre $termos[0] $termos[1] antes de realizar o pedido.
                <div class='contato-box-content'><p><strong>Observação: Os termos ATUAIS do contratante serão levados em conta, ou seja, nenhuma alteração futura surtirá efeito no contato.</strong></p>
                        <p><ul>";
                if($u->tipo == 2)
                {
                    echo"<li class='pointer'><span class='color-green'><strong id='contato-next-step-4'>Termos d$termos[0] $u->nome</strong></span></li>";
                    echo"<li class='pointer'><span class='color-green'><strong id='contato-next-step-5'>Seu Curriculum</strong></span></li>";
                }
                else
                {
                    echo"<li class='pointer'><span class='color-green'><strong id='contato-next-step-4'>Seus termos</strong></span></li>";
                    echo"<li class='pointer'><span class='color-green'><strong id='contato-next-step-5'>Curriculum d$termos[0] $u->nome</strong></span></li>";
                }
                echo "</ul></p>";
                echo "<input type='button' id='contato-next-step-2' class='login-button-input' value='Avançar' /><input type='button' id='contato-close' class='btn' value='Fechar' /> ";
                
                echo "</div></div>";
                unset($termos,$u,$tipo);
                break;
                case 2:
                    switch($_SESSION['tipo_usuario'])
                    {
                    case 1: $coisa = "curriculum"; break;
                    case 2: $coisa = "contato"; break;
                    }
                    $data_f = new dataformat();
                    $year = date("Y")+4;
                    echo "<div class='gra_top2'>Requerimento de Contato - 2ª Etapa</div>";
                    echo "<div class='contato-box-content'>";
                    echo "Preencha os campos abaixo para finalizar sua requisição de $coisa. <strong>Campos com asterisco são obrigatórios</strong>";
                    echo "<div class='padding-contato'>";
                    echo "<ul>";
                    echo "<li class='contato-box-content2'>";
                    echo "<div class='color-green'><strong>Assunto*</strong></div>";
                    echo "<div><input type='text' id='cf-assunto' class='contato-input' value='$assunto' maxlength='22' /></div>";
                    echo "</li>";
                    echo "<li class='contato-box-content2'>";
                    echo "<div class='color-green'><strong>Data de Expiração*</strong></div>";
                    echo "<div><input type='text' id='cf-dia' class='contato-input2' maxlength='2' value='$data[2]' />
                    <select class='contato-input3' id='cf-mes'> 
                    <option value='01' "; if($data[1] == '01') echo "selected='selected'"; echo ">Janeiro</option>
                    <option value='02' "; if($data[1] == '02') echo "selected='selected'"; echo ">Fevereiro</option>
                    <option value='03' "; if($data[1] == '03') echo "selected='selected'"; echo ">Março</option>
                    <option value='04' "; if($data[1] == '04') echo "selected='selected'"; echo ">Abril</option>
                    <option value='05' "; if($data[1] == '05') echo "selected='selected'"; echo ">Maio</option>
                    <option value='06' "; if($data[1] == '06') echo "selected='selected'"; echo ">Junho</option>
                    <option value='07' "; if($data[1] == '07') echo "selected='selected'"; echo ">Julho</option>
                    <option value='08' "; if($data[1] == '08') echo "selected='selected'"; echo ">Agosto</option>
                    <option value='09' "; if($data[1] == '09') echo "selected='selected'"; echo ">Setembro</option>
                    <option value='10' "; if($data[1] == '10') echo "selected='selected'"; echo ">Outubro</option>
                    <option value='11' "; if($data[1] == '11') echo "selected='selected'"; echo ">Novembro</option>
                    <option value='12' "; if($data[1] == '12') echo "selected='selected'"; echo ">Dezembro</option>
                    </select>";
                    $data_f->selectAnos(20,"contato-input4","cf-ano",$year,$data[0]);
                    "</div>";
                    echo "</li>";
                    echo "<li class='contato-box-content2'>";
                    echo "<div class='color-green'><strong>Valor</strong></div>";
                    echo "<div><input type='text' id='cf-valor' class='contato-input' maxlength='10' value='$valor' /></div>";
                    echo "</li>";
                    echo "<li class='contato-box-content2'>";
                    echo "<div class='color-green'><strong>Descrição</strong></div>";
                    echo "<div><textarea id='cf-descricao' class='contato-input'>$descricao</textarea></div>";
                    echo "</li>";
                    if($_SESSION['tipo_usuario'] == 1)
                    echo "<li class='contato-box-content2'><div class='color-green'><strong>Seu curriculum será exibido para o contratante automáticamente.(Visualizar Curriculum)</strong></div>";
                    echo "</ul>";
                    echo "<input type='button' class='login-button-input' id='contato-next-step-3' value='Enviar'><input type='button' id='contato-close' class='btn' value='Fechar' /> ";
                    echo "</div>";
                    echo "</div>";
                    unset($data_f,$u,$year);
                break;
                case 3:
                    $contato = new contato();
                    $a = $contato->createContato($assunto,$id,$data,$valor,$descricao);
                    $u = new usuario($id);
                    if($a == true)
                    {
                        echo "<div class='gra_top2'>Requerimento de Contato - Sucesso</div>";
                        echo "<div class='contato-big'>Sucesso</div>";
                        echo "<div class='contato-box-content'><div class='contato-success-div'>Contato criado com sucesso</div>";
                        echo "<p>Você acabou de finalizar com sucesso a criação de um contato com $u->nome, agora é necessário aguardar até o usuário dar um parecer sobre sua requisição.</p>";
                        echo "</div>";
                        echo "<script>var tc = setTimeout(function(){ $(\".ajax-box-contato\").fadeOut(); },5000);</script>";
                    }
                    else
                    {   
                        echo "<input type='hidden' id='old-assunto' value='$assunto' />";
                        echo "<input type='hidden' id='old-id' value='$id' />";
                        echo "<input type='hidden' id='old-valor' value='$valor' />";
                        echo "<input type='hidden' id='old-data' value='$data' />";
                        echo "<input type='hidden' id='old-descricao' value='$descricao' />";
                        $error_show = array_pop($contato->errorlog);
                        switch(substr($error_show,-1))
                        {
                            case '0': $error_show = "Erro de exigência: Idade insulficiente."; break;
                            case '1': $error_show = "Erro de exigência: Cidade inválida."; break;
                            case '2': $error_show = "Erro de exigência: Estado inválido."; break;
                            case '3': $error_show = "Errro de exigência: Valor do contato inválido."; break;
                        }
                        echo "<div class='gra_top2'>Requerimento de Contato - Falha</div>";
                        echo "<div class='contato-big'>Falha</div>";
                        echo "<div class=contato-box-content'><div class='contato-error-div'>$error_show</div>";
                        echo "<p>Não foi possível finalizar o processo de criação do contato, por favor verifique o erro citado acima.</p>";
                        echo "<input type='button' id='contato-close-back' class='btn' value='Voltar' />";
                        echo "<input type='button' id='contato-close' class='btn' value='Fechar' />";
                        echo "</div>";
                    }
                break;
            case 4:
                $u2 = new usuario($id);
                $u = new usuario();
                echo "<div class='gra_top2'>Requisição de Contato - Termos e Exigências</div>";
                echo "<div class='contato-box-content'>";
                if($u2->tipo == 1)
                {
                    echo "Verifique os seus termos de uso e suas exigências nesse contrato. ";
                    $exigencias = $u->getExigencias();
                    $termos = $u->getTermos();
                }
                else
                {
                    echo "Verifique os termos e exigências do contratante $u2->nome.";
                    $exigencias = $u2->getExigencias();
                    $termos = $u2->getTermos();
                }
                /**
                 *  Lista dos itens no array $exigencias
                 *  [0] => idade mínima
                 *  [1] => cidade
                 *  [2] => estado
                 *  [3] => pagamento
                 *  [4] => descricao
                 */
                echo "<p></p>";
                echo "<strong>Exigências</strong>";
                echo "<div class='padding-contato'>";
                echo "<ul>";
                if($exigencias[0] != "")
                {
                    echo "<li>";
                    echo "<div class='color-green'><strong>Idade Mínima</strong></div>";
                    echo "<div class='left-contato'>$exigencias[0] anos.</div>";
                    echo "</li>";
                }
                if($exigencias[1] != "")
                {
                    echo "<li>";
                    echo "<div class='color-green'><strong>Cidade</strong></div>";
                    echo "<div class='left-contato'>$exigencias[1].</div>";
                    echo "</li>";
                }
                if($exigencias[2] != "")
                {
                    echo "<li>";
                    echo "<div class='color-green'><strong>Estado</strong></div>";
                    echo "<div class='left-contato'>$exigencias[5].</div>";
                    echo "</li>";
                }
                if($exigencias[3] != "")
                {
                    echo "<li>";
                    echo "<div class='color-green'><strong>Valor</strong></div>";
                    echo "<div class='left-contato'>$exigencias[7]</div>";
                    echo "</li>";
                }
                if($exigencias[4] != "")
                {
                    echo "<li>";
                    echo "<div class='color-green'><strong>Descrição</strong></div>";
                    echo "<div class='left-contato2'>$exigencias[6]</div>";
                    echo "</li>";
                }
                
                echo "</ul>";
                echo "</div>";
                echo "<strong>Termos</strong>";
                echo "<div class='padding-contato'>$termos[0]</div>";
                echo "<a href='#'><input type='button' value='Voltar' id='contato-next-step-1' class='login-button-input' /></a>";
                echo "</div>";                            
                break;
            case 5:
                $u = new usuario($id);
                $u2 = new usuario();
                $cur = new curriculum();
                if($u->tipo == 1)
                {
                    $cur->getUser($u->id);
                    $nome = $u->nome;
                }
                else
                {
                    $cur->getUser($u2->id);
                    $nome = $u2->nome;
                }
                
                $infos = $cur->loadInfo();
                
                echo "<div class='gra_top2'>Requisição de Contato - Curriculum</div>";
                if($infos == false)
                {
                    echo "<div class='padding-contato'>O usuário $nome não possui um curriculum disponível atualmente.</div>";
                }
                else
                {
                    /**
                     *  Carrega as informações do curriculum.
                     */
                    
                    echo "<div class='contato-box-content3'>";
                    echo "<ul id='cur-general-info'>";
                    echo "<li>";
                    
                    // Inicia informações gerais
                    echo "<p><strong class='cur-title'>Informações Gerais</strong></p>";
                    echo "</li>";
                    if($infos['nome'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Nome</strong></div><span class='cur-list'> ". $infos['nome'] ."</span>";
                    echo "</li>";
                    }
                    if($infos['idade'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Idade</strong></div><span class='cur-list'> ". $infos['idade'] ." anos</span>";
                    echo "</li>";
                    }
                    if($infos['sexo'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Sexo</strong></div><span class='cur-list'> ". $infos['sexo'] ."</span>";
                    echo "</li>";
                    }
                    if($infos['ramo'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Ramo</strong></div><span class='cur-list'> ". $infos['ramo'] ."</span>";
                    echo "</li>";
                    }
                    if($infos['instrumento'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Instrumento</strong></div><span class='cur-list'> ". $infos['instrumento'] ."</span>";
                    echo "</li>";
                    }
                    if($infos['area'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Área</strong></div><span class='cur-list'> ". $infos['area'] ."</span>";
                    echo "</li>";
                    }
                    if($infos['grupo_musical'] != "")
                    {
                    echo "<li class='contato-box-content2' >";
                    echo "<div class='color-green'><strong class='cur-left'>Grupo Musical</strong></div><span class='cur-list'> ". $infos['grupo_musical'] ."</span>";
                    echo "</li>";
                    }
                    echo "</ul>";                    
                    // Finaliza informações gerais
                    // Inicia músicas principais
                    $infos = $cur->showListaMusicas(); // Vamos utilizar a mesma variável.        
                    if($infos != "")
                    {
                        echo "<p><strong class='cur-title'>Amosta de Músicas</strong></p>";
                        foreach($infos as $i => $v)
                        {
                            echo "<a href='".$_SESSION['root']."/site/musica/$v[4]' target='new'><div class='cur-div-box' title='Duração: $v[1]'>";
                            echo "<div><strong class='cur-title'>$v[0]</strong> ( <span class='italic'>$v[3] Visualizações<span> )</div>";
                            echo "<div><strong>Gênero</strong> $v[2]</div>";
                            echo "</div></a>";
                        }
                    }
                    // Finaliza músicas principais
                    // Inicia contatos
                    $infos = $cur->showListaContatos();
                    if($infos != "")
                    {
                        echo "<p><strong class='cur-title'>Formas de contato</strong></p>";
                        foreach($infos as $i => $v)
                        {
                            echo "<div class='cur-div-box'>";
                            echo "<div><strong class='cur-title'>$v[0]</strong></div>";
                            echo "<div>$v[1]</div>";
                            echo "</div>";
                        }
                    }
                    // Finaliza contatos
                    // Inicia referências
                    $infos = $cur->showListaReferencias();
                    if($infos != "")
                    {
                        echo "<p><strong class='cur-title'>Formas de contato</strong></p>";
                        foreach($infos as $i => $v)
                        {
                            echo "<a href='".$_SESSION['root']."/profile/$v[4]'><div class='cur-div-box2' title='Número de contatos: $v[5]'>";
                            echo "<div class='left'>";
                            if($v[1] == "0")
                                echo "<img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' alt='Avatar' class='thumb' />";
                            else
                                echo "<img src='".$_SESSION['root']."/imagens/profiles/$v[3]_thumb.$v[1]' alt='Avatar' class='thumb' />";                    
                            echo "</div>";
                            echo "<div class='left'>";
                            echo "<div class='marginleft'><strong class='cur-title'>$v[0]</strong></div>";
                            echo "<div class='marginleft'><strong>Preferência Musical</strong> $v[2]</div></div>";
                            echo "</div></a>";
                        }
                    }
                    echo "<a href='#'><input type='button' value='Voltar' id='contato-next-step-1' class='login-button-input' /></a>";
                    echo "</div>";
                }
                        
                break;
        }
    }
   
    public function closeContato($id,$step)
    {
        /**
         * Realiza todo o processo de fechar um contato.
         * 
         * @param int $id
         * @param int $step 
         */
        
        if($id == "" || !is_numeric($id))
            return;
        
        $c = new contato();
        $c->getId($id);
        $infos = $c->pegarInfo();
        
        if($_SESSION['tipo_usuario'] == 1) $nome = $infos[15];
        else $nome = $infos[14];
        
        switch($step)
        {
            case 0:
                echo "<div id='ajax-response-wrap'>";
                echo "<div id='ajax-response'>0</div>";
                echo "<div id='ajax-content'>";
                echo "<div class='gra_top1'>Cancelar Contato</div>";
                echo "<div class='padding-contato'><p>Você tem certeza que deseja cancelar esse contrato com o usuário <strong>$nome</strong> ?</p>";
                echo "<p><strong>ATENÇÃO:</strong> Ao cancelar o contato você não poderá mais enviar mensagens</p><p> dentro dele e os privilégios de permissão do usuário serão revogadas.</p>";
                echo "<input type='button' class='btn' value='Confirmar' id='bci-cancel2' />";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                break;
            case 1:
                $c->getStatus(1);
                $a = $c->alterarStatus();
                if($a == true)
                {
                    echo "<div id='ajax-response-wrap'>";
                    echo "<div id='ajax-response'>1</div>";
                    echo "<div id='ajax-content'>";
                    echo "<div class='gra_top1'>Cancelar Contato</div>";
                    echo "<div class='padding-contato'>";
                    echo "Contato finalizado com sucesso.";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                else
                {
                    echo "<div id='ajax-response-wrap'>";
                    echo "<div id='ajax-response'>0</div>";
                    echo "<div id='ajax-content'>";
                    echo "<div class='gra_top1'>Cancelar Contato</div>";
                    echo "<div class='padding-contato'>";
                    echo "Não foi possível realizar o cancelamento do contato..";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
        }
    }

    public function updateStatus($id_contato, $status_contato)
    {
        $contato = new contato();
        $contato->getId($id_contato);
        $contato->getStatus($status_contato);
        $a = $contato->alterarStatus();
        if($a == true)
            echo "<div id = 'response'>0</div>";
        else
            echo "<div id = 'response'>1</div>";
    }
    
    public function updateTermos($termos)
    {
        $usuario = new usuario();
        $a = $usuario->editaTermos($termos);
        if($a == false)
        {
            echo "Não foi possível alterar o seu termo.[e838732]";
            return;
        }
        unset($usuario);
        if($termos == "")
            $termos = "Clique para adicionar seus termos...";
        $c = new comentarios;
        $termos = $c->bbCode($termos,false);
        unset($c);
        echo "<div id='response'>".$termos."</div>";
    }
    
    public function updateExigencias($idade,$cidade,$estado,$pagamento,$descricao)
    {
        $u = new usuario();
        $a = $u->editaExigencias($idade,$cidade,$estado,$pagamento,$descricao);
        if($a == false)
            echo "<div id='response'><span class='side-tip'>".array_pop($u->errorlog)."</span></div>";
        else
            echo "<div id='response'><span class='side-tip-ok'>Exigências editadas com sucesso.</span></div>";       
    }
    
    public function mostraNotificacoes()
    {
        if($_SESSION['id_usuario'] == "")
            return false;
        
        $c = new contato;
        $c->getUser($_SESSION['id_usuario']);
        $notifica = $c->pegarNotificacoes();
        
       /**
        *  Lista de informações
        *  1. is_read
        *  2. id
        *  3. assunto
        *  4. data
        *  5. id_artista
        *  6. id_contratante
        */
        if(is_array($notifica) && $notifica[0] != "")
        {
            foreach($notifica as $i => $v)
            {
                if($_SESSION['tipo_usuario'] == 1)
                    $usu = $v[5];
                else
                    $usu = $v[4];
                
                $usu = new usuario($usu);
                switch($v[0])
                {
                    case 1:
                        $dt = new dataformat;
                        $dt->pegarData($v[3]);
                        $v[3] = $dt->formatData();
                        echo "<a href='".$_SESSION['root']."/contato/inbox/show&id=$v[1]'><div class='notifica-list'>";
                        echo "<div class='left'>";
                        if($usu->imagem == '0')
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' class='thumb' />";
                        else
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/".$usu->id."_thumb.$usu->imagem' class='thumb' />";                            
                        echo "</div>";
                        echo "Você recebeu um novo pedido de contato de $usu->nome.";
                        echo "<p><strong>Assunto</strong> $v[2] <br /><strong>Data</strong> $v[3]</strong></p>";
                        echo "<div class='clear'></div></div></a>";
                        break;
                    case 2:
                    case 3:
                        echo "<a href='".$_SESSION['root']."/contato/inbox/show&id=$v[1]'><div class='notifica-list'>";
                        echo "<div class='left'>";
                        if($usu->imagem == '0')
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' class='thumb' />";
                        else
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/".$usu->id."_thumb.$usu->imagem' class='thumb' />";                            
                        echo "</div>";
                        echo "<p><strong class='contato-title-noti'>Contato $v[2]</strong></p>";
                        echo "Você recebeu uma nova mensagem dentro de um contato com $usu->nome<div class='clear'></div></div></a>";
                        break;
                    case 4:
                    case 5:
                        echo "<a href='".$_SESSION['root']."/contato/inbox/show&id=$v[1]'><div class='notifica-list'>";
                        echo "<div class='left'>";
                        if($usu->imagem == '0')
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' class='thumb' />";
                        else
                            echo "<img src='".$_SESSION['root']."/imagens/profiles/".$usu->id."_thumb.$usu->imagem' class='thumb' />";                            
                        echo "</div>";
                        echo "Seu contato com $usu->nome foi cancelado. <p><strong class='contato-title-noti'>Contato $v[2]</strong></p><div class='clear'></div></div></a>";
                        break;
                }
            }
        }
        else
        {
            echo "<div class='notifica-list'><strong>Parece que não existe nenhuma notificação pendente na sua conta...</div>";
            echo "<script>removeNotificacao();</script>";
        }
    }
    
    public function contatoReloadComments($maxid,$idr)
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
        $new =  $c->loadNew($maxid,3,$idr);        
        if($new == 0)
            return;
        
        // Carrega os novos comentários
        $this->id = $idr;
        $comentarios = $this->showComment(3,0,$new);
        
        echo "<div id='reload-ajax'>";
        echo "<div id='last-id'>".$comentarios[0][0]."</div>";
        $this->templateComment($comentarios); 
        echo "</div>";        
        
    }
    

}








?>