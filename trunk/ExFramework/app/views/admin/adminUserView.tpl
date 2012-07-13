<h3 class="pageTitle">{$userViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$userTableHeadersNames.id}</th><td>{$user.id}</td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.login}</th><td>{$user.login}</td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.password}</th><td>{$user.password}</td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.firstName}</th><td>{$user.firstName}</td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.lastName}</th><td>{$user.lastName}</td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.email}</th><td><a href="mailto:{$user.email}">{$user.email}</a></td>
	</tr>
	<tr>
		<th>{$userTableHeadersNames.url}</th><td><a href="{$user.url}">{$user.url}</a></td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.city}</th><td>{$user.city}</td>		
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.address}</th><td>{$user.address}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.phone}</th><td>{$user.phone}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.info}</th><td>{$user.info}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.registerDate}</th><td>{$user.registerDate}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.code}</th><td>{$user.code}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.status}</th><td>{$user.status}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.lastAccess}</th><td>{$user.lastAccess}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.visitCount}</th><td>{$user.visitCount}</td>
	</tr>
	<tr>		
		<th>{$groupsString}</th>
		<td>
			<table>
			{section name=glist loop=$user.groups}
				<tr>
					<td>{$user.groups[glist].info} [{$user.groups[glist].name}]</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noGroupMessage}</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}id/{$user.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}id/{$user.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
