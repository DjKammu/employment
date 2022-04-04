<?php

namespace App\Http\Controllers;

use App\Models\Subcontractor;
use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\User;
use App\Models\Role;
use Auth;
use App\Http\Controllers\FileController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('getLinks');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $projectTypes = ProjectType::count();
        // $properties = Property::count();
        $users = User::count();
        $roles = Role::count();
        // $documentTypes = DocumentType::count();
        // $vendors = Vendor::count();
        // $trades = Trade::count();
        // $subcontractors = Subcontractor::count();
        // $categories = Category::count();

        // $files = \Storage::disk(FileController::DOC_UPLOAD)
                 // ->allFiles(FileController::PROPERTY);

        // $files = @count($files);

        return view('home',compact('users','roles'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();

        return view('profile',compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

        $request->validate([
              'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        
         if($request->hasFile('profile_picture')){
               $profile_picture = $request->file('profile_picture');
               $photoName = $user->name.'-'.time() . '.' . $profile_picture->getClientOriginalExtension();
              
               $avatar  = $request->file('profile_picture')->storeAs('users', $photoName, 'public');

               $user->avatar = $avatar;
        }

        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->save();

        return redirect()->back()->with('message', 'Profile Updated Successfully!');

    }

     public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
             'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }],
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
    
        $user->password = \Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('message', 'Password Updated Successfully!');

    }


    public function setup(){

        return view('setup');
    }

    public function getLinks(Request $request){

          $request->validate([
            'code' => 'required|exists:codes,code'
          ]);

          $codes = Code::whereCode($request->code)->paginate((new Code)->perPage);
   
         return view('frontend.code-links',compact('codes'));

    }

}
