var attached = null;
var dialog = null;
var xhr = null;

function insertAtCaret(myField, myValue) {
  if (document.selection) { //IE support
  	myField.focus();
  	sel = document.selection.createRange();
  	sel.text = myValue;
  	sel.moveStart('character', myValue.length);
  	sel.moveEnd('character', myValue.length);
  } else if (myField.selectionStart || myField.selectionStart == '0')	{ //MOZILLA/NETSCAPE support
  	var startPos = myField.selectionStart;
  	var endPos = myField.selectionEnd;
  	myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
  	myField.selectionStart = startPos + myValue.length;
  	myField.selectionEnd = startPos + myValue.length;
  } else {	//Anyone else.
  	myField.value += myValue;
  }
}

function countLength(text) {
  var length = text.length;
  var nonAscii = length - text.replace(/[\u0100-\uFFFF]/g, '').length;
  return length + nonAscii;
}

function updateCounter(){
  var length = Drupal.settings.annotation_book.maxlength - countLength(jQuery('#postbox .message').val());
  var info = (length>=0) ? Drupal.formatPlural(length, '@count character left.', '@count characters left.') :
  	Drupal.formatPlural(length, '@count character beyonds limit.', '@count characters beyond limit.');		
  jQuery('#postbox .counter').html(info);		
}

function attach(type, ret, html){
	attached = ret;
	
	jQuery('.ajax-status', dialog).remove();
	jQuery('.dialog', dialog).hide();
	jQuery('<div class="attached">' + html + '</div>').hide().appendTo(dialog).fadeIn();
}

(function($){
	$(function(){
		$('#postbox .message').keyup(updateCounter);
		$('#postbox .bnt-publish').click(publish);
		$('#postbox .toolbar a').click(openDialog);
	});

	function ajaxOption(){
		return {
			url  : Drupal.settings.basePath + 'ajax/annotation_book',
			type : 'POST',
			dataType : 'json',
			timeout : Drupal.settings.annotation_book.timeout * 1000,
			error : ajaxError
		}
	}
	
	function ajaxBeforeSend(loadingMsg){
		var status = $('.ajax-status', dialog);

		if( status.length == 0 )
		status = $('<div class="ajax-status"></div>').appendTo(dialog);
		
		status.removeClass('ajax-error').addClass('loading').css('line-height', parseInt(status.height()) + 'px').html('<span class="loading-img"></span>' + loadingMsg);
	}
	
	function ajaxError(jqXHR, textStatus, errorThrown){
		switch(textStatus){
		case 'error':
		 	$msg = 'Error happened in sending the request. Please try again later.';
		  break;
		case 'timeout':
		 	$msg = 'Request timeout. Please check your network connection or try again later.';
		 	break;
		default:
			$msg = textStatus;
		}
		
		if( $('.ajax-status', dialog).length != 0 ){
			$('.ajax-status', dialog).removeClass('loading').css('line-height', '').addClass('ajax-error').html(Drupal.t($msg));
		} else {
			alert(Drupal.t($msg));
		}
	}
	
	function ajaxComplete(){
		if( !$('.ajax-status', dialog).hasClass('ajax-error') ) {
			$('.ajax-status', dialog).remove();
			$('input[name="src"]', dialog).val('');
		}
	}
	
	function openDialog(){
		if( !dialog ){
			// init the dialog
			dialog = $('<div id="postbox-dialog"></div>').hide().appendTo($('body'));		
			
			$('<a href="javascript:void(0);" class="bnt-close"></a>')
			.appendTo(dialog)
			.click(closeDialog);
			
			// add switch tab actions within the dialog
			$('#postbox-dialog .tabs a').live('click', function(){
				if( $(this).hasClass('active') )
				return false;
				
				var hide_id = $('.tabs a.active', dialog).attr('href').replace(/^.*#/, '#');
				var show_id = $(this).attr('href').replace(/^.*#/, '#');
				
				$('.tabs a.active', dialog).removeClass('active');
				$(this).addClass('active');
				
				$(hide_id, dialog).hide();				
				$(show_id, dialog).show();
				
				$('.ajax-status', dialog).remove();
				$('input[name="url"]', dialog).val('');
					
				return false;
			});
			
			// bind enter key to the input in the dialog
			$('#postbox-dialog .dialog input[name="url"]').live('keyup', function(e){
				if( e.which==13 ){
					$(this).next('.bnt-attach').trigger('click');
				}
			});
		}

		var show = false;
		var id = $(this).attr('href').replace(/^.*#/, '#');
		if( dialog.is(':visible') ){
			var active_id = '#' + $('.dialog', dialog).attr('id');
			if( active_id != id ){
				if( closeDialog() ){
					show = true;
				}
			}
		} else {
			show = true;
		}

		if( show ){
			var content = $(id);
			if( content.length == 0 ) {
				var fn = 'create_' + id.replace('#', '').replace('-', '_');
				content = eval(fn + '()');
				content.addClass('dialog');
			}
			content.show().appendTo(dialog);
			var pos = $(this).offset();
			dialog.css('left', pos.left-dialog.innerWidth()).css('top', pos.top + $(this).outerHeight()).slideDown();
		}
		
		return false;
	}

	function closeDialog() {
		if( xhr ){
			if (xhr instanceof XMLHttpRequest) {
				xhr.abort();
			} else {
				xhr.swfu.cancelUpload();
			}
			xhr = null;
		}
		
		if( !attached || confirm( Drupal.t('Do you want to clear the attached?') ) ){
			resetDialog();
			return true;
		}

		return false;
	}
	
	function resetDialog(){
		if( dialog ) {
			dialog.hide();
			
			attached = null;			
			$('.attached', dialog).remove();
			
			$('.ajax-status', dialog).remove();
			$('input[name="url"]', dialog).val('');
						
			$('.dialog', dialog).hide().appendTo($('body'));
		}
	}

	function create_emotion_dialog() {
		var text = '';
		for(var key in smilies)
		text += '<a href="#">' + emotify(key) + '</a>';
		
		var emoticons = $('<div id="emotion-dialog">' + text + '</div>');		
		$('a', emoticons).click(function(){
			var title = $('img', $(this)).attr('title');
			var emotion = title.split(',')[1] + ' ';
			insertAtCaret($('#postbox .message')[0], emotion);
			updateCounter();
			return false;
		});		
		
		return emoticons;
	};

	function create_webpage_dialog(){
		var pageDialog = $('<div id="webpage-dialog"><label>' + Drupal.t('URL: ') + 
				'</label><input type="text" required value="" name="url" size="30" ><input type="submit" value="' +
				Drupal.t('Add') + '" class="bnt-attach form-submit"></div>');
		
		$('.bnt-attach', pageDialog).click( function(){
			var url = $('input[name="url"]', pageDialog).val();			
			if( url == '' )
			return false;

			var option = ajaxOption();
			option = $.extend(option, {
				data : {
					act : 'parse_page',
					url : url
				},
				beforeSend : ajaxBeforeSend(Drupal.t('Parsing url, please wait...')),
				complete : ajaxComplete,
				success: function(json){
					if( json.error ){
						ajaxError(this, json.error);
					} else {
						attach('page', {type:'page', url:url}, json);
					}
				}			
			});
			
			xhr = $.ajax(option);
			return false;
		});
		
		
		$('#postbox-dialog .nav.prev').live('click', function(){
			var current = $('.picture img:visible', dialog);
			current.hide().prev().show();									
			$('.nav.next', dialog).show();									
			$(this).toggle(current.prev().prev().length!=0);
			return false;								
		});
		
		$('#postbox-dialog .nav.next').live('click', function(){
			var current = $('.picture img:visible', dialog);
			current.hide().next().show();
			$('.nav.prev', dialog).show();
			$(this).toggle(current.next().next().length!=0);
			return false;
		});
		
		return pageDialog;
	}

	function create_image_dialog(){
		var imageDialog = $('<div id="image-dialog"></div>');		
		
		var tabs = $('<div class="tabs"><li><a href="#url-tab" class="active">' + Drupal.t('Add from URL') + '</a></li></div>');
		
		var url_tab = $('<div class="tab" id="url-tab"><label>' + Drupal.t('URL: ') + '</label><input type="text" required value="" name="url"><input type="submit" value="' + Drupal.t('Add') + '" class="bnt-attach form-submit"></div>');
		
		imageDialog.append(tabs).append(url_tab);
		
		var upload_tab = $('#image-uploader');
		if(upload_tab.length != 0){
			tabs.append('<li><a href="#image-uploader">' + Drupal.t('Upload image') + '</a></li>');
			upload_tab.hide().css({position:'', left:''}).addClass('tab').appendTo(imageDialog);
		}

		$('.bnt-attach', imageDialog).click(function(){
			var url = $('input[name="url"]', imageDialog).val();			
			if( url == '' )
			return false;

			var image = new Image();

			ajaxBeforeSend(Drupal.t('Loading image, please wait...') );
			
			image.onerror = function(){
				ajaxError(null, 'Error loading the image. Please check if the image url is correct.');
			};
			image.onload  = function(){
				attach('image', {type:'image', url:url}, '<img src="' + url + '">');
			};
			image.src = url;
		});
		
		return imageDialog;
	}
	
	function create_video_dialog(){
		var videoDialog = $('<div id="video-dialog"></div>');
		
		var tabs = $('<div class="tabs"><li><a href="#url-tab" class="active">' + Drupal.t('Add from URL') + '</a></li></div>');
		var url_tab = $('<div class="tab" id="url-tab"><label>' + Drupal.t('URL: ') + '</label><input type="text" required value="" name="url"><input type="submit" value="' + Drupal.t('Add') + '" class="bnt-attach form-submit"></div>');
		videoDialog.append(tabs).append(url_tab);
		
		var upload_tab = $('#video-uploader');
		if( upload_tab.length != 0 ){
			tabs.append('<li><a href="#video-uploader">' + Drupal.t('Upload video') + '</a></li>');
			upload_tab.hide().css({position:'', left:''}).addClass('tab').appendTo(videoDialog);
		}
		
		$('.bnt-attach', videoDialog).click(function(){
			var url = $('input[name="url"]', videoDialog).val();
			if( url == '' )
			return false;

			var option = ajaxOption();
			option = $.extend(option, {
				data : {
					act : 'parse_video',
					url : url
				},
				beforeSend : ajaxBeforeSend(Drupal.t('Parsing video, please wait...')),
				complete : ajaxComplete,
				success: function(json){
				}			
			});
			
			xhr = $.ajax(option);
		});
				
		return videoDialog;
	}	

	function publish(){
		if( dialog && $('.loading', dialog).length != 0 ) {
			alert( $('.loading', dialog).text() );
			return false;
		}
		
		if( $(this).hasClass('sending') ){
			alert(Drupal.t('System is processing your request. Please wait...'));
			return false;
		}
		
		var text = $.trim($('#postbox .message').val());		
		if( text == '' && !attached ) {
			alert(Drupal.t('Cannot post empty message.'));
			return false;
		}
		
		if( countLength(text) > Drupal.settings.annotation_book.maxlength ){
			alert(Drupal.t('Message is too long.'));
			return false;
		}
		
		var data = {
			act : 'publish', 
			selected : $.trim($('#postbox .selected').val()),
			msg : $.trim($('#postbox .message').val()),
			access : $.trim($('#postbox #privacy').val()),
			bid : $.trim($('#postbox #book_id').val()),
			pid : $.trim($('#postbox #page_id').val()),
			startx : $.trim($('#postbox #startx').val()),
			starty : $.trim($('#postbox #starty').val()),
			width : $.trim($('#postbox #width').val()),
			height : $.trim($('#postbox #height').val()),
			type : $.trim($('#postbox #type').val()),
			points: $.trim($('#postbox #points').val())
		};
		
		if( attached ){
			data.attach = attached;			
			if( attached.type == 'page' ){
				data.attach.title = $('.pageinfo .title', dialog).val();
				data.attach.desc = $('.pageinfo .description', dialog).val();
				data.attach.img = $('.pageinfo .picture img:visible', dialog).attr('src');
			}
		}
		
		var option = ajaxOption();
		option = $.extend(option, {
  		data : data,
			complete : function(){
				$('#postbox .bnt-publish').removeClass('sending');
			},
			success : function(json) {
                if (json.error) {
                    ajaxError(json.error);
                }
                else if (json.success){
                	resetDialog();
    				$('#postbox .message').val('');
    				updateCounter();
                    alert(json.success);
                    window.location.reload();
                }
			}		
		});

		resetDialog();
		$(this).addClass('sending');  	
		$.ajax(option);
		
		return false;
	}
})(jQuery);
