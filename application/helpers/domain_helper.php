<?php

function check_address(){
  if ((strpos($_SERVER['HTTP_HOST'], 'www.') === false)){
    redirect('http://www.'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
  }
}
