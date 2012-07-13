{if $menuEntries neq ''}
<div class="menu">
	<div class="menu_title">
		{$menuTitle}
	</div>
	<div class="menu_content">
		<ul class="menu_list">
		{section name=mlist loop=$menuEntries}
			<li class="menu_list_item"><a class="menu_link" href="{$menuEntries[mlist].url}">{$menuEntries[mlist].title}</a></li>
		{/section}
		</ul>
	</div>
</div>
{/if}

