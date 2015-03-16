<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: ranking.php - Interface do Ranking
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Interface para mostrar os 100 melhores artistas e os 100 melhores contratantes
 *    Principais funções:
 *      - Lista os 100 melhores artistas.
 *      - Lista os 100 melhores contratantes. 
 * 
 *********************************************/
if ($IN_ACCORDI != true) {
    exit();
}

class rankingInterface
{
    /**
     *  Classe para interface do ranking.
     */
    
    private $key;
    private $ranking;
    
    public function __construct($key)
    {
        $this->key = $key;        
        $this->ranking = new ranking();
        switch($this->key)
        {
            case 'contratante': $this->rankingContratante(); break;
            case 'artista': $this->rankingArtista(); break;
            default: $this->rankingArtista(); break;
        }
    }
    
    private function rankingArtista()
    {
        /**
         *  Mostra os 100 melhores artistas
         */
        $a_r = $this->ranking->rankingArtista();
        echo "<a href='".$_SESSION['root']."/top/contratante'><div class='rk-link1'>Visualizar ranking dos contratantes</div></a>";
        echo "<div class='default-box2' id='ranking-wrap'><div class='gra_top1'>Ranking dos Artistas</div>";
        echo "<div class='info'>Ranking dos 100 melhores artistas registrados no Accordi.</div>";
        echo "<div class='ranking-title'><div class='rt-pos'>Pos</div><div class='rt-nome'>Nome</div><div class='rt-musica'>N. Músicas</div><div class='rt-avaliacao'>Avaliação</div></div>";
        if($a_r == false)
            echo "<div class='ranking-warn'>Nenhum artista foi encontrado no ranking.</div>";
        else
        {
            foreach($this->ranking->rartista as $i => $v)
            {
                echo "<a href='".$_SESSION['root']."/profile/$v[4]'>";
                if($i%2 == 0)
                echo "<div class='ranking-lista'>";
                else
                echo "<div class='ranking-lista2' >";
                echo "<div class='rt-pos'>".($i+1)."</div><div class='rt-nome'>$v[0]</div><div class='rt-musica'>$v[1]</div><div class='rt-avaliacao'>$v[2]</div></div>";
                echo "</a>";
                
            }
        }
        echo "</div>";
    }
    
    private function rankingContratante()
    {
       /**
        *  Mostra os 100 melhores contratantes
        */
        $c_r = $this->ranking->rankingContratante();
        echo "<a href='".$_SESSION['root']."/top/artista'><div class='rk-link1'>Visualizar ranking dos artistas</div></a>";
            echo "<div class='default-box2' id='ranking-wrap'><div class='gra_top1'>Ranking dos Contratantes</div>";
        echo "<div class='info'>Ranking dos 100 melhores contratantes registrados no Accordi.</div>";
        echo "<div class='ranking-title'><div class='rt-pos'>Pos</div><div class='rt-nome'>Nome</div><div class='rt-musica'>N. Contatos</div><div class='rt-avaliacao'>Cidade</div></div>";
        if($c_r == false)
            echo "<div class='ranking-warn'>Nenhum contratante foi encontrado no ranking.</div>";
        else
        {
            foreach($this->ranking->rcontratante as $i => $v)
            {
                echo "<a href='".$_SESSION['root']."/profile/$v[4]'>";
                if($i%2 == 0)
                echo "<div class='ranking-lista'>";
                else
                echo "<div class='ranking-lista2'>";
                echo "<div class='rt-pos'>".($i+1)."</div><div class='rt-nome'>$v[0]</div><div class='rt-musica'>$v[2]</div><div class='rt-avaliacao'>$v[1]</div></div>";
                echo "</a>";
            }
        }
        echo "</div>";
    }
    
}
?>