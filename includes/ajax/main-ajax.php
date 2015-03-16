<?php
 /*********************************************
 * 
 * 
 *    Accordi© - 2011
 *    Arquivo: m-ajax.php / Funções gerais do AJAX.
 *    Desenvolvida por Paulo Felipe Possa Parreira
 *    Descrição: 
 *      Enviar o código HTML para o servidor pós requisição AJAX.
 *    Principais funções:
 *      - Reune funções gerais para realizar ações via AJAX.
 * 
 *********************************************/
if ($IN_ACCORDI != true)
{
   exit();
}

class AJAX
{
    
    /*
     *  @token
     *  HASHMD5 - [ Token md5 enviado pela requisição AJAX ]
     */
    protected $token;
    
    protected function ajax_true()
    {
        /*
         *  Verificamos se existe uma requisição ajax vinda do servidor.
         *  Valor de retorn: BOOL
         */
        
        if($_POST['majax'] == true)
            return true;
        else
            return false;
    }
   
    public function getToken($token)
    {
        /*
         *  Pega o hash MD5 enviado pela requisição AJAX.
         *  Valor de retorno: BOOL
         */        
         if(is_null($token))
             return false;
         else
             $this->token = $token;
             return true;
    }
    
    protected function showComment($tipo,$page,$page2='20')
    {
        /*
         *  Pega 20 comentários e imprime.
         *  Função abstrata.
         *  @tipo - INT [ Tipo de comentário que estamos buscando ]
         *  @page - INT [ Número de início para o limit dos comentários ]
         */
         $comments = new comentarios();
         $page = array($page,$page2);
         $c = $comments->listarComentario($this->id,$tipo,$page);
         unset($comments,$tipo,$page);
         return $c;
    }
    
   
    public function deleteComment($id)
    {
      /*
       *  Deleta o comentário e retorna se foi bem sucedido ou não.
       *  @id - INT [ Id do comentário a ser deletado ]
       */ 
       $comentario = new comentarios();
       $comentario->id = $id;
       $a = $comentario->deletarComentario();
       if($a != "true")
           echo "<div id='response'>0</div>";
       else
           echo "<div id='response'>1</div>";
       unset($comentario,$a,$id);
    }
    
    public function editComment($id,$text)
    {
      /*
       *  Edita o comentário e retorna o novo texto.
       *  @id - INT [ Id do comentário a ser editado ]
       *  @text - STRING [ Novo texto ]
       */ 
       
        $comentario = new comentarios();
        $comentario->id = $id;
        $e = $comentario->editarComentario($text);
        $c = $comentario->publicarComentario();
        
        if($e != "true")
           echo "<div id='response'></div>";
        else
           echo "<div id='response'>$c[2]</div>";
    }
    
    public function addComment($idr,$text,$tipo)
    {
      /*
       *  Adiciona um comentário e carrega ele na página.
       *  @idr - INT [ ID do receptor do recado ]
       *  @text - STRING [ Texto do recado ]
       *  @tipo - INT [ Tipo do recado ]
       */
        
        $comentario = new comentarios();
        $b = $comentario->novoComentario($_SESSION['id_usuario'],$idr,$tipo,$text);
        $c = $comentario->listarComentario($idr,$tipo,$page=array(0,1));
        if($c == false || $b == false)
            return false;
        $this->templateComment($c);
    }
    

}








?>