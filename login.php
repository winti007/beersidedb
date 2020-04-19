<?php
session_start();
include("faceb/autoload.php");
$fb = new Facebook\Facebook([
  'app_id' => '913947108775443', // Replace {app-id} with your app id
  'app_secret' => 'b48710aea460e153123b73b30ea540c3',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://www.beerside.hu/callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>