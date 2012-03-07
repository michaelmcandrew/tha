<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2010                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */

require_once 'CRM/Contact/Form/Search/Interface.php';

class CRM_Contact_Form_Search_Custom_MembershipAggregate
   implements CRM_Contact_Form_Search_Interface {

    protected $_formValues;

    function __construct( &$formValues ) {     
        $this->_formValues = $formValues;

        /**
         * Define the columns for search result rows
         */
        $this->_columns = array( 
                                 ts('Name'      )   => 'sort_name' ,
                                 ts('Address'      )   => 'address' ,
                                 ts('City'      )   => 'city' ,
                                 ts('Postal'      )   => 'postal_code' ,
                                 ts('Email'      )   => 'email' ,
                                 ts('Phone'      )   => 'phone'
                                 );
    }

    function buildForm( &$form ) {
        /**
         * You can define a custom title for the search form
         */
        $this->setTitle('Find Membership');

        /**
         * Define the search form fields here
         */
         
        /*
        require_once "CRM/Member/DAO/MembershipType.php";
        $memType =& new CRM_Member_DAO_MembershipType();
        $memType->whereAdd('is_active = 1'); 
        $memType->find(true);
        
        while($memType->fetch()){
    			$mem_type[$memType->id] = t($memType->name);
    		}
    		
    		$form->addElement( 'select',
                    'membership_type',
                    ts( 'Membership Type' ),
                    $mem_type ,
                    array("size"=>"5", "multiple" , style =>"width:200px")
                    );
        */
        
        require_once "CRM/Contact/DAO/RelationshipType.php";
        $relType =& new CRM_Contact_DAO_RelationshipType();
        $relType->find(true);
        
        while($relType->fetch()){
    			//$relationship_type[$relType->id] = t($relType->label_a_b);
    			$relationship_type[$relType->id] = t($relType->label_b_a);
    		}
        
        $form->addElement( 'select',
                    'relationship_type',
                    ts( 'Relationship Type' ),
                    $relationship_type
                    //array("size"=>"5", "multiple" , style =>"width:200px")
                    );          
    		
    		require_once "CRM/Member/DAO/MembershipStatus.php";
        $memStatus =& new CRM_Member_DAO_MembershipStatus();
        $memStatus->whereAdd('is_active = 1'); 
        $memStatus->find(true);
        
        $mem_status[1] = 'New';
        while($memStatus->fetch()){
    			$mem_status[$memStatus->id] = t($memStatus->name);
    		}
    		
        $form->addElement( 'select',
                    'membership_status',
                    ts( 'Membership Status' ),
                    $mem_status
                    //array("size"=>"5", "multiple" , style =>"width:200px")
                    );

        /**
         * If you are using the sample template, this array tells the template fields to render
         * for the search form.
         */
        $form->assign( 'elements', array( 'relationship_type' , 'membership_status' ) );
    }

    /**
     * Define the smarty template used to layout the search form and results listings.
     */
    function templateFile( ) {
       return 'CRM/Contact/Form/Search/Custom/Sample.tpl';
    }
       
    /**
      * Construct the search query
      */       
    function all( $offset = 0, $rowcount = 0, $sort = null,
                  $includeContactIDs = false, $onlyIDs = false ) {
        
        // SELECT clause must include contact_id as an alias for civicrm_contact.id
        if ( $onlyIDs ) {
            $select  = "DISTINCT contact_a.id as contact_id";
        } else {
            $select  = "
DISTINCT contact_a.id as contact_id
, contact_a.sort_name as sort_name
";
//, contact_e.email as email
//, contact_p.phone as phone
//, contact_address.street_address as address
//, contact_address.city as city
//, contact_address.postal_code as postal_code
        }
        $from  = $this->from( );

        $where = $this->where( $includeContactIDs );

        $having = $this->having( );
        if ( $having ) {
            $having = " HAVING $having ";
        }

        $sql = "
SELECT $select
FROM   $from
WHERE  $where
GROUP BY contact_a.id
$having
";
//GROUP BY contact_a.id
        //for only contact ids ignore order.
        if ( !$onlyIDs ) {
            // Define ORDER BY for query in $sort, with default value
            if ( ! empty( $sort ) ) {
                if ( is_string( $sort ) ) {
                    $sql .= " ORDER BY $sort ";
                } else {
                    $sql .= " ORDER BY " . trim( $sort->orderBy() );
                }
            } else {
                $sql .= "ORDER BY contact_id desc";
            }
        }

        if ( $rowcount > 0 && $offset >= 0 ) {
            $sql .= " LIMIT $offset, $rowcount ";
        }
        echo $sql;exit;
        return $sql;
    }
    
    function from( ) {
         
        $membership_status = $this->_formValues['membership_status'];
        $relationship_type = $this->_formValues['relationship_type'];  
    
        $from_tables = "
civicrm_contact AS contact_a
";
//LEFT JOIN civicrm_email as contact_e ON contact_a.id = contact_e.contact_id
//LEFT JOIN civicrm_phone as contact_p ON contact_a.id = contact_p.contact_id
//LEFT JOIN civicrm_address as contact_address ON contact_a.id = contact_address.contact_id
        if(!empty($membership_status)) {
            //$from_tables .= ", civicrm_membership AS member";
            $from_tables .= " LEFT JOIN civicrm_membership as member ON member.contact_id = contact_a.id";
        }
        if(!empty($relationship_type)) {
            //$from_tables .= ", civicrm_relationship AS relation";
            $from_tables .= " LEFT JOIN civicrm_relationship AS relation ON relation.contact_id_a = contact_a.id";
        }
        return $from_tables;

    }

     /*
      * WHERE clause is an array built from any required JOINS plus conditional filters based on search criteria field values
      *
      */
    function where( $includeContactIDs = false ) {
    
        $membership_status = $this->_formValues['membership_status'];
        $relationship_type = $this->_formValues['relationship_type'];
    
        $clauses = array( );
        
//        $clauses[] = "contact_a.contact_type = 'Individual'";
//        $clauses[] = "contact_a.id = contact_e.contact_id";
//        $clauses[] = "contact_e.is_primary='1'";
//        $clauses[] = "contact_a.id = contact_p.contact_id";
//        $clauses[] = "contact_p.is_primary='1'";
//        $clauses[] = "contact_a.id = contact_address.contact_id";
//        $clauses[] = "contact_address.is_primary='1'";
        
        if(!empty($membership_status)) {
            //$clauses[] = "member.contact_id = contact_a.id";
            $clauses[] = "member.status_id = $membership_status";
        }
        if(!empty($relationship_type)) {
            //$clauses[] = "relation.contact_id_b = contact_a.id";
            $clauses[] = "relation.relationship_type_id = $relationship_type";
        }
        $clauses[] = " 1";

        return implode( ' AND ', $clauses );
    }

    function having( $includeContactIDs = false ) {
        $clauses = array( );
        $min = CRM_Utils_Array::value( 'min_amount', $this->_formValues );
        if ( $min ) {
            $min = CRM_Utils_Rule::cleanMoney( $min );
            $clauses[] = "sum(contrib.total_amount) >= $min";
        }

        $max = CRM_Utils_Array::value( 'max_amount', $this->_formValues );
        if ( $max ) {
            $max = CRM_Utils_Rule::cleanMoney( $max );
            $clauses[] = "sum(contrib.total_amount) <= $max";
        }

        return implode( ' AND ', $clauses );
    }

    /* 
     * Functions below generally don't need to be modified
     */
    function count( ) {
           $sql = $this->all( );
           
           $dao = CRM_Core_DAO::executeQuery( $sql,
                                              CRM_Core_DAO::$_nullArray );
           return $dao->N;
    }
       
    function contactIDs( $offset = 0, $rowcount = 0, $sort = null) { 
        return $this->all( $offset, $rowcount, $sort, false, true );
    }
       
    function &columns( ) {
        return $this->_columns;
    }

   function setTitle( $title ) {
       if ( $title ) {
           CRM_Utils_System::setTitle( $title );
       } else {
           CRM_Utils_System::setTitle(ts('Search'));
       }
   }

   function summary( ) {
       return null;
   }

}


