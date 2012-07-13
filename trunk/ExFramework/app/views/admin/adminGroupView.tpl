<h3 class="pageTitle">{$groupViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$groupTableHeadersNames.id}</th><td>{$group.id}</td>
	</tr>
	<tr>
		<th>{$groupTableHeadersNames.name}</th><td>{$group.name}</td>
	</tr>
	<tr>
		<th>{$groupTableHeadersNames.info}</th><td>{$group.info}</td>
	</tr>
	<tr>
		<th>{$groupTableHeadersNames.root}</th><td>{$group.root}</td>
	</tr>
	<tr>		
		<th>{$usersString}</th>
		<td>
			<table>
			{section name=ulist loop=$group.users}
				<tr>
					<td>{$group.users[ulist].login}</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noUserMessage}</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}{$group.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$group.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
