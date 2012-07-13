<h3 class="pageTitle">{$userAddFormTitle}</h3>

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
		<th>{$userTableHeadersStrings.login}</th><td><input type="text" name="login" maxlength="60" size="40" value="{$user.login}" /></td>
	</tr>
	<tr>
		<th>{$userTableHeadersStrings.password}</th><td><input type="password" name="password" maxlength="60" size="40" /></td>
	</tr>
	<tr>
		<th>{$passwordConfirmationString}</th><td><input type="password" name="passwordConfirmation" maxlength="60" size="40" /></td>
	</tr>
	<tr>
		<th>{$userTableHeadersStrings.firstName}</th><td><input type="text" name="firstName" maxlength="60" size="40" value="{$user.firstName}" /></td>
	</tr>
	<tr>
		<th>{$userTableHeadersStrings.lastName}</th><td><input type="text" name="lastName" maxlength="80" size="40" value="{$user.lastName}" /></td>
	</tr>
	<tr>
		<th>{$userTableHeadersStrings.email}</th><td><input type="text" name="email" maxlength="100" size="40" value="{$user.email}" /></td>
	</tr>
	<tr>
		<th>{$userTableHeadersStrings.url}</th><td><input type="text" name="url" maxlength="100" size="40" value="{$user.url}" /></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersStrings.city}</th><td><input type="text" name="city" maxlength="80" size="40" value="{$user.city}" /></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersStrings.address}</th><td><input type="text" name="address" maxlength="200" size="40" value="{$user.address}" /></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersStrings.phone}</th><td><input type="text" name="phone" maxlength="20" size="40" value="{$user.phone}" /></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersStrings.info}</th><td><textarea name="info" cols="40" rows="10">{$user.info}</textarea></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersStrings.status}</th>
		<td>
			<select name="status">
				<option value="">...</option>
				{section name=slist loop=$statuses}
					{if $user.status ne ''}
						{if $statuses[slist].value eq $user.status}
						  <option value="{$statuses[slist].value}" selected="selected">{$statuses[slist].info}</option>
						{else}
						  <option value="{$statuses[slist].value}">{$statuses[slist].info}</option>
						{/if}
					{else}
						<option value="{$statuses[slist].value}">{$statuses[slist].info}</option>
					{/if}
				{/section}
			</select>
		</td>
	</tr>		
	<tr>		
		<th>{$groupsString}</th>
		<td>
			<select name="groups[]" size="4" multiple="multiple">
					<option value="">...</option>
					{section name=glist loop=$groups}
						{if $user.groups ne ''}
							<option value="{$groups[glist].id}"	{section name=pglist loop=$user.groups}{if $user.groups[pglist] eq $groups[glist].id} selected="selected"{/if}{/section}>{$groups[glist].info}</option>
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
