<?php

  require 'src/facebook.php';

  // Create our Application instance (replace this with your appId and secret).
  $facebook = new Facebook(array(
    'appId'  => '573857339361800',
    'secret' => '3d7cf4373c560989e06c2c58beba2a4a',
  ));

  // Get User ID
  $user = $facebook->getUser();
  $access_token = $facebook->getAccessToken();

  // We may or may not have this data based on whether the user is logged in.
  //
  // If we have a $user id here, it means we know the user is logged into
  // Facebook, but we don't know if the access token is valid. An access
  // token is invalid if the user logged out of Facebook.

  if ($user) {
    try {
      // Proceed knowing you have a logged in user who's authenticated.
      $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
      error_log($e);
      $user = null;
    }
  }

  // Login or logout url will be needed depending on current user state.
  if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
  } else {
    $statusUrl = $facebook->getLoginStatusUrl();
    $loginUrl = $facebook->getLoginUrl();
  }

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Reccur</title>
    <style>
        .red{
            color: red;
        }
    </style>
  </head>
  <body>
    <h1>Reccur</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Check the login status using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $statusUrl; ?>">Check the login status</a>
      </div>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <?php
        $id = $user_profile['id'];
        $name = $user_profile['name'];

        $friends_data = $facebook->api('me/friends');
        $friends = $friends_data['data'];

        foreach ($friends as $friend) {

            $id   = $friend['id'];
            $name = $friend['name'];

            $mutual_friends_data = $facebook->api('me/mutualfriends/'.$id);
            $mutual_friends      = $mutual_friends_data['data'];
            // $total               = count($mutual_friends);

            $last = key( array_slice( $array, -1, 1, TRUE ) );

            echo "<pre>";
            print_r($last);

            echo "$id - $name<br>";
        }
    ?>
  </body>
</html>
