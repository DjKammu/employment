<?php
namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Company;
use App\Models\Code;
use Gate;
use Carbon\Carbon;

class CodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        if(Gate::denies('add')) {
               return abort('401');
         } 

        return view('companies.includes.codes-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
          if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'code'               => 'required',
              // 'company_nick_name'  => 'required',
              'form_link'          => 'required' 
        ]);

        $data['company_id'] = $id;

        Code::create($data);

        return redirect(route('companies.show',['company' => $id]).'#codes')->with('message', 'Code Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
          if(Gate::denies('edit')) {
               return abort('401');
          } 

         $code = Code::find($id);         

         return view('companies.includes.codes-edit',compact('code'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeMultiple(Request $request, $id)
    {
         if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'company_id'   => 'required|exists:companies,id'
        ]);

        $codes = Code::whereCompanyId($data['company_id'])->get();
       
       if($codes->count() == 0){
             return redirect(route('companies.show',['company' => $id]).'#codes')->withErrors('Not exists any Code for this company');
       }
       

       if($codes->count() > 0){
          foreach ($codes as $key => $code) {
            $code = $code->toArray();
            $code =  Arr::except($code, ['company_id','id']);
          
             Code::updateOrCreate(
                ['company_id' => $id, 'code' => $code['code']],
                $code
            );
          }
          
       }
       
        return redirect(route('companies.show',['company' => $id]).'#codes')->with('message', 'Codes Added Successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::denies('update')) {
               return abort('401');
        } 

        $data = $request->except('_token');
        
       $request->validate([
              'code'               => 'required',
              // 'company_nick_name'  => 'required',
              'form_link'          => 'required' 
        ]);


         $code = Code::find($id);
         
         if(!$code){
            return redirect()->back();
         }
          
         $code->update($data);

        return redirect(route('companies.show',['company' => $code->company_id ]).'#codes')->with('message', 'Holiday Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if(Gate::denies('delete')) {
               return abort('401');
          } 

         $code = Code::find($id);

         $code->delete();       

        return redirect()->back()->with('message', 'Code Delete Successfully!');
    }


}
