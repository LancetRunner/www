<div id="postbox" class="clearfix">
	<table>
		<tr>
			<td colspan = "2">
				<input id="book_id" type="hidden" value="<?php if(is_numeric(arg(1))) print arg(1);?>">
				<input id="page_id" type="hidden">
				<input id="startx" type="hidden">
				<input id="starty" type="hidden">
				<input id="width" type="hidden">
				<input id="height" type="hidden">
				<input id="type" type="hidden">
				<input id="points" type="hidden">
				<textarea class="selected" id="selectedText" cols="45" rows="4" placeholder="<?php print t("Selected text goes here...");?>" disabled = "true" value="empty value"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan = "2">
				<textarea class="message" cols="45" rows="3" placeholder="<?php print t("Say something...");?>"></textarea>
				<div class="counter"><?php print t('@count characters left', array('@count' => variable_get('annotation_book_maxlength', 255))) ?></div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="toolbar"><?php print implode("\n", $links); ?></div>
			</td>
		</tr>
		<tr>
			<td>
				<select id="privacy">
					  <option value ="Public">Public</option>
					  <option value="Friends">Friends</option>
					  <option value="Private">Private</option>
				</select>
			</td>
			<td>
				<input type="button" class="bnt-publish" value="<?php print t('Annotate') ?>">
			</td>
		</tr>
	</table>
</div>