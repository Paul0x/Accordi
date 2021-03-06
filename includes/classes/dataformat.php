<?php

if ($IN_ACCORDI != true)
{
   exit();
}

///////////////////////////////////////////////////
//            Accordi - Classes                  //
//            Eventos                            //
///////////////////////////////////////////////////
// Changelog:
// v1.0 - Classe criada.

class dataformat
{
   
    // Data adicionada
    private $datainput;
    // Data formatada
    private $dataoutput;
    
    // Data e Hora
    private $data;
    private $hora;
    
    // EREG
    private $dateereg = "/^[0-9]{4}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}$/"; // Formato DATE do mysql
    private $tstampereg = "/^[0-9]{4}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1} [0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/"; // Formato TIMESTAMP do mysql
    // Datas em formato Unix
    private $datainputbuffer;
    private $dataoutputbuffer;
    
    // Meses com dia acima de 31
    private $meses_31 = array("01","03","05","07","08","10","12");
    private $meses_30 = array("04","06","09","11");
    
    // Número de dias/mês
    private $ndia_mes = array("01" => 31,"02" => 28,"03" => 31,"04" => 30,"05" => 31,"06" => 30,"07" => 31,"08" => 31,"09" => 30,"10" => 31,"11" => 30,"12" => 31);
    
    public function pegarData($data)
    {
      date_default_timezone_set('America/Sao_Paulo');
      if(preg_match($this->dateereg, $data))
      {
          $this->datainput = $data." 00:00:00";
          $separador = explode(" ", $this->datainput);
          $this->hora = explode(":",$separador[1]);
          $this->data = explode("-",$separador[0]);
          $this->datainputbuffer = mktime($this->hora[0], $this->hora[1], $this->hora[2], $this->data[1], $this->data[2], $this->data[0]);
      }
      elseif(preg_match($this->tstampereg, $data))
      {
          $this->datainput = $data;
          $separador = explode(" ", $this->datainput);
          $this->hora = explode(":",$separador[1]);
          $this->data = explode("-",$separador[0]);
          $this->datainputbuffer = mktime($this->hora[0], $this->hora[1], $this->hora[2], $this->data[1], $this->data[2], $this->data[0]);
      }
      else
      {
          return false;
      }
      return true; 
    }
    
    public function getUnix()
    {
       return $this->datainputbuffer; 
    }
    
    public function validaData($data,$valida_apos=false)
    {
        /**
         *  @param date $data
         *  @param bool $valida_apos
         */
        $this->pegarData($data);
        if($valida_apos == true)
        {
            if($this->datainputbuffer <=time())
                return false;
        }
        
        
        // Verifica se o o mês informado preenche os requerimentos de dias.
        if(!in_array($this->data[1],$this->meses_30))
        {
            if($this->data[2] > 31)
                    return false;
        }
        if(!in_array($this->data[1],$this->meses_31))
        {
            if($this->data[2] > 30)
                    return false;
        }
        if($this->data[1] == "02")
        {
            if($this->data[2] > 28)
                    return false;
        }
        if(preg_match($this->dateereg,$data))
            return true;
        else
            return false;
    }
    public function defineHorario($hours=true,$simplifica=true)
    {
       /**
        *
        * @param BOOL $hours
        * @param BOOL $simplifica
        * @return ARRAY ou FALSE
        */
        
       $this->dataoutputbuffer = time();
       $diferenca = $this->dataoutputbuffer - $this->datainputbuffer;
       if($simplifica == true)
       {
           if($diferenca == 0)
               return "Agora";
           elseif($diferenca < 60)
               return $diferenca." segundos atrás";
           elseif($diferenca <= 3600)
           {
              $minutos = number_format($diferenca/60,0);
              return $minutos." minutos atrás";
           }
           elseif($diferenca/3600 <= 24)
           {
              $horas = number_format($diferenca/3600,0);
              return $horas. " horas atrás";
           }
           else
           {
             if($diferenca/3600 == 24 || $diferenca/3600 < 48)
                 return "Ontem";

             $hora = $this->data[2]."/".$this->data[1]."/".$this->data[0];
             if($hours == true)
                 $hora.= " às ".$this->hora[0].":".$this->hora[1];
             return $hora;
           }
       }
       else
       {
           $hora = $this->data[2]."/".$this->data[1]."/".$this->data[0];
           if($hours == true)
               $hora.= " às ".$this->hora[0].":".$this->hora[1];
           return $hora;           
       }
    }
    
    public function formatData()
    {
        /**
         * @return string
         */
        if($this->data == null)
                return false;
        return $this->data[2]."/".$this->data[1]."/".$this->data[0];
    }
    
    public function pegarWeekDay()
    {
        if($this->datainputbuffer == "")
                return false;
        $sn = date("w", $this->datainputbuffer); // Pega o dia de forma numérica
        switch($sn) // Pega o dia na forma de string
        {
        case '0': $ss = "Domingo"; break;
        case '1': $ss = "Segunda"; break;
        case '2': $ss = "Terça"; break;
        case '3': $ss = "Quarta"; break;
        case '4': $ss = "Quinta"; break;
        case '5': $ss = "Sexta"; break;
        case '6': $ss = "Sábado"; break;
        }
        return array($sn,$ss);
    }
    
    public function pegarMaxMes($m,$ano="")
    {
       /**
        *  Entra a o número máximo de dias em um determinado mês/ano
        *  @param int $m
        *  @param int $ano
        *  @return int;
        */
       
       if($ano == "")
           $ano = date("Y");
        
       if($m == "02")
       {
           if($ano % 400 == 0 || ( $ano % 100 != 0 && $ano % 4 == 0 ))
           {
                $this->ndia_mes[$m] = $this->ndia_mes[$m]+1;
           }
       }
       return $this->ndia_mes[$m];
    }
    
    public function selectAnos($qntd,$classe,$id,$ano=0,$selected="")
    {
        /**
         *  @param INT $qntd
         *  @param INT $ano
         *  @param STRING $classe
         *  @return Select com os anos requisitados
         */
        
        if($ano == 0)
            $ano = date("Y");
        
        echo "<select class='$classe' id='$id'>";
        for($i=0;$i<=$qntd;$i++)
        {
            $ano-=1;
            if($selected == $ano && $selected != "")
                echo "<option value='$ano' selected='selected'>$ano</option>";
            else
                echo "<option value='$ano'>$ano</option>";
        }
        echo "</select>";
        
    }
    
    public function nomeMes()
    {
        /**
         * @return string $mes
         */
        
        switch($this->data[1])
        {
            case '01': $mes = "Janeiro"; break;
            case '02': $mes = "Fevereiro"; break;
            case '03': $mes = "Março"; break;
            case '04': $mes = "Abril"; break;
            case '05': $mes = "Maio"; break;
            case '06': $mes = "Junho"; break;
            case '07': $mes = "Julho"; break;
            case '08': $mes = "Agosto"; break;
            case '09': $mes = "Setembro"; break;
            case '10': $mes = "Outubro"; break;
            case '11': $mes = "Novembro"; break;
            case '12': $mes = "Dezembro"; break;
            default: $mes =  null; break;
        }
        return $mes;
    }
}