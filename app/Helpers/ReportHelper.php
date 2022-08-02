<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
class ReportHelper{
    public static function displayLoadCategoryAndItemWisePurchaseAndStockMonitoringDetail($m,$filterRegionId,$filterCategoryId,$filterSubItemId,$fromDate,$toDate){
		CommonHelper::companyDatabaseConnection($m);
			$detailDemandAndDemandData = DB::table('demand_data')
				->select('demand_data.id','demand_data.demand_id','demand_data.category_id','demand_data.sub_item_id','demand_data.demand_no','demand_data.demand_date','demand.location_id','demand_data.demand_status_type')
				->join('demand', 'demand_data.demand_no', '=', 'demand.demand_no')
				->whereBetween('demand_data.demand_date',[$fromDate,$toDate])
				->where('demand_data.category_id','=',$filterCategoryId)
				->where('demand_data.sub_item_id','=',$filterSubItemId)
				->where('demand.region_id','=',$filterRegionId)
				->get();
		CommonHelper::reconnectMasterDatabase();
		foreach($detailDemandAndDemandData as $row){
			$locationName = CommonHelper::getCompanyDatabaseTableValueById($m,'customer_location','location_name',$row->location_id);
			if($row->demand_status_type == '1'){
				$demandStatusType = 'Pending';
			}else if($row->demand_status_type == '2'){
				$demandStatusType = 'Approved';
			}else if($row->demand_status_type == '3'){
				$demandStatusType = 'Dis-Approved';
			}else if($row->demand_status_type == '4'){
				$demandStatusType = 'Decline';
			}
			CommonHelper::companyDatabaseConnection($m);
				$detailStoreChallanAndStoreChallanData = DB::table('store_challan_data')
					->select('store_challan_data.id','store_challan_data.store_challan_id','store_challan_data.demand_id','store_challan_data.demand_data_id','store_challan_data.store_challan_no','store_challan_data.store_challan_date','store_challan_data.store_challan_status','store_challan_data.status')
					->where('store_challan_data.demand_id','=',$row->demand_id)
					->where('store_challan_data.demand_data_id','=',$row->id)
					->get();
					
				$detailPurchaseRequestAndPurchaseRequestData = DB::table('purchase_request_data')
					->select('purchase_request_data.id','purchase_request_data.purchase_request_id','purchase_request_data.demand_id','purchase_request_data.demand_data_id','purchase_request_data.purchase_request_no','purchase_request_data.purchase_request_date','purchase_request_data.purchase_request_status','purchase_request_data.status')
					->where('purchase_request_data.demand_id','=',$row->demand_id)
					->where('purchase_request_data.demand_data_id','=',$row->id)
					->get();
			CommonHelper::reconnectMasterDatabase();
			if(count($detailStoreChallanAndStoreChallanData) == '0' && count($detailPurchaseRequestAndPurchaseRequestData) == '0'){
				$addRowsSpanValue = '1';
				$abc = 'Zero-One';
			}else{
				if(count($detailStoreChallanAndStoreChallanData) > count($detailPurchaseRequestAndPurchaseRequestData)){
					$addRowsSpanValue = count($detailStoreChallanAndStoreChallanData);
					$abc = 'Store Challan Greater Than';
				}else if(count($detailStoreChallanAndStoreChallanData) < count($detailPurchaseRequestAndPurchaseRequestData)){
					$addRowsSpanValue = count($detailPurchaseRequestAndPurchaseRequestData);
					$abc = 'Store Challan Less Than';
				}else{
					$addRowsSpanValue = '1';
					$abc = 'Zero-Two';
				}
			}
			?>
			<tr>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $locationName?> </td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $row->demand_no?></td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo CommonHelper::changeDateFormat($row->demand_date)?></td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $demandStatusType?></td>
				<?php 
					if(count($detailStoreChallanAndStoreChallanData) == '0'){
					?>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
					<?php	
					}else{
						foreach($detailStoreChallanAndStoreChallanData as $row2){
							$storeChallanItemStatus = StoreHelper::checkVoucherStatus($row2->store_challan_status,$row2->status);
						?>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo $row2->store_challan_no?></td>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo CommonHelper::changeDateFormat($row2->store_challan_date)?></td>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo $storeChallanItemStatus;?></td>
							
							<?php 
								if($storeChallanItemStatus == 'Approve'){
							?>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
							<?php
								}else{
							?>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							<?php 
								}
							?>
						<?php
						}
					}
				?>
				<?php 
					if(count($detailPurchaseRequestAndPurchaseRequestData) == '0'){
					?>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
					<?php	
					}else{
						foreach($detailPurchaseRequestAndPurchaseRequestData as $row3){
							$purchaseRequestItemStatus = StoreHelper::checkVoucherStatus($row3->purchase_request_status,$row3->status);
						?>
							<td><?php echo $row3->purchase_request_no?></td>
							<td><?php echo CommonHelper::changeDateFormat($row3->purchase_request_date)?></td>
							<td><?php echo $purchaseRequestItemStatus?></td>
							<?php 
								if($purchaseRequestItemStatus == 'Approve'){
							?>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
							<?php
								}else{
							?>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							<?php 
								}
							?>
						</tr>
						<?php
						}
					}
				?>
			</tr>
			<?php
		}
	}
}
?>