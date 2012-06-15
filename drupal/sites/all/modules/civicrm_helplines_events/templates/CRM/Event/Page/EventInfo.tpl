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
{* this template is used for displaying event information *}

{if $registerClosed }
<div class="spacer"></div>
<div class="messages status">
  <div class="icon inform-icon"></div>
     &nbsp;{ts}Registration is closed for this event{/ts}
  </div>
{/if}
<div class="vevent crm-block crm-event-info-form-block">
	<div class="event-info">
	
	{if $event.summary}
	    <div class="crm-section event_summary-section">{$event.summary}</div>
	{/if}
	{if $event.description}
	    <div class="crm-section event_description-section summary">{$event.description}</div>
	{/if}
	
    {include file="CRM/Custom/Page/CustomDataView.tpl"}
        
	{if $allowRegistration}
        <div class="action-link section register_link-section">
            <a href="{$registerURL}" title="{$registerText}" class="button crm-register-button"><span>{$registerText}</span></a>
        </div>
    {/if}
    { if $event.is_public }
        <br />{include file="CRM/Event/Page/iCalLinks.tpl"}
    {/if}
    
    </div>
</div>
