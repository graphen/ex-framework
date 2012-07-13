<h3 class="pageTitle">{$unitEditFormTitle}</h3>

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
<input type="hidden" name="id" value="{$unit.id}" />
<table class="table_form">
	<tr>
		<th>{$unitTableHeadersStrings.name}</th><td><input type="text" name="name" maxlength="100" size="40" value="{$unit.name}" /></td>
	</tr>
	<tr>
		<th>{$unitTableHeadersStrings.abbreviation}</th><td><input type="text" name="abbreviation" maxlength="240" size="40" value="{$unit.abbreviation}" /></td>
	</tr>
	<tr>
		<th>{$unitTableHeadersStrings.description}</th><td><input type="text" name="description" maxlength="240" size="40" value="{$unit.description}" /></td>
	</tr>
	<tr>
		<th> </th><td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
