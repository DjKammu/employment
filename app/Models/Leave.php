<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $perPage = 20;


    protected $fillable = [
     'company_id' ,'leave_type_id', 
     'employee_id', 'start_date',
     'end_date','image'
    ];

    protected $dates = [
      'start_date', 'end_date'
    ];
    

    public function employee(){
        return $this->belongsTo(Employee::class);
    } 

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function leave_type(){
        return $this->belongsTo(LeaveType::class);
    }

}
