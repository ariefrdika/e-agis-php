<?php
  class getAPI{
    function __construct($getUrl){
      $url = simplexml_load_file($getUrl);
      $this->xml = $url;
    }

    function getKodeCuaca($value){
      if($value==0||$value==100){
        return("Cerah");
      }
      elseif ($value==1||$value==101) {
        return("Cerah Berawan");
      }
      elseif ($value==2||$value==102) {
        return("Cerah Berawan");
      }
      elseif ($value==3||$value==103) {
        return("Berawan");
      }
      elseif ($value==4||$value==104) {
        return("Berawan Tebal");
      }
      elseif ($value==5) {
        return("Udara Kabur");
      }
      elseif ($value==10) {
        return("Asap");
      }
      elseif ($value==45) {
        return("Kabut");
      }
      elseif ($value==60) {
        return("Hujan Ringan");
      }
      elseif ($value==61) {
        return("Hujan Sedang");
      }
      elseif ($value==63) {
        return("Hujan Lebat");
      }
      elseif ($value==80) {
        return("Hujan Lokal");
      }
      elseif ($value==95) {
        return("Hujan Petir");
      }
      elseif ($value==97) {
        return("Hujan Petir");
      }
      else{
        return("Data tidak diketahui!");
      }
    }

    function getKodeCuacaWarna($value){
      if($value==0||$value==100){
        return("#FAC900");
      }
      elseif ($value==1||$value==101) {
        return("#DACF4B");
      }
      elseif ($value==2||$value==102) {
        return("#DACF4B");
      }
      elseif ($value==3||$value==103) {
        return("#90b8cf");
      }
      elseif ($value==4||$value==104) {
        return("#094479");
      }
      elseif ($value==5) {
        return("#8e7578");
      }
      elseif ($value==10) {
        return("#564144");
      }
      elseif ($value==45) {
        return("#999999");
      }
      elseif ($value==60) {
        return("#8fe0ff");
      }
      elseif ($value==61) {
        return("#2daad8");
      }
      elseif ($value==63) {
        return("#54416d");
      }
      elseif ($value==80) {
        return("#2b235a");
      }
      elseif ($value==95) {
        return("#094479");
      }
      elseif ($value==97) {
        return("#094479");
      }
      else{
        return("Data tidak diketahui!");
      }
    }

    function getMainData(){
      date_default_timezone_set("Asia/Bangkok");
      $data = array();
      foreach ($this->xml->forecast->area as $kota) {
        if ($kota['id']=='1200076'){ break; }
        $temp = array();

        $temp4timeWeater = array();
        $count = 1;
        foreach ($kota as $parameter) {
          if($parameter['id'] == "weather"){
            foreach ($parameter->timerange as $times) {
              if(strtotime(date("YmdHms")) <= strtotime($times['datetime'])){
                $temp4timeWeater['datetime'] = date("d M Y (H:i)", strtotime($times['datetime']->__toString()));;
                $temp4timeWeater['cuaca'] = $this->getKodeCuaca($times->value[0]);
                array_push($temp, $temp4timeWeater);
                if($count==4){ break; }
                $count++;
              }
            }
          }
        }
        $data[$kota['description']->__toString()] = $temp;
      }
      return($data);
    }

    function cuacaSaatIni(){
      date_default_timezone_set("Asia/Bangkok");
      $data = array();
      foreach ($this->xml->forecast->area as $kota) {
        if ($kota['id']=='1200076'){ break; }
        $temp = array();

        $temp4timeWeater = array();
        foreach ($kota as $parameter) {
          if($parameter['id'] == "weather"){
            foreach ($parameter->timerange as $times) {
              if(strtotime(date("YmdHms")) >= strtotime($times['datetime'])){
                $temp4timeWeater['datetime'] = date("d M Y (H:i)", strtotime($times['datetime']->__toString()));
                $temp4timeWeater['cuaca'] = $this->getKodeCuaca($times->value[0]);
                $temp4timeWeater['warna'] = $this->getKodeCuacaWarna($times->value[0]);
              }
            }
            array_push($temp, $temp4timeWeater);
          }
        }
        $data[$kota['description']->__toString()] = $temp;
      }
      return($data);
    }
  }
?>
