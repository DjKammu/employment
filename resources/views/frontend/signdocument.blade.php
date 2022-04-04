<?php

use \Httpful\Request;

// Form data from the user
$name = $_POST['name'];
$email = $_POST['email'];
$templateId = $_POST['template'];

$accessToken = getAccessToken();
$documentId = sendDocument($name, $email, $accessToken, $templateId);
$signlink = getSignLink($documentId, $email, $accessToken);

?>

<h2>Sign document</h2>

 <iframe src="<?php echo $signlink ?>" frameborder="0" height="900" width="1700"></iframe>

<script>
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

    // Listen to message from child window
    eventer(messageEvent, function(e) {
        console.log('parent received message!:  ', e.data);

        if (e.data === "signcompleted") {
            window.location.href = "/signcompleted"
        }
    }, false);
</script>

<?php
function getAccessToken()
{
    if (env("CLIENT_ID") == "" || env("CLIENT_SECRET") == "") {
        throw new Exception("Client ID or secret should not be empty, please update them in the .env");
    }

    $authResponse = Request::post('https://account.boldsign.com/connect/token')
        ->expectsJson()
        ->body(
            array(
                "grant_type" => 'client_credentials',
                "client_id" => env("CLIENT_ID"),
                "client_secret" => env("CLIENT_SECRET"),
            ),
            Httpful\Mime::FORM,
        )
        ->send();

    return json_decode($authResponse->raw_body)->access_token;
}

function sendDocument($name, $email, $token, $templateId)
{
    // TODO: update your template ID
    
    // $templateId = "5de89017-5946-4cef-975b-29fc6cf6252e";

    if ($templateId == "") {
        throw new Exception("Template ID cannot be empty");
    }

    $sendTemplateUrl = "https://api.boldsign.com/v1/template/send?templateId=" . $templateId;

    $sendTemplate = [
        "roles" =>
        array(
            [
                "roleIndex" => 1,
                "signerOrder" => 1,
                "signerName" => $name,
                "signerEmail" => $email,
            ]
        )
    ];

    $sendResponse = Request::post($sendTemplateUrl)
        ->sendsJson()
        ->addHeader('Authorization', "Bearer $token")
        ->body($sendTemplate)
        ->expectsjson()
        ->send();


        // dd($sendResponse);

    return $sendResponse->body->documentId;
}

function getSignLink($documentId, $email, $token)
{

    $app_url = env('APP_URL');

    $queryString = http_build_query([
        'documentId' => $documentId,
        'signerEmail' => $email,

        // TODO: you can provide redirect URL of your choice
        'RedirectUrl' => $app_url."/redirect"
    ]);

    $signLinkresponse = Request::get("https://api.boldsign.com/v1/document/getEmbeddedSignLink?" . $queryString)
        ->addHeader('Authorization', "Bearer $token")
        ->expectsjson()
        ->send();

    return $signLinkresponse->body->signLink;
}
?>
