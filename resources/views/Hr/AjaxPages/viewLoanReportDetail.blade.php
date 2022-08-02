<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$counter = 1;
$total = 0;
?>


<div class="">
      <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                 <textarea class="appendarea" id="copy-area" style="display:none;"></textarea>


                   <div id="get_area">
                       
                       <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LoanReport">
                            <thead>
                                <tr style="background: gold;">
                                    <th  class="text-center" colspan="6">Loan Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            {{ number_format($loanAmount,0) }}
                                        @else
                                            ******
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Month-Year</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>

                                @if(!empty($LoanData))
                                <tbody class="text-center">
  
                                   @foreach($LoanData as $y)
                                    <?php $total+= $y->loan_amount_paid;?>
                                      <tr>
                                        <td class="text-center"> {{$counter++}}</td>
                                        <td class="text-center">{{$y->emp_id}}</td>
                                        <td class="text-center">{{$y->emp_name}}</td>
                                        <td class="text-center">{{date('M-Y',strtotime($y->year.'-'.$y->month))}}</td>
                                        <td>
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($y->loan_amount_paid,0) }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                      </tr>
                                    @endforeach


                             </tbody>
                             <tfoot>
                                <tr>
                                  <td colspan="4" class="text-right"><b>Total Paid</b></td>
                                  <td class=""><b>
                                          @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                              {{ number_format($total,0) }}
                                          @else
                                              ******
                                          @endif
                                      </b></td>
                                </tr>
                                   <td colspan="4" class="text-right"><b>Remaining</b></td>
                                  <td class="" ><b>
                                          @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                              {{ number_format($loanAmount-$total,0) }}
                                          @else
                                              ******
                                          @endif
                                      </b></td>
                                </tr>
                            </tfoot>

                            @else
                                <tr >
                                    <td class="text-center" colspan="6"><b style="color:red;text-align: center;">Record Not Found !</b></td>
                                </tr>
                            @endif
                        </table>
                   </div>

                </div>

             
        </div>
    </div>
          <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <button class="btn btn-sm btn-info" id="copy-button">Copy to Clipboard
            </button>
            <button class="btn btn-sm btn-info" onclick="downloadimage()" class="clickbtn">Click To Download Image</button>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
  <script>
  var get_area = document.getElementById('get_area').innerHTML
  document.getElementsByClassName('appendarea')[0].value = get_area
  const button = document.getElementById('copy-button')
  const area = document.getElementById('copy-area')

  button.addEventListener('click', copyToClipboard)

  function copyToClipboard() {
    const handleCopy = copyText(area.value)
    document.addEventListener('copy', handleCopy)
    document.execCommand('copy')
    document.removeEventListener('copy', handleCopy)
  }

  function copyText(text) {
    return function handleCopyEvent(evt) {
      evt.clipboardData.setData('text/html', text)
      evt.clipboardData.setData('text/plain', text)
      evt.preventDefault(); // prevent writing to text/plain
    }
}

</script>