<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: main.php - Lista os contatos
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Realiza listagem, visualizaçãode mensagens e outros assuntos diversos com relação ao contato.
 *    Principais funções:
 *      - Menu principal dos contatos.
 *      - Carrega lista e informações sobre os contatos.
 * 
 *********************************************/

if ($IN_ACCORDI != true) {
    exit();
}

class contato_view
{
    private $key="inbox";
    private $contato;
    
    public function __construct()
    {

      if(URL_2 != "inbox" && URL_2 != "curriculum" && URL_2 != "termos" && URL_2 != "exigencias")
          $this->key = "inbox";
      elseif(URL_2 != "")
          $this->key = URL_2;
      elseif($_GET['key'] != "")
          $this->key = $_GET['key'];
      
      $this->loadMenu();
      $this->loadContent();
    }

    
    private function loadMenu()
    {
     /*
      * Coarrega o menu da página de contatos.  
      */
        $this->contato = new contato();
        $this->contato->getUser($_SESSION['id_usuario']);
        $ct = $this->contato->countContatos();
        unset($this->contato);
        echo "<div id='contrato-left-wrap'>";
        echo "<div class='default-box2' id='menu-contato1'>";
        echo "<div class='gra_top1'>Menu</div>";
        echo "<ul>
              <li class='c-m-strong'>Meus Contatos</li>
              <a href='".$_SESSION['root']."/contato/inbox/atuantes'><li class='c-m'>Atuante ";
              if($ct['atuantes'] != "")
                  echo "<strong>(<span id='atuantes-count'>".$ct['atuantes']."</span>)</strong>";
              echo "</li></a>
              <a href='".$_SESSION['root']."/contato/inbox/fechados'><li class='c-m'>Fechados ";              
              if($ct['fechados'] != "")
                  echo "<strong>(<span id='fechados-count'>".$ct['fechados']."</span>)</strong>";
              echo "</li></a>";
              echo "<li class='c-m-strong'>Pedidos ";
              if($ct['abertos'] != "")
                  echo "<strong>(<span id='abertos-count'>".$ct['abertos']."</span>)</strong>";
              echo "</li>
              <a href='".$_SESSION['root']."/contato/inbox/recebidos'><li class='c-m'>Recebidos</li></a>
              <a href='".$_SESSION['root']."/contato/inbox/enviados'><li class='c-m'>Enviados</li></a>
              </ul>";
        if($_SESSION['tipo_usuario'] == 2)
        {
        echo "<a href = '".$_SESSION['root']."/contato/termos'><div class='c-m-d'>Meus Termos</div></a>";
        echo "<a href = '".$_SESSION['root']."/contato/exigencias'><div class='c-m-d'>Minhas Exigências</div></a>";
        }
        elseif($_SESSION['tipo_usuario'] == 1)
        echo "<a href = '".$_SESSION['root']."/contato/curriculum'><div class='c-m-d'>Meu Curriculum</div></a>";
        echo "</div>";
        echo "</div>";
    }
    
    private function loadContent()
    {
       echo "<div id='contrato-mid-wrap'>";
       switch($this->key)
       {
           case "inbox":
               include("msg.php");
               $html = new contatos_inbox();
               unset($html);
               break;
           case "termos":
               if($_SESSION['tipo_usuario'] == 2)
               {
                   include("termos.php");
                   $html = new contatos_termos();
                   unset($html);
               }
               break;
           case "exigencias":
               if($_SESSION['tipo_usuario'] == 2)
               {
                   include("exigencias.php");
                   $html = new contatos_exigencias();
                   unset($html);
               }
               break;
           case "curriculum":
               if($_SESSION['tipo_usuario'] == 1)
               {
                   include("curriculum.php");
                   $html = new contatos_curriculum();
                   unset($html);
               }
               break;
       }
       echo "</div>";
       echo "<div class='clear'></div>";
    }

}
?>