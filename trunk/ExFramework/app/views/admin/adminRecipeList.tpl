<h3 class="pageTitle">{$recipeListTitle}</h3>

{if $flashNotice ne ''}
<ul>
	<li class="notice">{$flashNotice}</li>
</ul>
{/if}

<table class="table_list">
	<tr>
		<th>{$recipeTableHeadersNames.title}</th>
		<th>{$recipeTableHeadersNames.userName}</th>
		<th>{$categoriesString}</th>
		<th>{$recipeTableHeadersNames.description}</th>		
		<th>{$recipeTableHeadersNames.portions}</th>
		<th>{$recipeTableHeadersNames.preparationTime}</th>
		<th>{$recipeTableHeadersNames.created}</th>
		<th>{$recipeTableHeadersNames.visitCount}</th>
		<th colspan="3"> </th>
	</tr>
	{section name=list loop=$recipes}
	<tr>
		<td>{$recipes[list].title}</td>
		<td>{$recipes[list].userName}</td>
		<td>{$recipes[list].category}</td>		
		<td>{$recipes[list].description}</td>
		<td>{$recipes[list].portions}</td>
		<td>{$recipes[list].preparationTime}</td>
		<td>{$recipes[list].created}</td>
		<td>{$recipes[list].visitCount}</td>
		<td><a href="{$links.view[1]}{$recipes[list].id}">{$links.view[0]}</a></td>
		<td><a href="{$links.edit[1]}{$recipes[list].id}">{$links.edit[0]}</a></td>
		<td><a href="{$links.delete[1]}{$recipes[list].id}">{$links.delete[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="11">{$noRecipeMessage}</td>
	</tr>
	{/section}
</table>
{if $recipes ne ''}
{$paginator}
{/if}
