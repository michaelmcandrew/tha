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
  
  // Truncate table - mtl_civicrm_directory_information 
  $sql = "TRUNCATE TABLE civicrm_value_national_helplines_boroughs";
  CRM_Core_DAO::executeQuery( $sql );
  
  // get all the borough names and store it in a array
  $sql = "SELECT * FROM civicrm_option_value WHERE option_group_id IN (96 , 97 , 98 , 99 , 100 , 101)";
  $dao = CRM_Core_DAO::executeQuery( $sql );
  
  while($dao->fetch()) {
      $boroughArray[$dao->option_group_id][$dao->value] =$dao->label; 
  }
  
  $tableNameArray = array(
                          '97' => 'local_helplines_england' ,
                          '98' => 'local_helplines_rest_of_england' ,
                          '99' => 'local_helplines_scotland' ,
                          '100' => 'local_helplines_wales' ,
                          '101' => 'local_helplines_northern_ireland' ,
                          );
  
  $sql = "SELECT entity_id as contact_id ,  a__national_helplines_347 AS national_helplines,
                a__local_helplines__england__who_348 AS local_helplines_england,
                	a__local_helplines__northern_ire_352 AS local_helplines_northern_ireland,
                a__local_helplines__rest_of_engl_350 AS local_helplines_rest_of_england,
                a__local_helplines__scotland_349 AS local_helplines_scotland,
                a__local_helplines__wales_351 AS local_helplines_wales  
          FROM civicrm_value_directory_form_39 WHERE a__national_helplines_347 IS NOT NULL
                  ";
  $dao = CRM_Core_DAO::executeQuery( $sql );
  $insert_sql = "";
  while($dao->fetch()) {
      //print_r ($dao);exit;
      $local_borough = 0;
      $helplineName = "";
      $boroughName = "";
      if (!empty($dao->national_helplines)) {
          $temphelpArray = @explode('' , substr($dao->national_helplines , 1, strlen($dao->national_helplines)-2));
          $helpline_name = "";
          $i = 0;
          foreach($temphelpArray as $hkey => $hval) {
              if (!empty($helplineName)) $helplineName .=", ";
              $helplineName .= $boroughArray['96'][$hval];
          }
          
          if (!empty($dao->local_helplines_england)) {
              $tempArray = array();
              $tempArray = @explode('' , substr($dao->local_helplines_england , 1, strlen($dao->local_helplines_england)-2));
              foreach($tempArray as $key => $val) {
                  if (!empty($boroughName)) $boroughName .=", ";
                  $boroughName .= $boroughArray['97'][$val];
              }    
          }
          
          if (!empty($dao->local_helplines_northern_ireland)) {
              $tempArray = array();
              $tempArray = @explode('' , substr($dao->local_helplines_northern_ireland , 1, strlen($dao->local_helplines_northern_ireland)-2));
              foreach($tempArray as $key => $val) {
                  if (!empty($boroughName)) $boroughName .=", ";
                  $boroughName .= $boroughArray['101'][$val];
              }    
          }
          
          if (!empty($dao->local_helplines_rest_of_england)) {
              $tempArray = array();
              $tempArray = @explode('' , substr($dao->local_helplines_rest_of_england , 1, strlen($dao->local_helplines_rest_of_england)-2));
              foreach($tempArray as $key => $val) {
                  if (!empty($boroughName)) $boroughName .=", ";
                  $boroughName .= $boroughArray['98'][$val];
              }    
          }
          
          if (!empty($dao->local_helplines_scotland)) {
              $tempArray = array();
              $tempArray = @explode('' , substr($dao->local_helplines_scotland , 1, strlen($dao->local_helplines_scotland)-2));
              foreach($tempArray as $key => $val) {
                  if (!empty($boroughName)) $boroughName .=", ";
                  $boroughName .= $boroughArray['99'][$val];
              }    
          }
          
          if (!empty($dao->local_helplines_england)) {
              $tempArray = array();
              $tempArray = @explode('' , substr($dao->local_helplines_wales , 1, strlen($dao->local_helplines_wales)-2));
              foreach($tempArray as $key => $val) {
                  if (!empty($boroughName)) $boroughName .= ", ";
                  $boroughName .= $boroughArray['100'][$val];
              }    
          }
    
          $insert_sql = "INSERT INTO civicrm_value_national_helplines_boroughs SET contact_id = '".$dao->contact_id."' , national_helplines = '$helplineName' , borough_name = '$boroughName';";
          CRM_Core_DAO::executeQuery( $insert_sql );
      }    
  }
  
  
  /*foreach($tableNameArray as $tkey => $tval) {
              //echo $dao->$tval;    
              if (!empty($dao->$tval)) {
                  $tempEngArray = array();
                  $tempEngArray = @explode('' , substr($dao->$tval , 1, strlen($dao->$tval)-2));
                  foreach($tempEngArray as $key => $val) {
                      $boroughNameArray[] = $boroughArray[$tkey][$val];
                      //echo $hval.",".$val."<br />";
                  }
              }
          }
          $i++;*/
  
  //echo $insert_sql;
  CRM_Core_DAO::executeQuery( $insert_sql );
  exit;
}
// Run
CivicrmDirectoryInformation();
?>