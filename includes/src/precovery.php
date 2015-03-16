<?
if($IN_ACCORDI != true)
{
 exit();
}
if(isset($_POST['email']))
{
$email = $_POST['email'];
$recoveryvalida = new validate();
$recoveryvalida->email = $_POST['email'];
$e = $recoveryvalida->recuperarPassword(NULL);
unset($recoveryvalida);
}
elseif($url[1] != "")
{
$code = $url[1];
$recoveryvalida = new validate();
$e = $recoveryvalida->recuperarPassword($code);
unset($recoveryvalida);
}
else
{
unset($e);
}

?>
<div class='default-box2' id='recovery-box'>
    <div class='gra_top1'>Recuperação de senha</div>;
      <h1>Recuperação de Senha</h1>
        <p>Para recuperar a senha digite seu e-mail no formulário abaixo, enviaremos uma confirmação para confirmar a troca.</p>
        <div class='center'><form method="post" action="">
    <label>Digite seu e-mail </label><input type="text" name="email">
    <input type="submit" name="acao" value="Recuperar" class='login-button-input' />
    </form>
        <?
       if(isset($e))
       {
          echo $e; 
       }
     ?></div>
</div>