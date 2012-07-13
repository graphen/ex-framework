<h3 class="pageTitle">{$entryViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$entryTableHeadersNames.id}</th><td>{$entry.id}</td>
	</tr>
	<tr>
		<th>{$entryTableHeadersNames.title}</th><td>{$entry.title}</td>
	</tr>
	<tr>
		<th>{$entryTableHeadersNames.url}</th><td><a href="{$entry.url}">{$entry.url}</a></td>
	</tr>
	<tr>
		<th>{$entryTableHeadersNames.description}</th><td>{$entry.description}</td>
	</tr>
	<tr>		
		<th>{$menusString}</th>
		<td>{if $entry.menus ne ''}
				{$entry.menus.title}
			{else}
				{$noMenuMessage}
			{/if}
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}id/{$entry.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}id/{$entry.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
