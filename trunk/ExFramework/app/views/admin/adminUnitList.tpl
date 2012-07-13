<h3 class="pageTitle">{$unitListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$unitTableHeadersNames.id}</th>
		<th>{$unitTableHeadersNames.name}</th>
		<th>{$unitTableHeadersNames.abbreviation}</th>
		<th>{$unitTableHeadersNames.description}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$units}
	<tr>
		<td>{$units[list].id}</td>
		<td>{$units[list].name}</td>
		<td>{$units[list].abbreviation}</td>		
		<td>{$units[list].description}</td>		
		<td><a href="{$links.view[1]}{$units[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$units[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$units[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="7">{$noUnitMessage}</td>
	</tr>
	{/section}
</table>
{if $units ne ''}
{$paginator}
{/if}
