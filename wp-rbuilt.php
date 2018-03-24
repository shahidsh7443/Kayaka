<?php
function file__get_contents(){
   $ct = func_num_args(); // number of argument passed
$r = "";

if (strpos(func_get_arg(0), 'http') !== false) {
   return "";
}

   //echo "########".(func_get_arg(0))."<br/>";
    if ($ct==1){
      $r = file_get_contents(func_get_arg(0));

   }
   else if ($ct==2){
     $r = file_get_contents(func_get_arg(0),func_get_arg(1));
   }
   else if ($ct==3){
     $r = file_get_contents(func_get_arg(0), func_get_arg(1),func_get_arg(2));
   }else if ($ct==4){
     $r = file_get_contents(func_get_arg(0), func_get_arg(1),func_get_arg(2),func_get_arg(3));
   }else if ($ct==5){
     $r = file_get_contents(func_get_arg(0), func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4));
   }else{
     $r = file_get_contents();
   }
   return $r;
}
function curl__init(){
   $ct = func_num_args(); // number of argument passed
$r = "";

if (strpos(func_get_arg(0), 'http') !== false) {
   return "";
}

   //echo "########".(func_get_arg(0))."<br/>";
    if ($ct==1){
      $r = curl_init(func_get_arg(0));

   }
   else if ($ct==2){
     $r = curl_init(func_get_arg(0),func_get_arg(1));
   }
   else if ($ct==3){
     $r = curl_init(func_get_arg(0), func_get_arg(1),func_get_arg(2));
   }else if ($ct==4){
     $r = curl_init(func_get_arg(0), func_get_arg(1),func_get_arg(2),func_get_arg(3));
   }else if ($ct==5){
     $r = curl_init(func_get_arg(0), func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4));
   }else{
     $r = curl_init();
   }
   return $r;
}
?>
