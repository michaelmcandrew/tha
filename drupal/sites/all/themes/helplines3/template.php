<?php
function helplines3_preprocess_node(&$node){
  if($node['field_showauthor'][0]['value']=='0'){
    $node['submitted']="";
  }
}
?>

<?php
function helplines3_menu_item_link($link) {

  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }

$link_options = $link['localized_options'];
$link_options['html'] = TRUE;
  
  return l('<span>'.$link['title'].'</span>', $link['href'], $link_options);
}
?>

<?php
function helplines3_preprocess_page(&$vars) {
  $menu = menu_navigation_links("accountmenu");
  $vars['accountmenu'] = theme('links', $menu);
}
?>