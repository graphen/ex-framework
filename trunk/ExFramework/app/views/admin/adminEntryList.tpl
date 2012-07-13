<h3 class="pageTitle">{$entryListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$entryTableHeadersNames.id}</th>
		<th>{$entryTableHeadersNames.title}</th>
		<th>{$entryTableHeadersNames.url}</th>
		<th>{$entryTableHeadersNames.description}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=mlist loop=$menus}	
	<tr>
		<th colspan="7">{$menus[mlist].title}</th>
	</tr>
		{section name=elist loop=$menus[mlist].entries}
		<tr>
			<td>{$menus[mlist].entries[elist].id}</td>
			<td>{$menus[mlist].entries[elist].title}</td>
			<td>{$menus[mlist].entries[elist].url}</td>
			<td>{$menus[mlist].entries[elist].description}</td>
			<td><a href="{$links.view[1]}{$menus[mlist].entries[elist].id}">{$links.view[0]}</a></td>
			<td><a href="{$links.edit[1]}{$menus[mlist].entries[elist].id}">{$links.edit[0]}</a></td>
			<td><a href="{$links.delete[1]}{$menus[mlist].entries[elist].id}">{$links.delete[0]}</a></td>
		</tr>
		{sectionelse}
		<tr>
			<td colspan="7">{$noEntryMessage}</td>
		</tr>
		{/section}
	{sectionelse}
	<tr>
		<td colspan="7">{$noMenuMessage}</td>
	</tr>
	{/section}
</table>
