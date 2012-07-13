<div class="block">
	<div class="block_title">
		{$searchBlockTitle}
	</div>
	<div class="block_content">	
		<form method="post" action="{$actionLink}">
		<table border="0">
			<tr>
				<td colspan="2">
					<input type="text" name="q" size="15" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{$catFilterString}
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<select name="cId">
						<option value="">...</option>
						{section name=clist loop=$categories}
						<option value="{$categories[clist].id}">{$categories[clist].name}</option>
						{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td>
					{$userFilterString}
				</td>
			<tr>
				<td colspan="2">
					<select name="uId">
						<option value="">...</option>
						{section name=ulist loop=$users}
						<option value="{$users[ulist].id}">{$users[ulist].login}</option>
						{/section}
					</select><br />	
				</td>
			</tr>
			<tr>
				<td> </td>
				<td>
					<input type="submit" value="{$searchString}" />
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
