<h3 class="pageTitle">{$groupListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$groupTableHeadersNames.id}</th>
		<th>{$groupTableHeadersNames.name}</th>
		<th>{$groupTableHeadersNames.info}</th>
		<th>{$groupTableHeadersNames.root}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$groups}
	<tr>
		<td>{$groups[list].id}</td>
		<td>{$groups[list].name}</td>
		<td>{$groups[list].info}</td>
		<td>{$groups[list].root}</td>
		<td><a href="{$links.view[1]}{$groups[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$groups[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$groups[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="7">{$noGroupMessage}</td>
	</tr>
	{/section}
</table>
{if $groups ne ''}
{$paginator}
{/if}
