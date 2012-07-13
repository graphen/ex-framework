<h3 class="pageTitle">{$menuViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$menuTableHeadersNames.id}</th><td>{$menu.id}</td>
	</tr>
	<tr>
		<th>{$menuTableHeadersNames.title}</th><td>{$menu.title}</td>
	</tr>
	<tr>
		<th>{$menuTableHeadersNames.info}</th><td>{$menu.info}</td>
	</tr>
	<tr>		
		<th>{$entriesString}</th>
		<td>
			<table>
			{section name=elist loop=$menu.entries}
				<tr>
					<td>{$menu.entries[elist].title}</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noEntryMessage}</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}{$menu.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$menu.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
