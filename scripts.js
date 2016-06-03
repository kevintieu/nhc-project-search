$(document).ready(function() {
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function() {
		if ($(this).scrollTop() > 100) {
			$('.scroll').fadeIn();
		} else {
			$('.scroll').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scroll').click(function() {
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
	$('[data-toggle="tooltip"]').tooltip();

    $('.edit-btn').click(function() {
      	var currentTD = $(this).parents('tr').find('td');
      	if ($(this).html() == 'Edit') {
        	currentTD = $(this).parents('tr').find('td');
        	$.each(currentTD, function() {
          		$(this).prop('contenteditable', true)
          		$(this).addClass('editable')
        	});	
      	} else {
        	$.each(currentTD, function() {
          		$(this).prop('contenteditable', false)
          		$(this).removeClass('editable')
        	});
      	}

      	$(this).html($(this).html() == 'Edit' ? 'Save' : 'Edit')
    });

});