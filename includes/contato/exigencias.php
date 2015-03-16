<?
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: exigencias.php - Sistema de exigências para o sistema de contatos.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Edita e visualiza as exigências do contratante
 *    Principais funções:
 *      - Visualizar exigências
 *      - Editar exigências
 * 
 *********************************************/

if (IN_ACCORDI != true) {
    exit();
}

class contatos_exigencias
{
    
    public function __construct()
    {
        $this->loadExigencias();        
    }
    
    
    private function loadExigencias()
    {
        $u = new usuario();
        $a = $u->getExigencias();
        unset($u);
        echo "<div id='ebi-wrap' class='default-box2'>";
        echo "<div class='gra_top1'>Minhas Exigências</div>";
        echo "<div class='info'>As exigências do contratante são requisitos minímos para os artistas lhe enviarem pedidos de contato. Todos os campos de exigência são facultativos.</div>";
        echo "<form method='POST' action=''>";
        echo "<ul class='eci-text'>";
        echo "<li><label class='eci-text'>Idade mínima</label><input type='text' class='eci-text' value='$a[0]' name='ex-idade-min' id='ex-idade-min' maxlength='2'/></li>";
        echo "<li><label class='eci-text'>Cidade</label><input type='text' class='eci-text' name='ex-cidade' value='$a[1]' id='ex-cidade' maxlength='25'/></li>";
        echo "<li><label class='eci-text'>Estado</label>";
        echo "<select name=\"ex-estado\" id=\"ex-estado\" class='eci-text'>
              <option value=\"0\"  "; if($a[2] == '0'){ echo "selected=selected"; } echo">Selecione o Estado</option>
              <option value=\"ac\" "; if($a[2] == 'Ac'){ echo "selected=selected"; } echo">Acre</option>
              <option value=\"al\" "; if($a[2] == 'Al'){ echo "selected=selected"; } echo">Alagoas</option>
              <option value=\"ap\" "; if($a[2] == 'Ap'){ echo "selected=selected"; } echo">Amapá</option>
              <option value=\"am\" "; if($a[2] == 'Am'){ echo "selected=selected"; } echo">Amazonas</option>
              <option value=\"ba\" "; if($a[2] == 'Ba'){ echo "selected=selected"; } echo">Bahia</option>
              <option value=\"ce\" "; if($a[2] == 'Ce'){ echo "selected=selected"; } echo">Ceará</option>
              <option value=\"df\" "; if($a[2] == 'Df'){ echo "selected=selected"; } echo">Distrito Federal</option>
              <option value=\"es\" "; if($a[2] == 'Es'){ echo "selected=selected"; } echo">Espirito Santo</option>
              <option value=\"go\" "; if($a[2] == 'Go'){ echo "selected=selected"; } echo">Goiás</option>
              <option value=\"ma\" "; if($a[2] == 'Ma'){ echo "selected=selected"; } echo">Maranhão</option>
              <option value=\"ms\" "; if($a[2] == 'Ms'){ echo "selected=selected"; } echo">Mato Grosso do Sul</option>
              <option value=\"mt\" "; if($a[2] == 'Mt'){ echo "selected=selected"; } echo">Mato Grosso</option>
              <option value=\"mg\" "; if($a[2] == 'Mg'){ echo "selected=selected"; } echo">Minas Gerais</option>
              <option value=\"pa\" "; if($a[2] == 'Pa'){ echo "selected=selected"; } echo">Pará</option>
              <option value=\"pb\" "; if($a[2] == 'Pb'){ echo "selected=selected"; } echo">Paraíba</option>
              <option value=\"pr\" "; if($a[2] == 'Pr'){ echo "selected=selected"; } echo">Paraná</option>
              <option value=\"pe\" "; if($a[2] == 'Pe'){ echo "selected=selected"; } echo">Pernambuco</option>
              <option value=\"pi\" "; if($a[2] == 'Pi'){ echo "selected=selected"; } echo">Piauí</option>
              <option value=\"rj\" "; if($a[2] == 'Rj'){ echo "selected=selected"; } echo">Rio de Janeiro</option>
              <option value=\"rn\" "; if($a[2] == 'Rn'){ echo "selected=selected"; } echo">Rio Grande do Norte</option>
              <option value=\"rs\" "; if($a[2] == 'Rs'){ echo "selected=selected"; } echo">Rio Grande do Sul</option>
              <option value=\"ro\" "; if($a[2] == 'Ro'){ echo "selected=selected"; } echo">Rondônia</option>
              <option value=\"rr\" "; if($a[2] == 'Rr'){ echo "selected=selected"; } echo">Roraima</option>
              <option value=\"sc\" "; if($a[2] == 'Sc'){ echo "selected=selected"; } echo">Santa Catarina</option>
              <option value=\"sp\" "; if($a[2] == 'Sp'){ echo "selected=selected"; } echo">São Paulo</option>
              <option value=\"se\" "; if($a[2] == 'Se'){ echo "selected=selected"; } echo">Sergipe</option>
              <option value=\"to\" "; if($a[2] == 'To'){ echo "selected=selected"; } echo">Tocantins</option>
              </select>";
        echo "</li>";
        echo "<li><label class='eci-text'>Pagamento mínimo</label><input type='text' id='ex-pagamento' value='$a[3]' class='eci-text'  name='ex-pagamento' maxlength='9' />";
        echo "<li><input type='button' name='ex-btn-edt' class='btn' value='Editar' id='ex-btn-edit' /><span id='ex-response'></span></li>";
        echo "</ul>";
        echo "<ul class='eci-text'>";
        echo "<div class='eci-text'>Informações Adicionais</div>";
        echo "<div><textarea name='ext-textarea' id='ex-descricao' class='eci-text'>$a[4]</textarea>";
        echo "</ul>";
        echo "</form>";
        echo "</div>";

    }
    
}

?>