<?php

namespace App\Http\Controllers;

use App\Models\Subcontractor;
use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\User;
use App\Models\Role;
use Auth;
use App\Http\Controllers\FileController;

class SignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

}
