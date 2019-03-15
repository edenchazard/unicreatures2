<?php	require $template .'/header.php'; ?>
<?php
function has_enough($user, $ingredient){
	$n = $user->inventory($ingredient['obj']->name);

	if($n >= $ingredient['amount'])
		return true;
	return false;
}
?>

<script>
$(document).ready(function(){
	$("#transmute-partial").click(function(e){
		e.preventDefault();
			$('#readmore-hidden').slideToggle(600);
		});
	}
);
</script>

<?php	if(!empty($page->s)) _e($page->s[1]); ?>
					<div class='center'>
						<h1>The New Atlantis Plaza</h1>
						<div id='transmute-partial'>
							<p>You find yourself in a remarkable shop with thousands of drawers, cabinets, shelves and mysterious containers lining the walls and filling nearly all of the floor space. If you take time to study these many and varied containers you will find that they contain components from all over Esme. There are petrified dragon scales and moonrubies and dried mountain troll drool and fresh-picked hensbane and everything else you could possibly imagine. If you look far enough, you will probably find some things you've never imagined. <a href='#' id='readmore'>[read more...]</a></p>
							<div id='readmore-hidden' style='display:none'>
								<p>The shopkeeper is very patient while you look around, but he will stop you should you try to open any of the containers. Hephasteus is very meticulous about keeping all of his components in order.</p>
								<p>You take a moment to study Hephasteus. He clearly is a man with a lot of fashion sense. If you want to know the very latest on fae fashion or gnome haute couture... this is your guy. He smokes a tiny cigarette in a long holder. The smoke smells faintly of elderberries and, maybe, bluemaple. He is saying something to you, but he speaks softly and the words are lost in the sudden burst of loud operatic music playing in the background.</p>
								<p>When the music quiets down a bit, Hephasteus asks you in a quiet lilting voice, "So what can I do for you today?"</p>
							</div>
						</div>
						<br />
						<p class='purple'>"<?=$exchange['dialogue'] ?>", asks Hephasteus.</p>
						<? get_shop_navigation() ?>
					</div>
					<table class='maxwidth' id='transmute'>
						<thead>
							<th>Spell</th>
							<th>Ingredients</th>
							<th>&nbsp;</th>
						</thead>
						<tbody>
		<? foreach ($store_data as $spell) : ?>
			<? $fulfilled = true ?>
			<? $leave = calculate_leaving($spell['remaining'], $spell['every_year_until']) ?>
							<tr>

								<td class='transmute-first'>
									<div class='transmute-title'><?php _e($spell['spell_name']) ?></div>
									<div>Produces</div>
									<div><img src='<?=$spell['obj']->image() ?>' /></div>
									<div class='b'><?=$spell['amount'] ?>x <?=$spell['obj']->name ?></div>
									<div><?=$leave ?></div>
								</td>
								<td class='transmute-ingredients'>
				<? foreach($spell['ingredients'] as $ingredient) : ?>
					<? $thing = $ingredient['obj'] ?>
									<div class='transmute-blocks'>
					<? if(has_enough($user, $ingredient)): ?>
										<div><img src='<?=$thing->image() ?>' /></div>
					<? else : ?>
						<? $fulfilled = false ?>
										<div><img src='<?=$thing->image('_fade') ?>' /></div>
					<? endif ?>
										<div class='b'><?=$thing->name ?></div>
										<div>(<?=number_format($user->inventory($thing)) ?>/<?=number_format($ingredient['amount']) ?>)</div>
									</div>
<?php			endforeach ?>
								</td>
								<td>
									<form method='POST'>
										<div>
											<input type='hidden' name='spell' value='<?=$spell['ID'] ?>' />
											<input type='text' name='amount' value='1' size='2' />
											<br />
											<input type='submit' value='Transmute' <?=($fulfilled == false ? "disabled='disabled'" : "") ?>/>
										</div>
									</form>
								</td>
							</tr>
							<tr class='transmute-border'>
								<td colspan='3'><?=$spell['short_description'] ?></td>
							</tr>
<?php		endforeach ?>
<?php	//}; ?>
						</tbody>
					</table>
<?php require $template .'/footer.php'; ?>