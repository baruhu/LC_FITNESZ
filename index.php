<?
include 'kinezet.php';
fejlec();
jatek();
kozep();
menu();
lablec();

?>
<?php
ob_start();
require "facebook.php";
$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
$has_liked = $data["page"]["liked"];
 
if($has_liked){
  //az adott látogató rajongó -> kérjük el a megfelelő engedélyeket
  if (!$data["user_id"]) {
    //még nem engedélyezte -> irányítsuk át az engedélyező képernyőre
    $app_id = "0123456789"; //ide kerül a létrehozott alkalmazás ID-ja
    $redirect_url = urlencode("https://www.facebook.com/..."); //ide kerül a tab URL
    $scope = "email";
    $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . $redirect_url . "&scope=" . $scope;
    echo("<script> top.location.href='" . $auth_url . "'</script>");
  } else {
    //már engedélyezte -> írjuk ki az egyedi azonosítóját
    //echo $data["user_id"];
    $facebook = new Facebook(array(
      'appId' => '0123456789', //saját APP ID
      'secret' => 'afghjzthrgef5467zhgvwg5rn6', // saját APP SECRET
    ));
 
    $user = $facebook->getUser();
 
    if ($user) {
      try {
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
      }
    }
 
    echo "<pre>";
    print_r($user_profile);
    echo "</pre>";
 
    echo $user_profile['email'];
  }
} else {
  //az adott látogató nem rajongó -> kérjük meg, hogy előbb legyen az
  ?>
  Üdvözöllek Idegen! Lépj be rajongóink közé.
  <?php
}
?>