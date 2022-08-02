<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\CustomerLocation;
class SaleHelper
{
    public static function homePageURL()
    {
        return url('/');
    }
	
	public static function getOpeningQtyRateForSale($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
			$getOpeningQtyRateForSale = DB::table('fara')->where('main_ic_id','=',$param2)->where('sub_ic_id','=',$param3)->where('region_id','=',$param4)->where('status','=','1')->first();
		CommonHelper::reconnectMasterDatabase();
		if(empty($getOpeningQtyRateForSale)){
			return '0';
		}else{
			return $getOpeningQtyRateForSale->price;
		}
	}
	
	public static function getLocationAndItemWiseAssigneSaleRate($param1,$param2,$param4,$param5){
		CommonHelper::companyDatabaseConnection($param1);
        $customerLocation = DB::table('assigne_item_rates_customer_wise')->where('customer_id','=',$param2)->where('category_id','=',$param4)->where('subitem_id','=',$param5)->where('status','=','1')->first();
		CommonHelper::reconnectMasterDatabase();
		if(empty($customerLocation)){
			return '0';
		}else{
			return $customerLocation->minimum_rates;
		}
	}
	
	public static function totalCoountActiveCustomerLocation($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
        $customerLocation = new CustomerLocation;
        $customerLocation = $customerLocation::where('customer_id','=',$param2)->where('status','=','1')->count();
        CommonHelper::reconnectMasterDatabase();
		$paramOne = "sdc/viewSingleCustomerLocationDetail";
		$paramTwo = $param2;
		$paramThree = "View Customer Location Detail";
		//if($customerLocation == '0'){
			return $customerLocation;
		//}else{
			//return '<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')">'.$customerLocation.'</a>';
		//}
		
	}

    public static function checkVoucherStatus($param1,$param2)
    {
        if ($param1 == 1 && $param2 == 1) {
            return 'Pending';
        } else if ($param2 == 2) {
            return 'Deleted';
        } else if ($param1 == 2 && $param2 == 1) {
            return 'Approve';
        }
    }

    public static function categoryList($param1,$param2){
        echo '<option value="">Select Category</option>';
        CommonHelper::companyDatabaseConnection($param1);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['status', '=', '1'], ])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        foreach($categoryList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['main_ic'];?></option>
            <?php
        }
    }

    public static function subItemList($param1,$param2,$param3){
        echo '<option value="">Select Item</option>';
        CommonHelper::companyDatabaseConnection($param1);
        $subItemList = new Subitem;
        $subItemList = $subItemList::where([['status', '=', '1'],['main_ic_id', '=', $param3], ])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        foreach($subItemList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['sub_ic'];?></option>
            <?php
        }
    }

    public static function getInvoiceTotalAmountByInvoiceNo($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$param2)->first();
        $totalAmount = DB::table('inv_data')->where('inv_no','=',$param2)->sum('amount');
        CommonHelper::reconnectMasterDatabase();
        $calculatedTotalDiscount = $totalAmount*$invoiceDetail->inv_against_discount/100;
        $calculatedTotalAmount = $totalAmount - $calculatedTotalDiscount;
        return $calculatedTotalAmount;
    }

    public static function getInvoiceTotalAmountByInvoiceNoWithDiscountAmount($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$param2)->first();
        $totalAmount = DB::table('inv_data')->where('inv_no','=',$param2)->sum('amount');
        CommonHelper::reconnectMasterDatabase();
        $calculatedTotalAmount = $totalAmount;
        return $calculatedTotalAmount;
    }

    public static function creditSaleVoucherApprove($param1,$param2,$param3){
        if($param3 == 1) {
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true"
                    onclick="creditSaleVoucherApprove('<?php echo $param1;?>','<?php echo $param2;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>
            <?
        }else{}
    }

    public static function getDebitAccountHeadNameForInvoice($param1,$param2){
        CommonHelper::companyDatabaseConnection($param2);
        $accountName = DB::selectOne('select `name` from `accounts` where `id` = '.$param1.'')->name;
        CommonHelper::reconnectMasterDatabase();
        return $accountName;
    }

    public static function saleReceiptVoucherSummaryDetail($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $result = \DB::table("rv_data")
            ->select("rv_data.rv_no","rv_data.amount","rv_data.acc_id","rv_data.debit_credit","rv_data.id","rvs.rv_no","rvs.inv_no")
            ->join('rvs','rv_data.rv_no','=','rvs.rv_no')
            ->where(['rvs.inv_no' => $param2,'rvs.status' => '1','rvs.rv_status' => '2','rvs.rv_status' => '2','rv_data.debit_credit' => '1'])
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">RV No</th><th class="text-center">Account Head</th><th class="text-center col-sm-3">Amount</th></thead><tbody>';
        $counter = 1;
        $totalReceiptAmount = 0;
        foreach($result as $row){
            $totalReceiptAmount += $row->amount;
            $data .='<tr><td class="text-center">'.$counter++.'</td>';
            $data .='<td class="text-center">'.$row->rv_no.'</td>';
            $data .='<td class="text-center">'.static::getDebitAccountHeadNameForInvoice($row->acc_id,$param1).'</td>';
            $data .='<td class="text-right">'.number_format($row->amount).'</td></tr>';
        }
        $data.='</tbody></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" readonly name="totalReceiptAmount" id="totalReceiptAmount" value="'.$totalReceiptAmount.'" class="form-control" /></div></div>';
        return $data;
    }

    public static function getReceiptTotalAmountByInvoiceNo($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $rvsDetail = DB::table('rvs')->where('inv_no','=',$param2)->where('status','=',1)->get();
        $overAllAmount = 0;
        foreach ($rvsDetail as $row){
            $totalAmount = DB::table('rv_data')->where('rv_no','=',$row->rv_no)->where('debit_credit','=',0)->first()->amount;
            $overAllAmount += $totalAmount;
        }
        CommonHelper::reconnectMasterDatabase();
        return $overAllAmount;
    }

    public static function getJournalVoucherNoByInvoiceNo($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $jvsDetail = DB::table('jvs')->where('inv_no','=',$param2)->first();
        CommonHelper::reconnectMasterDatabase();
        return $jvsDetail;
    }

    public static function getReceiptVoucherNoByInvoiceNo($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $rvsDetail = DB::table('rvs')->where('inv_no','=',$param2)->first();
        CommonHelper::reconnectMasterDatabase();
        return $rvsDetail;
    }





}
?>