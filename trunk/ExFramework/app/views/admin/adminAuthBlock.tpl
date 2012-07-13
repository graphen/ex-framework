<div class="block">
	<div class="block_title">
		{$adminAuthBlockTitle}
	</div>
	<div class="block_content">
		{if $user ne ''} 
			{$WelcomeString}: {$user.login} <br />
			{$LastAccessString}: {$user.lastAccess} <br />
			<a href="{$logoutLink}">{$LogoutString}</a> 
		{/if}
	</div>
</div>
