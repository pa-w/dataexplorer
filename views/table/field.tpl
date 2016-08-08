<div class="row th">
<table>
	<tbody>
		<tr>
			<th>Data type:</th>

			<td>
				<select name="data_type">
					{foreach from=$data_types item=data_type}
					<option value="{$data_type}" {if $data_type == $field_info.data_type}selected{/if}>{$data_type}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</tbody>
</table>
</div>
<br>
<div class="row">
<a data-onload data-debug="Here I am" data-download="/table/unique/{$table}/{$field}.csv" data-download_id="unique" data-download_parse="#update_matrix"></a>
<a id="update_matrix" data-create_element="tr" data-element_container="#matrix" data-catalog="unique" data-item_element="td"></a>
<table>
	<tbody id="matrix">
	</tbody>
</table>
</div>
