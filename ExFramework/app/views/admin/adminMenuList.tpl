<h3 class="pageTitle">{$menuListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$menuTableHeadersNames.id}</th>
		<th>{$menuTableHeadersNames.title}</th>
		<th>{$menuTableHeadersNames.info}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$menus}
	<tr>
		<td>{$menus[list].id}</td>
		<td>{$menus[list].title}</td>
		<td>{$menus[list].info}</td>
		<td><a href="{$links.view[1]}{$menus[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$menus[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$menus[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="6">{$noGroupMessage}</td>
	</tr>
	{/section}
</table>
