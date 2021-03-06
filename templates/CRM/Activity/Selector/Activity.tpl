{*
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
*}
{* Displays Activities. *}

<div> 
  {if !$noFieldSet}	
  <fieldset>
  <legend>{ts}Activities{/ts}</legend>
  {/if}
{if $rows}
  <form title="activity_pager" action="{crmURL}" method="post">
  {include file="CRM/common/pager.tpl" location="top"}

  {strip}
    <table class="selector">
      <tr class="columnheader">
      {foreach from=$columnHeaders item=header}
        <th scope="col">
        {if $header.sort}
          {assign var='key' value=$header.sort}
          {$sort->_response.$key.link}
        {else}
          {$header.name}
        {/if}
        </th>
      {/foreach}
      </tr>

      {counter start=0 skip=1 print=false}
      {foreach from=$rows item=row}
      <tr class="{cycle values="odd-row,even-row"} {$row.class}">

        <td>{$row.activity_type}</td>
       
    	<td>{$row.subject}</td>
	
        <td>
        {if !$row.source_contact_id}
          <em>n/a</em>
        {elseif $contactId NEQ $row.source_contact_id}
          <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row.source_contact_id`"}" title="{ts}View contact{/ts}">{$row.source_contact_name}</a>
        {else}
          {$row.source_contact_name}	
        {/if}			
        </td>

        <td>
        {if $row.mailingId}
          <a href="{$row.mailingId}" title="{ts}View Mailing Report{/ts}">{$row.recipients}</a>
        {elseif $row.recipients}
          {$row.recipients}
        {elseif !$row.target_contact_name}
          <em>n/a</em>
        {elseif $row.target_contact_name}
            {assign var="showTarget" value=0}
            {foreach from=$row.target_contact_name item=targetName key=targetID}
                {if $showTarget < 5}
                    {if $showTarget};&nbsp;{/if}<a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$targetID`"}" title="{ts}View contact{/ts}">{$targetName}</a>
                    {assign var="showTarget" value=$showTarget+1}
                {/if}
            {/foreach}
            {if count($row.target_contact_name) > 5}({ts}more{/ts}){/if}
        {/if}
        </td>

        <td>
        {if !$row.assignee_contact_name}
            <em>n/a</em>
        {elseif $row.assignee_contact_name}
            {assign var="showAssignee" value=0}
            {foreach from=$row.assignee_contact_name item=assigneeName key=assigneeID}
                {if $showAssignee < 5}
                    {if $showAssignee};&nbsp;{/if}<a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$assigneeID`"}" title="{ts}View contact{/ts}">{$assigneeName}</a>
                    {assign var="showAssignee" value=$showAssignee+1}
                {/if}
            {/foreach}
            {if count($row.assignee_contact_name) > 5}({ts}more{/ts}){/if}
        {/if}	
        </td>

        <td>{$row.activity_date_time|crmDate}</td>

        <td>{$row.status}</td>

        <td>{$row.action|replace:'xx':$row.id}</td>
      </tr>
      {/foreach}

    </table>
  {/strip}

  {include file="CRM/common/pager.tpl" location="bottom"}
  </form>

{else}

  <div class="messages status">
    {if isset($caseview) and $caseview}
      {ts}There are no Activities attached to this case record.{/ts}{if $permission EQ 'edit'} {ts}You can go to the Activities tab to create or attach activity records.{/ts}{/if}
    {elseif $context eq 'home'}
      {ts}There are no Activities to display.{/ts}
    {else}
      {ts}There are no Activites to display.{/ts}{if $permission EQ 'edit'} {ts}You can use the links above to schedule or record an activity.{/ts}{/if}
    {/if}
  </div>

{/if}
{if !$noFieldSet}
</fieldset>
{/if}
</div>


{* ############ start #################### by jyoti : 18may10 ######################### *}
{* getting contact relationships & their activities using API *}

{assign var='main_cid' value=$smarty.get.cid}

{crmAPI entity='relationship' action="get" var="relationships" contact_id=$main_cid}                  
                                
{*<pre> {$relationships|print_r} </pre>
{$relationships|@count}*}
{foreach from=$relationships key=org_key item=org_relationship}
  {if $org_key == 'result'}
    {foreach from=$org_relationship item=any_other_relationship}
    {*$any_other_relationship|@count*}
        {*<pre> {$any_other_relationship|print_r} </pre> *}
		{*{foreach from=$any_other_relationship key=key item=item}
			{$key} => {$item}
			<br />
		{/foreach}*}
        
		<div>
		  {if !$noFieldSet}             
		  <fieldset>
		  <legend>{ts} Relationship ({$any_other_relationship.relation}) {$any_other_relationship.name} Activities Summary{/ts}</legend>
		  {/if}
		  
		  {assign var='row' value=''}
		  {assign var='rows' value=''}
		  {crmAPI entity='Activity' action="contact_get" var="rows" contact_id=$any_other_relationship.cid}
		  {*<pre> {$rows|print_r} </pre>*}
			{*{foreach from=$rows.result key=key item=item}
				{$key} => {$item} <br />
				{foreach from=$item key=k item=i}
					{$k} => {$i}
					
					{if $k == 'targets'}
						{foreach from=$i key=ik item=ii}
							{$ik} => {$ii};
							<br />
						{/foreach}
					{/if}
					<br />
				{/foreach}
			{/foreach}*}
		
			{if $rows.result}
			   <form title="ractivity_pager" action="{crmURL}" method="post">
			  {include file="CRM/common/pager.tpl" location="top"}

			  {strip}
				<table class="selector">
				  <tr class="columnheader">
				  {foreach from=$columnHeaders item=header}
					<th scope="col">
					{if $header.sort}
					  {assign var='key' value=$header.sort}
					  {$sort->_response.$key.link}
					{else}
					  {$header.name}
					{/if}
					</th>
				  {/foreach}
				  </tr>
				  


				  
				  {counter start=0 skip=1 print=false}
				   {*<pre> {$rows.result|print_r} </pre>*}
				  {foreach name=myloop from=$rows.result key=main_key item=main_row}
					
					
					{*assign var='rel_act' value=$main_row.$main_key*}
					
					<tr class="{cycle values="odd-row,even-row"} {$row.class}">

						<td>{$main_row.activity_name}</td>
					   
						<td>{$main_row.subject}</td>
					
						<td>
						      {crmAPI entity="contact" action="search" var="contacts" contact_id=$main_row.source_contact_id return ="contact_id,display_name"}
							{*<pre> {$contacts|print_r} </pre>*}
							{foreach from=$contacts key=added_contact_key item=added_contact_row}
							{if !$main_row.source_contact_id}
							  <em>n/a</em>
							{elseif $contactId NEQ $main_row.source_contact_id}
							  <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$main_row.source_contact_id`"}" title="{ts}View contact{/ts}">{$added_contact_row.display_name}</a>
							{else}
							  {$added_contact_row.display_name}
							{/if}
							{/foreach}
						</td>

						<td>
						      {foreach from=$main_row.targets key=target_key item=target_id}
							{crmAPI entity="contact" action="search" var="contacts" contact_id=$target_id return ="contact_id,display_name"}
							{*<pre> {$contacts|print_r} </pre>*}
							{foreach from=$contacts key=target_contact_key item=target_contact_row}
							{if !$target_id}
							  <em>n/a</em>
							{elseif $contactId NEQ $target_id}
							  <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$target_id`"}" title="{ts}View contact{/ts}">{$target_contact_row.display_name}</a>
							{else}
							  {$target_contact_row.display_name}
							{/if}
							{/foreach}
						      {/foreach}
						</td>

						<td>
							{if !$main_row.assignee_contact_name}
								<em>n/a</em>
							{elseif $main_row.assignee_contact_name}
								{assign var="showAssignee" value=0}
								{foreach from=$main_row.assignee_contact_name item=assigneeName key=assigneeID}
									{if $showAssignee < 5}
										{if $showAssignee};&nbsp;{/if}<a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$assigneeID`"}" title="{ts}View contact{/ts}">{$assigneeName}</a>
										{assign var="showAssignee" value=$showAssignee+1}
									{/if}
								{/foreach}
								{if count($main_row.assignee_contact_name) > 5}({ts}more{/ts}){/if}
							{/if}	
						</td>

						<td>{$main_row.activity_date_time|crmDate}</td>

						<td>{$main_row.status}</td>

						<td>&nbsp;</td>
				  </tr>
					
				  {/foreach}

				</table>
			  {/strip}

			  {include file="CRM/common/pager.tpl" location="bottom"}
			  </form>
			{else}

			  <div class="messages status">
			    {ts}There are no Activites to display.{/ts}
			  </div>

			{/if}
			{if !$noFieldSet}
			</fieldset>
			{/if}
			</div>
		{/foreach}
	      {/if}
	{/foreach}

{* ############ end #################### by jyoti : 18may10 ######################### *}