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

  $('.edit-btn').on('click', function() {
    $currentTD = $(this).parents('tr').find('td');
    if($(this).val() == 'Edit') {
      $(this).val('Save');
      $.each($currentTD, function() {
        if($(this).prop('class') == 'xx') {
          return false;
        }
        $(this).on('blur', function() {
          $.each($currentTD, function() {
            $(this).prop('contenteditable', false);
            $(this).removeClass('editable');
            $('.edit-btn').val('Edit');
          });
        });
        $(this).prop('contenteditable', true);
        $(this).addClass('editable');
      });
      return false;
    } else if($(this).val() == 'Save') {
      $(this).val('Edit');
      $.each($currentTD, function() {
        if($(this).prop('class') == 'xx') {
          return false;
        }
        $(this).prop('contenteditable', false);
        $(this).removeClass('editable');
        if($(this).prop('class') == 'proj_cd') {
          $proj_cd = $(this).prop('id');
        }
        $field_name = $(this).prop('class');
        $value = $(this).html();
        $.ajax({
          url: 'edit.php',
          type: 'POST',
          data: {
            field_name: $field_name,
            value: $value,
            proj_cd: $proj_cd
          },
          success: function(result) {
            console.log(result);
          }
        });
      });
      return false;
    }
  });


});

