<?php

namespace App\Http\Controllers;

use Httpful\Request as HttpfulRequest;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use App\Models\Code;
use Httpful;
use Auth;


class SignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $error = '';

    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    

    public function getLinks(Request $request){

          $request->validate([
            'code' => 'required|exists:codes,code'
          ]);

          $codes = Code::whereCode($request->code)->paginate((new Code)->perPage);
   
         return view('frontend.code-links',compact('codes'));

    }

    public function getTemplate(Request $request, $id){
        
         return view('frontend.template',compact('id'));

    }

    public function signDocument(Request $request){

        $name = $request->name;
        $email = $request->email;
        $templateId = $request->template;
    
        $accessToken = $this->getAccessToken();
        $documentId = ( $accessToken ) ? $this->sendDocument($name, $email, $accessToken, $templateId) :'';

        $signlink = ($documentId) ? $this->getSignLink($documentId, $email, $accessToken) : '';

         if(!$accessToken || !$documentId || !$signlink){
           return redirect()->back()->withErrors(['msg' => $this->error ]);
        }

         return view('frontend.signdocument',compact('signlink'));

    }
    
    public function getAccessToken()
    {
    
        $authResponse = HttpfulRequest::post('https://account.boldsign.com/connect/token')
            ->expectsJson()
            ->body(
                array(
                    "grant_type" => 'client_credentials',
                    "client_id" =>   env("CLIENT_ID"),
                    "client_secret" => env("CLIENT_SECRET"),
                ),
                Httpful\Mime::FORM,
            )
            ->send();
        
        if ($authResponse->hasErrors()) {
            $this->error = json_decode($authResponse->raw_body)->error;
            return;
         }
            
        return json_decode($authResponse->raw_body)->access_token;
    }

    function sendDocument($name, $email, $token, $templateId)
    {
        
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

        $sendResponse = HttpfulRequest::post($sendTemplateUrl)
            ->sendsJson()
            ->addHeader('Authorization', "Bearer $token")
            ->body($sendTemplate)
            ->expectsjson()
            ->send();

       if ($sendResponse->hasErrors()) {
            $errors = json_decode($sendResponse->raw_body,true)['errors'];
            foreach ($errors as $key => $err) {
                $this->error .= implode(',',$err);
            }
            return;
        }


        return $sendResponse->body->documentId;
    }

    function getSignLink($documentId, $email, $token)
    {

        $app_url = env('APP_URL');

        $queryString = http_build_query([
            'documentId' => $documentId,
            'signerEmail' => $email,
            'RedirectUrl' => $app_url."/redirect"
        ]);

        $signLinkresponse = HttpfulRequest::get("https://api.boldsign.com/v1/document/getEmbeddedSignLink?" . $queryString)
            ->addHeader('Authorization', "Bearer $token")
            ->expectsjson()
            ->send();

       if ($signLinkresponse->hasErrors()) {
            $errors = json_decode($signLinkresponse->raw_body,true)['errors'];
            foreach ($errors as $key => $err) {
                $this->error .= implode(',',$err);
            }
            return;
        }

        return $signLinkresponse->body->signLink;
    }

}
