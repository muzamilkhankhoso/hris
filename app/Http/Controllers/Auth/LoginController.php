<?php

namespace App\Http\Controllers\Auth;
use App\Models\MenuPrivileges;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\HrHelper;
use Auth;
use Illuminate\Support\Facades\Input;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected function authenticated($request, $user){
		session ( [
            'accountYear' => $request->get ( 'account_year' )
        ] );
        if($user->acc_type === 'master') {
            return redirect()->intended('/dMaster');
        }else if($user->acc_type === 'client') {
            return redirect()->intended('/dc/hrDashboard?m=12#Innovative');
        }else if($user->acc_type === 'company') {
            return redirect()->intended('/dCompany');
        }else if($user->acc_type === 'user') {
            $emp_status=HrHelper::getCompanyTableValueByIdAndColumn(Auth::user()->company_id,'employee','status',Auth::user()->emp_id,'emp_id');
            if($emp_status == '1'){
                if($user->password_status == '1'):
                    return redirect()->intended('/users/editUserProfile');
                endif;
                $user_rights = MenuPrivileges::where([['emp_id','=',Auth::user()->emp_id]]);

                $crud_permission='';
                if($user_rights->count() > 0):
                    $main_modules = explode(",",$user_rights->value('main_modules'));
                    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
                    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
                    $companyList= $user_rights->value('company_list');
                endif;


                if(empty($crud_rights)):
                    return "You have Insufficient Privileges, Please Contact Administrator.";
                endif;
                if(in_array('User_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/userDashboard?m='.Auth::user()->company_id.'#Innovative');
                elseif(in_array('HR_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/hrDashboard?m='.Auth::user()->company_id.'#Innovative');
                elseif(in_array('Finance_Dashboard',$crud_rights) !== false):
                    return redirect()->intended('/dc/financeDashboard?m='.Auth::user()->company_id.'#Innovative');
                else:

                    //return redirect()->intended('/d');
                endif;
            }
            else{
                Auth::logout();
            }


        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
