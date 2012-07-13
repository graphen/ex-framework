<h3 class="pageTitle">{$resourceAddFormTitle}</h3>

{if $flashError ne ''}
<ul>
	<li class="error">{$flashError}</li>
</ul>
<ul>
	{foreach from=$errors key=index item=error}
		{foreach from=$error item=err}
			<li class="error">{$index}: {$err}</li>		
		{/foreach}
	{/foreach}
</ul>
{/if}

<form method="post" action="{$actionLink}">
<table class="table_form">		
	<tr>
		<th>{$resourceTableHeadersStrings.name}</th><td><input type="text" name="name" maxlength="60" size="40" value="{$resource.name}" /></td>
	</tr>
	<tr>
		<th>{$resourceTableHeadersStrings.resource}</th><td><input type="text" name="resource" maxlength="240" size="40" value="{$resource.resource}" /></td>
	</tr>	
	<tr>		
		<th>{$groupsString}</th>
		<td>
			<select name="groups[]" size="4" multiple="multiple">
					<option value="">...</option>
					{section name=glist loop=$groups}
						{if $resource.groups ne ''}
							<option value="{$groups[glist].id}"	{section name=rglist loop=$resource.groups}{if $resource.groups[rglist] eq $groups[glist].id} selected="selected"{/if}{/section}>{$groups[glist].info}</option>
						{else}
							<option value="{$groups[glist].id}">{$groups[glist].info}</option>
						{/if}
					{/section}
			</select>
		</td>
	</tr>	
	<tr>
		<th> </th>
		<td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
