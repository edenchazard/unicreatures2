<?php require $template .'/header.php'; ?>
<div class='center'>
	<img src='<?=$image ?>' alt='<?=$creature['family'] ?>' />
	<br />
	<? if($creature['is_special']) : ?>
	<span class='b'><?=$creature['visual_description'] ?></span>
	<? else : ?>
	<?=$visual_description ?>
	<? endif ?>
</div>
<p class='center'>
	<? if(!$can_collect) : ?>
	<span class='deny'>You cannot claim this egg because you already have <?=$unhatched ?> unhatched eggs! You must have 5 or less eggs to get more from the trainer.</span>
	<? else : ?>
	<a href='/basket.php?egg=<?=$slot_number ?>&collect=true'>Collect this egg!</a>
	<? endif ?>
</p>
<br />
<p class='center'>There is a small scroll with the egg:</p>
<p class='basket-scroll center'>
	<?=$lifestyle ?>
</p>
<?php require $template .'/footer.php'; ?>