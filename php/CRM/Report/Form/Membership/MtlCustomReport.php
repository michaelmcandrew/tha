<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.0                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2009                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007.                                       |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2009
 * $Id$
 *
 */

require_once 'CRM/Report/Form.php';
require_once 'CRM/Member/PseudoConstant.php';
require_once 'CRM/Member/DAO/viewMtlCustomReport.php';

class CRM_Report_Form_Membership_MtlCustomReport extends CRM_Report_Form {

   // protected $_addressField = false;
    
   // protected $_emailField   = false;
    
    protected $_summary      = null;
    
    function __construct( ) {
        $this->_columns = 
            array( 'view_mtl_custom_report' =>
                   array( 'dao'     => 'CRM_Member_DAO_viewMtlCustomReport',
                          'fields'  =>
                          array( 'acc_no' => 
                                 array( 'title'      => ts( 'A/C No' ),
                                        'required'   => true,
                                        'no_repeat'  => true ),
										
								'mem_no' => 
                                 array( 'title'      => ts( 'Mem No' ),
                                        'default' => true
										),
										
								'band_type' => 
                                 array( 'title'      => ts( 'Band Type' ),
                                        'default' => true
										),
								'mem_status' => 
                                 array( 'title'      => ts( 'Membership Status' ),
                                        'default' => true
										),		
								'org_name' => 
                                 array( 'title'      => ts( 'Organization Name' ),
                                        'default' => true
										),
										
								'street_address' => 
                                 array( 'title'      => ts( 'Street Address' ),
                                        'default' => true
										),
										
								'street_name' => 
                                 array( 'title'      => ts( 'Street Name' ),
                                        'default' => true 
										),
										
								'supplemental_address_1' => 
                                 array( 'title'      => ts( 'Address1' ),
                                        'default' => true 
										),
										
								'supplemental_address_2' => 
                                 array( 'title'      => ts( 'Address2' ),
                                        'default' => true 
										),
								
								'supplemental_address_3' => 
                                 array( 'title'      => ts( 'Address3' ),
                                        'default' => true 
										),
										
								'city' => 
                                 array( 'title'      => ts( 'City' ),
                                        'default' => true 
										),
										
								'postal_code' => 
                                 array( 'title'      => ts( 'Postal code' ),
                                        'default' => true 
										),
										
								'country' => 
                                 array( 'title'      => ts( 'Country' ),
                                        'default' => true 
										),
								
                                'phone' => 
                                 array( 'title'      => ts( 'Phone' ),
                                        'default' => true 
										),
										
								'email' => 
                                 array( 'title'      => ts( 'Email' ),
                                        'default' => true 
										),
										
								'website' => 
                                 array( 'title'      => ts( 'Website' ),
                                        'default' => true 
										),
										
								'r_title' => 
                                 array( 'title'      => ts( 'Title (Mem Rep)' ),
                                        'default' => true 
										),
										
								'r_first_name' => 
                                 array( 'title'      => ts( 'First Name (Mem Rep)' ),
                                        'default' => true 
										),
										
								'r_last_name' => 
                                 array( 'title'      => ts( 'Last Name (Mem Rep)' ),
                                        'default' => true 
										),
										
								'r_job_title' => 
                                 array( 'title'      => ts( 'Job Title (Mem Rep)' ),
                                        'default' => true 
										),
										
								'c_title' => 
                                 array( 'title'      => ts( 'Title (CE)' ),
                                        'default' => true 
										),
										
								'c_first_name' => 
                                 array( 'title'      => ts( 'First Name (CE)' ),
                                        'default' => true 
										),
										
								'c_last_name' => 
                                 array( 'title'      => ts( 'Last Name (CE)' ),
                                        'default' => true 
										),
										
								'c_job_title' => 
                                 array( 'title'      => ts( 'Job Title (CE)' ),
                                        'default' => true 
										),
										
							),
                          
                          ),
                   
                  
                   );
				   //array();
        //echo "hi"; exit;
        parent::__construct( );
    }
    
    function preProcess( ) {
        $this->assign( 'reportTitle', ts('Custom Membership Report' ) );
        parent::preProcess( );
    }
    
    function select( ) {
        $select = $this->_columnHeaders = array( );
        
        foreach ( $this->_columns as $tableName => $table ) {
            if ( array_key_exists('fields', $table) ) {
                foreach ( $table['fields'] as $fieldName => $field ) {
                    if ( CRM_Utils_Array::value( 'required', $field ) ||
                         CRM_Utils_Array::value( $fieldName, $this->_params['fields'] ) ) {
                        if ( $tableName == 'civicrm_address' ) {
                            $this->_addressField = true;
                        } else if ( $tableName == 'civicrm_email' ) {
                            $this->_emailField = true;
                        }
                        $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['type']  = CRM_Utils_Array::value( 'type', $field );
                    }
                }
            }
        }
        
        $this->_select = "SELECT " . implode( ', ', $select ) . " ";
		//echo $this->_select; exit;
    }
    
	
	function from(){
		$this->_from = null;
        
        $this->_from = " FROM view_mtl_custom_report {$this->_aliases['view_mtl_custom_report']}";
	}
	
        
    function postProcess( ) {
        
        $this->beginPostProcess( );
        $sql  = $this->buildQuery( true );
       // echo $sql; exit;
        $rows = array( );
        $this-> buildRows( $sql, $rows );
       // echo $rows; exit;
        $this->formatDisplay( $rows );        
        $this->doTemplateAssignment( $rows );
        $this->endPostProcess( $rows);
        
    }
    
    
}