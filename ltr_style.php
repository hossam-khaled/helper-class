<?php
function proccess_css() {

  # read stylesheets from a file

  $sliderfile = "../assets/css/swiper.css";
  $stylesheetfile = "../khy-style.css";
  $stylesheet = "../style.css";

  $filen = file_get_contents($sliderfile);
  $filen .= file_get_contents($stylesheetfile);
  $filen .= file_get_contents($stylesheet);

  $filen = str_replace( 'left', 'vovo',$filen);

  $filen = str_replace( 'right', 'left' ,$filen);

  $filen = str_replace('vovo', 'right',$filen);

  $filen = str_replace("../assets/images/", "../zxc/", $filen);

  $filen = str_replace("assets/images/", "../assets/images/", $filen);

  $filen = str_replace("../assets/zxc/", "../assets/images/", $filen);

  $filen = str_replace("./images/", "../images/", $filen);

  $filen = preg_replace("/padding:(.*) (.*) (.*) (.*)\;/", "padding:$1 $4 $3 $2;", $filen);

  $filen = preg_replace("/margin:(.*) (.*) (.*) (.*)\;/", "margin:$1 $4 $3 $2;", $filen);

  $filen = preg_replace("/border-width:(.*) (.*) (.*) (.*)\;/", "border-width:$1 $4 $3 $2;", $filen);



  header("Content-Type: text/css");

  header("X-Content-Type-Options: nosniff");

  echo $filen;


  die();

}
proccess_css();



?>
