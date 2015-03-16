<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: vl-ajax.php - Realiza as validações básicas via AJAX
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Enviar o código HTML para o servidor pós requisição AJAX.
 *    Principais funções:
 *      - Processar as chamadas realizadas via AJAX.
 *      - Enviar para o javascript as requisições recebidas.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

require("main-ajax.php");

class AJAXvalidate extends AJAX
{
    public function cadastrarUsuario($login, $senha, $nome, $email, $tel, $logd, $bairro, $cidade, $estado, $website, $apelido, $sobrenome, $tipo)
    {
        /**
         *  Função para realizar novos cadastros no Accordi
         * 
         */
        
        $validate = new validate();
        try
        {
            $validate->cadastraUsuario($login, $senha, $nome, $email, $tel, $logd, $bairro, $cidade, $estado, $website, $apelido, $sobrenome, $tipo);
            echo json_encode(array("response" => 1));
            
        }
        catch(Exception $a)
        {
            echo json_encode(array("response" => 0,"error" => $a->getMessage()));
        }
    }
    
    public function validaEmail($email,$id)
    {
        /**
         *  Função para validar o e-mail enviado
         */
        
        $validate = new validate();
        
        // Verifica se existe um e-mail cadastrado com esse usuário.
        $a = $validate->ifExist($email, "email", "usuario", "");
        
        if ($id != NULL) 
        {
            $usuario = new usuario($id);
            if ($email == $usuario->email) 
            {
                $ev = true; // O e-mail existe mas é do usuário a ser utilizado.
            }
        }
        
        if ($a == false && $ev != true)
        {
            echo json_encode(array("response" => 1));
        }
        
        else
        {
            echo json_encode(array("response" => 0));
        }
        
    }
    
    public function validaSenha($senha)
    {
        /**
         *  Verifica se a senha informada é igual a senha do usuário atual.
         * 
         */
        
        $validate = new validate();
        if($senha == "" || $_SESSION['id_usuario'] == "")
            $a = false;
        else
        {
            $a = $validate->dadoPertence(md5($senha), $_SESSION['id_usuario'], "senha", "id", "usuario");            
        }
        
        if($a == true)
            echo json_encode(array("response" => 1));
        else
            echo json_encode(array("response" => 0));
        
    }
    
    public function validaLogin($login)
    {
        /**
         *  Verifica se o login informado já pertece a algum usuário.
         */
        
        $validate = new validate();
        $a = $validate->ifExist($login, "login", "usuario", "");
        if($a == true)
            echo json_encode(array("response" => 0));
        else
            echo json_encode(array("response" => 1));
            
    }
  
    

}








?>