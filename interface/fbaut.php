<?php
session_start();
// Essa página tem como objetivo redirecionar o usuário para a checagem do facebook preservando a URL.

/*
 *  Define o diretório root do site
 */
if (dirname($_SERVER["PHP_SELF"]) == DIRECTORY_SEPARATOR) { $root = ""; } 
else { $root = dirname($_SERVER["PHP_SELF"]); }
$root = $root;

/*
 *  Pega as informações da URL
 */
$state = $_GET['state'];
$code = $_REQUEST['code'];

/*
 * Realiza o redirecionamento 
 */
if($_SESSION['id_usuario'] != "")
header("location: ../home/edit&fb=response&state=$state&code=$code#redes");
else
header("location: ..");


?>