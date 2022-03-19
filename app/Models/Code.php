<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
     'company_id', 'code',
     'company_nick_name',
     'form_link'
    ];


    public function company(){

        return $this->belongsTo(Company::class);
    }

}
