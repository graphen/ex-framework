<h3 class="pageTitle">{$profileViewTitle}</h1>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_view">
	<tr>
		<th>{$userTableHeadersNames.login}</th><td>{$user.login}</td>
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
		<th>{$userTableHeadersNames.lastAccess}</th><td>{$user.lastAccess}</td>
	</tr>
	<tr>		
		<th>{$userTableHeadersNames.visitCount}</th><td>{$user.visitCount}</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}id/{$user.id}">{$links.edit[0]}</a>]</td>
	</tr>
</table>
