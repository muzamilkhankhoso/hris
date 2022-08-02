<?php
namespace App\Http\Controllers;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
use App\Models\Employee;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\EmployeeOfTheMonth;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Models\DegreeType;
use App\Models\Department;

use Input;
use Auth;
use DB;
use Config;

use Illuminate\Pagination\LengthAwarePaginator;
class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function hrDashboard()
    {

        return view('Dashboard.hrDashboard');
    }

    public function userDashboard()
    {
        

        return view('Dashboard.userDashboard');
	}

    public function financeDashboard()
    {
        return view('Dashboard.financeDashboard');
    }
}