<h3 class="pageTitle">{$categoryEditFormTitle}</h3>

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
<input type="hidden" name="id" value="{$category.id}" />
<table class="table_form">
	<tr>
		<th>{$categoryTableHeadersStrings.name}</th><td><input type="text" name="name" maxlength="100" size="40" value="{$category.name}" /></td>
	</tr>
	<tr>
		<th>{$categoryTableHeadersStrings.info}</th><td><input type="text" name="info" maxlength="240" size="40" value="{$category.info}" /></td>
	</tr>
	<tr>
		<th> </th><td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
