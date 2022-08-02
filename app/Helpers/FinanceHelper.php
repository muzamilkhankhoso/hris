<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
class FinanceHelper{
    public static function homePageURL(){
        return url('/');
    }

    public static function test(){
        echo "hello";
    }
	
	public static function getRvsDetailListPendingForForm($param1,$param2,$param3){
		static::companyDatabaseConnection($param1);
        $getRvsDetailListPending = DB::table('rvs')
			->select('rvs.id','rvs.rv_no','rvs.rv_date','rvs.slip_no','rvs.rv_status','rvs.payment_status','rvs.paid_amount',
			DB::raw("(SELECT SUM(rv_data.amount) FROM rv_data
			WHERE rv_data.rv_no = rvs.rv_no and rv_data.debit_credit = 1
			GROUP BY rv_data.rv_no) as totalRvsDataAmount"))
			->where('status','=','1')
			->where('rv_status','=','2')
			->where('payment_status','=','1')
			->where('region_id','=',$param2)
			->get();
        static::reconnectMasterDatabase();
		echo '<option value="">Select Rvs Detail</option>';
		foreach($getRvsDetailListPending as $row){
			echo '<option value="'.$row->id.'<*>'.$row->paid_amount.'<*>'.$row->totalRvsDataAmount.'">'.$row->rv_no.' <*> '.CommonHelper::changeDateFormat($row->rv_date).' <*> '.$row->slip_no.'</option>';
		}
		if(!empty($param3)){
			static::companyDatabaseConnection($param1);
			$getRvsDetailListPendingTwo = DB::table('rvs')
				->select('rvs.id','rvs.rv_no','rvs.rv_date','rvs.slip_no','rvs.rv_status','rvs.payment_status','rvs.paid_amount',
				DB::raw("(SELECT SUM(rv_data.amount) FROM rv_data
				WHERE rv_data.rv_no = rvs.rv_no and rv_data.debit_credit = 1
				GROUP BY rv_data.rv_no) as totalRvsDataAmount"))
				->where('status','=','1')
				->where('rv_status','=','2')
				->where('id','=',$param3)
				->where('region_id','=',$param2)
				->get();
			static::reconnectMasterDatabase();
			foreach($getRvsDetailListPendingTwo as $row1){
				echo '<option value="'.$row1->id.'<*>'.$row1->paid_amount.'<*>'.$row1->totalRvsDataAmount.'" selected>'.$row1->rv_no.' <*> '.CommonHelper::changeDateFormat($row1->rv_date).' <*> '.$row1->slip_no.'</option>';
			}
			
			?>
				<script>
					$("select").select2();
					explodeRVsDetail();
					checkPaidAmountAgainstRvs('1');
				</script>
			<?php
		}
	}

    public static function RangeWiseTotalAmountForTrialBalance($param1,$param2,$param3,$param4,$param5,$param6){
        //return 'Company Id => '.$param1.', From Date => '.$param2.', To Date => '.$param3.', Account Id => '.$param4.', Account Code => '.$param5.', Transaction Type => '.$param6;
        static::companyDatabaseConnection($param1);
        $RangeWiseTotalAmountForTrialBalance = DB::table('transactions')->select('acc_id','acc_code','debit_credit','amount','v_date','status')->whereBetween('v_date',[$param2,$param3])->where('acc_id','=',$param4)->where('debit_credit','=',$param6)->get();
        static::reconnectMasterDatabase();
        $RangeWiseTotalAmountForTrialBalanceAmount = 0;
        foreach ($RangeWiseTotalAmountForTrialBalance as $row) {
            $RangeWiseTotalAmountForTrialBalanceAmount += $row->amount;
        }
        return $RangeWiseTotalAmountForTrialBalanceAmount;
    }

    public static function displayApproveAdvanceSalaryVoucher($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        if($param5 == 'pv_no'){
            $tableOne = 'pvs';
            $tableTwo = 'pv_data';
        }else if($param5 == 'rv_no'){
            $tableOne = 'rvs';
            $tableTwo = 'rv_data';
        }else if($param5 == 'jv_no'){
            $tableOne = 'jvs';
            $tableTwo = 'jv_data';
        }
        ?>
        <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="displayApproveAdvanceSalaryVoucher('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>','<?php echo $param8?>')">
            <span class="glyphicon glyphicon-ok"></span> Approve Advance Salary and Payment Voucher
        </button>
        <?php
    }

    public static function displayCancelAdvanceSalaryVoucher($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        if($param5 == 'pv_no'){
            $tableOne = 'pvs';
            $tableTwo = 'pv_data';
        }else if($param5 == 'rv_no'){
            $tableOne = 'rvs';
            $tableTwo = 'rv_data';
        }else if($param5 == 'jv_no'){
            $tableOne = 'jvs';
            $tableTwo = 'jv_data';
        }
        ?>
        <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="displayCancelAdvanceSalaryVoucher('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>','<?php echo $param8?>')">
            <span class="glyphicon glyphicon-trash"></span>  Delete Advance Salary Payment Voucher
        </button>
        <?php
    }

    

    public static function chartOfAccountButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        if($param2 == '1'){
            ?>
            <?php if($param8 == 0){?>
                <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param6;?>','<?php echo $param3 ?>','<?php echo $param7 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"> P</span>

                </button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteChartOfAccountRecords('<?php echo $param1 ?>','','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo 'acc_id' ?>','status','<?php echo 'accounts' ?>','<?php echo 'transactions'?>')"><span class="glyphicon glyphicon-trash"> P</span></button>
            <?php }?>
            <?php
        }else if($param2 == '2'){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostChartOfAccountRecords('<?php echo $param1 ?>','','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo 'acc_id' ?>','status','<?php echo 'accounts' ?>','<?php echo 'transactions'?>')"><span class="glyphicon glyphicon-edit"> P</span></button>
            <?php
        }
    }

    public static function getCompanyName($param1){
        echo $companyName = DB::selectOne('select `name` from `company` where `id` = '.$param1.'')->name;
    }

    public static function dispalyVoucherAmountforEdit($param1,$param2,$param3,$param4,$param5,$param6){
        $dispalyAmountVoucher = DB::selectOne('select `amount` from '.$param2.' where `id` = '.$param6.' and `debit_credit` = '.$param5.'');
        if($dispalyAmountVoucher == ''){
            return '';
        }else{
            return $dispalyAmountVoucher->amount;
        }
    }

    public static function displayApproveDeleteRepostButton($param1,$param2,$param3,$param4,$param5,$param6,$param7){
        if($param5 == 'pv_no'){
            $tableOne = 'pvs';
            $tableTwo = 'pv_data';
        }else if($param5 == 'rv_no'){
            $tableOne = 'rvs';
            $tableTwo = 'rv_data';
        }else if($param5 == 'jv_no'){
            $tableOne = 'jvs';
            $tableTwo = 'jv_data';
        }

        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>
            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function getAccountNameByAccId($param1,$param2){
        static::companyDatabaseConnection($param2);
        echo $accountName = DB::selectOne('select `name` from `accounts` where `id` = '.$param1.'')->name;
        static::reconnectMasterDatabase();
    }



    public static function getAccountIdByAccName($param1,$param2){
        static::companyDatabaseConnection($param1);
        $accountId = DB::selectOne('select `id` from `accounts` where `name` = "'.$param2.'"')->id;
        static::reconnectMasterDatabase();
        return $accountId;
    }

    public static function getAccountCodeByAccId($param1,$param2){
        static::companyDatabaseConnection($param2);
        $accountCode = DB::selectOne('select `code` from `accounts` where `id` = '.$param1.'')->code;
        static::reconnectMasterDatabase();
        return $accountCode;
    }

    public static function companyDatabaseConnection($param1){
        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        //Config::set(['database.connections.tenant.username' => 'inno-sfr-01']);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
    }

    public static function reconnectMasterDatabase(){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
    }

    public static function changeDateFormat($param1){
        $date = date_create($param1);
        echo date_format($date,"d-m-Y");
    }

    public static function checkVoucherStatus($param1,$param2){
        if($param1 == 1 && $param2 == 1){
            echo 'Pending';
        }else if($param1 == 1 && $param2 == 0){
            echo 'Deleted';
        }else if($param2 == 2){
            echo 'Deleted';
        }else if($param2 == 3){
            echo 'Decline';
        }else if($param1 == 2 && $param2 == 1){
            echo 'Approve';
        }
    }
	
	public static function changeActionButtonsForPayments($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10){
		if($param5 == 'pv_no'){
            $tableOne = 'pvs';
            $tableTwo = 'pv_data';
        }else if($param5 == 'rv_no'){
            $tableOne = 'rvs';
            $tableTwo = 'rv_data';
        }else if($param5 == 'jv_no'){
            $tableOne = 'jvs';
            $tableTwo = 'jv_data';
        }
		if($param3 == 1 && $param2 == 1){
			?>
				<li><a onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
				<li><a onclick="deleteCompanyFinanceTwoTableRecordsPayments('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','<?php echo $param10?>')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
			<?php
		}else if($param3 == 2 && $param2 == 1){
			?>
				<li><a onclick="repostCompanyFinanceTwoTableRecordsPayments('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','<?php echo $param10?>')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>
			<?php
		}
		if($param3 != 2 && $param2 == 2){
			?>
				<li class="userHiddenGatePass"><a onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
				<li class="userHiddenGatePass"><a onclick="deleteCompanyFinanceThreeTableRecordsPayments('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions','<?php echo $param10?>')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
			<?php
		}else if($param3 == 2 && $param2 == 2){
			?>
				<li class="userHiddenGatePass"><a onclick="repostCompanyFinanceThreeTableRecordsPayments('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions','<?php echo $param10?>')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>
			<?php
		}
	}

    public static function changeActionButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param5 == 'pv_no'){
            $tableOne = 'pvs';
            $tableTwo = 'pv_data';
        }else if($param5 == 'rv_no'){
            $tableOne = 'rvs';
            $tableTwo = 'rv_data';
        }else if($param5 == 'jv_no'){
            $tableOne = 'jvs';
            $tableTwo = 'jv_data';
        }
        
		if($param3 == 1 && $param2 == 1){
			?>
				<li><a onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
				<li><a onclick="deleteCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
			<?php
		}else if($param3 == 2 && $param2 == 1){
			?>
				<li><a onclick="repostCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>
			<?php
		}
		if($param3 != 2 && $param2 == 2){
			?>
				<li class="userHiddenGatePass"><a onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
				<li class="userHiddenGatePass"><a onclick="deleteCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
			<?php
		}else if($param3 == 2 && $param2 == 2){
			?>
				<li class="userHiddenGatePass"><a onclick="repostCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>
			<?php
		}
    }

    public static function filterLedgerOpeningRow(){
        echo '0000';
    }

    public static function ChartOfAccountCurrentBalance($param1,$param2,$param3){
        static::companyDatabaseConnection($param1);
        $currentBalance = DB::selectOne("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0) 
							from `transactions` 
							where substring_index(`acc_code`,'-',$param2) = '$param3' and `debit_credit` = 0 
							AND `status` = 1) as bal 
							from `transactions` 
							where substring_index(`acc_code`,'-',$param2) = '$param3' and `debit_credit` = 1 
							AND `status` = 1")->bal;
        static::reconnectMasterDatabase();
        return $currentBalance;
    }
}
?>