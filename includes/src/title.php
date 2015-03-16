<?

switch($url[0])
{
case "":
    $a = "";
    break;
case "musicas":
     $a = "Musicas";
    break;
case "home":
    switch($url[1])
    {
     case "edit":
         $a = "Editar Perfil";
         break;
     case "":
         $a = "Home";
         break;
    }
    break;
case "top":
    switch($url[1])
    {
     case "artista":
         $a = "Top Artistas";
         break;
     case "contratante":
         $a = "Top Contratantes";
         break;
    }
    break;
case "eventos":
    $a = "Eventos";
    break;
case "login":
    $a = "Login";
    break;
case "cadastro":
    $a = "Cadastro";
    break;
case "recovery":
    $a = "Recuperar Senha";
    break;
case "portal":
    $a = "Portal";
    break;
case "busca":
    $a = "Busca";
    break;
case "site":
    switch($url[1])
    {
      case "eventos":
         $a = "Buscar Eventos";
         break;
      default:
         $a = "Não encontrado";
         break;
      case "musicas":
          $a = "Buscar Músicas";
          break;
      case "musica":
          $m = new musicas();
          $m->infoMusica($url[2],"public");
          $a = $m->nome;
          unset($m);
          break;  
      case "evento":
          $e = new eventos();
          $e->id = $url[2];
          $e->pegaInfo();
          $a = $e->nome;
          unset($e);
    }
    break;
case "profile":
    $existe = new validate();
    $a = $existe->ifExist($url[1],"login","usuario","login");
    if($url[1] == "") $a = "Não Encontrado";
    else{
    if($a == false)
    {
      $uid = $existe->pegaId($url[1]);  
      $u = new usuario($uid);
      $a = "Perfil de $u->nome";
    }
    else
    {
      $a = "Não Encontrado";  
    }}
    break;
case "sobre": $a="Sobre"; break;
case "contato":
    switch($url[1])
    {
        case 'termos': $a = "Termos"; break;
        case 'curriculum': $a = "Curriculum"; break;
        case 'exigencias': $a = "Exigencias"; break;
        default: $a = "Contato"; break;  
    }
    break;
case "playlists":
{
    if($url[1] == "") $a = "Playlists";
    else
    {
        try
        {
            $pl = new Playlist();
            $pl->getId($url[1]);
            $info = $pl->getInfo();
            $a = $info[2];
        }
        catch(Exception $a)
        {
            $a = "Playlist não encontrada";
        }
    }
    break;
    
}
default:
    $a = "Não Encontrado";
    break;
}

echo "Accordi"; if($a != "") echo " - ".$a; 



?>