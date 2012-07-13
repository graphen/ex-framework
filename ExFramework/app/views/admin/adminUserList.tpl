<h3 class="pageTitle">{$userListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$userTableHeadersNames.id}</th>
		<th>{$userTableHeadersNames.login}</th>
		<th>{$userTableHeadersNames.firstName}</th>
		<th>{$userTableHeadersNames.lastName}</th>
		<th>{$userTableHeadersNames.email}</th>
		<th>{$groupsString}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$users}
	<tr>
		<td>{$users[list].id}</td>
		<td>{$users[list].login}</td>
		<td>{$users[list].firstName}</td>
		<td>{$users[list].lastName}</td>
		<td><a href="mailto:{$users[list].email}">{$users[list].email}</a></td>
		<td>
			<table class="nested">
			{section name=glist loop=$users[list].groups}
				<tr>
					<td width="100%">{$users[list].groups[glist].name}</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noGroupMessage}</td>
				</tr>
			{/section}
			</table>
		</td>	
		<td><a href="{$links.view[1]}{$users[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$users[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$users[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="9">{$noUserMessage}</td>
	</tr>
	{/section}
</table>
{if $users ne ''}
{$paginator}
{/if}
