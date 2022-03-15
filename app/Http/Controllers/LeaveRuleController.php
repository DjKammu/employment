<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\LeaveRule;
use App\Models\LeaveType;
use App\Models\Company;
use App\Models\Document;
use Gate;


class LeaveRuleController extends Controller
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
         if(Gate::denies('view')) {
               return abort('401');
         } 
         $companies = Company::all();
         $leaveRules = LeaveRule::query();

         if( request()->filled('lr')){
             $lr = request()->lr;
              $leaveRules->whereHas('company', function($q) use ($lr){
                  $q->where('id', $lr);
              });
         } 
           
         $leaveRules = $leaveRules->paginate((new LeaveRule())->perPage); 
         
         return view('leave_rules.index',compact('leaveRules','companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('add')) {
               return abort('401');
         } 
        
         $leaveTypes = LeaveType::all(); 
         $companies = Company::all();

        return view('leave_rules.create',compact('leaveTypes','companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'name' => 'required',
              'company_id'    => 'required|exists:companies,id',
              'leave_type_id' => [
                'required','exists:leave_types,id', Rule::unique(LeaveRule::class)->where(function ($query) {
                      return $query->where('company_id', request()->company_id)->where('leave_type_id', request()->leave_type_id);
                  })
              ],
              'accrues_every_quarter' => [
                   function ($attribute, $value, $fail) {
                    if ( ( request()->filled($attribute) && request()->accrues_every_quarter == LeaveRule::NO ) === (request()->filled('accrues_every_year') && request()->accrues_every_year == LeaveRule::NO )) {
                        return $fail('Accrues Every Quarter or Acrual Days required only
                         one of them!');
                    }
                }
              ],
              'accrues_every_year'    => 'required_without:accrues_every_quarter',
               'carry_over_year'       => 'required',
              'leaves_accrual_after'  => 'required',
              'max_period'  => function ($attribute, $value, $fail) {
                  if ( (request()->max_period > LeaveRule::NO) && (request()->carry_over_year ==LeaveRule::NO) ) {
                      $message = 'Max Period not required if Carry Over Year!';
                     
                  }elseif( (request()->max_period <= LeaveRule::NO) && (request()->carry_over_year == LeaveRule::YES) ){
                       $message = 'Max Period required if Carry Over Year!';
                  }
                  return (@$message) ? $fail($message) : false;
              }
            ],['leave_type_id.unique' => 'Levae Rule already exists for same company and
              same leave type!',
              'accrues_every_year.required_without' => '']);
            
        LeaveRule::create($data);

        return redirect('leave-rules')->with('message', 'Leave Rule Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          if(Gate::denies('edit')) {
               return abort('401');
          } 

         $leaveTypes = LeaveType::all(); 
         $companies = Company::all(); 

         $rule = LeaveRule::find($id);

         return view('leave_rules.edit',compact('leaveTypes','companies','rule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
              'name' => 'required',
              'company_id'    => 'required|exists:companies,id',
              'leave_type_id' => [
                'required','exists:leave_types,id', Rule::unique(LeaveRule::class)->where(function ($query) use ($id) {
                      return $query->where('id','<>', $id)->where('company_id', request()->company_id)->where('leave_type_id', request()->leave_type_id);
                  })
              ],
              'accrues_every_quarter' => [
                   function ($attribute, $value, $fail) {
                    if ( ( request()->filled($attribute) && request()->accrues_every_quarter == LeaveRule::NO ) === (request()->filled('accrues_every_year') && request()->accrues_every_year == LeaveRule::NO )) {
                        return $fail('Accrues Every Quarter or Acrual Days required only
                         one of them!');
                    }
                }
              ],
              'accrues_every_year'    => 'required_without:accrues_every_quarter',
              'carry_over_year'       => 'required',
              'leaves_accrual_after'  => 'required',
              'max_period'  => function ($attribute, $value, $fail) {
                  if ( (request()->max_period > LeaveRule::NO) && (request()->carry_over_year ==LeaveRule::NO) ) {
                      $message = 'Max Period not required if Carry Over Year!';
                     
                  }elseif( (request()->max_period <= LeaveRule::NO) && (request()->carry_over_year == LeaveRule::YES) ){
                       $message = 'Max Period required if Carry Over Year!';
                  }
                  return (@$message) ? $fail($message) : false;
              }
             ],['leave_type_id.unique' => 'Levae Rule already exists for same company and same leave type!',
              'accrues_every_year.required_without' => '']);


         $rule = LeaveRule::find($id);
        
         if(!$rule){
            return redirect()->back();
         }
          
         $rule->update($data);

        return redirect('leave-rules')->with('message', 'Leave Rule Updated Successfully!');
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

         $leaveRule = LeaveRule::find($id);

         $leaveRule->delete();       

        return redirect()->back()->with('message', 'Leave Rule Delete Successfully!');
    }
}
