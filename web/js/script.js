$(function() {

    $('.userDelete').click(function(event) {
        event.preventDefault();
        href = $(this).attr('href');
        bootbox.confirm("<h4>Are you sure you want to delete user '" + $(this).data('username') + "'?</h4>", function(result) {
            if(result) {
                window.location = href;
            }
        });
    });

    $('.groupDelete').click(function(event) {
        event.preventDefault();
        href = $(this).attr('href');
        bootbox.confirm("<h4>Are you sure you want to delete group '" + $(this).data('groupname') + "'?</h4>", function(result) {
            if(result) {
                window.location = href;
            }
        });
    });

    $('.logout').click(function(event) {
      event.preventDefault();
      var request = new XMLHttpRequest();
      request.open("get", "logout", false, "false", "false");
      request.send();
      window.location.replace('/');
    });

});
