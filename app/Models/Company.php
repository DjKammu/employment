<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $perPage = 9;

    protected $fillable = [
     'name' ,'address_1' , 'address_2' ,
      'city','state' , 'country',
      'zip_code','notes' ,'photo',
      'nick_name'
    ];

    public function codes(){
    	return $this->hasMany(Code::class);
    }

}
