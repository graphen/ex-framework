<h3 class="pageTitle">{$resourceViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$resourceTableHeadersNames.id}</th><td>{$resource.id}</td>
	</tr>
	<tr>
		<th>{$resourceTableHeadersNames.name}</th><td>{$resource.name}</td>
	</tr>
	<tr>
		<th>{$resourceTableHeadersNames.resource}</th><td>{$resource.resource}</td>
	</tr>
	<tr>		
		<th>{$groupsString}</th>
		<td>
			<table>
			{section name=glist loop=$resource.groups}
				<tr>
					<td>{$resource.groups[glist].name} [{$resource.groups[glist].info}]</td>
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
		<th> </th><td>[<a href="{$links.edit[1]}{$resource.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$resource.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
