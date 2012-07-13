<h3 class="pageTitle">{$menuAddFormTitle}</h3>

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
		<th>{$menuTableHeadersStrings.title}</th><td><input type="text" name="title" maxlength="100" size="40" value="{$menu.title}" /></td>
	</tr>
	<tr>		
		<th>{$menuTableHeadersStrings.info}</th><td><textarea name="info" cols="40" rows="10">{$menu.info}</textarea></td>
	</tr>	
	<tr>
		<th> </th>
		<td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
