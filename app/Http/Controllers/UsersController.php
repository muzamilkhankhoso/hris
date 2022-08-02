<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\MainMenuTitle;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Menu;
use App\Helpers\CommonHelper;
use App\Models\ApprovalSystem;
use App\Models\Regions;
use App\Models\EmployeeCategory;
use App\Models\EmployeeProjects;
use App\Models\Role;
use App\Models\MaritalStatus;
use App\Models\EmployeeDocuments;
use Input;
use Auth;
use DB;
use Config;

class UsersController extends Controller
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
    
    public function index()
    {
        return view('dUser.home');
    }
   
   	public function toDayActivity()
    {
		return view('Users.toDayActivity');
   	}
	
	public function createUsersForm()
    {
		return view('Users.createUsersForm');
	}
	
	public function createMainMenuTitleForm()
    {
		return view('Users.MainMenuTitles.createMainMenuTitleForm');
	}
	
	public function createSubMenuForm()
    {
		$MainMenuTitles = new MainMenuTitle;
		$MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
		return view('Users.SubMenu.createSubMenuForm',compact('MainMenuTitles'));
	}
    public function editSubMenuDetailForm(){

        $MainMenuTitles = new MainMenuTitle;
        $MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
        $subMenu=menu::where([['id','=', Input::get('id')]])->first();
        return view('Users.SubMenu.editSubMenuDetailForm',compact('subMenu','MainMenuTitles'));
    }

    public function createRoleForm(){

        return view('Users.Roles.createRoleForm');
    }

    public function viewRoleList()
    {

        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();

        return view('Users.Roles.viewRoleList',compact('MenuPrivileges'));
    }

    public function viewEmployeePrivileges($id)
    {


        $MenuPrivileges = Role::where([['id','=',$id]])->get()->toArray();

        return view('Users.Roles.viewEmployeePrivileges',['MenuPrivileges'=>$MenuPrivileges,'company_id'=>$_GET['m']]);
    }


    public function editUserProfile()
    {
        return view('Users.editUserProfile');
    }
    public function editMyProfile()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee = Employee::select('*')->where('emp_id', '=', Auth::user()->emp_id)->get();
        $employee_cnic_copy = Employee::where([['emp_id','=',Auth::user()->emp_id],['status','=',1],['cnic_path', '!=', null]]);
        $employee_documents = EmployeeDocuments::where([['emp_id', '=', Auth::user()->emp_id], ['status','=', 1]]);
        CommonHelper::reconnectMasterDatabase();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();


        return view('Users.editMyProfile',compact('employee','marital_status','employee_cnic_copy','employee_documents'));
    }

}
