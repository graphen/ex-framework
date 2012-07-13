{if $userMenuEntries neq ''}
<div class="menu">
	<div class="menu_title">
		{$userMenuTitle}
	</div>
	<div class="menu_content">
		<ul class="menu_list">
		{section name=mlist loop=$userMenuEntries}
			<li class="menu_list_item"><a class="menu_link" href="{$userMenuEntries[mlist].url}">{$userMenuEntries[mlist].title}</a></li>
		{/section}
		</ul>
	</div>
</div>
{/if}

