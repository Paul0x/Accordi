<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Ranking                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.
class ranking
{

   public $rartista;
   public $rcontratante;
   public $erro;
   private $conn;

   function __construct()
   {
      $this->conn = new conn();
   }

   
   public function pegarPos($mid)
   {
      $u = new usuario($mid);
      if($u->tipo == 1)
      {
         $this->rankingArtista();
         $lista = $this->rartista;
      }
      elseif($u->tipo == 2)
      {
         $this->rankingContratante();
         $lista = $this->rcontratante;
      }
      
      if($lista == "") return false;
      
      foreach($lista as $i => $infos)
      { 
         if($infos[2] == $u->login){
            return $i+1;
         }
      }
      return false;
   }
   
   public function rankingArtista()
   {
      $this->conn->prepareselect("usuario", "id", array("tipo_usuario","status_contato"), array(1,0), "same", "", array("INNER","contato"), PDO::FETCH_COLUMN, "all", array("count(id_contratante_contato)","DESC"), array("0","100"), "id_usuario", "AND",0); 
      $this->conn->compararCampos("id_usuario","id_artista_contato");
      $this->conn->executa();
      $resultado = $this->conn->fetch;
      if($resultado != "")
      {
      $a = count($resultado);
      for ($i = 0; $i < $a; $i++)
      {
         $idartista = $resultado[$i];
         $this->conn->prepareselect("musica", "id", "id_artista", $idartista, "same", "count");
         $this->conn->executa();
         $total = $this->conn->fetch['count(id_musica)'];
         $this->conn->prepareselect("avaliacao", "valor", "", "", "same", "sum", array("INNER", "musica", "id", "id_musica_avaliacao", "id_artista", $idartista));
         $this->conn->prepareselect("avaliacao", "sum(valor_avaliacao)", "id_artista_musica", $idartista, "same", "", array("INNER","musica"), NULL, "", "", "", "id_artista_musica", "AND", 2);
         $this->conn->compararCampos("id_musica","id_musica_avaliacao");
         $this->conn->executa();
         $soma = $this->conn->fetch['sum(valor_avaliacao)'];
         if ($total != 0 && $soma != 0 && $total != null)
         {  
            $media = $soma / $total;
            $media = number_format($media, 2, ',', '');
            $lista[$idartista] = $media;
            unset($media,$total,$soma);
         }
      }
      }
      if ($lista != NULL)
      {
         arsort($lista);
         foreach ($lista as $aid => $amedia)
         {            
            $u = new usuario($aid);
            $nome = $u->nome;
            $n_musicas = $u->numeroMusicas();
            $this->rartista[] = array($nome,$n_musicas,$amedia,$u->id,$u->login);
            unset($nome,$n_musicas,$u);
         }
         return true;
      }
      else
      {
         $this->erro[0] = "Ranking vazio";
         return false;
      }
   }

   public function rankingContratante()
   {
      $this->conn->prepareselect("usuario", "id", array("tipo_usuario","status_contato"), array("2",0), "same", "", array("INNER","contato"), PDO::FETCH_COLUMN, "all", array("count(id_artista_contato)","DESC"), array("0","100"), "id_usuario", "AND",0);
      $this->conn->compararCampos("id_usuario","id_contratante_contato");
      $this->conn->executa();
      $f = $this->conn->fetch;
      if ($f != "")
      {
         foreach($f as $i => $contratante)
         {
            $u = new usuario($contratante);
            $cc = $u->numeroContatos();
            $lista[$u->id] = $cc;
            unset($cc,$u);
         }
      }
      if($lista != NULL)
      {
         arsort($lista);
         foreach($lista as $cid => $cmedia)
         {
            //$this->conn->prepareselect("contratante","website","id",$cid);
            //$this->conn->executa();
            //$resultado = $this->conn->fetch;
            $this->conn->prepareselect("usuario",array("nome","login","id","cidade"),"id",$cid);
            $this->conn->executa();
            $resultado2 = $this->conn->fetch;
            $this->rcontratante[] = array($resultado2['nome_usuario'], $resultado2['cidade_usuario'], $cmedia,$resultado2['id_usuario'],$resultado2['login_usuario']);
            unset($resultado,$resultado2);
         }
         return true;
      }
      else
      {
      $this->erro[1] = "Ranking vazio";
      return false;
      }
   }

}