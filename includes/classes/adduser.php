<?
$IN_ACCORDI = true;
include("classes.php");
$v = new validate();
for($i=0;$i<=5;$i++)
{
   $nome = "";
   $cpf = 0;
   $login = "";
   $nums = array(0,1,2,3,4,5,6,7,8,9);
   $letras = "abcdefghijklmnopqrstuvwxyz";
   $nomes = array("Paulo","Paula","João","Joana","Jéssica","Ana","Daniel","Daniela","Lívia","Maria","Mariana","Marco","Débora","Vitória","Eduardo","Eduarda","Victor","Valéria","Lucas","Luis","Leonardo","Leandro","Larissa","Lauro","Larissa","Laura","Caio","Catia","Caue","Marina","Barbara","Flavio","Flavia","Felipe","Fernando","Fernada","Marcela","Rogério","Iuri","Jorge","Michael","Cleber","Renata","Sofia","Denise","Geraldo","Raquel","Dante","Andreia","Carolina","Mateus","Marta","Marcia","Marcos","Vinícius","Humberto","André","Igor","Pablo","Otávio","Orlando","Lombarde","Itálo","Icaro","Iara","Israel","Saulo","Rodolfo","Ronaldo","Raimundo","Rebecca","Roberta","Reginaldo","Geová");
   for($ii = 0; $ii<=10; $ii++)
   {
     $r = rand(0,37);  
     $login.= $letras[$r];
     $r = rand(0,8);
     $cpf.= $nums[$r];
     $r = rand(0,count($nomes)-1); 
     $nome = $nomes[$r];
     $tipo = rand(1,2);
   }
   
   $f = $v->cadastraUsuario($login, "123456", "$nome", "abc$login@h.com", "(31)3843-4323", "", "", "", "", "", "", "", $tipo); 
   if($f == true);
   if($tipo == 1) $t = "artista";
   if($tipo == 2) $t = "contratante";
   echo "$i - Usuário $login cadastrado como $t - nome: $nome <br />";    
}

?>