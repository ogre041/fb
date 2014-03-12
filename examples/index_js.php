<?php

$sApplicationId = '573857339361800';
$sApplicationSecret = '3d7cf4373c560989e06c2c58beba2a4a';
$iLimit = 99;

?>
<!DOCTYPE html>
<html lang="en" xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta charset="utf-8" />
        <title>Facebook API</title>
        <link href='//fonts.googleapis.com/css?family=Convergence|Satisfy' rel='stylesheet' type='text/css'/>
        <style type="text/css">

            @font-face{
              font-family:"SignikaNegative";
              src:url(../fonts/SignikaNegative.ttf) format("truetype");
            }

            body{
                font-family: Convergence, Arial !important;
                padding: 5px;
            }

            .btn {
              display: inline-block;
              padding: 6px 12px;
              margin-bottom: 0;
              font-size: 14px;
              font-weight: normal;
              line-height: 1.428571429;
              text-align: center;
              white-space: nowrap;
              vertical-align: middle;
              cursor: pointer;
              border: 1px solid transparent;
              border-radius: 4px;
              -webkit-user-select: none;
                 -moz-user-select: none;
                  -ms-user-select: none;
                   -o-user-select: none;
                      user-select: none;
            }

            .btn:focus {
              outline: thin dotted #333;
              outline: 5px auto -webkit-focus-ring-color;
              outline-offset: -2px;
            }

            .btn:hover,
            .btn:focus {
              color: #333333;
              text-decoration: none;
            }

            .btn:active,
            .btn.active {
              background-image: none;
              outline: 0;
              -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
                      box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            }

            .btn-primary {
              color: #ffffff;
              background-color: #428bca;
              border-color: #357ebd;
            }

            .btn-primary:hover,
            .btn-primary:focus,
            .btn-primary:active,
            .btn-primary.active,
            .open .dropdown-toggle.btn-primary {
              color: #ffffff;
              background-color: #3276b1;
              border-color: #285e8e;
            }

            .btn-success {
              color: #ffffff;
              background-color: #5cb85c;
              border-color: #4cae4c;
            }

            .btn-success:hover,
            .btn-success:focus,
            .btn-success:active,
            .btn-success.active,
            .open .dropdown-toggle.btn-success {
              color: #ffffff;
              background-color: #47a447;
              border-color: #398439;
            }
        </style>
    </head>
    <body>
        <header>
            <center>
                <h2>Facebook API - Get friends list</h2>
            </center>            
        </header>

        <center>
            <h1>Authorization step:</h1>
            <div id="user-info"></div>
            <button id="fb-auth" class="btn btn-primary">Login</button>
            <textarea id="feed_text"></textarea>
            <button id="feed" class="btn btn-primary">Login</button>
        </center>

        <div id="result_friends"></div>

        <div id="test"></div>
        <div id="fb-root"></div>

        <script>
            function sortMethod(a, b) {
                var x = a.name.toLowerCase();
                var y = b.name.toLowerCase();
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            }

            window.fbAsyncInit = function() {
                FB.init({ appId: '<?= $sApplicationId ?>',
                    status: true,
                    cookie: true,
                    xfbml: true,
                    oauth: true
                });

                function updateButton(response) {
                    var button = document.getElementById('fb-auth');

                    if (response.authResponse) { // in case if we are logged in
                        var userInfo = document.getElementById('user-info');
                        FB.api('/me', function(response) {
                            userInfo.innerHTML = '<img src="https://graph.facebook.com/' + response.id + '/picture">' + response.name;
                            button.innerHTML = 'Logout';
                        });

                        // get friends
                        FB.api('/me/friends?limit=<?= $iLimit ?>', function(response) {
                            var result_holder = document.getElementById('result_friends');
                            var friend_data = response.data.sort(sortMethod);

                            var results = '';
                            for (var i = 0; i < friend_data.length; i++) {
                                results += '<div><img src="https://graph.facebook.com/' + friend_data[i].id + '/picture">' + friend_data[i].name + '</div>';
                            }

                            // and display them at our holder element
                            result_holder.innerHTML = '<h2>Result list of your friends:</h2>' + results;
                        });

                        // test
                        FB.api('/me/friends?limit=5', function(response) {
                            var test = document.getElementById('test');
                            var friend_data = response.data.sort(sortMethod);

                            console.log(friend_data);
                        });

                        button.onclick = function() {
                            FB.logout(function(response) {
                                window.location.reload();
                            });
                        };

                        
                    } else { // otherwise - dispay login button
                        button.onclick = function() {
                            FB.login(function(response) {
                                if (response.authResponse) {
                                    window.location.reload();
                                }
                            }, {scope:'email'});
                        }
                    }
                }

                // run once with current status and whenever the status changes
                FB.getLoginStatus(updateButton);
                FB.Event.subscribe('auth.statusChange', updateButton);
            };

            (function() {
                var e = document.createElement('script'); e.async = true;
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                document.getElementById('fb-root').appendChild(e);
            }());
        </script>

</body>
</html>