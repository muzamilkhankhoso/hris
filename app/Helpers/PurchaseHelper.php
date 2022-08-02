<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\SupplierLocationDetail;
class PurchaseHelper
{
    public static function homePageURL()
    {
        return url('/');
    }
	
	public static function reversePurchaseRequestDetailAfterApproval($param1,$param2,$param3){
		CommonHelper::companyDatabaseConnection($param1);
			$countPurchaseOrderDetail = DB::table('purchase_order')->where('purchase_request_no','=',$param2)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($countPurchaseOrderDetail) == '0'){
			if($param3 == '0'){
				$a .= '<a class="btn btn-xs btn-danger" onclick="reversePurchaseRequestDetailAfterApproval(\''.$param1.'\',\''.$param2.'\')">Reverse Purchase Request Detail After Approval</a>';
			}else{
				$a .= '<a class="btn btn-xs btn-danger" onclick="reversePurchaseRequestDetailAndMaterialRequestAfterApproval(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\')">Reverse Purchase Request and Material Request Detail Before Approval</a>';
			}
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function purchaseGoodsReceiptNoteSummaryDetail($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
			$purchaseGoodsReceiptNoteSummaryDetail = DB::table('grn_data')->select('grn_data.po_date','grn_data.category_id','grn_data.sub_item_id','goods_receipt_note.supplier_id','goods_receipt_note.supplier_location_id','grn_data.grn_date','grn_data.rate','grn_data.tqrigc')
			->join('goods_receipt_note', 'grn_data.grn_no', '=', 'goods_receipt_note.grn_no')
			->where('grn_data.grn_no', '=', $param2)
			->where('grn_data.status', '=', '1')
			->get();
		CommonHelper::reconnectMasterDatabase();
		$data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">Category Name</th><th class="text-center">Item Code</th><th class="text-center">Item Name</th><th class="text-center">Receive Qty.</th><th class="text-center">Unit Price</th><th class="text-center">Sub-Total</th></thead><tbody>';
		$counter = 1;
		foreach($purchaseGoodsReceiptNoteSummaryDetail as $row){
			$categoryName = CommonHelper::getCompanyDatabaseTableValueById($param1,'category','main_ic',$row->category_id);;
			$itemCode = CommonHelper::getCompanyDatabaseTableValueById($param1,'subitem','item_code',$row->sub_item_id);
			$itemName = CommonHelper::getCompanyDatabaseTableValueById($param1,'subitem','sub_ic',$row->sub_item_id);
			$data.='<tr><td class="text-center">'.$counter++.'</td><td>'.$categoryName.'</td><td>'.$itemCode.'</td><td>'.$itemName.'</td><td class="text-center">'.$row->tqrigc.'</td><td class="text-center">'.$row->rate.'</td><td class="text-center">'.number_format($row->tqrigc * $row->rate).'</td></tr>';
		}
		$data.='</tbody></table></div></div></div>';
		return $data;
	}
	public static function displayItemWiseLastPurchaseDetail($param1,$param2,$param3){
		$regionList = DB::table('regions')->where('status','=','1')->get();
		$data = '<div class="table-responsive"><table id="buildyourform" class="table table-bordered"><thead><tr><th class="text-center">Region Name</th><th class="text-center">Purchase Date</th><th class="text-center">Location Name</th><th class="text-center">Unit Price</th></tr></thead><tbody>';
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
			$data .= '<tr><td>'.$row->employee_region.'</td>';
			if(empty($displayItemWiseLastPurchaseRate)){
				$data .= '<td colspan="3">Last Purchase Not Available</td><tr>';
			}else{
				$data .= '<td>'.CommonHelper::changeDateFormat($displayItemWiseLastPurchaseRate->po_date).'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($param1,'supplier_location_detail','location_name',$displayItemWiseLastPurchaseRate->supplier_location_id).'</td><td class="text-center">'.$displayItemWiseLastPurchaseRate->rate.'</td><tr>';
			}
		}
		
		$data .= '</tbody></table></div>';
		return $data;
	}
	
	public static function reverseGoodsReceiptNoteDetailBeforeApproval($m,$grnNo){
		CommonHelper::companyDatabaseConnection($m);
			$countJVSDetail = DB::table('jvs')->where('grn_no','=',$grnNo)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($countJVSDetail) == '0'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reverseGoodsReceiptNoteDetailBeforeApproval(\''.$m.'\',\''.$grnNo.'\')">Reverse Goods Receipt Note Detail Before Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function reverseGoodsReceiptNoteDetailAfterApproval($m,$grnNo){
		CommonHelper::companyDatabaseConnection($m);
			$countPVSDetail = DB::table('pvs')->where('grn_no','=',$grnNo)->get();
		CommonHelper::reconnectMasterDatabase();
		$a = '';
		if(count($countPVSDetail) == '0'){
			$a .= '<a class="btn btn-xs btn-danger" onclick="reverseGoodsReceiptNoteDetailAfterApproval(\''.$m.'\',\''.$grnNo.'\')">Reverse Goods Receipt Note Detail After Approval</a>';
		}else{
			$a = '';
		}
		return $a;
	}
	
	public static function displayCreateStoreChallanAndApproveGoodsReceiptNoteForm($m,$lotNo,$grnNo,$grnDate,$categoryId,$subItemId,$supplierId,$grnDataId,$grnId,$poDataId,$regionId,$pageType,$parentCode){
		CommonHelper::companyDatabaseConnection($m);
        $getPurchaseOrderDataDetail = DB::table('purchase_order_data')->where('id','=',$poDataId)->where('category_id','=',$categoryId)->where('sub_item_id','=',$subItemId)->first();
        $getPurchaseRequestDataDetail = DB::table('purchase_request_data')->where('id','=',$getPurchaseOrderDataDetail->purchase_request_data_record_id)->first();
		CommonHelper::reconnectMasterDatabase();
		if($getPurchaseRequestDataDetail->demand_data_id == 0){}else{
		?>
		<form method="POST" action="<?php echo url('pad/createStoreChallanandApproveGoodsReceiptNote?m='.$m.'')?>" accept-charset="UTF-8" id="createStoreChallanandApproveGoodsReceiptNote">
			<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
			<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
			<input type="hidden" readonly name="lot_no" id="lot_no" value="<?php echo $lotNo?>" />
			<input type="hidden" readonly name="grn_no" id="grn_no" value="<?php echo $grnNo?>">
			<input type="hidden" readonly name="grn_date" id="grn_date" value="<?php echo $grnDate?>">
			<input type="hidden" readonly name="category_id" id="category_id" value="<?php echo $categoryId?>">
			<input type="hidden" readonly name="sub_item_id" id="sub_item_id" value="<?php echo $subItemId?>">
			<input type="hidden" readonly name="supplier_id" id="supplier_id" value="<?php echo $supplierId?>">
			<input type="hidden" readonly name="grn_data_id" id="grn_data_id" value="<?php echo $grnDataId?>">
			<input type="hidden" readonly name="grn_id" id="grn_id" value="<?php echo $grnId?>">
			<input type="hidden" readonly name="po_data_id" id="po_data_id" value="<?php echo $poDataId?>">
			<input type="hidden" readonly name="region_id" id="region_id" value="<?php echo $regionId?>">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Receiver Name</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<input type="text" class="form-control" name="receiver_name" id="receiver_name"  placeholder="Receiver Name" value="">
								</div>
							</div>
							<div style="line-height:8px;">&nbsp;</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Issuance Qty.</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<input type="numbers" class="form-control" name="issuance_qty" id="issuance_qty"  placeholder="Issuance Qty." value="">
								</div>
							</div>
							<div style="line-height:8px;">&nbsp;</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
									<button class="btn btn-success btn-update-CSCAndGRN-submit btnSubmit" type="button">Approve G.R.N And Create Store Challan</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<form >
		<?php
		}
	}
	
	public static function getRegionWiseItemOpeningQty($param1,$param2,$param3){
		CommonHelper::companyDatabaseConnection($param1);
        $getRegionWiseItemOpeningQty = DB::table('fara')->where('region_id','=',$param2)->where('sub_ic_id','=',$param3)->where('action','=','1')->sum('qty');
        CommonHelper::reconnectMasterDatabase();
		return $getRegionWiseItemOpeningQty;
	}
	
	public static function checkRegionWiseOpeningQtyAndValuesDetail($param1,$param2,$param3){
		CommonHelper::companyDatabaseConnection($param1);
        $checkRegionWiseOpeningQtyAndValuesDetail = DB::table('fara')->where('sub_ic_id','=',$param2)->where('region_id','=',$param3)->count();
        CommonHelper::reconnectMasterDatabase();
		if($checkRegionWiseOpeningQtyAndValuesDetail != 0){
			return 'disabled';
		}else{
			return '';
		}
	}
	
	public static function masterTableButtonsForSupplier($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        ?>
        <li>
			<a onclick="showDetailModelMasterTable('<?php echo $param1?>','<?php echo $param9?>','<?php echo $param2?>','<?php echo $param3;?>','<?php echo $param4;?>','<?php echo $param5;?>','<?php echo $param6;?>','<?php echo $param10?>')"><span class="glyphicon glyphicon-eye-open"></span> View Profile</a>
		</li>
        <?php if($param2 == 2){?>
            <li>
				<a onclick="repostSupplierWithLocationDetail('<?php echo $param12?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-refresh"></span> Repost </a> 
			</li>
		<?php }else{?>
            <li>
				<a onclick="showMasterTableEditModel('<?php echo $param7?>','<?php echo $param3 ?>','<?php echo $param8 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"></span> Edit </a> 
			</li>
			<li>
				<a onclick="deleteSupplierWithLocationDetail('<?php echo $param11?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-trash"></span> Delete </a> 
			</li>
		<?php }?>

        <?php
    }
	
	public static function totalCoountActiveSupplierLocation($param1,$param2){
		CommonHelper::companyDatabaseConnection($param1);
        $supplierLocationDetail = new SupplierLocationDetail;
        $supplierLocationDetail = $supplierLocationDetail::where('supplier_id','=',$param2)->where('status','=','1')->count();
        CommonHelper::reconnectMasterDatabase();
		$paramOne = "pdc/viewSingleSupplierLocationDetail";
		$paramTwo = $param2;
		$paramThree = "View Supplier Location Detail";
		if($supplierLocationDetail == '0'){
			return $supplierLocationDetail;
		}else{
			return '<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')">'.$supplierLocationDetail.'</a>';
		}
		
	}

    public static function displayApproveDeleteRepostButtonDeliveryChallan($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyDeliveryChallanDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <?php /*?><a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a><?php */?>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyProductionTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
        <?php }
    }

    public static function getExpiryDateFinishGoodsIssuance($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
            $getExpiryDateFinishGoodsIssuance = DB::table('finish_good_issuance_for_sampling_and_quarantine')->where('packing_dispensing_id','=',$param2)->where('packing_dispensing_no','=',$param3)->first();
        CommonHelper::reconnectMasterDatabase();
        return $getExpiryDateFinishGoodsIssuance->finish_good_expiry_date;
    }

    public static function getBatchNoDependPRDNoPRDDataId($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
            $getBatchNoDependPRDNoPRDDataId = DB::table('production_request_data')->where('id','=',$param3)->where('production_request_no','=',$param2)->where('status','=','1')->first();
        CommonHelper::reconnectMasterDatabase();
        return $getBatchNoDependPRDNoPRDDataId->assign_batch_no;
    }

    public static function getPackingMaterialDispensingDetail($param1,$param2,$param3,$param4,$param5,$param6){
        CommonHelper::companyDatabaseConnection($param1);
        $getPackingMaterialDispensingDetail = DB::table('prd_pack_size_data')->select('prd_pack_size_data.id','prd_pack_size_data.production_request_no','prd_pack_size_data.production_request_date','prd_pack_size_data.production_request_data_id','prd_pack_size_data.batch_size_id','prd_pack_size_data.pack_size_id','prd_pack_size_data.pack_size_qty','prd_pack_size_data.pack_size_bulk_consume','prd_pack_size_data.packing_dispensing_status','prd_pack_size_data.packing_material_dispensing_status','packing_material_dispensing.packing_dispensing_no','packing_material_dispensing.packing_dispensing_date','packing_material_dispensing.packing_material_dispensing_status','packing_material_dispensing.status')
        ->join('packing_material_dispensing','prd_pack_size_data.id','=','packing_material_dispensing.prd_pack_size_data_id')
        ->where('prd_pack_size_data.production_request_data_id','=',$param2)->get();
        CommonHelper::reconnectMasterDatabase();
        $data = '';
        foreach ($getPackingMaterialDispensingDetail as $row) {
            $packSize = CommonHelper::getMasterTableValueById($param1,'pack_size','pack_size_name',$row->pack_size_id);
            $data.='<tr><td class="text-center">'.$packSize.'</td><td class="text-center">'.$row->packing_dispensing_no.'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->packing_dispensing_date).'</td><td class="text-center">'.static::checkVoucherStatus($row->packing_material_dispensing_status,$row->status).'</td></tr>';
        }
        return $data;
    }

    public static function packingDispensingButtonList($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        CommonHelper::companyDatabaseConnection($param1);
            $getPackingDispensingRecorderList = DB::table('prd_pack_size_data')->where('production_request_no','=',$param5)->where('production_request_data_id','=',$param6)->where('batch_size_id','=',$param2)->get();
        CommonHelper::reconnectMasterDatabase();
        $packSizeRecorde = '';
        foreach ($getPackingDispensingRecorderList as $row) {
            CommonHelper::companyDatabaseConnection($param1);
            $getPackingMaterialDispensingDetail = DB::table('packing_material_dispensing')->where('prd_pack_size_data_id','=',$row->id)->first();
            CommonHelper::reconnectMasterDatabase();

            $packSize = CommonHelper::getMasterTableValueById($param1,'pack_size','pack_size_name',$row->pack_size_id);
            $batchSize = CommonHelper::getMasterTableValueById($param1,'batch_size','batch_size_name',$row->batch_size_id);
            
            $paramTwo = $row->id.'<*>'.$row->production_request_no.'<*>'.$row->batch_size_id.'<*>'.$row->pack_size_id.'<*>'.$row->pack_size_qty.'<*>'.$row->pack_size_bulk_consume.'<*>'.$packSize.'<*>'.$batchSize.'<*>'.$param3.'<*>'.$param7.'<*>'.$param8.'<*>'.$row->production_request_data_id.'<*>'.$row->production_request_date;
            if($row->packing_dispensing_status == '1'){
                $paramOne = "pdc/addPackingMaterialDispensingForm";
                $paramThree = "Packing Material Dispensing Form";
                $packSizeRecorde .= '<li><a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"><span class="glyphicon glyphicon-edit"></span> Add '.$packSize.' Pack Size P.M.D.D</a></li>';    
            }else if($row->packing_dispensing_status == '2'){
                $paramOne = "pdc/viewPackingMaterialVoucherDetail";
                $paramThree = "View Packing Material Dispensing Detail";
                $packSizeRecorde .= '<li><a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"><span class="glyphicon glyphicon-eye-open"></span> View '.$packSize.' Pack Size P.M.D.D</a></li>';    
            }

            if($row->packing_material_dispensing_status == '1' && $row->packing_dispensing_status == '2'){
                $paramAbcOne = $row->id.'<*>'.$row->production_request_no.'<*>'.$row->batch_size_id.'<*>'.$row->pack_size_id.'<*>'.$row->pack_size_qty.'<*>'.$row->pack_size_bulk_consume.'<*>'.$packSize.'<*>'.$batchSize.'<*>'.$param3.'<*>'.$param7.'<*>'.$param8.'<*>'.$row->production_request_data_id.'<*>'.$row->production_request_date.'<*>'.$getPackingMaterialDispensingDetail->bom_no.'<*>'.$getPackingMaterialDispensingDetail->bom_pack_no.'<*>'.$getPackingMaterialDispensingDetail->packing_dispensing_no;
                $param4 = "purchase/editPackingMaterialVoucherDetailForm";
                $param6 = "Edit Packing Material Dispensing Voucher Detail Form";
                
                $packSizeRecorde .= '<li><a onclick="showDetailModelOneParamerter(\''.$param4.'\',\''.$paramAbcOne.'\',\''.$param6.'\')"><span class="glyphicon glyphicon-edit"></span> Edit '.$packSize.' Pack Size P.M.D.D</a></li>';   


            }
            
        }
        return $packSizeRecorde;
    }
    
    public static function checkBomStatusProductWise($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
            $checkBomStatusProductWise = DB::table('bom')->where('bom_no','=',$param2)->first();
        CommonHelper::reconnectMasterDatabase();
        return $checkBomStatusProductWise->bom_status;
    }

    public static function getTotalReceivedQuantityByPoNoAndPoDataId($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
            $getTotalReceivedQuantityByPoNoAndPoDataId = DB::table('grn_data')->where('sub_item_id','=',$param4)->where('po_no','=',$param3)->where('status','=','1')->orWhere('po_data_id','=',$param2)->get();
        CommonHelper::reconnectMasterDatabase();
        $a = 0;
        foreach($getTotalReceivedQuantityByPoNoAndPoDataId as $row){
            $tqrigc = $row->tqrigc + $row->loss_quantity;
            $a += $tqrigc;
        }
        echo $a;
    }


    public static function displayApproveDeleteRepostButtonAdditionalRequestMaterialDispensing($param1,$param2,$param3,$param4){
        if($param3 == 1){
            ?>
            <?php
                if($param4 == '1'){
            ?>
                    <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseAdditionalRequestRawMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-ok"></span> Approve Additional Request Raw Material Dispensing Voucher
                    </a>

                    <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseAdditionalRequestRawMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-trash"></span> Delete Additional Request Raw Material Dispensing Voucher
                    </a>
            <?php 
                }else if($param4 == '2'){
            ?>
                    <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseAdditionalRequestPackingMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-ok"></span> Approve Additional Request Packing Material Dispensing Voucher
                    </a>

                    <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseAdditionalRequestPackingMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-trash"></span> Delete Additional Request Packing Material Dispensing Voucher
                    </a>
            <?php }?>
        <?php }else if($param3 == 3){?>
            <?php 
                if($param4 == '1'){
            ?>
                    <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseAdditionalRequestRawMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-edit"></span> Repost Additional Request Raw Material Dispensing Voucher
                    </a>
            <?php 
                }else if($param4 == '2'){
            ?>
                    <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseAdditionalRequestPackingMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param4?>')">
                        <span class="glyphicon glyphicon-edit"></span> Repost Additional Request Packing Material Dispensing Voucher
                    </a>
            <?php 
                }
            ?>
        <?php }
    }

    public static function displayApproveDeleteRepostButtonRawMaterialDispensing($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8){
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseRawMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Raw Material Dispensing Voucher
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseRawMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7?>','<?php echo $param8?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Raw Material Dispensing Voucher
            </a>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
        <?php }
    }

    public static function displayApproveDeleteRepostButtonPackingMaterialDispensing($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchasePackingMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Packing Material Dispensing Voucher
            </a>
            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchasePackingMaterialDispensing('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7?>','<?php echo $param8?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Packing Material Dispensing Voucher
            </a>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
        <?php }
    }

    public static function totalDispensingLotNoAndDispenseQtyAgainstSubItemIDandProductionRequestDataID($param1,$param2,$param3,$param4,$param5){
        CommonHelper::companyDatabaseConnection($param1);
            if($param5 == 'raw'){
            $assignMaterialDispensingLotNo = DB::table('assign_raw_material_dispensing_lot_no')->where('raw_material_dispensing_data_id','=',$param2)->where('raw_dispensing_no','=',$param3)->get();
            }else if($param5 == 'packing'){
            $assignMaterialDispensingLotNo = DB::table('assign_packing_material_dispensing_lot_no')->where('packing_material_dispensing_data_id','=',$param2)->where('packing_dispensing_no','=',$param3)->get();    
            }
        CommonHelper::reconnectMasterDatabase();
        $a = '';
        $b = 0;
        if($param4 == 'lot_no'){
            foreach($assignMaterialDispensingLotNo as $row){
                $lotNo = $row->$param4;
                $countLotNoLength = strlen($lotNo);
                if($countLotNoLength == 4){
                    $makeLotNo = substr_replace($lotNo, '00' . substr($lotNo, -1), -1);
                }else if($countLotNoLength == 5){
                    $makeLotNo = substr_replace($lotNo, '0' . substr($lotNo, -2), -2);
                }else{
                    $makeLotNo = $lotNo;
                }
                $a .= '<span>'.$makeLotNo.'</span><br />';
            }
            return $a;
        }else{
            foreach($assignMaterialDispensingLotNo as $row){
                $b += $row->$param4;
                $a .= '<span>'.$row->$param4.'</span><br />';
            }
            return $a.'<*>'.$b;    
        }
        
    }

    public static function displayDispensingButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        CommonHelper::companyDatabaseConnection($param1);
            $checkDispensingDetail = DB::table('production_request_data')->where('id','=',$param4)->where('production_request_no','=',$param2)->where('status','=','1')->first();
        CommonHelper::reconnectMasterDatabase();
        $a = '';
        $param14 = 'pdc/addPackingMaterialDispensingForm';
        $param15 = 'Packing Material Dispensing Form';
        if($checkDispensingDetail->raw_material_issuance_status == 1){
            $param16 = 'pdc/addRawMaterialDispensingForm';
            $param12 = $param2.'<*>'.$param3.'<*>'.$param4.'<*>'.$param5.'<*>'.$param6.'<*>'.$param7.'<*>'.$param8.'<*>'.$param9.'<*>'.$param10.'<*>'.$param11;
            $param13 = 'Raw Material Dispensing Form';
            $a .= '<li><a onclick="showMasterTableEditModel(\''.$param16.'\',\''.$param12.'\',\''.$param13.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"> </span> Add R.M.D.D</a></li>';
        }else{
            $param16 = 'pdc/viewRawMaterialVoucherDetail';
            $param12 = $param11.'<*>'.$param3.'<*>'.$param5.'<*>'.$param6;
            $param13 = 'Raw Material Dispensing Detail';

            $a .= '<li><a onclick="showMasterTableEditModel(\''.$param16.'\',\''.$param12.'\',\''.$param13.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-eye-open"> </span> View R.M.D.D</a></li>';
        }
        //if($checkDispensingDetail->packing_material_issuance_status == 1){
            //$a .= '<li><a onclick="showMasterTableEditModel(\''.$param14.'\',\''.$param12.'\',\''.$param15.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Add P.M.D.D</a></li>';
        //}

        return $a;
    }

    public static function getOpeningQuantityForSubItem($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
            $getOpeningQuantityForSubItem = DB::table('fara')->where('sub_ic_id','=',$param2)->where('status','=','1')->first();
        CommonHelper::reconnectMasterDatabase();

        return $getOpeningQuantityForSubItem->qty;
    }

    public static function getAllProductNameUsingProductionNoAndProductionRequestDataID($param1,$param2,$param3,$param4){

    }

    public static function purchaseOrderNumberByPurchaseRequestNo($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $getPurchaseOrderDetail = DB::table('purchase_order')->select('purchase_order_no','purchase_order_date')->where('purchase_request_no','=',$param2)->get();
        $a = '';
        foreach ($getPurchaseOrderDetail as $row) {
            $title = 'View Purchase Order Detail';
            $pageLink = 'pdc/viewPurchaseOrderVoucherDetail';
            $a .= '<a onclick="showDetailModelOneParamerter(\''.$pageLink.'\',\''.$row->purchase_order_no.'\',\''.$title.'\')">PO No =>'.$row->purchase_order_no.' => '.'PO Date =>'.CommonHelper::changeDateFormat($row->purchase_order_date).'</a><br />';
        }
        CommonHelper::reconnectMasterDatabase();
        return $a;
    }
	
	public static function demandDetailByDemandId($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $getDemandDetail = DB::table('demand')->select('demand_no','demand_date')->where('id','=',$param2)->get();
        $a = '';
        foreach ($getDemandDetail as $row) {
            $title = 'View Material Request Voucher Detail';
            $pageLink = 'stdc/viewStockDemandRequestVoucherDetail';
            $a .= '<a onclick="showDetailModelOneParamerter(\''.$pageLink.'\',\''.$row->demand_no.'\',\''.$title.'\')">M.R. No. =>'.$row->demand_no.' => '.'M.R. Date =>'.CommonHelper::changeDateFormat($row->demand_date).'</a><br />';
        }
        CommonHelper::reconnectMasterDatabase();
        return $a;
    }

    public static function purchaseOrderNumberByGRNNo($param1,$param2,$param3){
        return $param1.'-'.$param2.'-'.$param3;
        CommonHelper::companyDatabaseConnection($param1);
        $getPurchaseOrderDetail = DB::table('purchase_order')->select('purchase_order_no','purchase_order_date')->where('purchase_request_no','=',$param2)->get();
        $a = '';
        foreach ($getPurchaseOrderDetail as $row) {
            $title = 'View Purchase Order Detail';
            $pageLink = 'pdc/viewPurchaseOrderVoucherDetail';
            $a .= '<a onclick="showDetailModelOneParamerter(\''.$pageLink.'\',\''.$row->purchase_order_no.'\',\''.$title.'\')">PO No =>'.$row->purchase_order_no.' => '.'PO Date =>'.CommonHelper::changeDateFormat($row->purchase_order_date).'</a><br />';
        }
        CommonHelper::reconnectMasterDatabase();
        return $a;
    }

    public static function SystemGeneratedLotNoList($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $getLotNoList = DB::table('fara')->select('lot_no')->where('sub_ic_id','=',$param2)->where('lot_no','!=','')->get();
        $a = '';
        foreach ($getLotNoList as $row) {
            $lotNo = $row->lot_no;
            $countLotNoLength = strlen($lotNo);
            if($countLotNoLength == 4){
                $makeLotNo = substr_replace($lotNo, '00' . substr($lotNo, -1), -1);
            }else if($countLotNoLength == 5){
                $makeLotNo = substr_replace($lotNo, '0' . substr($lotNo, -2), -2);
            }else{
                $makeLotNo = $lotNo;
            }
            $selectedLotNo = '';
            if($param3 == $row->lot_no){
                $selectedLotNo = 'selected';
            }
            $a .= '<option value="'.$row->lot_no.'"'.$selectedLotNo.'>'.$makeLotNo.'</option>';
        }
        CommonHelper::reconnectMasterDatabase();
        return $a;
    }

    public static function getSaleTaxHeadUsingPurchaseOrderNo($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $getPurchaseRequestNo = DB::table('purchase_order_data')->where('purchase_order_no','=',$param2)->where('category_id','=',$param3)->where('sub_item_id','=',$param4)->first();
        CommonHelper::reconnectMasterDatabase();
        return $getPurchaseRequestNo->sale_tax_head;
    }

    public static function getPurchaseRequestNoUsingPurchaseOrderNo($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $getPurchaseRequestNo = DB::table('purchase_order_data')->where('purchase_order_no','=',$param2)->where('category_id','=',$param3)->where('sub_item_id','=',$param4)->first();
        CommonHelper::reconnectMasterDatabase();
        return $getPurchaseRequestNo->purchase_request_no;
    }

    public static function getPurchaseRequestDateUsingPurchaseOrderNo($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $getPurchaseRequestDate = DB::table('purchase_order_data')->where('purchase_order_no','=',$param2)->where('category_id','=',$param3)->where('sub_item_id','=',$param4)->first();
        CommonHelper::reconnectMasterDatabase();
        return $getPurchaseRequestDate->purchase_request_date;
    }

    public static function makeSystemGeneratedGrnLotNo($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $str = DB::selectOne("select max(convert(substr(`lot_no`,4,length(substr(`lot_no`,4))-4),signed integer)) reg from `grn_data` where substr(`lot_no`,-2,2) = ".date('y')."")->reg;
        $checkStrNumber = $str + 1;
        if ($checkStrNumber >= 1 && $checkStrNumber <= 9) {
            $concateValue = $str.'00';
            if($str == 0){
                $newStrNumber = '001';
            }else {
                $newStrNumber = $concateValue + 1;
            }
            $makeNewGrnLotNo = 'R' . date('y') . ($newStrNumber);
        }else {
            $makeNewGrnLotNo = 'R' . date('y') . ($str);
        }
        //$makeNewGrnLotNo = DB::selectOne('select `lot_no` from `accounts` where `id` = '.$param1.'')->name;
        //'R18001';
        CommonHelper::reconnectMasterDatabase();
        echo $makeNewGrnLotNo;
    }

    public static function categoryList($param1,$param2){
        echo '<option value="">Select Category</option>';
        CommonHelper::companyDatabaseConnection($param1);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['status', '=', '1'], ])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        foreach($categoryList as $row){
            ?><option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['main_ic'];?></option><?php
        }
    }

    public static function subItemList($param1,$param2,$param3){
        echo '<option value="">Select Sub Item</option>';
		CommonHelper::companyDatabaseConnection($param1);
        $subItemList = DB::table('subitem')->select('id','item_code','sub_ic')->where('status','=','1')->where('main_ic_id','=',$param3)->get();
		CommonHelper::reconnectMasterDatabase();
        foreach($subItemList as $row){
            ?>
            <option value="<?php echo $row->id;?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->item_code.' <--> '.$row->sub_ic;?></option>
            <?php
        }
    }
	
	public static function subItemListTwo($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $subItemList = DB::table('subitem')->select('id','item_code','sub_ic')->where('status','=','1')->where('main_ic_id','=',$param3)->get();
		CommonHelper::reconnectMasterDatabase();
        foreach($subItemList as $row){
            ?>
            <option value="<?php echo $row->id;?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->item_code.' <--> '.$row->sub_ic;?></option>
            <?php
        }
    }
	
	public static function customerLocationListRegionWise($param1,$param2,$param3){
		CommonHelper::companyDatabaseConnection($param1);
        $customerLocationList = DB::table('customer_location')->select('id','customer_id','location_name')->where('status','=','1')->where('region_id','=',$param3)->get();
		CommonHelper::reconnectMasterDatabase();
        foreach($customerLocationList as $row){
            ?>
            <option value="<?php echo $row->id;?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->location_name;?></option>
            <?php
        }
	}

    public static function checkVoucherStatus($param1,$param2){
        if($param1 == 1 && $param2 == 1){
            return 'Pending';
        }else if($param2 == 2){
            return 'Deleted';
        }else if($param1 == 2 && $param2 == 1){
            return 'Approve';
        }
    }

    public static function displayApproveDeleteRepostButtonOneTable(){

    }

    public static function displayApproveDeleteRepostButtonTwoTable($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a>
            <?php
        }else if($param3 == 2 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
            <?php
        }
    }

    public static function displayApproveDeleteRepostButtonGoodsReceiptNote($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        $param1.' - '.$param2.' - '.$param3.' - '.$param4.' - '.$param5.' - '.$param6.' - '.$param7.' - '.$param8.' - '.$param9;
        if($param3 == 1 && $param2 == 1){
            ?>
            <a class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyPurchaseGoodsReceiptNote('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>','<?php echo $param11;?>','<?php echo $param12;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
            </a>

            <?php /*?><a class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
            </a><?php */?>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <a class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyPurchaseGoodsReceiptNoteDetail('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>','<?php echo $param11;?>','<?php echo $param12;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
            </a>
        <?php }
    }

    public static function displayPurchaseRequestVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<li><a onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Edit </a></li>
            <li><a onclick="deleteCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
        }else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostCompanyPurchaseTwoTableRecords(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\')"><span class="glyphicon glyphicon-edit"></span> Repost</a></li>';
        }
    }

    public static function displayGoodsReceiptNoteVoucherListButton($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12,$param13){
        $tableOne = $param8;
        $tableTwo = $param9;
        if($param3 == 1 && $param2 == 1){
            return '<li class="hidden"><a onclick="showMasterTableEditModel(\''.$param10.'\',\''.$param4.'\',\''.$param11.'\',\''.$param1.'\')"><span class="glyphicon glyphicon-edit"></span> Edit</a></li><li><a onclick="deleteCompanyPurchaseGoodsReceiptNoteDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\',\''.$param12.'\',\''.$param13.'\')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>';
        }else if($param3 == 2 && $param2 == 1){
            return '<li><a onclick="repostCompanyPurchaseGoodsReceiptNoteDetail(\''.$param1.'\',\''.$param2.'\',\''.$param3.'\',\''.$param4.'\',\''.$param5.'\',\''.$param6.'\',\''.$param7.'\',\''.$param8.'\',\''.$param9.'\',\''.$param12.'\',\''.$param13.'\')"><span class="glyphicon glyphicon-edit"></span> Repost</a></li>';
        }
    }



    public static function changeActionButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11){
        $tableOne = $param8;
        $tableTwo = $param9;
        ?>
        <?php if($param3 == 1 && $param2 == 1){?>
            <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param10;?>','<?php echo $param4 ?>','<?php echo $param11 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> P</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-trash"> P</span>
            </button>
        <?php }else if($param3 == 2 && $param2 == 1){?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyPurchaseTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $param8;?>','<?php echo $param9;?>')">
                <span class="glyphicon glyphicon-edit"> P</span>
            </button>
        <?php }?>


        <?php if($param3 != 2 && $param2 == 2){?>
            <button class="edit-modal btn btn-xs btn-info hidden" onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> A</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger btn-xs hidden" onclick="deleteCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')">
                <span class="glyphicon glyphicon-trash"> A</span>
            </button>
        <?php }else if($param3 == 2 && $param2 == 2){?>
            <button class="delete-modal btn btn-xs btn-warning btn-xs hidden" onclick="repostCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')">
                <span class="glyphicon glyphicon-edit"> A</span>
            </button>
        <?php }?>
        <?php
    }


    public static function getCreditAccountHeadNameForInvoice($param1,$param2){
        CommonHelper::companyDatabaseConnection($param2);
        $accountName = DB::selectOne('select `name` from `accounts` where `id` = '.$param1.'')->name;
        CommonHelper::reconnectMasterDatabase();
        return $accountName;
    }

    public static function purchasePaymentVoucherSummaryDetail($param1,$param2){
        CommonHelper::companyDatabaseConnection($param1);
        $result = \DB::table("pv_data")
            ->select("pv_data.pv_no","pv_data.amount","pv_data.acc_id","pv_data.debit_credit","pv_data.id","pvs.pv_no","pvs.grn_no")
            ->join('pvs','pv_data.pv_no','=','pvs.pv_no')
            ->where(['pvs.grn_no' => $param2,'pvs.status' => '1','pvs.pv_status' => '2','pvs.pv_status' => '2','pv_data.debit_credit' => '0'])
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">PV No</th><th class="text-center">Account Head</th><th class="text-center col-sm-3">Amount</th></thead><tbody>';
        $counter = 1;
        $totalPaymentAmount = 0;
        foreach($result as $row){
            $totalPaymentAmount += $row->amount;
            $data .='<tr><td class="text-center">'.$counter++.'</td>';
            $data .='<td class="text-center">'.$row->pv_no.'</td>';
            $data .='<td class="text-center">'.static::getCreditAccountHeadNameForInvoice($row->acc_id,$param1).'</td>';
            $data .='<td class="text-right">'.$row->amount.'</td></tr>';
        }
        $data.='</tbody></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" readonly name="totalPaymentAmount" id="totalPaymentAmount" value="'.$totalPaymentAmount.'" class="form-control" /></div></div>';
        return $data;
    }

    public static function monthWisePurchaseActivitySupplierWise($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        $supplierId = $param2;
        $monthStartDate = date(''.$param3.'-01');
        $monthEndDate   = date(''.$param3.'-t');
        $resultFara = DB::table('fara')
            ->select('grn_no','grn_date','main_ic_id','sub_ic_id','supp_id','qty','price','value','action')
            ->where('supp_id','=',$param2)
            ->whereBetween('date', array($monthStartDate, $monthEndDate))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list">
                        <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Item Name</th>
                            <th class="text-center">GRN. No.</th>
                            <th class="text-center">GRN. Date</th>
                            <th class="text-center">Qty.</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $counter = 1;
                        $totalPurchaseAmount = 0;
                        foreach($resultFara as $row){
                            $totalPurchaseAmount += $row->value;
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($param1,'category','main_ic',$row->main_ic_id);?></td>
                                <td class="text-center"><?php echo CommonHelper::getCompanyDatabaseTableValueById($param1,'subitem','sub_ic',$row->sub_ic_id);?></td>
                                <td class="text-center"><?php echo $row->grn_no;?></td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                                <td class="text-center"><?php echo $row->qty;?></td>
                                <td class="text-center"><?php echo $row->value/$row->qty;?></td>
                                <td class="text-right"><?php echo $row->value;?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="7">Total Amount</td>
                            <td class="text-right"><?php echo $totalPurchaseAmount;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public static function monthWisePaymentVoucherActivitySupplierWise($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
        $supplierId = $param2;
        $monthStartDate = date(''.$param3.'-01');
        $monthEndDate   = date(''.$param3.'-t');
        $accId = $param4;
        $result = \DB::table("pv_data")
            ->select("pv_data.pv_no","pv_data.pv_date","pv_data.amount","pv_data.acc_id","pv_data.debit_credit","pv_data.id","pvs.pv_no","pvs.grn_no","pvs.cheque_no","pvs.cheque_date","pvs.voucherType")
            ->join('pvs','pv_data.pv_no','=','pvs.pv_no')
            ->where(['pv_data.acc_id' => $accId,'pvs.status' => '1','pvs.pv_status' => '2'])
            ->whereBetween('pv_data.pv_date', array($monthStartDate, $monthEndDate))
            ->get();
        CommonHelper::reconnectMasterDatabase();
        $data='';
        $data.='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="table-responsive"><table class="table table-bordered sf-table-list"><thead><th class="text-center">S.No</th><th class="text-center">PV No</th><th class="text-center">PV Date</th><th class="text-center">Cheque No</th><th class="text-center">Cheque Date</th><th class="text-center col-sm-3">Amount</th></thead><tbody>';
        $counter = 1;
        $totalPaymentAmount = 0;
        foreach($result as $row){
            $totalPaymentAmount += $row->amount;
            $data .='<tr><td class="text-center">'.$counter++.'</td>';
            $data .='<td class="text-center">'.$row->pv_no.'</td>';
            $data .='<td class="text-center">'.CommonHelper::changeDateFormat($row->pv_date).'</td>';
            if($row->voucherType == 4 || $row->voucherType == 2){
                $data .='<td class="text-center">'.$row->cheque_no.'</td>';
                $data .='<td class="text-center">'.CommonHelper::changeDateFormat($row->cheque_date).'</td>';
            }else{
                $data .='<td class="text-center">---</td>';
                $data .='<td class="text-center">---</td>';
            }
            $data .='<td class="text-right">'.$row->amount.'</td></tr>';
        }
        $data.='</tbody></table></div></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><input type="hidden" readonly name="totalPaymentAmount" id="totalPaymentAmount" value="'.$totalPaymentAmount.'" class="form-control" /></div></div>';
        return $data;
    }

    public static function getAllProductNameUsingProductionNoAndFGI($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
        if(empty($param3)){
            $getAllProductNameUsingProductionNoAndFGI = DB::table('production_request_data')->select('id','finish_good_bulk_id','pack_size_id','batch_size_id','production_machine_id')->where('production_request_no','=',$param2)->groupBy('id')->get();
        }else{
            $getAllProductNameUsingProductionNoAndFGI = DB::table('production_request_data')->select('id','finish_good_bulk_id','pack_size_id','batch_size_id','production_machine_id')->where('production_request_no','=',$param2)->where('finish_good_bulk_id','=',$param3)->groupBy('id')->get();
        }
        $a = '';

        foreach ($getAllProductNameUsingProductionNoAndFGI as $row) {
            $paramOne = "pdc/viewRawMaterialDetailProductWise";
            $paramTwo = $param2.'<*>'.$row->id.'<*>'.$row->finish_good_bulk_id.'<*>'.$row->pack_size_id.'<*>'.$row->batch_size_id.'<*>'.$row->production_machine_id;
            $paramThree = "View Raw Material and Packing Material Detail Product Wise";
            CommonHelper::reconnectMasterDatabase();
            $packSize = CommonHelper::getMasterTableValueById($param1,'pack_size','pack_size_name',$row->pack_size_id);
            $batchSize = CommonHelper::getMasterTableValueById($param1,'batch_size','batch_size_name',$row->batch_size_id);
            $productName = CommonHelper::getCompanyDatabaseTableValueById($param1,'subitem','sub_ic',$row->finish_good_bulk_id);
            CommonHelper::companyDatabaseConnection($param1);
            $a .= '<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success">Product Name => '.$productName.' => Batch Size No => '.$batchSize.' => '.'Pack Size No => '.$packSize.'</a><div style="line-height: 5px;">&nbsp;</div>';
        }
        CommonHelper::reconnectMasterDatabase();
        return $a;
    }
}
?>