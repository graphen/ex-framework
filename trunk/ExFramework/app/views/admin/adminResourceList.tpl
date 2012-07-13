<h3 class="pageTitle">{$resourceListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$resourceTableHeadersNames.id}</th>
		<th>{$resourceTableHeadersNames.name}</th>
		<th>{$resourceTableHeadersNames.resource}</th>
		<th>{$groupsString}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$resources}
	<tr>
		<td>{$resources[list].id}</td>
		<td>{$resources[list].name}</td>
		<td>{$resources[list].resource}</td>
		<td>
			<table class="nested">
			{section name=glist loop=$resources[list].groups}
				<tr>
					<td>{$resources[list].groups[glist].name}</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noGroupMessage}</td>
				</tr>
			{/section}
			</table>
		</td>	
		<td><a href="{$links.view[1]}{$resources[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$resources[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$resources[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="7">{$noResourceMessage}</td>
	</tr>
	{/section}
</table>
{if $resources ne ''}
{$paginator}
{/if}
