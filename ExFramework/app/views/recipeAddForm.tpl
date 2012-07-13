<h3 class="pageTitle">{$recipeAddFormTitle}</h3>

{if $flashError ne ''}
<ul>
	<li class="error">{$flashError}</li>
</ul>
<ul>
	{foreach from=$errors key=index item=error}
		{foreach from=$error item=err}
			<li class="error">{$index}: {$err}</li>		
		{/foreach}
	{/foreach}
</ul>
{/if}

<form method="post" action="{$actionLink}">
<input type="hidden" name="usr" value="{$usr}" />
<table class="table_form">
	<tr>
		<th>{$categoryString}</th>
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
		<th>{$recipeTableHeadersStrings.title}</th><td><input type="text" name="title" maxlength="240" size="40" value="{$recipe.title}" /></td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersStrings.description}</th><td><textarea name="description" cols="50" rows="10">{$recipe.description}</textarea></td>
	</tr>
	<tr>		
		<th>{$recipeTableHeadersStrings.preparationMethod}</th><td><textarea name="preparationMethod" cols="50" rows="10">{$recipe.preparationMethod}</textarea></td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersStrings.portions}</th><td><input type="text" name="portions" maxlength="10" size="40" value="{$recipe.portions}" /></td>
	</tr>
	<tr>
		<th>{$recipeTableHeadersStrings.preparationTime}</th><td><input type="text" name="preparationTime" maxlength="10" size="40" value="{$recipe.preparationTime}" /></td>
	</tr>
	<tr>		
		<th>{$ingredientsString}</th>
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
		<th> </th>
		<td><input type="reset" name="reset" value="{$resetString}" /><input type="submit" name="submit" value="{$submitString}" /></td>
	</tr>
</table>
</form>
