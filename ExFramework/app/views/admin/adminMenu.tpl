{if $adminMenuEntries neq ''}
<div class="menu">
	<div class="menu_title">
		{$adminMenuTitle}
	</div>
	<div class="menu_content">
		<ul class="menu_list">
		{section name=mlist loop=$adminMenuEntries}
			<li class="menu_list_item"><a class="menu_link" href="{$adminMenuEntries[mlist].url}">{$adminMenuEntries[mlist].title}</a></li>
		{/section}
		</ul>
	</div>
</div>
{/if}

