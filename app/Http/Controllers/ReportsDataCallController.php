<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReportHelper;
use Auth;
use DB;
use Config;
class ReportsDataCallController extends Controller
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
    public function showBranchInventoryList(){
        $m = $_GET['m'];
        return CommonHelper::showBranchInventoryList($m);
    }
	
	public function filterViewStockIssueanceLocationWiseReport(){
		return view('Reports.Inventory.filterViewStockIssueanceLocationWiseReport');
    }
	
	public function filterViewPurchaseAndStockMonitoringReport(){
		return view('Reports.Inventory.filterViewPurchaseAndStockMonitoringReport');
    }
	
	public function filterViewStockPurchaseAndIssuanceRangeWiseReport(){
		return view('Reports.Inventory.filterViewStockPurchaseAndIssuanceRangeWiseReport');
    }
	
	public function loadCategoryWiseStockPurchaseAndIssuanceInventoryReport(){
		$m = $_GET['m'];
		$filterRegion = $_GET['filterRegion'];
		$filterCategoryId = $_GET['filterCategoryId'];
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];
		$filterLocationId = $_GET['filterLocationId'];
		$filterSubItemId = $_GET['filterSubItemId'];
		$categoryName = $_GET['categoryName'];
		$regionName = $_GET['regionName'];
		CommonHelper::companyDatabaseConnection($m);
		if(empty($filterSubItemId)){
			$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->get();
		}else{
			$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->whereIn('id',$filterSubItemId)->get();
		}
		
		if(empty($filterLocationId)){
			$customerLocationList = DB::table('customer_location')->where('region_id','=',$filterRegion)->get();
		}else{
			$customerLocationList = DB::table('customer_location')->where('region_id','=',$filterRegion)->whereIn('id',$filterLocationId)->get();
		}
		CommonHelper::reconnectMasterDatabase();
		?>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<th class="text-center" rowspan="3">Sr.No.</th>
										<th class="text-center" rowspan="3">ITEM NAME</th>
										<th class="text-center" rowspan="3">TOTAL UNITS AT FIRST (Pcs.)</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$counter = 1;
										CommonHelper::companyDatabaseConnection($m);
										foreach($subItemList as $row1){
											$openingQty = StoreHelper::makeOpeningQuantityItemWise($m,$filterRegion,$filterCategoryId,$row1->id,$fromDate);
									?>
										<tr>
											<td class="text-center"><?php echo $counter++;?></th>
											<td><?php echo $row1->sub_ic?></th>
											<td class="text-center"><?php echo $openingQty?></th>
										</tr>
									<?php
										}
										CommonHelper::reconnectMasterDatabase();
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="overflow-x: scroll; ">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th class="text-center" rowspan="3">Add on Quantity (Pcs.)</th>
										<th class="text-center" rowspan="3">Total Units (Pcs.)</th>
										<th class="text-center" rowspan="3">Per Unit Cost</th>
										<th class="text-center" rowspan="3">Total Cost</th>
									</tr>
									<tr>
										<?php 
											foreach($customerLocationList as $row2){
										?>
											<th class="text-center" colspan="2"><?php echo $row2->location_name?></th>
										<?php 
											}
										?>
									</tr>
									<tr>
										<?php 
											foreach($customerLocationList as $row3){
										?>
											<th class="text-center">Qty.</th>
											<th class="text-center">Total Amount</th>
										<?php 
											}
										?>
									</tr>
									</tr>
								</thead>
								<tbody>
									
									<?php
										CommonHelper::companyDatabaseConnection($m);
										foreach($subItemList as $row4){
											$openingQtyTwo = StoreHelper::makeOpeningQuantityItemWise($m,$filterRegion,$filterCategoryId,$row4->id,$fromDate);
											$purchaseQty = StoreHelper::makeCurrentRangePurchaseQtyItemWise($m,$filterRegion,$filterCategoryId,$row4->id,$fromDate,$toDate);
											$purchaseAmount = StoreHelper::makeCurrentRangePurchaseAmountItemWise($m,$filterRegion,$filterCategoryId,$row4->id,$fromDate,$toDate);
											$totalQty = $purchaseQty + $openingQtyTwo;
											if($totalQty == 0){
												$perUnitCost = $purchaseAmount / 1;
											}else{
												$perUnitCost = $purchaseAmount / $totalQty;
											}
									?>
										<tr>
											<td class="text-center"><?php echo $purchaseQty?></td>
											<td class="text-center"><?php echo $totalQty?></td>
											<td class="text-center"><?php echo number_format($perUnitCost,2)?></td>
											<td class="text-right"><?php echo number_format($purchaseAmount,2);?></td>
											<?php
												foreach($customerLocationList as $row5){
													CommonHelper::reconnectMasterDatabase();
													$issuanceQty = StoreHelper::displayRangeWiseItemIssuanceQtyLocationWise($m,$row5->id,$filterCategoryId,$row4->id,$fromDate,$toDate,$filterRegion);
													CommonHelper::companyDatabaseConnection($m);
											?>
												<td class="text-center"><?php echo number_format($issuanceQty,2);?></td>
												<td class="text-right">0.00</td>
											<?php
												}
											?>
										</tr>
									<?php
										}
										CommonHelper::reconnectMasterDatabase();
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	
	
	public function loadCategoryWisePurchaseAndStockMonitoringDetail(){
		$m = $_GET['m'];
		$filterRegion = $_GET['filterRegion'];
		$filterCategoryId = $_GET['filterCategoryId'];
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];
		$filterSubItemId = $_GET['filterSubItemId'];
		$categoryName = $_GET['categoryName'];
		$regionName = $_GET['regionName'];
		CommonHelper::companyDatabaseConnection($m);
			if(empty($filterSubItemId)){
				$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->get();
			}else{
				$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->whereIn('id',$filterSubItemId)->get();
			}
		CommonHelper::reconnectMasterDatabase();
			$counter = 1;
			foreach($subItemList as $row){
		?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<?php //echo CommonHelper::displayPrintButtonInBlade('printLoadCategoryWiseStockIssuanceReport_'.$filterCategoryId.'','','1');?>
					<?php //echo CommonHelper::displayExportButton('exportLoadCategoryWiseStockIssuanceReport_'.$filterCategoryId.'','','1')?>
					<?php $fileName = $categoryName.' - '.date('d-m-Y').' - '.Auth::user()->name .'.pdf'?>
					<button class="btn btn-sm btn-warning" onclick="generatePDFFile('printLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>','<?php echo $fileName?>','<?php echo $filterCategoryId?>','<?php echo $row->id?>')">Generate PDF</button>
				</div>
				<div class="lineHeight">&nbsp;</div>
				<div class="printLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>_<?php echo $row->id?>" style="overflow-y: scroll;">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
						<div class="lineHeight">&nbsp;</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<?php echo '<strong>Filter By : (Region => '.$regionName.')&nbsp;&nbsp;,&nbsp;&nbsp;(Category => '.$categoryName.')&nbsp;&nbsp;,&nbsp;&nbsp;(Item Name => '.$row->sub_ic.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')</strong>'?>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table  class="table table-bordered table-striped table-condensed tableMargin" id="exportLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>">
							<thead>
								<tr>
									<th class="text-center" colspan="4">Material Request Detail</th>
									<th class="text-center" colspan="3">Delivery Challan Detail</th>
									<th class="text-center" colspan="3">Delivery Challan Return Detail</th>
									<th class="text-center" colspan="3">Purchase Request Detail</th>
									<th class="text-center" colspan="4">Purchase Order Detail</th>
									<th class="text-center" colspan="3">Goods Receipt Note Detail</th>
								</tr>
								<tr>
									<th class="text-center">Branches Name</th>
									<th class="text-center">M.R.No.</th>
									<th class="text-center">M.R.Date</th>
									<th class="text-center">M.R.I.Status</th>
									<th class="text-center">D.C.No.</th>
									<th class="text-center">D.C.Date</th>
									<th class="text-center">D.C.I.Status</th>
									<th class="text-center">D.C.R.No.</th>
									<th class="text-center">D.C.R.Date</th>
									<th class="text-center">D.C.R.I.Status</th>
									<th class="text-center">P.R.No.</th>
									<th class="text-center">P.R.Date</th>
									<th class="text-center">P.R.I.Status</th>
									<th class="text-center">P.O.No.</th>
									<th class="text-center">P.O.Date</th>
									<th class="text-center">P.O.I.Status</th>
									<th class="text-center">S.L.Name</th>
									<th class="text-center">G.R.N.No.</th>
									<th class="text-center">G.R.N.Date</th>
									<th class="text-center">G.R.N.I.Status</th>
								</tr>
							</thead>
							<tbody id="tbodyLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>_<?php echo $row->id?>">
							</tbody>
						</table>
						<script>
							loadCategoryAndItemWisePurchaseAndStockMonitoringDetail('<?php echo $m ?>','<?php echo $filterRegion?>','<?php echo $filterCategoryId?>','<?php echo $row->id ?>','<?php echo $fromDate ?>','<?php echo $toDate ?>','tbodyLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>_<?php echo $row->id?>');
						</script>
					</div>
				</div>
			</div>
		<?php
			}
	}
	
	public function loadCategoryAndItemWisePurchaseAndStockMonitoringDetail(){
		$m = $_GET['m'];
		$filterRegionId = $_GET['filterRegion'];
		$filterCategoryId = $_GET['filterCategoryId'];
		$filterSubItemId = $_GET['filterSubItemId'];
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];
		echo ReportHelper::displayLoadCategoryAndItemWisePurchaseAndStockMonitoringDetail($m,$filterRegionId,$filterCategoryId,$filterSubItemId,$fromDate,$toDate);
		//echo $m.' - '.$filterRegion.' - '.$filterCategoryId.' - '.$filterSubItemId.' - '.$fromDate.' - '.$toDate.'';
		
	}
	
	
	
	public function loadCategoryWiseStockInventoryReport(){
		$m = $_GET['m'];
		$filterRegion = $_GET['filterRegion'];
		$filterCategoryId = $_GET['filterCategoryId'];
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];
		$filterLocationId = $_GET['filterLocationId'];
		$filterSubItemId = $_GET['filterSubItemId'];
		$categoryName = $_GET['categoryName'];
		$regionName = $_GET['regionName'];
		CommonHelper::companyDatabaseConnection($m);
		if(empty($filterSubItemId)){
			$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->get();
		}else{
			$subItemList = DB::table('subitem')->where('company_id','=',$m)->where('main_ic_id','=',$filterCategoryId)->whereIn('id',$filterSubItemId)->get();
		}
		
		if(empty($filterLocationId)){
			$customerLocationList = DB::table('customer_location')->where('region_id','=',$filterRegion)->get();
		}else{
			$customerLocationList = DB::table('customer_location')->where('region_id','=',$filterRegion)->whereIn('id',$filterLocationId)->get();
		}
		CommonHelper::reconnectMasterDatabase();
		?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
				<?php //echo CommonHelper::displayPrintButtonInBlade('printLoadCategoryWiseStockIssuanceReport_'.$filterCategoryId.'','','1');?>
				<?php //echo CommonHelper::displayExportButton('exportLoadCategoryWiseStockIssuanceReport_'.$filterCategoryId.'','','1')?>
				<?php $fileName = $categoryName.' - '.date('d-m-Y').' - '.Auth::user()->name .'.pdf'?>
				<button class="btn btn-sm btn-warning" onclick="generatePDFFile('printLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>','<?php echo $fileName?>','<?php echo $filterCategoryId?>')">Generate PDF</button>
			</div>
			<div class="lineHeight">&nbsp;</div>
			<div class="printLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>" style="overflow-y: scroll;">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
					<div class="lineHeight">&nbsp;</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<?php echo '<strong>Filter By : (Region => '.$regionName.')&nbsp;&nbsp;,&nbsp;&nbsp;(Category => '.$categoryName.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')</strong>'?>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<table  class="table table-bordered table-striped table-condensed tableMargin" id="exportLoadCategoryWiseStockIssuanceReport_<?php echo $filterCategoryId?>">
						<thead>
							<tr>
								<th class="text-center" rowspan="3">S.No</th>
								<th class="text-center" rowspan="3">Branches Name</th>
							</tr>
							<tr>
								<?php 
									foreach($subItemList as $row1){
								?>
									<th class="text-center" colspan="4"><?php echo $row1->sub_ic?></th>
								<?php 
									}
								?>
								<th class="text-center" rowspan="3">Total Location Wise</th>
							</tr>
							<tr>
								<?php 
									foreach($subItemList as $row2){
								?>
									<th class="text-center">Qty.</th>
									<th class="text-center">M.R.No</th>
									<th class="text-center">M.R.Date</th>
									<th class="text-center">Ticket No</th>
								<?php 
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php 
								$counter = 1;
								$overAllTotalLocationWise = 0;
								foreach($customerLocationList as $row3){
							?>
								<tr>
									<td class="text-center"><?php echo $counter++;?></td>
									<td><?php echo $row3->location_name?></td>
									<?php 
										$locationWiseItemIssuanceTotal = 0;
										
										foreach($subItemList as $row4){
											$issuanceQty = StoreHelper::displayRangeWiseItemIssuanceQtyLocationWise($m,$row3->id,$filterCategoryId,$row4->id,$fromDate,$toDate,$filterRegion);
											$locationWiseItemIssuanceTotal += $issuanceQty;
									?>
										<td class="text-center qtyItemWiseSingle_<?php echo $row4->id?>"><?php echo $issuanceQty;?></td>
										<td class="text-center">-</td>
										<td class="text-center">-</td>
										<td class="text-center">-</td>
									<?php
										}
										$overAllTotalLocationWise += $locationWiseItemIssuanceTotal;
									?>
									<th class="text-center"><?php echo $locationWiseItemIssuanceTotal?></th>
								</tr>
							<?php
								}
							?>
							<tr>
								<th colspan="2" class="text-center">Total Item Wise</th>
								<?php foreach($subItemList as $row5){?>
									<th class="text-center totalQtyItemWise_<?php echo $row5->id?>"></th>
									<th class="text-center totalItemWise_<?php echo $row5->id?>">-</th>
									<th class="text-center totalItemWise_<?php echo $row5->id?>">-</th>
									<th class="text-center totalItemWise_<?php echo $row5->id?>">-</th>
									<script>
										var sum = 0;
										$('.qtyItemWiseSingle_<?php echo $row5->id?>').each(function() {
											sum += parseInt($(this).text());
										});
										$('.totalQtyItemWise_<?php echo $row5->id?>').text(sum);
										//alert(sum);
									</script>
								<?php }?>
								<th class="text-center"><?php echo $overAllTotalLocationWise?></th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}
	




}
