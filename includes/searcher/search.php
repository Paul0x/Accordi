<?
if ($IN_ACCORDI != true) {
    exit();
}

$bpessoa = new buscar();
$bpessoa->tag = $_GET['s'];
$artista = $bpessoa->pesquisaPessoa($_GET['p'],$_GET['tipo']);
?>
<ul class='menu-busca'>
    <a href='<? echo $root . "/busca/&s=" . $_GET['s'] . "&tipo=&p=" . $_GET['p']; ?>'><li class='menu-busca' id='buscar-artista'>Todos</li></a>
    <a href='<? echo $root . "/busca/&s=" . $_GET['s'] . "&tipo=artista&p=" . $_GET['p']; ?>'><li class='menu-busca' id='buscar-artista'>Artistas</li></a>
    <a href='<? echo $root . "/busca/&s=" . $_GET['s'] . "&tipo=contratante&p=" . $_GET['p']; ?>'><li class='menu-busca' id='buscar-artista'>Contratantes</li></a>
    <li><form method='get' id="query-form-busca2" action='<? echo $root; ?>/busca'>
<ul class='busca-rapida'>
<li class='busca-rapida'><div class='busca3'><button type='submit' id='busca-button' class='busca-rapida'></button><input type='text' name='s' id='query-s' class='busca-rapida'/></div></form></li>
</ul>
</li>
</ul>
<div class='busca-wrapper'>
    <? if ($_GET['s'] != "bolota") { ?>

        <div class='search-artista-wrapper'>
            <div class='default-box2'>
                    <div class='info-2'><? echo "Pesquisa realizada em $bpessoa->tempoquery segundos com $bpessoa->nresultados resultado"; if($bpessoa->nresultados != 1) echo "s"; echo "."; ?></div>
                <ul class='search-artistas-ul'>
                    <?
                    if ($artista[0] != "") {
                        $i = 0;
                        foreach ($artista as $key => $v) {
                            $v = $v[1];
                            if ($i == 30)
                                break;
                            echo "<a href='$root/profile/$v[8]'><li class='search-li'>";
                            echo "<div class='search-avatar'>";
                            if ($v[6] == '0')
                                echo "<img src='$root/imagens/profiles/noavatar_thumb.png' class='search-thumb' />";
                            else
                                echo "<img src='$root/imagens/profiles/$v[0]_thumb2.$v[6]' class='search-thumb'/>";
                            echo "</div>";
                            echo "<div class='search-content'>";
                            echo "<span class='title-search'>$v[1]</span>";
                            echo "<div class='search-content-info'>Possui $v[7] ";
                            if ($v[9] == 1)
                                echo "música";
                            elseif ($v[9] == 2)
                                echo "evento";
                            if ($v[7] != 1)
                                echo "s";
                            if ($v[4] != "" || $v[5] != "0") 
                            {
                                echo " e mora em ";
                                echo $v[4];
                                if ($v[4] != "" && $v[5] != "0")
                                    echo " - " . strtoupper($v[5]);
                                elseif($v[5] != "0")
                                    echo strtoupper($v[5]);
                            }
                            echo "</div>";
                            if ($v[9] == 1)
                                echo "<div class='bar-search'><span class='bar-user-artista'>Artista</span></div>";
                            elseif ($v[9] == 2)
                                echo "<div class='bar-search'><span class='bar-user-contratante'>Contratante</span></div>";
                            echo "</div>";
                            echo "</li></a>";
                        }
                    }
                    else
                        echo "<li class='search-not-found'>Nenhum resultado encontrado.</li>";
                    ?>
                </ul>
                <div class='clear'></div>
            </div>
            <?
            $count = 0;
            
            if($_GET['p'] == "")
                $_GET['p'] = 0;
            $tp = $_GET['p'];
                $_GET['p'] = $_GET['p']-5;
            for ($i = $_GET['p']; $i <= $bpessoa->npesquisa; $i++) {
                if($count == 11)
                    break;
                if($i>=0)
                {
                echo "<a href='" . $root . "/busca/&s=" . $bpessoa->tag . "&tipo=".$_GET['tipo']."&p=" . $i . "' class='pagefield2'>";
                if($i == $tp)
                echo "<span class='page-change2'>";
                else
                echo "<span class='page-change'>";
                echo $i . "</span></a>";
                $count++;
                }
            }
            ?>
        </div>
        <? } ?>
</div>
