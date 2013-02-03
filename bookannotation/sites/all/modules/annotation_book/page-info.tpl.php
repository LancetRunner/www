<div class="pageinfo" >
	<?php if (count($images) > 0) : ?>
	<div class="left">
		<div class="picture">
		<?php foreach($images as $i => $image) : ?>
			<img src="<?php print $image;?>" <?php if($i!=0): ?> style="display:none;" <?php endif;?> >
		<?php endforeach;?> 
		</div> 
		<?php if( count($images) > 1 ): ?>
		<a href="javascript:void(0);" class="nav prev" style="display:none;"></a>
		<a href="javascript:void(0);" class="nav next"></a>
		<?php endif; ?>
	</div>
	<div class="main">
	<?php endif; ?>
	<div>
			<label><?php print t('Title');?>: </label>
			<input type="text" class="title" value="<?php print $title;?>">
		</div>
		<div>
			<label><?php print t('Description');?>: </label>
			<textarea class="description" value="<?php print $description;?>"></textarea>
		</div>
	<?php if (count($images) > 0 ): ?>
	</div>
	<?php endif;?>
</div>