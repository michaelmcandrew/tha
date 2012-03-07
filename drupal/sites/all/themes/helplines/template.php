<?php
function helplines_preprocess_node(&$node){
  if($node['field_showauthor'][0]['value']=='0'){
    $node['submitted']="";
  }
}


