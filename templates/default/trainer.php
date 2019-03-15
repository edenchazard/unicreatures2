<?php require $template .'/header.php'; ?>
<p><img style='float:left;' src='/images/npc/darla_nettingbird.png' />
<p>"Welcome back!" says a beautiful woman as you enter.</p>

<? if(!$found_eggs) : ?>
<span class='deny'>You've already collected enough eggs this hour!</span>
<? else : ?>
<p>"Come in! Here! I've got some new pets for you, as long as you don't have too many already."</p>
<p>She holds out a basket of eggs. As you reach over to take one, you see there are different types. (These change every hour, so make sure to take what you want!)</p>
<table class='table-creatures'>
	<tbody>	
	<?=draw_fancy_table_rows($basket_eggs, 3,
			function($egg){
				if($egg['claimed']) return;
				
				return "<a href='/basket.php?egg={$egg['slot']}'><img src='".mkimg($egg)."' alt='{$egg['creatureID']}' /></a>
						<br />".
						($egg['speciality'] > 0 ? "<span class='b'>{$egg['visual_description']}</span>"
												: $egg['visual_description']);
			}
		) ?>
	</tbody>
</table>
<div class='center'>
	<img src='/images/world/wild.png' />
	<br />
	<a href='/area.php?wild=<?=$user->username ?>'>Your egg will be delivered to your wild area!</a>
</div>
<? endif ?>
<?php require $template .'/footer.php'; ?>