<?php	require $template .'/header.php'; ?>
<?php	add_filter('profile_title', function($v){ return "<h1>".get_profile_username()."'s Inventory</h1>"; }) ?>
<?php	get_profile_navigation() ?>
<?php	$fancy_item = function($arr_v) use ($profile_user){
				$obj = new something('item', $arr_v['name']);
				return "<img src='{$obj->image()}' />
						<br />
						{$obj->name} ({$profile_user->inventory($obj->name)})";
		};
		$fancy_comp = function($arr_v) use ($profile_user){
				$obj = new something('component', $arr_v['name']);
				return "<img src='{$obj->image()}' />
						<br />
						{$obj->name} ({$profile_user->inventory($obj->name)})";
		};
?>
						<table id='inventory' class='center'>
							<tbody>
								<tr>
									<td colspan='4'><span class='b'>Equipment</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($equipment, 4, $fancy_item)) ?>
								<tr>
									<td colspan='4'><span class='b'>Regular components</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($std_components, 4, $fancy_comp)) ?>
								<tr>
									<td colspan='4'><span class='b'>Rare Components</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($rare_components, 4, $fancy_comp)) ?>
								<tr>
									<td colspan='4'><span class='b'>Building Materials</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($building_materials, 4, $fancy_comp)) ?>
								<tr>
									<td colspan='4'><span class='b'>Tree components</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($tree_components, 4, $fancy_comp)) ?>
								<tr>
									<td colspan='4'><span class='b'>Orbs</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($orb_components, 4, $fancy_comp)) ?>
								<tr>
									<td colspan='4'><span class='b'>Shards</span></td>
								</tr>
<?php	_e(draw_fancy_table_rows($shard_components, 4, $fancy_comp)) ?>
							</tbody>
						</table>
<?php	require $template .'/footer.php'; ?>