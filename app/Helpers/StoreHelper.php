<?php
namespace App\Helpers;
use Auth;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\Category;
use App\Models\Subitem;

class StoreHelper
{
    public static function homePageURL()
    {
        return url('/');
    }
	
	public static function DisplayGrnLotNoList($param1,$param2,$param3,$param4,$param5){
		CommonHelper::companyDatabaseConnection($param1);
			$DisplayGrnLotNoList = DB::table('grn_data')
				->where('grn_data.category_id', '=', $param2)
				->where('grn_data.sub_item_id', '=', $param3)
				->get();
		CommonHelper::reconnectMasterDatabase();
		if(count($DisplayGrnLotNoList) == '0'){
		?>
			<option value="Opening">Opening</option>
		<?php
		}else{
			?>
			<option value="Opening">Opening</option>
			<?php
			foreach($DisplayGrnLotNoList as $row){
				?>
				<option value="<?php echo $row->lot_no?>" <?php if($param5 == $row->lot_no){echo 'selected';}?>><?php echo $row->lot_no?></option>
				<?php
			}
	}
	}
	public static function displayMaterialRequestDetail($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
			$displayMaterialRequestDetail = DB::table('store_challan_data')
				->select('demand.ticket_prefix','demand.ticket_no','demand.customer_id','demand.location_id','demand.demand_no','demand.demand_date')
				->join('demand', 'store_challan_data.demand_id', '=', 'demand.id')
				->where('store_challan_data.store_challan_no', '=', $param2)
				->where('store_challan_data.category_id', '=', $param3)
				->where('store_challan_data.sub_item_id', '=', $param4)
				->first();
		CommonHelper::reconnectMasterDatabase();
		echo '<td>'.$displayMaterialRequestDetail->demand_no.'</td><td>'.CommonHelper::changeDateFormat($displayMaterialRequestDetail->demand_date).'</td><td>'.$displayMaterialRequestDetail->ticket_prefix.' - '.$displayMaterialRequestDetail->ticket_no.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($param1,'customers','name',$displayMaterialRequestDetail->customer_id).'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($param1,'customer_location','location_name',$displayMaterialRequestDetail->location_id).'</td>';
	}
	public static function displayStockTransferVoucherListButton($m,$stockTransferNo,$transferRegionId,$receiverRegionId,$url,$pageTitle,$status){
		$getRegionPrivilagesArray = CommonHelper::getRegionPrivilagesArray($m,Auth::user()->emr_no);
		if (in_array($transferRegionId, $getRegionPrivilagesArray)){
			if($status == '1'){
				CommonHelper::companyDatabaseConnection($m);
					$displayStockTransferVoucherStatusDetailBreakup = DB::table('stock_transfer_data')
						->where('stock_transfer_no', '=', $stockTransferNo)
						->where('stock_transfer_status', '=', '1')
						->get();
				CommonHelper::reconnectMasterDatabase();
				if(count($displayStockTransferVoucherStatusDetailBreakup) != '0'){
					return '<li><a onclick="showDetailModelOneParamerter(\''.$url.'\',\''.$stockTransferNo.'\',\''.$pageTitle.'\')"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>';
				}
			}else{
				
			}
		}
	}
	
	public static function displayStockTransferVoucherStatusDetail($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
			$displayStockTransferVoucherStatusDetailBreakup = DB::table('stock_transfer_data')
				->select('id','stock_transfer_status', DB::raw('count(*) as total'))
                ->where('stock_transfer_no', '=', $param2)
				->groupBy('stock_transfer_status')
				->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		foreach($displayStockTransferVoucherStatusDetailBreakup as $row){
			if($row->stock_transfer_status == '1'){
				$a .= '<strong>'.$row->total.'</strong> Items Hold <br />';
			}else if($row->stock_transfer_status == '3'){
				$a .= '<strong>'.$row->total.'</strong> Items Receiver Received <br />';
			}else if($row->stock_transfer_status == '4'){
				$a .= '<strong>'.$row->total.'</strong> Items Receiver Decline <br />';
			}
		}
		return $a;
	}
	
	public static function displayUserActivityAgainstMaterialRequest($param1,$param2,$param3,$param4,$param5){
		//return $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5;
		if($param4 == '2'){
			CommonHelper::companyDatabaseConnection($param1);
				$countDeliveryChallanCreatedAgainstDemandId = DB::table('store_challan_data')->where('demand_id','=',$param2)->where('status','=','1')->count();
			CommonHelper::reconnectMasterDatabase();
			if($countDeliveryChallanCreatedAgainstDemandId <= '0'){
				$displayUserActivityAgainstMaterialRequest = 'Delivery Challan not Created';
			}else{
				CommonHelper::companyDatabaseConnection($param1);
					$countDeliveryChallanPendingAgainstDemandId = DB::table('store_challan_data')->where('demand_id','=',$param2)->where('status','=','1')->where('store_challan_status','=','1')->count();
				CommonHelper::reconnectMasterDatabase();
				if($countDeliveryChallanPendingAgainstDemandId > '0'){
					$displayUserActivityAgainstMaterialRequest = 'Delivery Challan is Created but Not Approved';
				}else if($countDeliveryChallanPendingAgainstDemandId == '0'){
					if($param5 == '2'){
						$displayUserActivityAgainstMaterialRequest = 'Complete Stock Delivered';
					}else{
						$displayUserActivityAgainstMaterialRequest = 'Stock Delivered Partially';
					}
				}
			}
		}else{
			$displayUserActivityAgainstMaterialRequest = 'Delivery Challan not Created';
		}
		return $displayUserActivityAgainstMaterialRequest;
	}
	
	public static function displayRangeWiseItemIssuanceQtyLocationWise($param1,$param2,$param3,$param4,$param5,$param6,$param7){
		CommonHelper::companyDatabaseConnection($param1);
		$issuanceQty = DB::table('store_challan_data')
					->join('demand', 'store_challan_data.demand_id', '=', 'demand.id')
					->where('demand.location_id', '=', $param2)
					->where('store_challan_data.category_id', '=', $param3)
					->where('store_challan_data.sub_item_id', '=', $param4)
					->whereBetween('store_challan_data.store_challan_date',[$param5,$param6])
					->where('demand.region_id', '=', $param7)
					->where('store_challan_data.status', '=', '1')
					->where('store_challan_data.store_challan_status', '=', '2')
					->sum('store_challan_data.issue_qty');
					
		$returnQty = DB::table('store_challan_return_data')
					->join('store_challan_data', 'store_challan_return_data.store_challan_data_id', '=', 'store_challan_data.id')
					->join('demand', 'store_challan_data.demand_id', '=', 'demand.id')
					->where('demand.location_id', '=', $param2)
					->where('store_challan_return_data.category_id', '=', $param3)
					->where('store_challan_return_data.sub_item_id', '=', $param4)
					->whereBetween('store_challan_return_data.store_challan_return_date',[$param5,$param6])
					->where('demand.region_id', '=', $param7)
					->where('store_challan_return_data.status', '=', '1')
					->where('store_challan_return_data.store_challan_return_status', '=', '2')
					->sum('store_challan_return_data.return_qty');
		CommonHelper::reconnectMasterDatabase();
		return $issuanceQty - $returnQty;
	}
	
	public static function reversePurchaseOrderDetailAfterApproval($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
			$countGRNDataDetail = DB::table('grn_data')->where('po_no','=',$param2)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($countGRNDataDetail) == '0'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reversePurchaseOrderDetailAfterApproval(\''.$param1.'\',\''.$param2.'\')">Reverse Purchase Order Detail After Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function reversePurchaseOrderDetailBeforeApproval($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
			$checkPurchaseOrderStatus = DB::table('purchase_order')->where('purchase_order_no','=',$param2)->first();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if($checkPurchaseOrderStatus->purchase_order_status == '1'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reversePurchaseOrderDetailBeforeApproval(\''.$param1.'\',\''.$param2.'\')">Reverse Purchase Order Detail After Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function makeOpeningQuantityItemWise($param1,$param2,$param3,$param4,$param5){
		$makeOpeningQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('1'))
			->sum('qty');
			
		$purchaseQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('3'))
			->sum('qty');
			
		$issuanceQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('2'))
			->sum('qty');
		
		$returnQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('4'))
			->sum('qty');
			
		$receivedQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('9'))
			->sum('qty');
		
		$transferQty = DB::table('fara')
			->where('transfer_region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('9','10'))
			->sum('qty');
			
		$damageQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('dll_type','=','1')
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('10'))
			->sum('qty');
		
		$lossQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('dll_type','=','2')
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('10'))
			->sum('qty');
		
		return $openingQtyBalance = $makeOpeningQty + $purchaseQty + $returnQty + $receivedQty - $issuanceQty - $transferQty;
	}
	
	public static function makeCurrentRangePurchaseQtyItemWise($param1,$param2,$param3,$param4,$param5,$param6){
		$purchaseQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->whereBetween('date',[$param5,$param6])
			->whereIn('action',array('3'))
			->sum('qty');
		return $purchaseQty;
	}
	
	public static function makeCurrentRangePurchaseAmountItemWise($param1,$param2,$param3,$param4,$param5,$param6){
		$purchaseAmount = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->whereBetween('date',[$param5,$param6])
			->whereIn('action',array('3'))
			->sum('value');
		return $purchaseAmount;
	}
	
	public static function displayRowDateWiseStockInventoryReport($param1,$param2,$param3,$param4,$param5,$param6){
		$makeOpeningQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('1'))
			->sum('qty');
			
		$purchaseQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('3'))
			->sum('qty');
			
		$issuanceQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('2'))
			->sum('qty');
		
		$returnQty = DB::table('fara')
			->where('region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('4'))
			->sum('qty');
			
		$receivedQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('9'))
			->sum('qty');
		
		$transferQty = DB::table('fara')
			->where('transfer_region_id','=',$param2)
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('9','10'))
			->sum('qty');
			
		$damageQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('dll_type','=','1')
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('10'))
			->sum('qty');
		
		$lossQty = DB::table('fara')
			->where('receiver_region_id','=',$param2)
			->where('dll_type','=','2')
			->where('main_ic_id','=',$param3)
			->where('sub_ic_id','=',$param4)
			->where('date','<=',$param5)
			->whereIn('action',array('10'))
			->sum('qty');
			//echo 'region_id => '.$param2.' main_ic_id => '.$param3.' => sub_ic_id => '.$param4.' => Date => '.$param5;
			
		$paramAOne = "stdc/viewStockPurchaseSummaryDetailItemWise";
		$paramATwo = "View Stock Purchase Summary Detail Item Wise";
		
		$paramBOne = "stdc/viewStockReceivedSummaryDetailItemWise";
		$paramBTwo = "View Stock Received Summary Detail Item Wise";
		
		$paramCOne = "stdc/viewStockReturnSummaryDetailItemWise";
		$paramCTwo = "View Stock Return Summary Detail Item Wise";
		
		$paramDOne = "stdc/viewStockIssuanceSummaryDetailItemWise";
		$paramDTwo = "View Stock Issuance Summary Detail Item Wise";
		
		$paramEOne = "stdc/viewStockTransferSummaryDetailItemWise";
		$paramETwo = "View Stock Transfer Summary Detail Item Wise";
		
		$paramFOne = "stdc/viewStockDamageSummaryDetailItemWise";
		$paramFTwo = "View Stock Damage Summary Detail Item Wise";
		
		$paramGOne = "stdc/viewStockLossSummaryDetailItemWise";
		$paramGTwo = "View Stock Loss Summary Detail Item Wise";
		
		$parameterDetail = $param1.'<*>'.$param2.'<*>'.$param3.'<*>'.$param4;
		
		?>
			<td><?php echo $param6?></td>
			<td class="text-center">
				<?php 
					echo number_format($makeOpeningQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($purchaseQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($receivedQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($returnQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($makeOpeningQty + $purchaseQty + $returnQty + $receivedQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($issuanceQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					echo number_format($transferQty,2);
				?>
			</td>
			
			<td class="text-center">
				<?php 
					echo number_format($damageQty,2);
				?>
			</td>
			
			<td class="text-center">
				<?php 
					echo number_format($lossQty,2);
				?>
			</td>
			<td class="text-center">
				<?php 
					$clossingBalance = $makeOpeningQty + $purchaseQty + $returnQty + $receivedQty - $issuanceQty - $transferQty;
					echo number_format($clossingBalance,2);
				?>
			</td>
			<td class="text-center hidden-print">
				<div class="dropdown theme-btn">
					<button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a onclick="showDetailModelOneParamerter('<?php echo $paramAOne?>','<?php echo $parameterDetail?>','<?php echo $paramATwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Purcahse Summary</a></li>
						<li><a onclick="showDetailModelOneParamerter('<?php echo $paramBOne?>','<?php echo $parameterDetail?>','<?php echo $paramBTwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Received Summary</a></li>
						<li class="hidden"><a onclick="showDetailModelOneParamerter('<?php echo $paramCOne?>','<?php echo $parameterDetail?>','<?php echo $paramCTwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Return Suumary</a></li>
						<li><a onclick="showDetailModelOneParamerter('<?php echo $paramDOne?>','<?php echo $parameterDetail?>','<?php echo $paramDTwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Issuance Summary</a></li>
						<li><a onclick="showDetailModelOneParamerter('<?php echo $paramEOne?>','<?php echo $parameterDetail?>','<?php echo $paramETwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Transfer Summary</a></li>
						<li class="hidden"><a onclick="showDetailModelOneParamerter('<?php echo $paramFOne?>','<?php echo $parameterDetail?>','<?php echo $paramFTwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Damage Summary</a></li>
						<li class="hidden"><a onclick="showDetailModelOneParamerter('<?php echo $paramGOne?>','<?php echo $parameterDetail?>','<?php echo $paramGTwo?>')"><span class="glyphicon glyphicon-eye-open"></span> View Loss Summary</a></li>
					</ul>
				</div>
			</td>
		<?php
	}
	public static function addUserActivityLog($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
		
		//$userIp = $_SERVER['REMOTE_ADDR'];
		//$userLocation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$userIp));
		//$userLocation = file_get_contents('http://api.ipstack.com/'.$userIp.'?access_key=3e6d6c920073c51be79e7887ca96f25f');
		//$userLocation = file_get_contents('http://freegeoip.net/json/'.$userIp.'');
		
		
		//echo $userLocationTwo = file_get_contents('http://api.ip2location.com/?ip='.$userIp.'&key=3e6d6c920073c51be79e7887ca96f25f&package=WS24');
		
		//$userLocation = explode(';',$userLocationTwo);
		//print_r($userLocation);
		//die;
		//echo $makeUserLocation = 'User Location =>(Ip Address => '.$userIp.', City => '.$userLocation[3].', State => '.$userLocation[2].', Country => '.$userLocation[1].', Latitude => '.$userLocation[4].', Longitude => '.$userLocation[5].')';
		
		$data1['activity_type'] = $param3;
		$data1['option_name'] = $param2;
		$data1['description'] = $param9;
		//$data1['user_location'] = $makeUserLocation;
		$data1['user_name'] = $param5;
		$data1['user_id'] = $param6;
		$data1['activity'] = $param4;
		$data1['date'] = $param8;
		$data1['time'] = $param7;
		DB::table($param1)->insert($data1);
	}
	public static function displayMaterialRequestReverseButton($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
			$counterStoreChallanDependDemandId = DB::table('store_challan')->where('demand_id','=',$param3)->get();
			$countPurchaseRequestDependDemandId = DB::table('purchase_request')->where('demand_id','=',$param3)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($counterStoreChallanDependDemandId) == '0' && count($countPurchaseRequestDependDemandId) == '0'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reverseMaterialRequestDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\')">Reverse Material Request Detail</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function displayReverseStoreChallanButtonAfterApproval($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
			$counterStoreChallanReturnDependStoreChallanId = DB::table('store_challan_return_data')->where('store_challan_id','=',$param3)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($counterStoreChallanReturnDependStoreChallanId) == '0'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reverseStoreChallanDetailAfterApproval(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\')">Reverse Store Challan Detail After Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function displayReverseStoreChallanButtonBeforeApproval($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
			$counterStoreChallan = DB::table('store_challan')->where('id','=',$param3)->first();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if($counterStoreChallan->status == '1'){
			$a .= '<a class="btn btn-xs btn-warning" onclick="reverseStoreChallanDetailBeforeApproval(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\')">Reverse Store Challan Detail Before Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	
	
	public static function displayItemWiseLastPurchaseRate($param1,$param2,$param3,$param4){
		$regionList = DB::table('regions')->where('status','=','1')->get();
		$a = '<tr>';
		foreach($regionList as $row){
			CommonHelper::companyDatabaseConnection($param1);
				$displayItemWiseLastPurchaseRate = DB::table('grn_data')->select('grn_data.po_date','goods_receipt_note.supplier_id','goods_receipt_note.supplier_location_id','grn_data.grn_date','grn_data.rate')
                ->join('goods_receipt_note', 'grn_data.grn_no', '=', 'goods_receipt_note.grn_no')
                ->where('grn_data.category_id', '=', $param2)
				->where('grn_data.sub_item_id', '=', $param3)
				->where('grn_data.region_id', '=', $row->id)
                ->where('grn_data.status', '=', '1')
				->orderBy('grn_data.id', 'desc')
				->first();
			CommonHelper::reconnectMasterDatabase();
			if(empty($displayItemWiseLastPurchaseRate)){
				$a .= '<td colspan="'.$param4.'">Last Purchase Not Available</td>';
			}else{
				$a .= '<td colspan="'.$param4.'">Region Name : '.$row->employee_region.' <br /> Purchase Order Date : '.CommonHelper::changeDateFormat($displayItemWiseLastPurchaseRate->po_date).' <br /> Supplier Name : '.CommonHelper::getCompanyDatabaseTableValueById($param1,'supplier','company_business_name',$displayItemWiseLastPurchaseRate->supplier_id).' <br /> Supplier Location Name : '.CommonHelper::getCompanyDatabaseTableValueById($param1,'supplier_location_detail','location_name',$displayItemWiseLastPurchaseRate->supplier_location_id).' <br /> Unit Price : '.$displayItemWiseLastPurchaseRate->rate.' </td>';
			}
		}
		$a .= '</tr>';
		
		return $a;
	}
	
	public static function displayApprovedStockDemandRequestVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        CommonHelper::companyDatabaseConnection($param1);
			$displayMaterialRequestVoucherDetailBreakUp = DB::table('demand_data')
				->select('id','demand_status_type')
                ->where('demand_no', '=', $param4)
				->where('demand_status_type', '=', '2')
				->get();
		CommonHelper::reconnectMasterDatabase();
		if(count($displayMaterialRequestVoucherDetailBreakUp) == '0'){
			return '';
		}else{
			if($param6 == '1'){
				return '<li id="createStoreChallanButton_'.$param4.'" class=""><a onclick="showMasterTableEditModel(\''.$param7.'\',\''.$param4.'\',\''.$param8.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Create Delivery Challan</a></li>';
			}else{
				return '';
			}
		}
    }
	
	public static function displayMaterialRequestVoucherDetailBreakUp($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
			$displayMaterialRequestVoucherDetailBreakUp = DB::table('demand_data')
				->select('id','demand_status_type', DB::raw('count(*) as total'))
                ->where('demand_id', '=', $param2)
				->groupBy('demand_status_type')
				->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		foreach($displayMaterialRequestVoucherDetailBreakUp as $row){
			if($row->demand_status_type == '1'){
				$a .= '<strong>'.$row->total.'</strong> Items Hold <br />';
			}else if($row->demand_status_type == '2'){
				$a .= '<strong>'.$row->total.'</strong> Items Approved <br />';
			}else if($row->demand_status_type == '3'){
				$a .= '<strong>'.$row->total.'</strong> Items Dis-Approved <br />';
			}else if($row->demand_status_type == '4'){
				$a .= '<strong>'.$row->total.'</strong> Items Decline <br />';
			}
		}
		return $a;
	}
	
	public static function displayStoreChallanDetailDependMaterialRequest($param1,$param2,$param3){
		$a = '';
		CommonHelper::companyDatabaseConnection($param1);
			$checkDemandDataDetaiAndUpdateDemandTableRecord = DB::table('store_challan')
				->where('demand_id', '=', $param2)
				->get();
		CommonHelper::reconnectMasterDatabase();
		foreach($checkDemandDataDetaiAndUpdateDemandTableRecord as $row){
			$pageLink = 'stdc/viewStoreChallanVoucherDetail';
			$pageTitle = 'View Delivery Challan Voucher Detail';
			$parameter = $row->store_challan_no;
			$a .= '<a onclick="showDetailModelOneParamerter(\''.$pageLink.'\',\''.$parameter.'\',\''.$pageTitle.'\')">'.$row->store_challan_no.' - '.CommonHelper::changeDateFormat($row->store_challan_date).' - '.$row->username.'</a><br />';
		}
		return $a;
		
	}
	
	
	public static function displayPurchaseRequestDetailDependMaterialRequest($param1,$param2,$param3){
		$a = '';
		CommonHelper::companyDatabaseConnection($param1);
			$checkPurchaseRequestDetaiAndUpdateDemandTableRecord = DB::table('purchase_request')
				->where('demand_id', '=', $param2)
				->get();
		CommonHelper::reconnectMasterDatabase();
		foreach($checkPurchaseRequestDetaiAndUpdateDemandTableRecord as $row){
			$pageLink = 'pdc/viewPurchaseRequestVoucherDetail';
			$pageTitle = 'View Purchase Request Voucher Detail';
			$parameter = $row->purchase_request_no;
			$a .= '<a onclick="showDetailModelOneParamerter(\''.$pageLink.'\',\''.$parameter.'\',\''.$pageTitle.'\')">'.$row->purchase_request_no.' - '.CommonHelper::changeDateFormat($row->purchase_request_date).' - '.$row->username.'</a><br />';
		}
		return $a;
		
	}
	
	public static function checkDemandDataDetaiAndUpdateDemandTableRecord($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
		$checkDemandDataDetaiAndUpdateDemandTableRecord = DB::table('demand_data')
			->where('demand_id', '=', $param2)
			->where('demand_send_type', '=', '1')
            ->count();
			if($checkDemandDataDetaiAndUpdateDemandTableRecord == '0'){
				DB::table('demand')->where('id','=',$param2)->update(['deliver_stock_status' => 2]);
			}
		CommonHelper::reconnectMasterDatabase();
		
	}
	
	public static function checkPriviousIssuanceItemWise($m,$subItemId,$recordId,$demandQty){
		CommonHelper::companyDatabaseConnection($m);
		$checkPriviousIssuanceItemWise = DB::table('store_challan_data')
			->where('demand_data_id', '=', $recordId)
			->where('sub_item_id', '=', $subItemId)
            ->sum('issue_qty');
		CommonHelper::reconnectMasterDatabase();
		if($demandQty <= $checkPriviousIssuanceItemWise){
			$checkPriviousIssuanceItemWiseDetail = $checkPriviousIssuanceItemWise.'<*>1';
		}else{
			$checkPriviousIssuanceItemWiseDetail = $checkPriviousIssuanceItemWise.'<*>2';
		}
		return $checkPriviousIssuanceItemWiseDetail;
	}
	
	public static function displayNewGeneratedMaterialRequest($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
        $displayNewGeneratedMaterialRequest = DB::table('demand')
            ->where('trail_id', '=', $param2)
			->first();
        CommonHelper::reconnectMasterDatabase();
		if(empty($displayNewGeneratedMaterialRequest)){
			$returnValue =  '---';
		}else{
			$returnValue =  $displayNewGeneratedMaterialRequest->demand_no;
		}
		return $returnValue;
	}
	
	public static function getMaterialRequestColorCode($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
		?>
		<script>
			//setTimeout(
				makeTimer('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>','<?php echo $param4?>','<?php echo $param5?>','<?php echo $param6?>','<?php echo $param7?>','<?php echo $param8?>');
			// 2000)
		</script>
		<?php
	}
	public static function displayRadioButtonsApproveDisApproveDecline($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
		?>
			<label class="radio-inline"><input type="radio" disabled class="<?php echo $param1?> abcCheckBox_<?php echo $param9?>" name="<?php echo $param4.''.$param6?>" value="2" <?php if($param8 == '1'){echo 'checked';}else{echo StoreHelper::displayMaterialRequestDataStatusRecordWise($param7,$param6,'2','demand_data','demand_status_type');}?> onchange="<?php echo $param5?>(),changeRemarksRecordWise('2','<?php echo $param6?>')">A</label>
			<label class="radio-inline"><input type="radio" disabled class="<?php echo $param2?> abcCheckBox_<?php echo $param9?>" name="<?php echo $param4.''.$param6?>" value="3" <?php echo StoreHelper::displayMaterialRequestDataStatusRecordWise($param7,$param6,'3','demand_data','demand_status_type');?> onchange="<?php echo $param5?>(),changeRemarksRecordWise('3','<?php echo $param6?>')">D-A</label>
			<label class="radio-inline"><input type="radio" disabled class="<?php echo $param3?> abcCheckBox_<?php echo $param9?>" name="<?php echo $param4.''.$param6?>" value="4" <?php echo StoreHelper::displayMaterialRequestDataStatusRecordWise($param7,$param6,'4','demand_data','demand_status_type');?> onchange="<?php echo $param5?>(),changeRemarksRecordWise('4','<?php echo $param6?>')">D</label>
			&nbsp;&nbsp;&nbsp;<label><?php echo StoreHelper::displayMaterialRequestDataRecordStatus($param7,$param6,$param8,'demand_data','demand_status_type')?></label>
		<?php
	}
	
	public static function displayMaterialRequestDataStatusRecordWise($param1,$param2,$param3,$param4,$param5){
		CommonHelper::companyDatabaseConnection($param1);
        $displayMaterialRequestDataStatusRecordWise = DB::table($param4)
            ->where('id', '=', $param2)
			->where($param5,'=',$param3)
			->first();
        CommonHelper::reconnectMasterDatabase();
		if(empty($displayMaterialRequestDataStatusRecordWise)){
			return '';
		}else{
			return 'checked';
		}
	}
	
	public static function displayMaterialRequestDataRecordStatus($param1,$param2,$param3,$param4,$param5){
		CommonHelper::companyDatabaseConnection($param1);
        $displayMaterialRequestDataRecordStatus = DB::table($param4)
            ->where('id', '=', $param2)
			->first();
        CommonHelper::reconnectMasterDatabase();
		if($displayMaterialRequestDataRecordStatus->demand_status_type == '1'){
			return 'Pending';
		}else if($displayMaterialRequestDataRecordStatus->demand_status_type == '2'){
			return 'Approved';
		}else if($displayMaterialRequestDataRecordStatus->demand_status_type == '3'){
			return 'Dis-Approved';
		}else if($displayMaterialRequestDataRecordStatus->demand_status_type == '4'){
			return 'Decline';
		}
		
	}
	
	public static function loadCustomerLocationListWithParameter($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
        $customerLocationList = DB::table('customer_location')
            ->where('customer_id', '=', $param3)
			->where('region_id', '=', $param4)
			->where('status', '=', '1')
            ->get();
        CommonHelper::reconnectMasterDatabase();
		foreach($customerLocationList as $row){
			?>
			<option value="<?php echo $row->id?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->location_name?></option>
			<?php
		}
	}
	
	public static function customerLocationList($param1,$param2,$param3,$param4){
		CommonHelper::companyDatabaseConnection($param1);
        $customerLocationList = DB::table('customer_location')
            ->where('customer_id', '=', $param2)
			->where('region_id', '=', $param3)
			->where('status', '=', '1')
            ->get();
        CommonHelper::reconnectMasterDatabase();
		?>
		<option value="">Select Location</option>
		<?php
		foreach($customerLocationList as $row){
			?>
			<option value="<?php echo $row->id?>" <?php if($param4 == $row->id){echo 'selected';}?>><?php echo $row->location_name?></option>
			<?php
		}
	}
	
	public static function getItemAndLocationRateDetail($param1,$param2,$param3){
		CommonHelper::companyDatabaseConnection($param1);
        $getItemAndLocationRateDetail = DB::table('assigne_item_rates_customer_wise')
            ->where('location_id', '=', $param2)
			->where('subitem_id', '=', $param3)
			->where('aircw_status', '=', '2')
            ->first();
        CommonHelper::reconnectMasterDatabase();
		if(empty($getItemAndLocationRateDetail)){
			$rate = '0';
		}else{
			$rate = $getItemAndLocationRateDetail->minimum_rates;
		}
		return $rate;
	}

    public static function displayApproveDeleteRepostButtonPurchaseOrder($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approvePurchaseOrder('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyStoreTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyStoreTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function makeSubTotalAmountPurchaseOrder($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
            $makeSubTotalAmountPurchaseOrder = DB::table('purchase_order_data')
                ->where('purchase_order_no', '=', $param2)
                ->sum('sub_total');
        CommonHelper::reconnectMasterDatabase();
        return $makeSubTotalAmountPurchaseOrder;
    }

    public static function makeSubTotalWithPercentageAmountPurchaseOrder($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $makeSubTotalAmountPurchaseOrder = DB::table('purchase_order_data')
            ->where('purchase_order_no', '=', $param2)
            ->sum('sub_total');

        $makePercentAmountPurchaseOrder = DB::table('purchase_order_data')
            ->where('purchase_order_no', '=', $param2)
            ->sum('sub_total_with_persent');
        CommonHelper::reconnectMasterDatabase();

        return $makeSubTotalAmountPurchaseOrder + $makePercentAmountPurchaseOrder;
    }

    public static function displayPurchaseOrderButton($param1,$param2,$param3,$param4,$param5){
        if($param3 == 1 && $param2 == 1) {
            $paramOned = "store/editPurchaseOrderVoucherForm";
            $paramTwod = $param5;
            $paramThreed = "Purchase Order Voucher Edit Detail Form";
            $paramFourd = $param1;
            return '<li><a onclick="showMasterTableEditModel(\'' . $paramOned . '\',\'' . $paramTwod . '\',\'' . $paramThreed . '\',\'' . $paramFourd . '\')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>';
        }else{

        }
    }

    public static function getTotalPurchaseRequestQtyItemWise($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $purchaseRequestQty = DB::table("purchase_request_data")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['category_id' => $param3,'sub_item_id' => $param4,'status' => '1'])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalPurchaseRequestQty = 0;
        foreach ($purchaseRequestQty as $row){
            $totalPurchaseRequestQty += $row->qty;
        }
        return $totalPurchaseRequestQty;
    }

    public static function displayApproveDeleteRepostButtonStockDemandDetail($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveStockDemandRequestDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteStockDemandRequestDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostStockDemandRequestDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
            <?php
        }
    }
	
	public static function displayApproveDeleteRepostButtonMaterialRequestTemplateDetail($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveMaterialRequestTemplateDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteMaterialRequestTemplateDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostMaterialRequestTemplateDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
            <?php
        }
    }
	
	

    public static function displayStockDemandRequestVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12,$param13,$param14){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<li id="editMaterialRequestButton_'.$param4.'" class=""><a onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
					<li id="reGenerateMaterialRequestButton_'.$param4.'" class=""><a onclick="showMasterTableEditModel(\''.$param12.'\',\''.$param4.'\',\''.$param13.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Re-Generate Matrial Request</a></li>
                    <li id="deleteMaterialRequestButton_'.$param4.'" class=""><a onclick="deleteStockDemandRequestDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\',\''.$param14.'\')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
        }else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostStockDemandRequestDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\',\''.$param14.'\')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>';
        }
    }
	
	public static function displayMaterialRequestTemplateVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<li><a onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
                    <li><a onclick="deleteMaterialRequestTemplateDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
        }else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostMaterialRequestTemplateDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>';
        }
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

    public static function priviousPurchaseOrderQtyThisPurchaseRequest($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
            if($param5 == 0) {
				$priviousSendPurchaseOrderQty = DB::table("purchase_order_data")
                    ->select(DB::raw("SUM(purchase_order_qty) as purchase_order_qty"))
                    ->where(['category_id' => $param3, 'sub_item_id' => $param4, 'status' => '1'])
                    ->where('purchase_request_no' ,'=', $param2)
                    ->groupBy(DB::raw("sub_item_id"))
                    ->get();
            }else{
                $priviousSendPurchaseOrderQty = DB::table("purchase_order_data")
                    ->select(DB::raw("SUM(purchase_order_qty) as purchase_order_qty"))
                    ->where(['category_id' => $param3, 'sub_item_id' => $param4, 'status' => '1'])
                    ->where('purchase_request_no' ,'=', $param2)
                    ->where('id' ,'!=', $param5)
                    ->groupBy(DB::raw("sub_item_id"))
                    ->get();
            }
        CommonHelper::reconnectMasterDatabase();
        $totalPriviousSendPurchaseOrderQty = 0;
        foreach ($priviousSendPurchaseOrderQty as $row){
            $totalPriviousSendPurchaseOrderQty += $row->purchase_order_qty;
        }
        return $totalPriviousSendPurchaseOrderQty;

    }



    public static function checkItemWiseCurrentBalanceQty($param1,$param2,$param3,$param4,$param5){
        //return $param1.'----'.$param2.'----'.$param3.'<br />';
        CommonHelper::companyDatabaseConnection($param1);
        $openingBlc = DB::selectOne('select `qty` from `fara` where `action` = 1 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ');
        if($openingBlc):
            $openingBalance = $openingBlc->qty;
        else:
            $openingBalance = 0;
        endif;
        $purchaseBalance = '';
        //$sendBalance = DB::selectOne('select `qty` from `fara` where `action` = 2 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ');
        $sendBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '2'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $returnBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '4'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $purchaseBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '3'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $cashSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '5'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $creditSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '6'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalSendBalance = 0;
        foreach ($sendBalance as $row){
            $totalSendBalance += $row->qty;
        }
        $totalReturnBalance = 0;
        foreach ($returnBalance as $row){
            $totalReturnBalance += $row->qty;
        }
        $totalPurchaseBalance = 0;
        foreach ($purchaseBalance as $row){
            $totalPurchaseBalance += $row->qty;
        }

        $totalCashSaleBalance = 0;
        foreach ($cashSaleBalance as $row){
            $totalCashSaleBalance += $row->qty;
        }

        $totalCreditSaleBalance = 0;
        foreach ($creditSaleBalance as $row){
            $totalCreditSaleBalance += $row->qty;
        }
        $currentBalanceInStore = $openingBalance + $totalPurchaseBalance + $totalReturnBalance - $totalSendBalance  - $totalCashSaleBalance  - $totalCreditSaleBalance;

        return $currentBalanceInStore;
    }

    public static function issueQtyItemWiseDetail($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_data")
            ->select(DB::raw("SUM(issue_qty) as issue_qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->issue_qty;
        }
        return $totalQty;
    }

    public static function demandAndRemainingQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::issueQtyItemWiseDetail($param1,$param2,$param3,$param4,$param5);
        return 'Demand and Remaining Qty Item Wise';
    }

    public static function displayApproveDeleteRepostButtonPurchaseRequest($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approvePurchaseRequest('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function displayApproveDeleteRepostButtonPurchaseRequestSale($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approvePurchaseRequestSale('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <?php /*?><button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button><?php */?>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }
	
	public static function displayApproveDeleteRepostButtonStoreChallanReturn($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
		if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveDeliveryChallanReturnVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
	}

    public static function displayApproveDeleteRepostButtonTwoTable($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </button>

            <button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </button>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </button>
            <?php
        }
    }

    public static function displayApproveDeleteRepostStoreChallanButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveStoreChallanVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher & Generate Bar Code
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteStoreChallanVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostStoreChallanVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
            <?php
        }

    }
	
	
	
	public static function displayApproveDeleteRepostStockTransferButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveStockTransferVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteStockTransferVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostStockTransferVoucherDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
            <?php
        }

    }



    public static function displayStoreChallanVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12,$param13){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<li><a onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
                    <li><a onclick="deleteStoreChallanVoucherDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
        }else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostStoreChallanVoucherDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-edit"></span> Restore</a></li>';
        }else{
			return '<li><a onclick="showMasterTableEditModel(\''.$param12.'\',\''.$param4.'\',\''.$param13.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Create Delivery Challan Return</a></li>';
		}
    }

    public static function displayPurchaseChallanButton($param1,$param2,$param3,$param4,$param5){
        if($param3 == 1 && $param2 == 1) {
            $paramOned = "store/editPurchaseRequestVoucherForm";
            $paramTwod = $param5;
            $paramThreed = "Purchase Request Voucher Edit Detail Form";
            $paramFourd = $param1;
            return '<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel(\'' . $paramOned . '\',\'' . $paramTwod . '\',\'' . $paramThreed . '\',\'' . $paramFourd . '\')"><span class="glyphicon glyphicon-edit"> P</span></button>';
        }else{

        }
    }

    public static function displayStoreChallanReturnButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12,$param13)
    {
        if ($param3 == 1 && $param2 == 1) {
            return '<li><a onclick="showMasterTableEditModel(\'' . $param6 . '\',\'' . $param4 . '\',\'' . $param7 . '\',\'' . $param1 . '\')"><span class="glyphicon glyphicon-edit"></span> Edit</a><li><li><a onclick="deleteCompanyStoreThreeTableRecords(\'' . $param1 . '\',\'' . $param2 . '\',\'' . $param3 . '\',\'' . $param4 . '\',\'' . $param5 . '\',\'' . $param8 . '\',\'' . $param9 . '\',\'' . $param10 . '\',\'' . $param11 . '\',\'' . $param12 . '\')"><span class="glyphicon glyphicon-trash"></span> Delete</a><li>';
		}else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostCompanyStoreThreeTableRecords(\'' . $param1 . '\',\'' . $param2 . '\',\'' . $param3 . '\',\'' . $param4 . '\',\'' . $param5 . '\',\'' . $param8 . '\',\'' . $param9 . '\',\'' . $param10 . '\',\'' . $param11 . '\',\'' . $param12 . '\',\'' . $param13 . '\')"><span class="glyphicon glyphicon-edit"></span> Restore</a><li>';
		}
    }

    public static function getDemandQtyByDemandNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("demand_data")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->qty;
        }
        return $totalQty;
    }

    public static function getDemandQtyByDemandIdAndDemandDataId($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("demand_data")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where(['demand_id' => $param4])
            ->where(['id' => $param5])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->qty;
        }
        return $totalQty;
    }

    public static function issueQtyItemWiseDetailDemandId($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_data")
            ->select(DB::raw("SUM(issue_qty) as issue_qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where(['demand_id' => $param4])
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->issue_qty;
        }
        return $totalQty;
    }

    

    public static function getReturnQtyByStoreChallanNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_return_data")
            ->select(DB::raw("SUM(return_qty) as return_qty"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->where('status','=',1)
            ->groupBy(DB::raw("sub_item_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalQty = 0;
        foreach ($data as $row){
            $totalQty += $row->return_qty;
        }
        return $totalQty;
    }

    public static function getReturnQtyByDemandNo($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
        $data = DB::table("store_challan_data")
            ->select(DB::raw("store_challan_no"))
            ->where(['category_id' => $param2,'sub_item_id' => $param3])
            ->where([$param5 => $param4])
            ->where('status','=',1)
            ->get();
        $totalQty = 0;
        foreach ($data as $row){
            $dataOne = DB::table("store_challan_return_data")
                ->select(DB::raw("SUM(return_qty) as return_qty"))
                ->where(['category_id' => $param2,'sub_item_id' => $param3])
                ->where(['store_challan_no' => $row->store_challan_no])
                ->where('status','=',1)
                ->groupBy(DB::raw("sub_item_id"))
                ->get();
            foreach ($dataOne as $rowOne){
                $totalQty += $rowOne->return_qty;
            }
        }
        CommonHelper::reconnectMasterDatabase();
		return $totalQty;
    }

    public static function itemWiseCreatedStoreChallan($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
            $data = DB::table('store_challan_data')
                ->select('store_challan_no','store_challan_date','issue_qty')
                ->where(['category_id' => $param3,'sub_item_id' => $param4,'demand_no' => $param2])
                ->where('store_challan_no','!=',$param5)
                ->get();
        CommonHelper::reconnectMasterDatabase();
        foreach ($data as $row){
            echo $row->store_challan_no.'  ---  ';
            echo CommonHelper::changeDateFormat($row->store_challan_date).'  ---  ';
            echo $row->issue_qty;
            echo '<br />';
        }
        if($data->isEmpty()){
            echo 'Not Found!';
        }
    }

    public static function itemWiseCreatedStoreChallanDependDemandIdAndDemandDataId($param1,$param2,$param3,$param4,$param5,$param6){
        CommonHelper::companyDatabaseConnection($param1);
            $data = DB::table('store_challan_data')
                ->select('store_challan_no','store_challan_date','issue_qty')
                ->where(['category_id' => $param4,'sub_item_id' => $param5,'demand_id' => $param2,'demand_data_id' => $param3])
                ->where('store_challan_no','!=',$param6)
                ->get();
        CommonHelper::reconnectMasterDatabase();
        foreach ($data as $row){
            echo $row->store_challan_no.'  ---  ';
            echo CommonHelper::changeDateFormat($row->store_challan_date).'  ---  ';
            echo $row->issue_qty;
            echo '<br />';
        }
        if($data->isEmpty()){
            echo 'Not Found!';
        }
    }





    public static function checkItemWiseCurrentBalanceQtyDependDemandIdAndDemandDataId($param1,$param2,$param3,$param4,$param5){
        //return $param1.'----'.$param2.'----'.$param3.'<br />';
        CommonHelper::companyDatabaseConnection($param1);
        $openingBlc = DB::selectOne('select `qty` from `fara` where `action` = 1 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ');
        if($openingBlc):
            $openingBalance = $openingBlc->qty;
        else:
            $openingBalance = 0;
        endif;
        $purchaseBalance = '';
        //$sendBalance = DB::selectOne('select `qty` from `fara` where `action` = 2 and `status` = 1 and `main_ic_id` = '.$param2.' and `sub_ic_id` = '.$param3.' ');
        $sendBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '2'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $returnBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '4'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        $purchaseBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '3'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $cashSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '5'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();

        $creditSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '6'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $totalSendBalance = 0;
        foreach ($sendBalance as $row){
            $totalSendBalance += $row->qty;
        }
        $totalReturnBalance = 0;
        foreach ($returnBalance as $row){
            $totalReturnBalance += $row->qty;
        }
        $totalPurchaseBalance = 0;
        foreach ($purchaseBalance as $row){
            $totalPurchaseBalance += $row->qty;
        }

        $totalCashSaleBalance = 0;
        foreach ($cashSaleBalance as $row){
            $totalCashSaleBalance += $row->qty;
        }

        $totalCreditSaleBalance = 0;
        foreach ($creditSaleBalance as $row){
            $totalCreditSaleBalance += $row->qty;
        }
        $currentBalanceInStore = $openingBalance + $totalPurchaseBalance + $totalReturnBalance - $totalSendBalance  - $totalCashSaleBalance  - $totalCreditSaleBalance;

        return $currentBalanceInStore;
    }

    
}
?>