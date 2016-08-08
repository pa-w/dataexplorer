<!DOCTYPE html>
<html lang="en-us">
	<head>
	<meta charset="UTF-8">
	<title>{$page.title} | Data explorer</title>
                <meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <script src="/js/libs/jquery.min.js"></script>
		<script src="/js/libs/d3.v4.min.js"></script>
		<script src="/js/libs/d3-geo.v1.min.js"></script>
		<script src="/js/libs/d3-geo-projection.v1.min.js"></script>
		<script src="/js/libs/d3-selection-multi.v0.4.min.js"></script>
		<script src="/js/libs/d3-axis.v1.min.js"></script>
                <script src="/js/libs/topojson.js"></script>
                <script src="/js/libs/queue.min.js"></script>
		<script src="/js/ant.js"></script>
		{foreach from=$page.js item=js}
		<script src="{$js}"></script>
		{/foreach}
		{foreach from=$page.css item=css} 
                <link rel="stylesheet" href="{$css}"/>
		{/foreach}
	</head>
	<body>
	<div>
		<div class="header row">
			{include 'header.tpl'}
		</div>
		<div class="content row">
			{$content}
		</div>
	</div>
	<script>
	$(document).ready (function () {
		{fetch file=$antDefinition}
		new Ant (conf);
	});
	</script>
	</body>
</html>
