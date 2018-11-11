var capcus;

$(document).ready(function() {
    capcus = new Capcus();
    capcus.init();
});

function setupGoogleSignin() {
    capcus.setupGoogleSignin();
}

function Capcus() {
    this.init = function() {
        setupDivHeight();
        setupStickyNavigation();
        $('#account-content').tabs().css({heightStyle: "fill"});
    }

    function setupDivHeight() {
        var navigationHeight = $('#navigation').height();
        var accountHeaderHeight = $('#account-header').height();
        var accountFooterHeight = $('#account-footer').height();
        var height = $(window).innerHeight() - navigationHeight;
        var accountContentHeight = ((height - accountHeaderHeight) - accountFooterHeight) - 40;
        var tabsPanelHeight = accountContentHeight - 75;
    
        $('#main').css('min-height', height + 'px');
        $('#account').css('min-height', height + 'px');
        $('#account-content').css('height', accountContentHeight + 'px');
        $('.tabs-panel').css('height', tabsPanelHeight + 'px');
        $('#plan').css('min-height', height + 'px');
        $('#about').css('min-height', height + 'px');
    }
    
    function setupStickyNavigation() {
        $('#navigation').sticky({ zIndex: 9999 });
    }

    this.setupGoogleSignin = function() {
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: '323546387834-dkchfj8vaufarvao44m5ndvjupnse44l.apps.googleusercontent.com'
            });

            gapi.signin2.render('google-signin', {
                'scope': 'email',
                'width': 96,
                'height': 32,
                'longtitle': false,
                'theme': 'dark',
                'onsuccess': function(user) {
                    $('#account-footer').html('<div id="account-info">' + user.getBasicProfile().getEmail() + '</div>'
                        + '<div id="account-logout"><img src="/res/logout.png" onclick="capcus.signOut()" /></div>');
                    $('#tabs-token').html('<b>Your Token</b>:<br><br>' + user.getAuthResponse().id_token);
                },
                'onfailure': function() { }
            });
        });
    }

    this.jumpTo = function(section) {
        var navHeight = $('#navigation').height();
        if ('#main' === section) {
            navHeight = 0;
        }

        $('html,body').animate({
            scrollTop: $(section).offset().top - navHeight
        });
    }

    this.copyUrl = function() {
        window.getSelection().selectAllChildren( document.getElementById( 'url' ) );
        document.execCommand('copy');
    }

    this.submitUrl = function() {
        $('#output').css('visibility', 'hidden');
        $('#submitUrl').prop('disabled', true);

        var sourceUrl = $('#inputUrl').val().trim();
        var owner = 'anonymous';
        var auth2 = gapi.auth2.getAuthInstance();
        if (auth2.isSignedIn.get()) {
            owner = auth2.currentUser.get().getAuthResponse().id_token;
        }

        if (sourceUrl.length > 0) {
            $.ajax({
                type: "POST",
                url: "/index.php",
                data: JSON.stringify({
                    "id": "1000",
                    "type": "capcus.create",
                    "owner": owner,
                    "url": sourceUrl
                }),
                dataType: "json",
                contentType: "application/json",
                success: function(response) {
                    updateInformation(response);
                },
                error: function(xhr, status, error) {
                }
            });
        }

        $('#output').css('visibility', 'visible');
        $('#inputUrl').val('');
        $('#submitUrl').prop('disabled', false);
        return false;
    }

    function updateInformation(response) {
        $('#url').html(response.target_url);
        $('#info').html('<ul>'
            + '<li>Click the URL above to select and copy</li>'
            + '<li>Created at ' + response.create_time + '</li>'
            + '<li>Expired at ' + response.expire_time + '</li>'
            + '</ul>');
        $('#output').css('visibility', 'visible');
    }

    this.signOut = function() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            $('#account-footer').html('<div id="google-signin"></div>');
            $('#tabs-token').html('You need to sign-in first');
            capcus.setupGoogleSignin();
        });

        return false;
    }
}