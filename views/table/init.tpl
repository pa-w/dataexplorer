<a id="hide_overlay" data-control_element=".overlay" data-element_hide></a>
<h2>Table info {$table}</h2>
<a id="get_columns" data-download="/table/columns/{$table}.csv" data-download_id="columns" data-onload></a>
<a id="get_data_types" data-download="/table/info/data_types.csv" data-download_id="data_types" data-onload data-download_parse="#refresh_data_types"></a>
<a id="get_operators" data-download="/table/info/operators.csv" data-download_id="operators" data-download_parse="#refresh_operators" data-onload></a>
<a id="get_aggregate_functions" data-download="/table/info/aggregate_functions.csv" data-download_id="aggregate_functions" data-onload></a>
<a id="get_functions" data-download="/table/info/functions.csv" data-download_id="functions" data-download_parse="#refresh_functions" data-onload/>
<a id="refresh_functions" data-catalog="functions" data-create_element="option" data-element_container=".functions" data-element_attr='{literal}{{/literal}"value": "key"{literal}}{/literal}' data-element_html="key"/>
<a id="refresh_operators" data-catalog="operators" data-create_element="option" data-element_container=".operators" data-element_attr='{literal}{{/literal}"value": "key"{literal}}{/literal}' data-element_html="value"></a>
<a id="refresh_data_types" data-catalog="data_types" data-create_element="option" data-element_container=".data_types" data-element_attr='{literal}{{/literal}"value": "value"{literal}}{/literal}' data-element_html="value"/>
<div class="row">
	<form id="matrix_form">
	<div class="col two">
		<div class="row">
			<h2>Columns</h2>
			<a id="update_groups" data-debug="Update groups" data-download_method="post" data-download="/table/matrix/{$table}.csv" data-download_id="matrix" data-query_string_form="matrix_form" data-download_parse="#create_hierarchy,#clear_matrix,#update_matrix,#chart_matrix,#chart_treemap"></a>
			<a id="create_hierarchy" data-callback="create_hierarchy"></a>
			<a id="clear_matrix" data-control_element="#matrix tr" data-element_remove></a>
			<a id="update_matrix" data-catalog="matrix" data-create_element="tr" data-element_container="#matrix" data-item_element="td"></a>
			<a id="chart_matrix" data-control_chart="chart" data-quantify="matrix" data-quantifier="matrix"></a>
			<a id="chart_treemap" data-control_chart="treemap" data-quantify="matrix" data-quantifier="treemap"></a>
			<a id="update_unique" data-catalog="unique" data-create_element="option" data-element_container=".unique" data-element_html="column_name" data-debug="Will update"></a>
			<a id="refresh_unique" data-control_element=".unique" data-element_remove_children data-parse="#update_unique"></a>
			<a id="update_trees" data-parse_sequence=".update_tree,#update_groups"></a>
			<a id="update_tree" class="update_tree" data-callback="update_tree"></a>
			{foreach from=$columns item=column key=i}
				<a id="update_groups_{$i}" data-callback="update_groups" data-callback_args='{literal}{{/literal}"column": "{$column.table_name}.{$column.column_name}.{$column.data_type}", "id": {$i}{literal}}{/literal}'></a>
				<a id="update_unique_{$i}" data-download="/table/unique/{$table}/{$column.column_name}.csv" data-download_id="unique" data-download_parse="#refresh_unique"></a>
			{/foreach}
			<table>
				<tr>
					<td>
						Group by:
						<select data-control>
							<option>- choose a column -</option>
							{foreach from=$columns item=column key=i}
							<option value="{$column.table_name}.{$column.column_name}" data-parse="#update_groups_{$i}">{$column.column_name}</option>
							{/foreach}
						</select>
					</td>
					<td>
						<input type="button" value="Add">
					</td>
				</tr>
			</table>
		</div>
		<div class="row">
			<table>
				<thead>
				<tr><th>&nbsp;</th><th>Aggregate</th><th>Distinct</th><th>Column Name</th><th>Data Type</th><th>&nbsp;</th></tr>
				</thead>
				<tbody id="groups">
				</tbody>
				<tfoot>
					<tr><th colspan=6><input type="button" value="Refresh" data-click="#update_trees"/></th></tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="col two">
		<input type="hidden" id="filter" name="filter"/>
		<h2>Filters</h2>
		<div id="filter_ui">

		<table>
			<thead id="filter_control">
				<tr>
					<th colspan="2">
						Group:
						<input type="radio" class="group_op" name="group_op" value="AND"/> AND
						<input type="radio" class="group_op" name="group_op" value="OR"/> OR
					</th>
					<th colspan="5">
					<input type="button" value="Add rule" class="ui rule" data-click data-callback="add_rule" data-callback_args='{literal}{{/literal}"type": "rule", "cont": "#filter_main"{literal}}{/literal}'/>
					<input type="button" value="Add group" class="ui group" data-click data-callback="add_rule" data-callback_args='{literal}{{/literal}"type": "group", "cont": "#filter_main"{literal}}{/literal}'/>
					</th>
				</tr>
			</thead>
			<tbody id="filter_main"></tbody>
		</table>
		</div>
		<input type="button" value="Send" data-click="#test"/>
	</div>
	</form>
</div>
<div class="row">
	<div>
		<table>
			<tbody id="matrix">
			</tbody>
		</table>
	</div>
</div>
