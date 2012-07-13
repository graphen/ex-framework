<h3 class="pageTitle">{$recipeViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$recipeTableHeadersNames.id}</th><td>{$recipe.id}</td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersNames.title}</th><td>{$recipe.title}</td>
	</tr>
	{if $recipe.userName neq ''}
	<tr>
		<th>{$recipeTableHeadersNames.userName}</th><td>{$recipe.userName}</td>
	</tr>
	{/if}
	<tr>
		<th>{$categoriesString}</th><td>{$recipe.category}</td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersNames.description}</th><td>{$recipe.description}</td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersNames.preparationMethod}</th><td>{$recipe.preparationMethod}</td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersNames.portions}</th><td>{$recipe.portions}</td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersNames.preparationTime}</th><td>{$recipe.preparationTime}</td>		
	</tr>
	<tr>		
		<th>{$recipeTableHeadersNames.approved}</th><td>{$recipe.approved}</td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersNames.created}</th><td>{$recipe.created}</td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersNames.updated}</th><td>{$recipe.updated}</td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersNames.visitCount}</th><td>{$recipe.visitCount}</td>
	</tr>	
	<tr>		
		<th>{$ingredientsString}</th>
		<td>
			<table>
			{section name=inglist loop=$recipe.items}
				<tr>
					<td>{$recipe.items[inglist].amount} {$recipe.items[inglist].unit} {$recipe.items[inglist].ingredient}</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}id/{$recipe.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}id/{$recipe.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
