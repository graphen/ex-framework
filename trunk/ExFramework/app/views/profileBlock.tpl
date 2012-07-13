<div class="block">
	<div class="block_title">
		{$profileBlockTitle}
	</div>
	<div class="block_content">
		{if $user ne ''} 
			{$WelcomeString}: {$user.login} <br />
			{$LastAccessString}: {$user.lastAccess} <br />
			<a href="{$logoutLink}">{$LogoutString}</a> 
		{else}
		<form method="post" action="{$actionLink}">
		<table>
			<tr>
				<td>
					{$LoginLabel}
				</td>	
				<td>
					<input type="text" name="login" size="10" />
				</td>
			</tr>
			<tr>
				<td>
					{$PasswordLabel}
				</td>	
				<td>
					<input type="password" name="password" size="10" />
				</td>
			</tr>
			<tr>
				<td> </td>
				<td>
					<input type="submit" value="{$LoginString}" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{$NoAccountString} - <a href="{$registerLink}">{$RegisterString}</a>
				</td>
			</tr>	
		</table>
		</form>
		{/if}
	</div>
</div>
