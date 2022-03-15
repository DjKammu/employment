<?php
namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\HolidayList;
use App\Models\LeaveRule;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\Company;
use App\Models\Document;
use App\Models\Leave;
use Gate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveController extends Controller
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

         $leaves = Leave::query();

         $leaves = $leaves->paginate((new Leave())->perPage); 

         $leaves->filter(function($leave){
            return $leave->count = $this->getHolidayCount($leave);
         });
         
         return view('leaves.index',compact('leaves'));
    }

    public function getHolidayCount($leave){ 
         $company_id  = $leave->company_id;

         $holidayList = HolidayList::where(['company_id' => $company_id])
                                ->where(function($query) use ($leave){
                                  $query->where('holiday_date', '>=', $leave->start_date)
                                      ->orWhere('holiday_date', '<=', $leave->end_date);
                               })->pluck('holiday_date');

         $holidayList =  $holidayList->map(function($list){
           return $list->format('Y-m-d');
         });                   

         $weekends = Holiday::where(['company_id' => $company_id])
                                ->where('year', $leave->start_date->year)->first();
         $isSaturdayOff = @$weekends->saturday_off;                     
         $isSundayOff =   @$weekends->sunday_off;                    

        $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
         
        $dates = []; 
        foreach ($period as $key => $date) {
           $dates[] =  $date->format('Y-m-d');
        }

        $leaveCount = $period->count();
        foreach ($dates as $key => $date) {
            if( (in_array($date, @$holidayList->toArray()))
                || ($isSaturdayOff && (Carbon::parse($date)->isSaturday()))
                 || ($isSundayOff && (Carbon::parse($date)->isSunday())) ){
                 unset($dates[$key]);  
            }
        }
          
        $leaveCount = count($dates);  
        return $leaveCount;                    

    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(Gate::denies('add')) {
               return abort('401');
         } 

         $companiesQry = Company::query(); 
         $companies = $companiesQry->get();

         $company = ($request->filled('c')) ? $companiesQry->where('id',$request->c)->first() 
                      : $companies->first();     


         $employees = @$company->employees()->get();    

         $leaveTypes = LeaveType::all(); 

        return view('leaves.create',compact('company','leaveTypes','companies','employees'));
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
              'company_id' => 'required|exists:companies,id',
              'employee_id' => 'required|exists:employees,id',
              'leave_type_id' => 'required|exists:leave_types,id',
              'start_date' => 
               function ($attribute, $value, $fail) {
                        $exists =  LeaveRule::where('company_id', request()->company_id)->where('leave_type_id', request()->leave_type_id)->exists();

                        return (@$exists == false) ? $fail('Leave Rule not exists for company and leave type!') : false;
              },
              'end_date'=> 'required|after_or_equal:start_date' 
        ]);

         $data['image'] = '';    

        if($request->hasFile('image')){
               $image = $request->file('image');
               $photoName = 'leave-'.time() . '.' . $image->getClientOriginalExtension();
              
               $data['image']  = $request->file('image')->storeAs(Document::LEAVES, $photoName, 
                'public');
        }

   
        Leave::create($data);

        return redirect('leaves')->with('message', 'Leave Created Successfully!');
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

         $leaveTypes = LeaveType::all(); 
         $companiesQry = Company::query(); 
         $companies = $companiesQry->get(); 

         $leave = Leave::find($id);

         $company = ($request->filled('c')) ? $companiesQry->where('id',$request->c)->first() 
                      : $leave->company()->first();

         $employees = (@$company->employees()->exists() ) ? @$company->employees()->get() : [];         

         return view('leaves.edit',compact('company','leaveTypes','companies','employees','leave'));
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
              'company_id' => 'required|exists:companies,id',
              'employee_id' => 'required|exists:employees,id',
              'leave_type_id' => 'required|exists:leave_types,id',
              'start_date' => 
               function ($attribute, $value, $fail) {
                        $exists =  LeaveRule::where('company_id', request()->company_id)->where('leave_type_id', request()->leave_type_id)->exists();

                        return (@$exists == false) ? $fail('Leave Rule not exists for company and leave type!') : false;
              },
              'end_date'=> 'required|after_or_equal:start_date' 
        ]);

         $leave = Leave::find($id);
        
         if(!$leave){
            return redirect()->back();
         }
          
        $data['image'] = $leave->image;    

        if($request->hasFile('image')){
               @unlink('storage/'.$leave->image);
               $image = $request->file('image');
               $photoName ='leave-'.time() . '.' . $image->getClientOriginalExtension();
              
               $data['image']  = $request->file('image')->storeAs(Document::LEAVES,
                $photoName, 'public');
        }
        
         
         $leave->update($data);

        return redirect('leaves')->with('message', 'Leave Updated Successfully!');
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

         $leave = Leave::find($id);

         $leave->delete();       

        return redirect()->back()->with('message', 'Leave Delete Successfully!');
    }


    public function getCmployeeLeaves(Request $request){
        
        if(Gate::denies('view')) {
               return abort('401');
        }

         $companiesQry = Company::query(); 
         $companies = $companiesQry->get();
         
         $startDate = ($request->filled('year')) ? Carbon::parse($request->year.'-1-1')->startOfYear()->format('Y-m-d')  : Carbon::now()->startOfYear()->format('Y-m-d');
         $endDate =  ($request->filled('year')) ? Carbon::parse($request->year.'-1-1')->endOfYear()->format('Y-m-d')  : Carbon::now()->endOfYear()->format('Y-m-d');

         $company = ($request->filled('c')) ? $companiesQry->where('id',$request->c)->first() 
                      : $companies->first();              

         $employees = @$company->employees()->paginate((new Leave())->perPage); 

         $all_leave_types = LeaveType::all();   

         $employees->filter(function($employee) use ($startDate, $endDate){

             $employee->leave_types = $this->getLeaveSummary($employee, $startDate, $endDate);
      
              return $employee;
          
        });
       

        return view('leaves.employees-leaves',compact('company','companies','employees','all_leave_types'));

    }


    function getLeaveSummary( $employee, $startDate, $endDate){

       $leave_types = LeaveType::all();         
       $addYears =  (request()->filled('year')) ? request()->year -  Carbon::now()->year : 0 ;  
       $currentDate = Carbon::now()->addYears($addYears);  

       // $currentDate = '2023-06-01';  
       $joiningDate = $employee->pivot->date_of_joining;
       $terminationDate = $employee->pivot->termination_date;
       $joiningYear = Carbon::parse($joiningDate)->format('Y');
       $maxPeriod = $accrualAfter = 0;  
       // dd($joiningDate);
       
       $leavesArr = [];

       if (Carbon::parse($joiningDate)->gt(Carbon::parse($currentDate)) || 
        ($terminationDate && Carbon::parse($currentDate)->gt(Carbon::parse($terminationDate)))){
        
          foreach (@$leave_types as $key => $type) {
               $leavesArr[$type->id]['count'] = 0;
               $leavesArr[$type->id]['left'] = 0;
          }
          return $leavesArr;
       }

       foreach (@$leave_types as $key => $type) {

            $leaveRule = LeaveRule::where(['leave_type_id' => $type->id,
                          'company_id' => $employee->pivot->company_id])->first();  


            $noOfDays = (@$leaveRule->accrues_every_quarter  > 0) ? @$leaveRule->accrues_every_quarter : @$leaveRule->accrues_every_year;

            $carry_over_year = @$leaveRule->carry_over_year;
            $maxPeriod      = @$leaveRule->max_period;
            $accrualAfter   = @$leaveRule->leaves_accrual_after;
            $diffInMonths   = Carbon::parse($joiningDate)->diffInMonths($currentDate); 

            $totalDays = $remainingMonths = 0;


            if($carry_over_year == LeaveRule::YES){

                $carryOverPeriod = LeaveRule::YEAR_PERIOD*$maxPeriod;
            
                // $diffInMonths = ($diffInMonths > $carryOverPeriod) ? ($diffInMonths - $carryOverPeriod) : $diffInMonths;

                   // var_dump($currentDate->format('Y-m-d'));
                    // var_dump($diffInMonths);

                if(@$leaveRule->accrues_every_quarter) {
                   $remainingMonths = $diffInMonths  - $accrualAfter ;
                   if(($diffInMonths > LeaveRule::QUARTER_PERIOD) && ($diffInMonths > $accrualAfter)){
                        $totalDays = intdiv($remainingMonths,LeaveRule::QUARTER_PERIOD);
                   }

                }else{

                   if($diffInMonths > $accrualAfter){

                         $multiDays = intdiv($diffInMonths,LeaveRule::YEAR_PERIOD);
                         $multiDays = ($diffInMonths >= $carryOverPeriod) ? $maxPeriod : $multiDays; 
                         $totalDays = @$leaveRule->accrues_every_year * $multiDays;
                   }
                }
            }else{
                $diffInMonths = $diffInMonths%12;  
                if(@$leaveRule->accrues_every_quarter) {
                   $remainingMonths = $diffInMonths  - $accrualAfter ;
                   if(($diffInMonths > LeaveRule::QUARTER_PERIOD) && ($diffInMonths > $accrualAfter)){
                        $totalDays = intdiv($remainingMonths,LeaveRule::QUARTER_PERIOD);
                   }

                }else{

                   if($diffInMonths > $accrualAfter){
                        $totalDays = @$leaveRule->accrues_every_year;
                   }
                }
            }

            $employee_leaves = @$employee->leaves()
                               ->where('start_date', '>=', $startDate)
                               ->where('end_date', '<=', $endDate)
                               ->where(['leave_type_id' => $type->id,
                          'company_id' => $employee->pivot->company_id])->get();
            $leaveCount = 0;
            
            foreach (@$employee_leaves as $key => $leave) {
              $leaveCount += $this->getHolidayCount($leave);
            }               

            $leavesArr[$type->id]['count'] = @$leaveCount;
            $leavesArr[$type->id]['left'] = $totalDays - $leaveCount;
              
       }

       // dd($leavesArr);

       return $leavesArr;
    }
}
