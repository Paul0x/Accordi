<?php
if($IN_ACCORDI != true)
{
 exit();
}
// Include de todas as classes - Accordi
include("errorhandle.php");
include("sqlcon.php");
include("dataformat.php");
include("imagemanager.php");
include("comentarios.php");
include("search.php");
include("snapps.php");
include("phpmailer.php");
include("validate.php");
include("usuario.php");
include("ranking.php");
include("musicas.php");
include("eventos.php");
include("contatos.php");
include("curriculum.php");
include("portal.php");
include("playlist.php");
include("atualizacoes.php");
?>