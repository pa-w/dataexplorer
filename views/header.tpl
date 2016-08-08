<div class="col">
	Tables:
	<ul>
	{foreach from=$_tables item=table}
		<li><a href="/table/{$table}">{$table}</a></li>
	{/foreach}
	</ul>
	<a href="/table/import/">Import table</a>
</div>
