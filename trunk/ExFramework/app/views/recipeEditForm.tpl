<h3>{$authLoginFormTitle}</h3>

{if $flashError ne ''}
<ul>
	<li>{$flashError}</li>
</ul>
<ul>
	{foreach from=$errors key=index item=error}
		{foreach from=$error item=err}
			<li>{$index}: {$err}</li>		
		{/foreach}
	{/foreach}
</ul>
{/if}

<form method="post" action="{$actionLink}">
<input type="hidden" name="id" value="{$recipe.id}" />
<table>
	<tr>		
		<td>{$categoryString}</td>
		<td>
			<select name="cats">
					<option value="">...</option>
					{section name=clist loop=$categories}
						{if $recipe.cats ne ''}
							<option value="{$categories[clist].id}"	{if $recipe.cats eq $categories[clist].id}selected="selected"{/if}>{$categories[clist].name}</option>
						{else}
							<option value="{$categories[clist].id}">{$categories[clist].name}</option>
						{/if}
					{/section}
			</select>
		</td>
	</tr>
	<tr>
		<td>{$recipeTableHeadersStrings.title}</td><td><input type="text" name="title" maxlength="240" size="40" value="{$recipe.title}" /></td>
	</tr>
	<tr>		
		<td>{$recipeTableHeadersStrings.description}</td><td><textarea name="description" cols="40" rows="10">{$recipe.description}</textarea></td>
	</tr>
	<tr>		
		<td>{$recipeTableHeadersStrings.preparationMethod}</td><td><textarea name="preparationMethod" cols="40" rows="10">{$recipe.preparationMethod}</textarea></td>
	</tr>
	<tr>
		<td>{$recipeTableHeadersStrings.portions}</td><td><input type="text" name="portions" maxlength="10" size="40" value="{$recipe.portions}" /></td>
	</tr>
	<tr>
		<td>{$recipeTableHeadersStrings.preparationTime}</td><td><input type="text" name="preparationTime" maxlength="10" size="40" value="{$recipe.preparationTime}" /></td>
	</tr>
	<tr>		
		<td>{$ingredientsString}</td>
		<td>
			<table>
				<tr>
					<td>{$ingredientNameString}</td><td>{$amountString}</td><td>{$unitNameString}</td>
				</tr>
				{if $recipe.itms ne ''}
				{section name=ilist loop=$recipe.itms}
				<tr>
					<td>
					<select name="ingredients[]">
						<option value="">...</option>
						{section name=inglist loop=$ingredients}
							{if $recipe.itms[ilist] ne ''}
								<option value="{$ingredients[inglist].id}" {if $ingredients[inglist].id eq $recipe.itms[ilist].ingredients}selected="selected"{/if}>{$ingredients[inglist].name}</option>
							{else}
								<option value="{$ingredients[inglist].id}">{$ingredients[inglist].name}</option>
							{/if}				
						{/section}
					</select><br />
					</td>
					<td>
					{if $recipe.itms[ilist] ne ''}
						{if $recipe.itms[ilist].amount ne ''}
							<input type="text" name="amount[]" value="{$recipe.itms[ilist].amount}" size="10" /><br />
						{else}
							<input type="text" name="amount[]" value="" size="10" /><br />
						{/if}
					{/if}
					</td>
					<td>
					<select name="units[]">
						<option value="">...</option>
						{section name=unlist loop=$units}
							{if $recipe.itms[ilist] ne ''}
								<option value="{$units[unlist].id}" {if $units[unlist].id eq $recipe.itms[ilist].units}selected="selected"{/if}>{$units[unlist].abbreviation}</option>
							{else}
								<option value="{$units[unlist].id}">{$units[unlist].abbreviation}</option>
							{/if}				
						{/section}
					</select><br />
					</td>
				</tr>		
				{/section}
				{/if}
			</table>
		</td>
	</tr>
	<tr>
		<td> </td>
		<td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
