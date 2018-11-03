$(document).ready(function() {
    setupDivHeight();
    setupStickyNavigation();
});

function setupDivHeight() {
    var navigationHeight = $('#navigation').height();
    var height = $(window).innerHeight() - navigationHeight;

    $('#main').css('min-height', height + "px");
    $('#signin').css('min-height', height + "px");
    $('#plan').css('min-height', height + "px");
    $('#about').css('min-height', height + "px");
}

function setupStickyNavigation() {
    $('#navigation').sticky({ zIndex: 9999 });
}

function jumpTo(section) {
    var navHeight = $('#navigation').height();
    if ('#main' === section) {
        navHeight = 0;
    }

    $('html,body').animate({
        scrollTop: $(section).offset().top - navHeight
    });
}

function copyUrl() {
    window.getSelection().selectAllChildren( document.getElementById( 'url' ) );
    document.execCommand('copy');
}

function submitUrl() {
    $('#output').css('visibility', 'hidden');
    $('#submitUrl').prop('disabled', true);

    var sourceUrl = $('#inputUrl').val().trim();

    if (sourceUrl.length > 0) {
        $.ajax({
            type: "POST",
            url: "/index.php",
            data: JSON.stringify({
                "id": "1000",
                "type": "capcus.create",
                "url": sourceUrl
            }),
            dataType: "json",
            contentType: "application/json",
            success: function(response) {
                updateInformation(response);
            },
            error: function(xhr, status, error) {
                alert("error: " + status);
            }
        });
    }

    $('#output').css('visibility', 'visible');
    $('#inputUrl').val('');
    $('#submitUrl').prop('disabled', false);
    return false;
}

function updateInformation(response) {
    $('#url').html(response.targetUrl);
    $('#info').html('<ul><li>Click the URL above to select and copy</li><li>Created at ' + response.createTime + '</li><li>URL valid for 14 days</li></ul>');
    $('#output').css('visibility', 'visible');
}