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
    if($(this).val() == 'Edit') {
      $(this).val('Cancel');
      $(this).prop('title', 'Cancel');
      $(this).html("<i class='fa fa-times' aria-hidden='true'></i>");
      $(this).removeClass('btn-success');
      $(this).addClass('btn-danger');
      $.each($currentTD, function() {
        if($(this).prop('class') == 'xx') {
          return false;
        }
        $(this).prop('contenteditable', true);
        $(this).addClass('editable'); 
      }); 
      return false;
    } else if($(this).val() == 'Cancel') {
      $(this).val('Edit');
      $(this).prop('title', 'Edit');
      $(this).html("<i class='fa fa-pencil fa-lg' aria-hidden='true'></i></span>");
      $(this).removeClass('btn-danger');
      $(this).addClass('btn-success');
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
      $edit_btn = $(this).parents('tr').find('.edit-btn');
      $edit_btn.val('Edit');
      $edit_btn.html("<span><i class='fa fa-pencil fa-lg' aria-hidden='true'></i></span>");
      $edit_btn.removeClass('btn-danger');
      $edit_btn.addClass('btn-success');
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
      $.ajax({
        url: '',
        type: 'POST',
        data: {
          proj_cd: $proj_cd
        },
        success: function(result) {
          console.log(result);
        }
      });
      $('#delete-alert').alert();
      $('#delete-alert').show().delay(1500).fadeOut(1500, function() {
        $('#delete-alert').hide();
      });
      $(this).fadeTo(800, 0, function() {
        $(this).remove();
      });
    });
  });


  $('#insert').on('click', function() {
    $proj_cd_form = $('#proj_cd_form').val();
    $proj_nm_form = $('#proj_nm_form').val();
    $proj_mgr_form = $('#proj_mgr_form').val();
    $client_nm_form = $('#client_nm_form').val();
    $proj_loc_form = $('#proj_loc_form').val();
    $.ajax({
      url: 'newentry.php',
      type: 'POST',
      data: {
        proj_cd_form: $proj_cd_form,
        proj_nm_form: $proj_nm_form,
        proj_mgr_form: $proj_mgr_form,
        client_nm_form: $client_nm_form,
        proj_loc_form: $proj_loc_form
      },
      success: function(result) {
        console.log(result);
      }
    });
    $('#new_entry_form')[0].reset();
  });

});