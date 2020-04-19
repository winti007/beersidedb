<?php

include "php/eloszor.php";




include(Face_incl);

$fb = new Facebook\Facebook([
  'app_id' => Face_appid, // Replace {app-id} with your app id
  'app_secret' => Face_secret,
  'default_graph_version' => Face_version,
  ]);
  

    if (isset($_SESSION['fb_access_token']))
    {
        Face_beleptet($fb,$_SESSION['fb_access_token']);
 
    }  
   


    $helper = $fb->getRedirectLoginHelper();


try {
  $accessToken = $helper->getAccessToken();

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}


if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}



// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(Face_appid); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

}


$_SESSION['fb_access_token'] = (string) $accessToken;

Face_beleptet($fb,$_SESSION['fb_access_token']);

function Face_beleptet($fb,$fb_access_token)
{
        
        try 
        {
   
          $response = $fb->get('/me?fields=email,id,name,first_name,last_name&scope=email', $fb_access_token);
//  $response = $fb->get('/363917344084601', $_SESSION['fb_access_token']);
  
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) 
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }
        
        $user = $response->getGraphUser();
        $Data["name"]=$user["name"];
        $Data["email"]=$user["email"];
        $Data["id"]=$user["id"];
        $Data["first_name"]=$user["first_name"];
        $Data["last_name"]=$user["last_name"];
        
        $Fonal=new CVaz_bovit();

        $Oldalir=$Fonal->Futtat(Focsop_azon)->FaceInit($Data);
        echo $Oldalir;
        
        exit;  
  
    
}


// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>