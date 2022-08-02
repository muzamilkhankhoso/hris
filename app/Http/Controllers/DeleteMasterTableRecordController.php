<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use DB;
use Auth;
use Config;
use Redirect;
use Session;
use Input;
use File;
use Hash;

class DeleteMasterTableRecordController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMasterTableReceord()
    {
		
		$id = Input::get('id');
        $tableName = Input::get('tableName');
        DB::update('update '.$tableName.' set status = ? where id = ?',['2',$id]);

        Session::flash('dataDelete','Successfully Deleted.');
    }
    public function deleteUserAccountDetail()
    {
        $id = Input::get('id');
        $tableName = Input::get('tableName');
        DB::delete('delete from '.$tableName.' where status = ? and id = ?',['1',$id]);

        Session::flash('dataDelete','Successfully Deleted.');
    }

   
}
