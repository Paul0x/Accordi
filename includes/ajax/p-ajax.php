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

class AJAXprofile extends AJAX
{
    /*
     *  @id
     *  INT - [ ID do usuário ]
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
    
    public function loadProfileInfo()
    {
        /*
         *  Pega informações do usuário e imprime formatado em uma box.
         */
        $root = $_SESSION['root'];
        if(is_null($this->id) || is_null($this->token) || !$this->ajax_true())
                return false;
        $this->usuario = new usuario($this->id);
        $u = $this->usuario;
        echo "<div class='ajax-tt-box2'>";
        echo "<div id='response' token='$this->token'>";
        echo "<div class='left'>";
        if($u->imagem != "0")
        echo "<div class='thumb-img'><a href='".$_SESSION['root']."/profile/$u->login' ><img src='".$_SESSION['root']."/imagens/profiles/".$u->id."_thumb.$u->imagem' class='thumbim' alt='thumb' /></a></div> ";
        else
        echo "<div class='thumb-img'><a href='".$_SESSION['root']."/profile/$u->login' ><img src='".$_SESSION['root']."/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
        echo "</div>";
        echo "<div class='left' id='thumb-tt-content'>";
        echo "<strong><p><a href='$root/profile/$u->login' class='fonte-tt-profile'>$u->nome";
        if($u->apelido != "")
            echo " ".$u->apelido." ";
        if($u->sobrenome != "" && $u->apelido == "")
            echo " ";
        echo $u->sobrenome;
        echo "</a></p></strong>";
        if($u->tipo == 1)
        echo "<div class='artista-badge'>Artista</div>";
        elseif($u->tipo == 2)
        echo "<div class='contratante-badge'>Contratante</div>";
        echo "</div>";
        echo "<div class='tt-right-bar'>";
        echo "<a href='".$_SESSION['root']."/profile/$u->login/'><div class='tt-right-menu' title='Perfil' id='tt-menu-pro'></div></a>";
        if($u->tipo == 2)
        echo "<a href='".$_SESSION['root']."/profile/$u->login/eventos'><div class='tt-right-menu' title='Eventos' id='tt-menu-ev'></div></a>";
        else
        echo "<a href='".$_SESSION['root']."/profile/$u->login/musicas'><div class='tt-right-menu' id='tt-menu-ms' title='Músicas'></div></a>";
        echo "<a href='".$_SESSION['root']."/profile/$u->login/comentarios'><div class='tt-right-menu' id='tt-menu-con' title='Comentários'></div></a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        unset($u);
    }
    
    public function profileComment($tipo,$page)
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
                echo "<div class='title-c'><a href='$root/profile/$v[5]'>$v[7]</a> - $v[3]</div>";
                echo "<div class='box-img-c'>";
                if ($v[6] != "0")
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/$v[4]_thumb.$v[6]' class='thumbim' alt='thumb' /></a></div> ";
                else
                echo "<div class='thumb-img'><a href='$root/profile/$v[5]' id='thumb-$v[0]-$v[4]'  rel='thumb_box'><img src='$root/imagens/profiles/noavatar_thumb.png' alt='thumb' class='thumbim' /></a></div>";
                echo "</div>";
                echo "<div class='comment-content'>";
                echo "<input type='hidden' id='content-ajax-$v[0]' value='$v[8]' />";
                echo "<div id='content-$v[0]'>$v[2]";
                echo "</div>";
                echo "</div>";
                echo "<div class='comment-footer'>";
                if ($_SESSION['id_usuario'] == $v[1] || $_SESSION['id_usuario'] == $v[4])
                {
                echo "<span class='del-comment' id='dc-$v[0]'>Deletar</span>";
                if ($_SESSION['id_usuario'] == $v[4])
                echo "<span class='edit-comment' id='ec-$v[0]'>Editar</span>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            unset($array);
            return;   
    }
    
    public function switchCalendario($mes, $ano)
    {
        /**
         *  Exibe os comentários e os eventos via requisição AJAX
         *  @param INT $mes  
         */
        
        if($mes > 12 || $mes < 1 || !is_numeric($mes))
            return false;
        
        if(!is_numeric($ano))
            return false;
        
        if(strlen($mes) == 1)
            $mes = "0".$mes;
        
        if($ano > date("Y")+10 || $ano < date("Y")-10)
            $ano = date("Y");
        
        // Instâncias
        $event = new eventos();
        $data_format = new dataformat;
        
        // Funções
        $eventos_mes = $event->eventosMes(0,$mes,$ano);
        $data_format->pegarData($ano."-".$mes."-01");
        $wd = $data_format->pegarWeekDay();
        $mes_nome = $data_format->nomeMes();
        $max_day = $data_format->pegarMaxMes($mes,$ano);
        $count_day = 1;
        $count_mes = $mes;
            if($mes < 10)
                    $count_mes = $count_mes[1];        
        echo "<input type='hidden' value='".$count_mes."' id='mes-atual'>";    
        echo "<input type='hidden' value='".$ano."' id='ano-atual'>";
       echo "<div class='gra_top1'><span id='calendario-back'><div id='seta_2'></div></span><strong>$mes_nome / $ano </strong><span id='calendario-next'><div id='seta_1'></div></span></div>";        echo "<div class='calendario-space-t'>D</div>";
        echo "<div class='calendario-space-t'>S</div>";
        echo "<div class='calendario-space-t'>T</div>";
        echo "<div class='calendario-space-t'>Q</div>";
        echo "<div class='calendario-space-t'>Q</div>";
        echo "<div class='calendario-space-t'>S</div>";
        echo "<div class='calendario-space-t'>S</div>";
        for($row_mes=0;$row_mes<=5;$row_mes++)
        {
            for($col_mes=0;$col_mes<=6;$col_mes++)
            {
               if($row_mes == 0 && $col_mes < $wd[0])
                   echo "<div class='calendario-space-w'></div>";
               else
               {
                   if($eventos_mes[$count_day] != "")
                   echo "<div class='calendario-space-n' id='c-tt-ajax-$count_day'>$count_day</div>";
                   else
                   echo "<div class='calendario-space'>$count_day</div>";
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
        unset($eventos_mes,$col_mes,$count_day,$v,$wd,$event);
    }
    
    public function loadProfileTabs($aba,$id)
    {
        /**
         *  Carrega as abas do perfil.
         */
        
        if(!is_numeric($id))
        {
            $this->errolog[] = "ID inválido";
            return false;
        }
        
        include("includes/prf/public2.php");
        $html = new profile_1($id,$aba,true);      
    }
    
    public function profileReloadComments($maxid,$idr)
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
        $new =  $c->loadNew($maxid,0,$idr);        
        if($new == 0)
            return;
        
        // Carrega os novos comentários
        $this->id = $idr;
        $comentarios = $this->showComment(0,0,$new);
        
        echo "<div id='reload-ajax'>";
        echo "<div id='last-id'>".$comentarios[0][0]."</div>";
        $this->templateComment($comentarios); 
        echo "</div>";
        
    }
    

}








?>