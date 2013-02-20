(function($){
	$(function(){
		$('.annotation-teaser a.more-link').click(function(event) {
			var attr = $(this).attr('id');
			PDFView.page = attr.replace(/[^0-9]/g, '');
        });
	});
})(jQuery);
