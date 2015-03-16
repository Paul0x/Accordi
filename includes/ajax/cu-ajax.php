<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: cu-ajax.php - Requisições AJAX dos curriculums;
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

class AJAXcurriculum extends AJAX
{
    /*
     *  @id
     *  INT - [ ID do curriculum ]
     */    
    protected $id;
    
    /*
     *  @uid
     *  INT - [ ID do usuário ]
     */    
    protected $uid;
    
    /*
     *  @usuario
     *  OBJETO - [ Instância da classe usuário ]
     */
    private $usuario;
      
    /*
     *  @musica
     *  OBJETO - [ Instância da classe musica ]
     */
    private $musica;
    
    private $curriculum;
        
    public function getId($id)
    {
        /**
         *  Pega o ID do curriculum do quals erá pego as informações.
         *  @param INT $id
         *  @return bool
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
         *  Pega o ID do usuário do quals erá pego as informações.
         *  @param INT $id
         *  @return bool
         */
        if(is_null($id))
            return false;
        else
            $this->uid = $id;
            return true;
    }
   
    public function newCurriculum()
    {
        /**
         *  Cria um novo curriculum para o usuário.
         * 
         */
        
    
        $this->curriculum = new curriculum();
        
        $this->curriculum->getUser($_SESSION['id_usuario']);
        $a = $this->curriculum->newCurriculum();
        if($a == true)
        {
            echo "<div id='response'>1</div>";
            return;
        }
        else
        {
            echo "<div id='response'>0</div>";
            return;
        }
        
    }
    
    public function editCampo($campo,$valor,$id)
    {
        $this->curriculum = new curriculum();
        $this->curriculum->getId($id);
        $a = $this->curriculum->editCampo($campo,$valor);
        if($a == true)
        {
            echo "<div id='response-ajax'>";
            echo "<div id='response'>1</div>";
            echo "<div id='response-content'>".$this->curriculum->campo_editado."</div>";
            echo "</div>";
        }
        else
        {
            echo "<div id='response-ajax'>";
            echo "<div id='response'>0</div>";
            echo "<div id='response-content'></div>";
            echo "</div>";            
        }
    }
    
    public function deleteCurriculum($id)
    {
        /**
         *  Deleta seu curriculum atual
         */
       $this->curriculum = new curriculum();
       $this->curriculum->getId($id);
       $a = $this->curriculum->deleteCurriculum();
       echo "lol";
       if($a == true)
           echo "<div id='response'>1</div>";
       else
           echo "<div id='response'>0</div>";
    }
    
    public function addItemLista($tipo,$item)
    {
        /**
         *  Adiciona o item em um dos tipos de lista
         *  @param int $tipo
         *  @param string $item
         */
        if($this->id == "")
        {
            $this->errorlog[] = "Não existe parametro para iniciar a função.";
            return false;
        }
        
        $this->curriculum = new curriculum();
        $this->curriculum->getId($this->id); // Adiciona o ID do curriculum no qual queremos editar
        $a = $this->curriculum->listaAddItem($tipo,$item);        
        if($a == true)
        {
            // Pega o index do array, para adicionar o delete.
            $this->curriculum->pegarLista($tipo);
            switch($tipo)
            {
                case 0:
                    $i = count($this->curriculum->lista_musicas)-1;
                    $v = array_pop($this->curriculum->showListaMusicas());
                    echo "<div id='response-wrap-ajax' token='true'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='response-content-ajax'>";
                    echo "<div class='ci-music-wrap' id='box-musicas-wrap-$i'>";
                    echo "<a href='".$_SESSION['root']."/site/musica/$v[4]'>";
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='ci-music-lista-t'>$v[0]</div>";
                    echo "<div><div class='ci-music-lista2'><strong class='ci-music-lista'>Gênero</strong> $v[2]</div>
                         <div class='ci-music-lista2'><strong class='ci-music-lista'>Duração</strong> $v[1]</div>
                         <div class='ci-music-lista2'><strong class='ci-music-lista'>Visualizações</strong> $v[3]</div>";
                    echo "</div>";
                    echo "<div class='clear'></div>";
                    echo "</div>";       
                    echo "</a>";
                    echo "<div class='ci-del-list' id='cidel-0-$i'></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    break;
                case 1:
                    $i = count($this->curriculum->lista_contatos)-1;
                    echo "<div id='response-wrap-ajax' token='true'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='response-content-ajax'>";
                    echo "<div class='ci-music-wrap' id='box-contatos-wrap-$i'>";
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='ci-music-lista-t'>$item[1]</div>";
                    echo "<div>$item[0]</div>";
                    echo "</div>";
                    echo "<div class='ci-del-list' id='cidel-1-$i'></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    break;
                case 2:
                    $i = count($this->curriculum->lista_referencias)-1;
                    $v = array_pop($this->curriculum->showListaReferencias());
                    echo "<div id='response-wrap-ajax' token='true'>";
                    echo "<div id='response'>1</div>";
                    echo "<div id='response-content-ajax'>";
                    echo "<div class='ci-music-wrap' id='box-referencias-wrap-$i'>";
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='left'>";
                    if($v[1] == "0")
                        echo "<img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' alt='Avatar' class='thumb' />";
                    else
                        echo "<img src='".$_SESSION['root']."/imagens/profiles/$v[3]_thumb.$v[1]' alt='Avatar' class='thumb' />";                    
                    echo "</div>";
                    echo "<div class='left'>";
                    echo "<div class='ci-music-lista-t'>$v[0]</div>";
                    echo "<div><strong>Gosto Musical</strong> $v[2]</div>";
                    echo "<div><strong>Num. Contatos</strong> $v[5]</div>";
                    echo "</div>";
                    echo "<div class='clear'></div>";
                    echo "</div>";
                    echo "<div class='ci-del-list' id='cidel-2-$i'></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    break;
            }
        }
        else
            echo "<div id='reseponse'>0</div>";
        
    }
    
    public function delItemLista($tipo,$pos)
    {
        /**
         *  Deleta o item em um dos tipos de lista
         *  @param int $tipo
         *  @param int $pos
         */
        $this->curriculum = new curriculum();
        $this->curriculum->getId($this->id);
        $a = $this->curriculum->listaDelItem($tipo,$pos);
        if($a == true)
            echo "<div id='response'>1</div>";
        else
            echo "<div id='response'>0</div>";
    }
    
    public function atualizaCurriculum()
    {
        /**
         *  Atualiza o curriculum com atualizações do perfil.
         */
        $this->curriculum = new curriculum();
        $this->curriculum->getId($this->id);
        $a = $this->curriculum->atualizaCurriculum();
        if($a == true)
            echo "<div id='response'>1</div>";
        else
            echo "<div id='response'>0</div>";
    }
    
    public function loadInsert($tipo)
    {
        /**
         *  Carrega a lista de itens utilizados para recarregar o curriculum.
         *  @param int $tipo
         */
        
        if($this->id == "")
        {
            $this->errorlog[] = "Precisamos do ID para conseguir informações do curriculum.";
        }
        $this->curriculum = new curriculum();
        $this->curriculum->getId($this->id);
        $this->curriculum->loadInfo();
        
        switch($tipo)
        {
            case 0:
                if(count($this->curriculum->lista_musicas) < 6 || !is_array($this->curriculum->lista_musicas))
                {
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='ci-music-lista-t'>Adicionar nova música</div>";
                    // Percorre as músicas
                    $lista_musicas = $this->curriculum->listaMusicas();
                    if($lista_musicas != false)
                    {
                    echo "<select class='ci-music-lista' id='music-list-curriculum-add'>";
                    foreach($lista_musicas as $i => $v)
                    {
                        echo "<option id='ci-select-musicas-$v[0]' value='$v[0]'>$v[1] - $v[2]</option>";
                    }
                    echo "</select>";
                    echo "<input type='button' class='btn' value='Adicionar' id='ci-list-btn-musica' />";
                    }
                    else
                        echo "Você não possui mais músicas para adicionar ao seu curriculum... ";
                    echo "</div>";
                }
                break;
            case 1:
                if(count($this->curriculum->lista_contatos) < 6 || !is_array($this->curriculum->lista_contatos))
                {
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='ci-music-lista-t'>Adicionar novo contato</div>";
                    echo "<div>";
                    echo "<strong>Tipo</strong>";
                    echo "<input type='text' class='ci-contato-lista1' id='contato-list-curriculum-add1' maxlength='15' />";
                    echo "<strong>Contato</strong>";
                    echo "<input type='text' class='ci-contato-lista2' id='contato-list-curriculum-add2' maxlength='30' />";
                    echo "</div>";
                    echo "<input type='button' class='btn' value='Adicionar' id='ci-list-btn-contato' />";
                    echo "</div>";
                }
                break;
            case 2:
                if(count($this->curriculum->lista_referencias) < 6 || !is_array($this->curriculum->lista_referencias))
                {
                    echo "<div class='ci-music-lista'>";
                    echo "<div class='ci-music-lista-t'>Adicionar nova referência</div>";
                    // Percorre as referências
                    $lista_referencias = $this->curriculum->listaReferencias();
                    if($lista_referencias != false)
                    {
                    echo "<select class='ci-music-lista' id='referencias-list-curriculum-add'>";
                    foreach($lista_referencias as $i => $v)
                    {
                        echo "<option id='ci-select-referencias-$v[0]' value='$v[0]'>$v[1]</option>";
                    }
                    echo "</select>";
                    echo "<input type='button' class='btn' value='Adicionar' id='ci-list-btn-referencia' />";
                    }
                    else
                        echo "Você não possui contatos para adicionar como referência em seu curriculum...";
                    echo "</div>";
                }
                break;
        }
    }

}








?>