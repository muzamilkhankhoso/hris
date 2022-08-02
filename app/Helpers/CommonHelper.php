<?php
namespace App\Helpers;
use App\Models\Regions;
use DB;
use Config;
use Auth;
use Illuminate\Support\Facades\App;
use Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\MenuPrivileges;
use App\Models\Employee;

class CommonHelper{

    public static function changeDateFormat($param1){
        $date = date_create($param1);
        return date_format($date,"d-M-Y");
    }
    public static function changeDateFormatWithoutYear($param1){
        $date = date_create($param1);
        return date_format($date,"d-M");
    }
    public static function trim_all( $str , $what = NULL , $with = ' ' ){
        if( $what === NULL ){
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }

    public static function number_to_word( $num = '' ){
        $num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) ){
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );

            foreach( $num_levels as $num_part ){
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                if( $tens < 20 ){
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }else{
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }
            $commas = count( $words );
            if( $commas > 1 ){
                $commas = $commas - 1;
            }
            $words  = implode( ', ' , $words );
            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , static::trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas ){
                $words  = str_replace_last( ',' , ' and' , $words );
            }
            return $words;
        }else if( ! ( ( int ) $num ) ){
            return 'Zero';
        }
        return '';
    }


    public static function companyDBName($param1){
        return $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
    }



    public static function companyDatabaseConnection($param1){
        static::reconnectMasterDatabase();
        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
	}

    public static function reconnectMasterDatabase(){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
    }



    public static function displayExportButton($param1,$param2,$param3){
        ?>
        <button style="color:white;" class="btn btn-sm btn-warning btn-export" onclick="exportView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
        <i class="fas fa-print"></i>&nbsp; Export CSV
        </button>
        <?php
    }

    public static function displayPrintButtonInView($param1,$param2,$param3){
        ?>
        <button type="button" class="btn btn-sm btn-info btn-rounded" onclick="printView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Print
        </button>
        <?php
    }

    public static function displayPrintButtonInBlade($param1,$param2,$param3){
        ?>
        
        <button type="button" class="btn btn-sm btn-info" onclick="printView('<?php echo $param1?>','<?php echo $param2?>','<?php echo $param3?>')" style="<?php echo $param2;?>">
            <i class="fas fa-print"></i>&nbsp; Print
        </button>
        <?php
    }

    public static function getCompanyName($param1){
        static::reconnectMasterDatabase();
        echo $companyName = DB::selectOne('select `name` from `company` where `id` = '.$param1.'')->name;
    }

    public static function headerPrintSectionInPrintView($param1){
        $current_date = date('Y-m-d');
        ?>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <label class="heading">Printed On Date&nbsp;:&nbsp;</label><label class="heading"><?php echo static::changeDateFormat($current_date);?></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                         >
                        <label class="headingCompanyName"><?php echo static::getCompanyName($param1);?></label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                <label class="heading" >Printed On Day&nbsp;:&nbsp;</label><label class="heading"><?php echo '&nbsp;'.$nameOfDay;?></label>

            </div>
        </div>
        <div style="line-height:5px;">&nbsp;</div>

        <?php
    }

    public static function masterTableButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        ?>
        <a onclick="showDetailModelMasterTable('<?php echo $param1?>','<?php echo $param9?>','<?php echo $param2?>','<?php echo $param3;?>','<?php echo $param4;?>','<?php echo $param5;?>','<?php echo $param6;?>','<?php echo $param10?>')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
        <?php if($param2 == 2){?>
            <button class="delete-modal btn btn-xs btn-primary" onclick="repostCompanyMasterTableRecord('<?php echo $param12?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')">
                <span class="glyphicon glyphicon-refresh"> Repost</span>
            </button>

        <?php }else{?>
            <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param7?>','<?php echo $param3 ?>','<?php echo $param8 ?>','<?php echo $param1?>')">
                <span class="glyphicon glyphicon-edit"> Edit</span>
            </button>
            <button class="delete-modal btn btn-xs btn-danger" onclick="deleteCompanyMasterTableRecord('<?php echo $param11?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')">
                <span class="glyphicon glyphicon-trash"> Delete</span>
            </button>
        <?php }?>
        <?php
    }

    public static function checkMasterTableVoucherDetailStatus($param1){
        if($param1 == 1){
            echo 'Active';
        }else if($param1 == 0){
            echo 'In-Active';
        }
    }

    public static function voucherStatusSelectList(){
        return '<option value="0">All Vouchers</option><option value="1">Pending Vouchers</option><option value="2">Approve Vouchers</option><option value="3">Delete Vouchers</option>';
    }

    public static function accountHeadSelectList($param1){
        static::companyDatabaseConnection($param1);
        $accountList = DB::table('accounts')->get();
        static::reconnectMasterDatabase();
        ?>
        <datalist id="selectAccountHead">
            <?php foreach ($accountList as $row){?>
            <option data-id="<?php echo $row->id;?>" value="<?php echo $row->name;?>">
                <?php }?>
        </datalist>
        <?php
    }

   
    public static function getMasterTableValueByIdTwo($param1,$param2,$param3,$param4){
        static::reconnectMasterDatabase();
		$detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
		static::companyDatabaseConnection($param1);
		return $detailName;
	}
	
	public static function getMasterTableValueById($param1,$param2,$param3,$param4){
		
        //return $param1.'P1--'.$param2.'P2--'.$param3.'P3--'.$param4.'P4--';
        return $detailName = DB::selectOne('select '.$param3.' from ' .$param2.' where company_id = '.$param1.' and id = '.$param4.'' )->$param3;
    }

    public static function getMasterTableValueByIdWithoutCompanyId($param1,$param2,$param3){
        return $detailName = DB::selectOne('select  '.$param2.' from ' .$param1.' where id = '.$param3.'' )->$param2;
    }



    public static function getCompanyDatabaseTableValueById($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where id = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where id = '.$param4.'' )->$param3;
        else:
            $detailName = '<span style="color:red">Deleted</span>';
        endif;
        static::reconnectMasterDatabase();
        return $detailName;
    }


    public static function getCompanyDatabaseTableValueByColumnNameValue($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where '.$param4.' = '.$param5.'' )->$param3;
        static::reconnectMasterDatabase();
        return $detailName;
    }

    public static function categoryList($param1,$param2){
        echo '<option value="">Select Category</option>';
        static::companyDatabaseConnection($param1);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['status', '=', '1'], ])->orderBy('id')->get();
        static::reconnectMasterDatabase();
        foreach($categoryList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['main_ic'];?></option>
            <?php
        }
    }

    public static function uomList($param1,$param2){
        echo '<option value="">Select UOM</option>';
        $uomList = DB::table('uom')->where([['status', '=', '1'],['company_id', '=', $param1], ])->orderBy('id')->get();
        foreach($uomList as $row){?>
            <option value="<?php echo $row->id;?>" <?php if($param2 == $row->id){echo 'selected';}?>><?php echo $row->uom_name;?></option><?php
        }
    }

    public static function subItemList($param1,$param2,$param3){
        echo '<option value="">Select Item</option>';
        static::companyDatabaseConnection($param1);
        $subItemList = new Subitem;
        $subItemList = $subItemList::where([['status', '=', '1'],['main_ic_id', '=', $param3], ])->orderBy('id')->get();
        static::reconnectMasterDatabase();
        foreach($subItemList as $row){
            ?>
            <option value="<?php echo $row['id'];?>" <?php if($param2 == $row['id']){echo 'selected';}?>><?php echo $row['sub_ic'];?></option>
            <?php
        }
    }

    public static function newMasterTableButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9,$param10,$param11,$param12){
        ?>
        <li><a onclick="showDetailModelMasterTable('<?php echo $param1?>','<?php echo $param9?>','<?php echo $param2?>','<?php echo $param3;?>','<?php echo $param4;?>','<?php echo $param5;?>','<?php echo $param6;?>','<?php echo $param10?>')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
        <?php if($param2 == 2){?>
            <li><a onclick="repostCompanyMasterTableRecord('<?php echo $param12?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-eye-open"></span> Repost</a></li>
        <?php }else{?>
            <li><a onclick="showMasterTableEditModel('<?php echo $param7?>','<?php echo $param3 ?>','<?php echo $param8 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
            <li><a onclick="deleteCompanyMasterTableRecord('<?php echo $param11?>','<?php echo $param3 ?>','<?php echo $param6 ?>','<?php echo $param1 ?>','<?php echo $param5 ?>')"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
        <?php }
    }

    public static function getAccountIdByMasterTable($param1,$param2,$param3){
        static::companyDatabaseConnection($param1);
        $accountId = DB::selectOne('select `acc_id` from `'.$param3.'` where `id` = '.$param2.'')->acc_id;
        static::reconnectMasterDatabase();
        return $accountId;
    }

    public static function getAllPurchaseQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $purchaseBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '3'])
            ->where('date','<=',$param5 )
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalPurchaseBalance = 0;
        foreach ($purchaseBalance as $row){
            $totalPurchaseBalance += $row->qty;
        }
        return $totalPurchaseBalance;
    }

    public static function getAllIssueQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $sendBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '2'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalSendBalance = 0;
        foreach ($sendBalance as $row){
            $totalSendBalance += $row->qty;
        }
        return $totalSendBalance;
    }

    public static function getAllCashSaleQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $cashSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '5'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalCashSaleBalance = 0;
        foreach ($cashSaleBalance as $row){
            $totalCashSaleBalance += $row->qty;
        }
        return $totalCashSaleBalance;
    }

    public static function getAllCreditSaleQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $creditSaleBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '6'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalCreditSaleBalance = 0;
        foreach ($creditSaleBalance as $row){
            $totalCreditSaleBalance += $row->qty;
        }
        return $totalCreditSaleBalance;
    }

    public static function getAllStoreChallanReturQtyItemWise($param1,$param2,$param3,$param4,$param5){
        static::companyDatabaseConnection($param1);
        $storeReturnBalance = DB::table("fara")
            ->select(DB::raw("SUM(qty) as qty"))
            ->where(['main_ic_id' => $param2,'sub_ic_id' => $param3,'action' => '4'])
            ->where('date','<=',$param5)
            ->groupBy(DB::raw("sub_ic_id"))
            ->get();
        static::reconnectMasterDatabase();
        $totalStoreReturnBalance = 0;
        foreach ($storeReturnBalance as $row){
            $totalStoreReturnBalance += $row->qty;
        }
        return $totalStoreReturnBalance;
    }

    public static function getTotalGRNAmountByGRNNo($param1,$param2,$param3,$param4,$param5,$param6){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table($param3)
            ->select(DB::raw("SUM($param4) as $param4"))
            ->where([$param5 => $param2,'status' => '1',$param6 => 2])
            ->groupBy(DB::raw($param5))
            ->get();
        static::reconnectMasterDatabase();
        $totalAmount = 0;
        foreach ($dataRecord as $row){
            $totalAmount += $row->$param4;
        }
        return $totalAmount;
    }

    public static function loadPurchaseOrderQtyItemId($param1,$param2){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_order_data')
            ->where('id','=',$param2)
            ->first();
        //->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
        //->first();
        static::reconnectMasterDatabase();
        return $dataRecord->purchase_order_qty;
    }

    public static function getDemandNoByPrNo($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_request_data')
            ->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->demand_no;
    }

    public static function getDemandDateByPrNo($param1,$param2,$param3,$param4){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('purchase_request_data')
            ->where(['category_id' => $param3,'sub_item_id' => $param4,'purchase_request_no' => $param2,'status' => 1,'purchase_request_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->demand_date;
    }

    public static function getInvoiceNoByGRNNo($param1,$param2){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table('goods_receipt_note')
            ->where(['grn_no' => $param2,'status' => 1,'grn_status' => 2])
            ->first();
        static::reconnectMasterDatabase();
        return $dataRecord->invoice_no;
    }

    public static function getTotalInvoiceAmountByInvoiceNo($param1,$param2,$param3,$param4,$param5,$param6){
        static::companyDatabaseConnection($param1);
        $dataRecord = DB::table($param3)
            ->select(DB::raw("SUM($param4) as $param4"))
            ->where([$param5 => $param2,'status' => '1',$param6 => 2])
            ->groupBy(DB::raw($param5))
            ->get();
        $invoiceDetail = DB::table('invoice')->where('inv_no','=',$param2)->where('status','=',1)->first();
        static::reconnectMasterDatabase();
        $totalAmount = 0;
        foreach ($dataRecord as $row){
            $totalAmount += $row->$param4;
        }
        $calculatedTotalDiscount = $totalAmount*$invoiceDetail->inv_against_discount/100;
        $calculatedTotalAmount = $totalAmount - $calculatedTotalDiscount;
        return $calculatedTotalAmount;
    }

    public static function getImages($dir){
        // array to hold return value
        $retval = [];

        // add trailing slash if missing
        if(substr($dir, -1) != "/") {
            $dir .= "/";
        }

        // full server path to directory
        echo $fulldir = "{$_SERVER['DOCUMENT_ROOT']}/$dir";

        $d = @dir($fulldir) or die("getImages: Failed opening directory {$dir} for reading");
        while(FALSE !== ($entry = $d->read())) {
            // skip hidden files
            if($entry{0} == ".") continue;

            // check for image files
            $f = escapeshellarg("{$fulldir}{$entry}");
            $mimetype = trim(shell_exec("file -bi {$f}"));
            foreach($GLOBALS['imagetypes'] as $valid_type) {
                if(preg_match("@^{$valid_type}@", $mimetype)) {
                    $retval[] = [
                        'file' => "/{$dir}{$entry}",
                        'size' => getimagesize("{$fulldir}{$entry}")
                    ];
                    break;
                }
            }
        }
        $d->close();

        return $retval;
    }


    public static function operations_rights()
    {
    if(Auth::user()->acc_type == 'user'):
        static::reconnectMasterDatabase();
        $user_rights = MenuPrivileges::where([['emp_id','=',Auth::user()->emp_id]]);
        $crud_permission[]='';
        if($user_rights->count() > 0):
            $crud_rights  = explode(",",$user_rights->value('crud_rights'));

            $link = Request::segment(1)."/".Request::segment(2);
            $getTitle = $user_rights = Menu::where([['m_controller_name','=',$link]])->value('m_main_title');

            if(in_array('view_'.$getTitle,$crud_rights)):
                $crud_permission[] = "view";
            endif;
            if(in_array('edit_'.$getTitle,$crud_rights)):
                $crud_permission[] = "edit";
            endif;
            if(in_array('repost_'.$getTitle,$crud_rights)):
                $crud_permission[] = "repost";
            endif;
            if(in_array('delete_'.$getTitle,$crud_rights)):
                $crud_permission[] = "delete";
            endif;
            if(in_array('print_'.$getTitle,$crud_rights)):
                $crud_permission[] = "print";
            endif;
            if(in_array('export_'.$getTitle,$crud_rights)):
                $crud_permission[] = "export";
            endif;
            if(in_array('approve_'.$getTitle,$crud_rights)):
                $crud_permission[] = "approve";
            endif;
            if(in_array('reject_'.$getTitle,$crud_rights)):
                $crud_permission[] = "reject";
            endif;

        endif;

        else:
            $crud_permission[] = "view";
            $crud_permission[] = "edit";
            $crud_permission[] = "repost";
            $crud_permission[] = "delete";
            $crud_permission[] = "print";
            $crud_permission[] = "export";
            $crud_permission[] = "approve";
            $crud_permission[] = "reject";

    endif;

        return $crud_permission;
    }

    public static function operations_rights_ajax_pages($url)
    {
		
        if(Auth::user()->acc_type == 'user'):

            $user_rights = MenuPrivileges::where([['emp_id','=',Auth::user()->emp_id]]);
            $crud_permission[]='';
            if($user_rights->count() > 0):
                $crud_rights  = explode(",",$user_rights->value('crud_rights'));
                $getTitle = $user_rights = Menu::where([['m_controller_name','=',$url]])->value('m_main_title');

                if(in_array('view_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "view";
                endif;
                if(in_array('edit_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "edit";
                endif;
                if(in_array('repost_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "repost";
                endif;
                if(in_array('delete_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "delete";
                endif;
                if(in_array('print_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "print";
                endif;
                if(in_array('export_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "export";
                endif;
                if(in_array('approve_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "approve";
                endif;
                if(in_array('reject_'.$getTitle,$crud_rights)):
                    $crud_permission[] = "reject";
                endif;

            endif;

        else:
            $crud_permission[] = "view";
            $crud_permission[] = "edit";
            $crud_permission[] = "repost";
            $crud_permission[] = "delete";
            $crud_permission[] = "print";
            $crud_permission[] = "export";
            $crud_permission[] = "approve";
            $crud_permission[] = "reject";

        endif;

        return $crud_permission;
    }



}
?>