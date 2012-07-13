<h3 class="pageTitle">{$profileResetFormTitle}</h3>

{if $flashError ne ''}
<ul>
	<li class="error">{$flashError}</li>
</ul>
{/if}

<form method="post" action="{$actionLink}">
<table class="table_form">
	<tr>
		<th>{$loginLabel}</th>	
		<td><input type="text" name="login" size="20" /></td>
	</tr>
	<tr>
		<th>{$emailLabel}</th>	
		<td><input type="text" name="email" size="20" /></td>
	</tr>
	<tr>
		<th> </th>
		<td>
			<input type="submit" value="{$submitString}" />
		</td>
	</tr>
</table>
</form>
