<h3 class="pageTitle">{$unitViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$unitTableHeadersNames.id}</th><td>{$unit.id}</td>
	</tr>
	<tr>
		<th>{$unitTableHeadersNames.name}</th><td>{$unit.name}</td>
	</tr>
	<tr>
		<th>{$unitTableHeadersNames.abbreviation}</th><td>{$unit.abbreviation}</td>
	</tr>
	<tr>
		<th>{$unitTableHeadersNames.description}</th><td>{$unit.description}</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}{$unit.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$unit.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
