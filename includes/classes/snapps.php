<?php

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Social Networks App's              //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.

class snapps {
    
    /*
     * Facebook
     * 
     *  Vamos realizar a autenticação do usuário no facebook e realizar as chamadas necessárias pelo social plugin do Accordi
     *  OBS: Utilizando PHP SDK do facebook @ v3.1.1
     */
    
    /* Declaração de variáveis privadas */
    private $user_id; // Id do usuário @ facebook
    private $access_token; // Token de acesso ao facebook
    private $app_url = "http://www.accordi.com.br/interface/fbaut.php";
    private $app_params = array(  // Parametros utilizados para realizar conexão com a classe
        "appId" => "142206992525179",
        "secret" => "8b317e0915d56899db1c6d8696451891"
                               );
    private $user;  // Local onde armazenaremos ações do usuário.
    private $user_data; // Informações do usuário.
    private $app_on; // Instância da classe Facebook.
    
    
   public function facebookStart()
   {
       if($_SESSION['id_usuario'] == false)
           return false;
       
       $u = new usuario();
       if($u->facebook == "" && $this->user_id != "")
       {
           $a = $u->upFb($this->user_id);
           if($a == true)
               return true;
           else
               return false;
       }
       if($this->user_id != $u->facebook)
           return false;
       else
           return true;
   }
   public function autenticaFacebook()
   {
        $is_auth = false;
        $code = $_GET["code"];

        $app_id = $this->app_params['appId'];
        $app_secret = $this->app_params['secret'];
        $my_url = $this->app_url;
        
        if($_GET['fb'] != "response" && $is_auth == false) {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
        . $_SESSION['state']."&scope=user_activities,user_status,publish_stream";

        echo("<script> top.location.href='" . $dialog_url . "'</script>");
        }
        
        if($_GET['state'] == $_SESSION['state'] && $_GET['code'] != "") {
        $token_url = "https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
        . "&client_secret=" . $app_secret . "&code=" . $code;

        $response = file_get_contents($token_url);
        $params = null;
        parse_str($response, $params);

        $graph_url = "https://graph.facebook.com/me?access_token=" 
        . $params['access_token'];

        $user = json_decode(file_get_contents($graph_url));
        $_SESSION['fb_142206992525179_user_id'] = $user->id;
        $this->user_id = $user->id;
        $_SESSION['fb_142206992525179_access_token'] = $params['access_token'];
        if($this->facebookStart() == false)
            return false;

        return true;
        }
        else {
        return false;
        }
   }
   
   public function postOnWall($msg="teste")
   {
       if(isset($_SESSION['fb_142206992525179_user_id']))
       {
       $uid = $_SESSION['fb_142206992525179_user_id'];
       $at = $_SESSION['fb_142206992525179_access_token'];
       // Pegamos as informações necessárias para o envio
       $data = "message=".$msg."&access_token=".$at;
       $url = "https://graph.facebook.com/$uid/feed";
       
       
       // Vamos fazer uma requisição POST simples para enviar esses recados, não ta fácil para ninguém.
       $v = new validate;
       try
       {
       $pr = $v->do_post_request($url,$data);
       }
       catch(Exception $a)
       {
           
       }
       if($pr === false)
           return false;
       else
           return true;
       }
       else
       {
           return false;
       }
   }
   
   public function jsfbGetTokenID()
   {   
       /*
       *   Verificar se o usuário está logado e realizar o pedido do token
       */
       if($_SESSION['id_usuario'] != "" && $_SESSION['fb_142206992525179_access_token'] == ""):
       $u = new usuario();
       if($u->facebook != "")
       {
       ?>
        <div id="fb-root"></div>
        <script src="https://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(document).ready(function () {

        FB.init({ 
        appId: '142206992525179', 
        cookie: true, 
        xfbml: true, 
        status: true });

        FB.getLoginStatus(function (response) {
        var root = $("#dir-root").val();
        if (response.session) {
            $.ajax({
            type: 'POST',
            url: root+'/',
            data: {
            access_token: response.session.access_token // Envia o token para o servidor pelo método GET
            }
            }) 
        }
        else
        showWarn("Falha ao conectar-se com o Facebook.");
        });
        });
        </script>
            <?
        }
        return true;
    endif;
    return false;
   

   }
   
   public function getToken($tk)
   {
        /*
        *   Pega o valor recebido do token e passa para variável de sessão.
        */
        $u = new usuario();
        echo "ok sir";
        if($_SESSION['id_usuario'] != "" && $u->facebook != "" && $tk != "" && $_SESSION['fb_142206992525179_access_token'] == "")
        {
                $_SESSION['fb_142206992525179_user_id'] = $u->facebook;
                $_SESSION['fb_142206992525179_access_token'] = $tk;
                return true;
        }
        return false;
   }
   
}
?>