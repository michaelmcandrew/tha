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
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Member_DAO_CIVI extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'Civi';
    /**
     * static instance to hold the field values
     *
     * @var array
     * @static
     */
    static $_fields = null;
    /**
     * static instance to hold the FK relationships
     *
     * @var string
     * @static
     */
    static $_links = null;
    /**
     * static instance to hold the values that can
     * be imported / apu
     *
     * @var array
     * @static
     */
    static $_import = null;
    /**
     * static instance to hold the values that can
     * be exported / apu
     *
     * @var array
     * @static
     */
    static $_export = null;
    /**
     * static value to see if we should log any modifications to
     * this table in the civicrm_log table
     *
     * @var boolean
     * @static
     */
    static $_log = false;
    /**
     * Membership Id
     *
     * @var int unsigned
     */
    public $id;
    /**
     * FK to Contact ID
     *
     * @var int unsigned
     */
    public $acc_no;
    /**
     * FK to Membership Type
     *
     * @var int unsigned
     */
    public $mem_no;
    /**
     * Beginning of initial membership period (member since...).
     *
     * @var date
     */
    public $band_type ;
    /**
     * Beginning of current uninterrupted membership period.
     *
     * @var date
     */
    public $org_name;
    /**
     * Current membership period expire date.
     *
     * @var date
     */
    public $street_address;
    /**
     *
     * @var string
     */
    public $street_name;
    /**
     * FK to Membership Status
     *
     * @var int unsigned
     */
    public $supplemental_address_1;
    /**
     * Admin users may set a manual status which overrides the calculated status. When this flag is true, automated status update scripts should NOT modify status for the record.
     *
     * @var boolean
     */
    public $supplemental_address_2;
    /**
     * When should a reminder be sent.
     *
     * @var date
     */
    public $supplemental_address_3;
    /**
     * Optional FK to Parent Membership.
     *
     * @var int unsigned
     */
    public $city;
    /**
     *
     * @var boolean
     */
    public $postal_code;
    /**
     *
     * @var boolean
     */
	 public $country;
    /**
     *
     * @var boolean
     */
	 public $phone;
    /**
     *
     * @var boolean
     */
	 public $email;
    /**
     *
     * @var boolean
     */
	 public $website;
    /**
     *
     * @var boolean
     */
	 public $r_title;
    /**
     *
     * @var boolean
     */
	 public $r_first_name;
    /**
     *
     * @var boolean
     */
    public $r_last_name;
	
	public $r_job_title;
    /**
     *
     * @var boolean
     */
	 public $c_title;
    /**
     *
     * @var boolean
     */
	 public $c_first_name;
    /**
     *
     * @var boolean
     */
	 public $c_last_name;
    /**
     *
     * @var boolean
     */
	 public $c_job_title;
    /**
     *
     * @var boolean
     */
	 
	 
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_membership
     */
    function __construct() 
    {
        parent::__construct();
    }
  
    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    function &fields()
    {
        if (!(self::$_fields)) {
            self::$_fields = array(
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'acc_no' => array(
                    'name' => 'acc_no',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Account No') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
                 'mem_no' => array(
                    'name' => 'mem_no',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Mem No') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'band_type' => array(
                    'name' => 'band_type',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Band Type') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'org_name' => array(
                    'name' => 'org_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Organization Name') ,
                    'maxlength' => 250,
                    'size' => 73,
                ) ,
				 'street_address' => array(
                    'name' => 'street_address',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Street Address') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'street_name' => array(
                    'name' => 'street_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Street Name') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'supplemental_address_1' => array(
                    'name' => 'supplemental_address_1',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Supplemental Aaddress1') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'supplemental_address_2' => array(
                    'name' => 'supplemental_address_2',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Supplemental Address2') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'supplemental_address_3' => array(
                    'name' => 'supplemental_address_3',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Supplemental Address3') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'city' => array(
                    'name' => 'city',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('City') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'postal_code' => array(
                    'name' => 'postal_code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Postal Code') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'country' => array(
                    'name' => 'country',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Country') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'phone' => array(
                    'name' => 'phone',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Phone') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'email' => array(
                    'name' => 'email',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Email') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				
				 'website' => array(
                    'name' => 'website',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Website') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'r_title' => array(
                    'name' => 'r_title',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Mem Rep Title') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'r_first_name' => array(
                    'name' => 'r_first_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Mem Rep First Name') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'r_last_name' => array(
                    'name' => 'r_last_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Mem Rep Last Name') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'r_job_title' => array(
                    'name' => 'r_job_title',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Mem Rep Job Title') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'c_title' => array(
                    'name' => 'c_title',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('CEO Title') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'c_first_name' => array(
                    'name' => 'c_first_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('CEO First Name') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'c_last_name' => array(
                    'name' => 'c_last_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('CEO Last Name') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				 'c_job_title' => array(
                    'name' => 'c_job_title',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('CEO Job Title') ,
                    'maxlength' => 100,
                    'size' => 73,
                ) ,
				
               
            );
        }
        return self::$_fields;
    }
    /**
     * returns the names of this table
     *
     * @access public
     * @return string
     */
    function getTableName()
    {
        return self::$_tableName;
    }
    /**
     * returns if this table needs to be logged
     *
     * @access public
     * @return boolean
     */
    function getLog()
    {
        return self::$_log;
    }
    /**
     * returns the list of fields that can be imported
     *
     * @access public
     * return array
     */
    function &import($prefix = false)
    {
        if (!(self::$_import)) {
            self::$_import = array();
            $fields = & self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        self::$_import['batch'] = & $fields[$name];
                    } else {
                        self::$_import[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_import;
    }
    /**
     * returns the list of fields that can be exported
     *
     * @access public
     * return array
     */
    function &export($prefix = false)
    {
        if (!(self::$_export)) {
            self::$_export = array();
            $fields = & self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        self::$_export['batch'] = & $fields[$name];
                    } else {
                        self::$_export[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
}
