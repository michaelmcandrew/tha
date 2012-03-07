<?php

/* 
 * Author: rajesh@millertech.co.uk
 * Date: 04 Nov 2010
 */
 
require_once '../civicrm.config.php';
require_once 'CRM/Core/Config.php';

$debug = false;

function CivicrmDirectoryInformation( ) {
  global $debug;
    
  $config =& CRM_Core_Config::singleton();
        
  require_once 'CRM/Utils/System.php';
  require_once 'CRM/Utils/Hook.php';
  
  $sql = "SELECT * FROM civicrm_option_value WHERE option_group_id = 104";
  $dao = CRM_Core_DAO::executeQuery( $sql );
  
  while($dao->fetch()) {
      $chapterArray[$dao->option_group_id][$dao->value] =$dao->label; 
  }
  
  $sql = "SELECT what_keywords_should_we_use_to_i_344 as keywords ,
                 which_chapter_heading_s_should___343 as chapters ,
                 name_of_helpline__a_312 as helplines_name ,
                 id as id 
           FROM civicrm_value_directory_form_39";
           //drupal_search_text_360 
           //WHERE what_keywords_should_we_use_to_i_344 IS NOT NULL OR which_chapter_heading_s_should___343 IS NOT NULL OR name_of_helpline__a_312 IS NOT NULL";
  $dao = CRM_Core_DAO::executeQuery( $sql );
  $insert_sql = "";
  while($dao->fetch()) {
      $helplineName = "";
      if (!empty($dao->chapters)) {
          $temphelpArray = @explode('' , substr($dao->chapters , 1, strlen($dao->chapters)-2));
          $i = 0;
          foreach($temphelpArray as $hkey => $hval) {
              $helplineName .= " ";          
              $helplineName .= $chapterArray[$hval];
          }
      }
      if (!empty($dao->keywords)) {
          $helplineName .= " ";
          $helplineName .=  str_replace ( '' , ' ' , $dao->keywords);
      }
      if (!empty($dao->helplines_name)) {
          $helplineName .= " ";
          $helplineName .= $dao->helplines_name; 
      }
      //echo $helplineName." , ".$dao->id;echo "<hr />";
      $insert_sql = "UPDATE civicrm_value_directory_form_39 SET a__drupal_search_text_363 = '".addslashes($helplineName)."' WHERE id = ".$dao->id; 
      CRM_Core_DAO::executeQuery( $insert_sql );             
  }
  //exit;
  //echo $insert_sql;
  //CRM_Core_DAO::executeQuery( $insert_sql );
  //exit;
}
// Run
CivicrmDirectoryInformation();
?>