<h3 class="pageTitle">{$recipeListTitle}</h3>

{if $flashNotice ne ''}
<ul class="notice">
	<li>{$flashNotice}</li>
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
		<th> </th>
	</tr>
	{section name=list loop=$recipes}
	<tr>
		<td><a href="{$links.view[1]}{$recipes[list].id}">{$recipes[list].title}</a></td>
		<td>{$recipes[list].userName}</td>
		<td>{$recipes[list].category}</td>		
		<td>{$recipes[list].description}</td>
		<td>{$recipes[list].portions}</td>
		<td>{$recipes[list].preparationTime}</td>
		<td>{$recipes[list].created}</td>
		<td>{$recipes[list].visitCount}</td>
		<td><a href="{$links.view[1]}{$recipes[list].id}">{$links.view[0]}</a></td>
	</tr>
	{sectionelse}
	<tr>
		<td colspan="9">{$noRecipeMessage}</td>
	</tr>
	{/section}
</table>
{if $recipes ne ''}
{$paginator}
{/if}
