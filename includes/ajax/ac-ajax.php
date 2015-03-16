<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: pt-ajax.php - Imprime um arquivo .xml dinâmico para a função de autocomplete.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
  *         Gera arquivo XML dinâmico para autocomplete.
 *    Principais funções:
 *      - Processar as chamadas realizadas via AJAX.
 *      - Enviar para o javascript as requisições recebidas.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

require("includes/classes/autocomplete.php");
require("main-ajax.php");

class AJAXautocomplete extends AJAX
{
    
    
    private $valor;
    private $id;
    private $ac;
    
    public function loadAutoComplete()
    {
        /**
         *  Carrega a classe AutoComplete
         */
        
        $this->ac = new autocomplete();
               
    }
   
    
    public function autoComplete($id,$valor)
    {
        /**
         *  Realiza a função do autocomplete.
         */
        
        $this->loadAutoComplete();
        
        try
        {
            $this->ac->loadId($id);
            $itens = $this->ac->loadCampos($valor); 
            echo json_encode($itens);
        }
        catch(Exception $a)
        {   
            
        }
    }
    
    
    
   
    
    

}








?>