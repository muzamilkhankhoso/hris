<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePromotion extends Model{
    protected $table = 'employee_promotion';
    protected $fillable = ['sub_department_id','emp_id','designation_id','grade_id', 'increment', 'salary','approval_status','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
