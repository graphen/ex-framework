<h3 class="pageTitle">{$profileLoginFormTitle}</h3>

{if $flashError ne ''}
<ul>
	<li class="error">{$flashError}</li>
</ul>
{/if}

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<form method="post" action="{$actionLink}">
<table class="table_form">
	<tr>
		<th>{$LoginLabel}</th>	
		<td><input type="text" name="login" size="20" /></td>
	</tr>
	<tr>
		<th>{$PasswordLabel}</th>	
		<td><input type="password" name="password" size="20" /></td>
	</tr>
	<tr>
		<th> </th>
		<td><input type="submit" value="{$LoginString}" /></td>
	</tr>
	<tr>
		<th colspan="2">{$NoAccountString} - <a href="{$registerLink}">{$RegisterString}</a></th>
	</tr>	
	
</table>
</form>
