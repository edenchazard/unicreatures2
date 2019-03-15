<?php	require $template .'/header.php'; ?>
<?php
$fancy = function($creature){
	$leaving = calculate_leaving($creature['rem_days'], $creature['every_year_until']);
					return "<a href='donate.php?id={$creature['familyID']}'><img src='".mkimg($creature)."' /></a>
							<br />
							{$creature['family_name']}
							<br />
							family
							<br />".
							(strpos($leaving, 'Retires') === false ? "<span style='color:#AA4444'>{$leaving}</span>"
																	: "<span style='color:#CC0000; font-weight:bold'>{$leaving}</span>
																	<br />
																	<span class='b blue'>This creature will not return!</span>");
		} ?>
							<div>
								<h1 class='b u'>Help Us Evolve! Donate To UniFaction!</h1>
								<p>Generously donating to UniCreatures will help ensure that new art is often produced and the site is frequently updated. To encourage frequent support, donators can receive exotic pets!</p>

								<h1 class='b u'>What are Exotic Pets?</h1>
								<p>As the name suggests, they're pets that are unique and extremely rare and hard to come by. In this case, you can acquire them through donations. We want to thank our donators by giving them that unique ability.</p>
							</div>
							<div class='center'>
								<div class='b u'>Choose an Exotic or Legendary Creature with your Exotic Credits:</div>
<?php	if(is_logged_in()){ ?>
								You currently have <span class='b'><?php _e($user->exotic_credits) ?></span> exotic credits to spend!
<?php	} ?>
								<table class='table-creatures'>
									<tbody>
<?php _e(draw_fancy_table_rows($available_creatures, 4, $fancy)) ?>
									</tbody>
								</table>
							</div>
<?php	require $template .'/footer.php'; ?>