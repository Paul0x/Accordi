<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Agendado                           //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class agendado
{
    /**
     *  Realiza as tarefas agendadas pelo programa
     *   Lista de tarefas:
     *     - Fecha os contatos expirados [ diário ]
     *     - Deleta os eventos expirados [ diário ]
     *     - Recalcula as músicas da semana [ semanalmente ]
     */
    
    
    // Variáveis de objetos
    private $contato;
    private $musica;
    private $evento;
    private $data_manage;
    private $conn;
    
    public function __construct($tipo)
    {
        /**
         *  Decide quais ações a ser realizadas.
         */
        
        
        switch($tipo)
        {
            case 0: 
                // Realizamos as funções diárias.
                $this->reloadContatos();
                $this->reloadEventos();
                break;
            case 1:
                // Realizamos as funções semanais
                $this->manageMS();
                break;
            
        }       
    }
    
    private function reloadContatos()
    {
        /** 
         *  Passa para fechado os contatos que passaram da data.
         */
        
        $data = date("Y-m-d");
        $this->conn = new conn();
        $this->conn->freeQuery("UPDATE contato SET status_contato = 1, id_read_contato = 6 WHERE data_contato < $data AND status_contato = 0",true,false);
        
    }
    
    private function reloadEventos()
    {
        /**
         *  Deleta todos os eventos onde os contatos 
         */
        
        $data = date("Y-m-d");
        $this->conn = new conn();
        $fetch = $this->conn->freeQuery("UPDATE evento SET status_evento = 'f' WHERE data_evento < $data",true,false);
        
    }
    
    private function manageMS()
    {
        /**
         *  Reseta as músicas da semana, adicionado as visualizações realizadas na semana dentro das visualizações gerais.
         */
        
        
        $this->conn = new conn();
        
        // Soma os valores dos dois campos.
        $this->conn->freeQuery("UPDATE musica SET view_musica = view_musica + view_week_musica",true,false);
        
        // Zera as visualizações da semana.
        $this->conn->freeQuery("UPDATE musica SET view_week_musica = 0",true,false);
        
    }

   
}