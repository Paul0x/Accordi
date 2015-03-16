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

class contatos_termos
{
    
    public function __construct()
    {
        $this->loadTermos();        
    }
    
    
    private function loadTermos()
    {
       echo "<div class = 'default-box2' id = 'tci-main'>";
       echo "<div class = 'gra_top1'>Meus Termos</div>";
       echo "<div class = 'info'>Edite os seus termos para a realização dos contatos.<p>Para editar os termos clique no texto abaixo.</p></div>";
       $usuario = new usuario();
       $termos = $usuario->getTermos();
       if($termos[1] == "")
           $termos[0] = "Clique para adicionar seus termos...";
       echo "<div id = 'exibe-termos'><div id = 'termos-edit'>".$termos[0]."</div></div>";
       echo "<div class = 'hidden' id = 'termos-hidden'>$termos[1]</div>";
       
       
       
       echo"</div>";
        
    }
}

?>