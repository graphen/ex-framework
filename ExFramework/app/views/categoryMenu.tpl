{if $categoriesMenuList neq ''}
<div class="menu">
	<div class="menu_title">
		{$categoryMenuTitle}
	</div>
	<div class="menu_content">
		<ul class="menu_list">
		{section name=mlist loop=$categoriesMenuList}
			<li class="menu_list_item"><a class="menu_link" href="{$categoriesMenuList[mlist].url}">{$categoriesMenuList[mlist].title}</a></li>
		{/section}
		</ul>
	</div>
</div>
{/if}



