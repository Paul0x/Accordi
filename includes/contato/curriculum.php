<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: msg.php - Sistema de mensagens de contrato
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Sistema para visualizar todas as mensagens do usuário.
 *    Principais funções:
 *      - Carrega as mensagens do usuário
 *      - Gerencia mensagens do usuário
 * 
 *********************************************/

if (IN_ACCORDI != true) {
    exit();
}

class contatos_curriculum
{
    private $usuario;
    private $curriculum;
    
    public function __construct()
    {
        $this->usuario = new usuario();
        $this->curriculum = new curriculum();
        
        /**
         *  Verifica se curriculum já existe.
         */
        
        $this->curriculum->getUser($_SESSION['id_usuario']);
        $a = $this->curriculum->validaCurriculum();
        if($a == false)
            $this->newCurriculum();
        else
            $this->loadCurriculum();                
    }
    
    private function loadCurriculum()
    {
        /**
         * Carrega a página principal para o curriculum ser.
         * 
         *  
         */
        
        $this->curriculum->getUser($_SESSION['id_usuario']);
        $a = $this->curriculum->loadInfo();
        foreach($a as $i => $v)
        {
            if($v == "") $a[$i] = "<span class='italic'>Editar</span>";
        }
        
        echo "<div id='cib-true-wrap'>";
        echo "<input type='hidden' id='id-curriculum' value='".$a['id']."' />";
        echo "<div class='default-box2' id='cib-wrap'>";
        echo "<div class='gra_top1'>Meu Curriculum</div>";
        echo "<div class='info'>Criar um curriculum é a melhor maneira formal de exibir suas informções importantes e relevantes para o mercado de trabalho. Você também tem a opção de salva-lo em diversas formas.</div>";
        echo "<div id='curriculum-edit'>";
        echo "<div id='ce-top'>";
        echo "<div class='ce-title'>Meu Curriculum</div>";
        echo "<div id='ce-img'>";
        $this->curriculum->showImagem();
        echo "</div>";
        echo "<div id='ce-info'>";
        echo "<ul class='ce-list1'>";
        echo "<li><div class='ce-list1'>Nome</div><div class='ce-field1' id='ce-nome'>".$a['nome']."</div></li>";
        echo "<li><div class='ce-list1'>Idade</div><div class='ce-field1' id='ce-idade'>".$a['idade']."</div></li>";
        echo "<li><div class='ce-list1'>Sexo</div><div class='ce-field1' id='ce-sexo'><span class='c-event-edit' id='cedit-sexo'>".$a['sexo']."</span></div></li>";
        echo "<li><div class='ce-list1'>Área Musical</div><div class='ce-field1' id='ce-area'><span class='c-event-edit' id='cedit-area'>".$a['ramo']."</span></div></li>";
        echo "<li><div class='ce-list1'>Instrumento</div><div class='ce-field1' id='ce-instrumento'><span class='c-event-edit' id='cedit-instrumento'>".$a['instrumento']."</span></div></li>";
        echo "<li><div class='ce-list1'>Região de atuação</div><div class='ce-field1' id='ce-regiao'><span class='c-event-edit' id='cedit-regiao'>".$a['area']."</span></div></li>";
        echo "<li><div class='ce-list1'>Grupo Musical</div><div class='ce-field1' id='ce-grupo_musical'><span class='c-event-edit' id='cedit-grupo_musical'>".$a['grupo_musical']."</span></div></li>";
        echo "</ul>";
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "</div>";
        echo "<div id='ce-lista-musicas'>";
        echo "<div class='ce-title2'>Minhas Músicas</div>";
        $this->listaMusicas();
        echo "</div>";
        echo "<div id='ce-lista-contatos'>";
        echo "<div class='ce-title2'>Meus Contatos</div>";
        $this->listaContatos();
        echo "</div>";
        echo "<div id='ce-lista-referencias'>";
        echo "<div class='ce-title2'>Minhas Referências</div>";
        $this->listaReferencias();
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<input type='button' class='btn' value='Deletar' id='del-curriculum' /> <input type='button' class='btn' id='atual-curriculum' value='Atualizar' />";
        echo "</div>";
    }
    
    private function listaMusicas()
    {
        
        /**
         *  Carrega a lista de músicas do usuário
         */
        $lista_array = $this->curriculum->showListaMusicas(); // Carrega array daas músicas
        // Conteúdo do array
        //
        // [0] => Nome
        // [1] => Duração
        // [2] => Gênero
        // [3] => Visualizações
        // [4] => Id
        //       
        echo "<div id='ci-list-musicas'>";
        if($lista_array != "")
        {
            foreach($lista_array as $i => $v)
            {
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
            }
        }
        echo "</div>";
        
        echo "<div id='ci-insert-musicas'>";
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
                echo "Você não possui mais músicas para adicionar ao seu curriculum...";
            echo "</div>";
        }        
        echo "</div>";
    }
    
    private function listaContatos()
    {
        /**
         *  Lista as maneiras de contactar o artista
         */
        
        $lista_array = $this->curriculum->showListaContatos();
        echo "<div id='ci-list-contatos'>";
        if($lista_array != "")
        {
            foreach($lista_array as $i => $v)
            {
                echo "<div class='ci-music-wrap' id='box-contatos-wrap-$i'>";
                echo "<div class='ci-music-lista'>";
                echo "<div class='ci-music-lista-t'>$v[0]</div>";
                echo "<div>$v[1]</div>";
                echo "</div>";
                echo "<div class='ci-del-list' id='cidel-1-$i'></div>";
                echo "</div>";
            }
        }
        echo "</div>";
        echo "<div id='ci-insert-contatos'>";
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
        echo "</div>";
        
    }
    
    private function listaReferencias()
    {
        /**
         *  Mostra as referências (contratantes).
         *  
         *  Lista de informações do $lista_array
         *  [0] => nome
         *  [1] => imagem
         *  [2] => tipomusical
         *  [3] => id
         *  [4] => login
         *  [5] => Número de contatos
         */
        $lista_array = $this->curriculum->showListaReferencias();
        echo "<div id='ci-list-referencias'>";
        if($lista_array != "")
        {
            foreach($lista_array as $i => $v)
            {
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
            }
        }
        echo "</div>";
        
        echo "<div id='ci-insert-referencias'>";
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
        echo "</div>";
    }
    
    
    private function newCurriculum()
    {
        echo "<div id='cib-true-wrap'>";
        echo "<div id='curriculum-new'>Criar Curriculum</div>";
        echo "</div>";
    }
}

?>