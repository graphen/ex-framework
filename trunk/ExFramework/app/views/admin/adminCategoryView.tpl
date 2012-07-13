<h3 class="pageTitle">{$categoryViewTitle}</h3>
<table class="table_view">
	<tr>
		<th>{$categoryTableHeadersNames.id}</th><td>{$category.id}</td>
	</tr>
	<tr>
		<th>{$categoryTableHeadersNames.name}</th><td>{$category.name}</td>
	</tr>
	<tr>
		<th>{$categoryTableHeadersNames.info}</th><td>{$category.info}</td>
	</tr>
	<tr>		
		<th>{$recipesString}</th>
		<td>
			<table>
			{section name=relist loop=$category.recipes}
				<tr>
					<td>{$category.recipes[relist].title}</td>
				</tr>
			{sectionelse}
				<tr>
					<td>{$noRecipeMessage}</td>
				</tr>
			{/section}
			</table>
		</td>
	</tr>
	<tr>
		<th> </th><td>[<a href="{$links.edit[1]}{$category.id}">{$links.edit[0]}</a>][<a href="{$links.delete[1]}{$category.id}">{$links.delete[0]}</a>]</td>
	</tr>
</table>
