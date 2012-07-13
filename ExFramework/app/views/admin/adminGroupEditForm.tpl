<h3 class="pageTitle">{$groupEditFormTitle}</h3>

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
<input type="hidden" name="id" value="{$group.id}" />
<table class="table_form">
	<tr>
		<th>{$groupTableHeadersStrings.name}</th><td><input type="text" name="name" maxlength="100" size="40" value="{$group.name}" /></td>
	</tr>
	<tr>
		<th>{$groupTableHeadersStrings.info}</th><td><input type="text" name="info" maxlength="240" size="40" value="{$group.info}" /></td>
	</tr>
	<tr>		
		<th>{$groupTableHeadersStrings.root}</th>
		<td>
			<select name="root">
				<option value="">...</option>
				{section name=rlist loop=$rootValues}
					{if $group.root ne ''}
						{if $rootValues[rlist].value eq $group.root}
							<option value="{$rootValues[rlist].value}" selected="selected">{$rootValues[rlist].info}</option>
						{else}
							<option value="{$rootValues[rlist].value}">{$rootValues[rlist].info}</option>
						{/if}
					{else}
						<option value="{$rootValues[rlist].value}">{$rootValues[rlist].info}</option>
					{/if}
				{/section}
			</select>
		</td>
	</tr>	
	<tr>
		<th> </th><td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
