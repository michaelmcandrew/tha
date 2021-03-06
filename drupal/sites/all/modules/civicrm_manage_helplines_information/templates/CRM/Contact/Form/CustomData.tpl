{*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.3                                                |
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
*}
{* this template is used for building tabbed custom data *}
{if $cdType }
    {include file="CRM/Custom/Form/CustomData.tpl" customRowId=$customRowId}
{else}

    {php}
        global $user;
        $role = $user->roles;
        if (in_array("authenticated user", $role) && in_array("competent user", $role) && count($role) == 2 ){
        {/php}    
         To view and edit the information stored on your helpline(s) please click on the relevant Directory entries below. Please then click save to record any changes. Changes will be reflected in the directory subject to THA approval.
          <br /><br />
          If you would like to add an additional helpline to the directory, please click on 'Add another Directory Form record.’
          <br /><br />    
        {php}    
        }
    {/php}
    <div id="customData"></div>
    <div class="html-adjust">{$form.buttons.html}</div>  

    {*include custom data js file*}
    {include file="CRM/common/customData.tpl"}

	{if $customValueCount }
		{literal}
		<script type="text/javascript">
			var customValueCount = {/literal}"{$customValueCount}"{literal}
			var groupID = {/literal}"{$groupID}"{literal}
			var contact_type = {/literal}"{$contact_type}"{literal};
			var contact_subtype = {/literal}"{$contact_subtype}"{literal};
			buildCustomData( contact_type, contact_subtype );
			for ( var i = 1; i < customValueCount; i++ ) {
				buildCustomData( contact_type, contact_subtype, null, i, groupID, true );
			}
		</script>
		{/literal}
	{/if}
{/if}
