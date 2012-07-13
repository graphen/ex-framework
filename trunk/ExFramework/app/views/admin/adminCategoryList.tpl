<h3 class="pageTitle">{$categoryListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$categoryTableHeadersNames.id}</th>
		<th>{$categoryTableHeadersNames.name}</th>
		<th>{$categoryTableHeadersNames.info}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$categories}
	<tr>
		<td>{$categories[list].id}</td>
		<td>{$categories[list].name}</td>
		<td>{$categories[list].info}</td>
		<td><a href="{$links.view[1]}{$categories[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$categories[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$categories[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="6">{$noCategoryMessage}</td>
	</tr>
	{/section}
</table>
{if $categories ne ''}
{$paginator}
{/if}
