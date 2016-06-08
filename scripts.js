$(document).ready(function() {

  $('.alert').hide();

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

  $('.edit-btn').on('click', function () {
    $currentTD = $(this).parents('tr').find('td');
    if($(this).html() == 'Edit') {
      $(this).html('Cancel');
      $.each($currentTD, function() {
        if($(this).prop('class') == 'xx') {
          return false;
        }
        $(this).prop('contenteditable', true);
        $(this).addClass('editable');
      });
      return false;
    } else if($(this).html() == 'Cancel') {
      $(this).html('Edit');
      $.each($currentTD, function() {
        $(this).prop('contenteditable', false);
        $(this).removeClass('editable');
      });
      return false;
    }
  });

  $('.save-btn').on('click', function() {
    $currentTD = $(this).parents('tr').find('td');
    $.each($currentTD, function() {
      if($(this).prop('class') == 'xx') {
        return false;
      }
      $(this).prop('contenteditable', false);
      $(this).removeClass('editable');
      $('#save-alert').alert();
      $('#save-alert').show().delay(1500).fadeOut(1500, function() {
        $('#save-alert').hide();
      });
      $('.edit-btn').html('Edit');
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
  });

  $('.delete-btn').on('click', function() {
    $currentTD = $(this).parents('tr').find('td');
    $.each($currentTD, function() {
      if($(this).prop('class') == 'proj_cd') {
        $proj_cd = $(this).prop('id');
      }
      /*
      $.ajax({
        url: 'delete.php',
        type: 'POST',
        data: {
          proj_cd: $proj_cd
        },
        success: function(result) {
          console.log(result);
        }
      });
      */
      $('#delete-alert').alert();
      $('#delete-alert').show().delay(1500).fadeOut(1500, function() {
        $('#delete-alert').hide();
      });
    });
  });
  
});