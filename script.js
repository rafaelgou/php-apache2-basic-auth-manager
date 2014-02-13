$(function() {

    $('.userDelete').click(function(event) {
        event.preventDefault();
        href = $(this).attr('href');
        bootbox.confirm("<h4>Are you sure you want to delete user '" + $(this).data('username') + "'?</h4>", function(result) {
            
            console.log(result);
            console.log(href);
            if(result) {
                window.location = href;
            }
        });
    });

    $('.groupDelete').click(function(event) {
        event.preventDefault();
        href = $(this).attr('href');
        bootbox.confirm("<h4>Are you sure you want to delete group '" + $(this).data('groupname') + "'?</h4>", function(result) {
            
            console.log(result);
            console.log(href);
            if(result) {
                window.location = href;
            }
        });
    });

});

    
