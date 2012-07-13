<h3 class="pageTitle">{$entryAddFormTitle}</h3>

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
		<th>{$entryTableHeadersStrings.title}</th><td><input type="text" name="title" maxlength="100" size="40" value="{$entry.title}" /></td>
	</tr>
	<tr>
		<th>{$entryTableHeadersStrings.url}</th><td><input type="text" name="url" maxlength="100" size="40" value="{$entry.url}" /></td>
	</tr>
	<tr>	
		<th>{$entryTableHeadersStrings.description}</th><td><textarea name="description" cols="40" rows="10">{$entry.description}</textarea></td>
	</tr>
	<tr>
		<th>{$menusString}</th>
		<td>
			<select name="menuId">
					<option value="">...</option>
					{section name=mlist loop=$menus}
						{if $entry.menuId ne ''}
							<option value="{$menus[mlist].id}" {if $entry.menuId eq $menus[mlist].id} selected="selected"{/if}>{$menus[mlist].title}</option>
						{else}
							<option value="{$menus[mlist].id}">{$menus[mlist].title}</option>
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
