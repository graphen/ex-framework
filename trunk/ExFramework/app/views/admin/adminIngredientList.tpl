<h3 class="pageTitle">{$ingredientListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$ingredientTableHeadersNames.id}</th>
		<th>{$ingredientTableHeadersNames.name}</th>
		<th>{$ingredientTableHeadersNames.info}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$ingredients}
	<tr>
		<td>{$ingredients[list].id}</td>
		<td>{$ingredients[list].name}</td>
		<td>{$ingredients[list].info}</td>
		<td><a href="{$links.view[1]}{$ingredients[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$ingredients[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$ingredients[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="6">{$noIngredientMessage}</td>
	</tr>
	{/section}
</table>
{if $ingredients ne ''}
{$paginator}
{/if}
