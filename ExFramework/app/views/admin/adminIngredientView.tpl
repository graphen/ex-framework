<h3 class="pageTitle">{$ingredientViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$ingredientTableHeadersNames.id}</th><td>{$ingredient.id}</td>
	</tr>
	<tr>
		<th>{$ingredientTableHeadersNames.name}</th><td>{$ingredient.name}</td>
	</tr>
	<tr>
		<th>{$ingredientTableHeadersNames.info}</th><td>{$ingredient.info}</td>
	</tr>
	<tr>
		<th>{$itemsCountString}</th><td>{$ingredient.itemsCount}</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}{$ingredient.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$ingredient.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
