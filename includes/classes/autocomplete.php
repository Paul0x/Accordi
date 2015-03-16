<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            AutoComplete                       //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.

class autocomplete
{
    
    private $conn;
    private $xml;
    private $id;
    private $campos_pesquisa;
    
    public function __construct()
    {
        // Gera o objeto para conexão do BD.
        $this->conn = new conn();
    }
    
    public function loadId($id)
    {
        /**
         *  Carrega o ID do autocomplete e verifica se o mesmo existe no banco de dados.
         *  @param int $id         * 
         */
        
        if(!is_numeric($id))
        {
            throw new Exception("O id informado é inválido.",48);
        }
        
        $this->conn->prepareselect("autocomplete_querys",array("tabela","valor","nome","tipo","pesquisarpor"),"id",$id);
        $this->conn->executa();
        
        if($this->conn->fetch == "")
        {
            throw new Exception("Não foi enocntrado nenhum registro de autocomplete",49);
        }
        
        // Honestamente, eu não quero sobrecarregar o BD com esse autocomplete. Vou adicionar o ID em uma variável de sessão para verificar no fúturo.
        $_SESSION['ac_query'] = array($id,true);
        
        // Passo os valores para os campos de pesquisa do objeto.
        $this->campos_pesquisa = $this->conn->fetch;      
    }
    
    public function loadCampos($valor)
    {
        /**
         *  Pesquisa os campos e gera o XML.
         *  @param string $valor
         */
        
        if($this->campos_pesquisa == "")
        {
            throw new Exception("Não existe nenhum campo de pesquisa.",50);
        }
        
        // Verifica se o valor não é vazio, afinal não vamos dar sugestões para nada.
        if(trim($valor) == "")
        {
            throw new Exception("O valor é nulo, seu filho da puta.");
        }
        
        try
        {
            switch($this->campos_pesquisa[3])
            {
                case 'xml': $itens = $this->pesquisaValorXML($valor); break;
                case 'html': $itens = $this->pesquisaValorSQL($valor); break;
            }
            
            return $itens;
        }
        catch(Exception $a)
        {
            throw new Exception($a->getMessage());
        }
    }
    
    private function pesquisaValorSQL($valor)
    {
        /**
         *  Pesquisa um valor dentro da query estipulada.
         *  @param $valor
         *  
         *      Estrutura do $campos_pesquisa
         *          [0] => Tabela para pesquisa
         *          [1] => Campo VALOR do autocomplete
         *          [2] => Campo PESQUISADO do autocomplete
         *          [3] => Tipo do autocomplete
         */
        
        if($this->campos_pesquisa == "")
        {
            throw new Exception("Não existe nenhum campo de pesquisa.",50);
        }
        
        if($this->campos_pesquisa[4] == 'v')
        {
            $pesquiasar = $this->campos_pesquisa[1];
        }
        else
        {
            $pesquiasar = $this->campos_pesquisa[2];            
        }
        
        // Realiza a verificação dos valores.
        $this->conn->prepareselect($this->campos_pesquisa[0],array($this->campos_pesquisa[1],$this->campos_pesquisa[2]),$pesquiasar,$valor,"like","","",PDO::FETCH_NUM,"all","",10);   
        $this->conn->executa();
        
        // Verifica se não encontraram nenhum valor.
        if($this->conn->fetch == "")
        {
            throw new Exception("Nenhum registro encontrado",51);
        }
        
        $itens = array();   
        foreach($this->conn->fetch as $i => $v)
        {
            if(!in_array($v[0],$itens))
            {
                $lista_itens[] = array("valor" => $v[0], "item" => $v[1]);  
                $itens[] = $v[0];
            }
        }
        
        
        //$lista_itens = array_unique($lista_itens);
        return $lista_itens;
    }
    
    private function pesquisaValorXML($valor)
    {
        /**
         *  Abre o arquivo XML estático e procura dentro dele.
         *  @param string $valor         
         * 
         *      Estrutura do $campos_pesquisa
         *          [0] => Nome do XML
         *          [1] => Campo VALOR do autocomplete
         *          [2] => Campo PESQUISADO do autocomplete
         *          [3] => Tipo do autocomplete
         */
        
        if($this->campos_pesquisa == "")
        {
            throw new Exception("Não existe nenhum campo de pesquisa.",50);
        }
        
        // Instancia a classe para gerenciar o XML
        $count_ac = 0; // Lista quantos valores já existem dentro da query.
        try
        {
            $xml_file = new DOMDocument();
            $xml_file->load("xml/".$this->campos_pesquisa[0].".xml");
            foreach($xml_file->documentElement->childNodes as $valores)
            {
                // Realiza a leitura de um XML e joga as informações para dentro de um Array. 
                // Vamos verificar se o valor do módulo tem caractéres errados.
                if(preg_match("/".$valor."/i",$valores->nodeValue))
                {                    
                    $lista_completa[] = array("valor" => $valores->getAttribute('valor'), "item" => $valores->nodeValue);  
                    $count_ac++;
                }
                
                if($count_ac == 10)
                    break;                        
            }
            
            if($lista_completa == "")
                throw new Exception("Nenhum valor encontrado.");
            
            return $lista_completa;
        }
        catch(DOMException $a)
        {
            throw new Exception("Impossível abrir arquivo xml.");
        }
        
        
    }
    
}



?>﻿