<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRule extends Model
{
    use HasFactory;

    protected $perPage = 20;

    CONST RULE_PERIOD     = 10;
    CONST ACCRUAL_AFTER_PERIOD  = 12;
    CONST QUARTER_PERIOD  = 3;
    CONST YEAR_PERIOD  = 12;
    CONST YES       = 1;
    CONST NO        = 0; 
    CONST YES_TEXT  = 'Yes';
    CONST NO_TEXT   = 'No';

    protected $fillable = [
     'name' , 'company_id' ,'leave_type_id', 
     'accrues_every_quarter', 'accrues_every_year',
     'carry_over_year', 'max_period','leaves_accrual_after'
    ];
    

    public function projects(){
    	return $this->hasMany(Project::class);
    }


    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function leave_type(){
        return $this->belongsTo(LeaveType::class);
    }

}
