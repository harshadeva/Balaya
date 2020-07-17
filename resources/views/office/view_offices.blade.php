@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <form action="{{route('viewOffice')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-4 ">
                                        <label for="officeName">Office Name</label>
                                        <input class="form-control " type="text" placeholder="Search office name here"
                                               id="officeName"
                                               name="officeName">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="district">By District</label>

                                        <select class="form-control select2" name="district"
                                                id="district">
                                            <option value="" disabled selected>Select district
                                            </option>
                                            @if($districts != null)
                                                @foreach($districts as $district)
                                                    <option value="{{$district->iddistrict}}">{{$district->name_en}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>By Created Date</label>

                                        <div class="input-daterange input-group" id="date-range">
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="startDate" name="start"/>
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="endDate" name="end"/>

                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="submit"
                                                class="btn form-control text-white btn-info waves-effect waves-light"
                                                style="margin-top: 21px;">Search
                                        </button>
                                    </div>

                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>OFFICE NAME</th>
                                                    <th>DISTRICT</th>
                                                    <th style="text-align: right;">DISCOUNT</th>
                                                    <th style="text-align: right;">MONTHLY PAYMENT</th>
                                                    <th>PAYMENT DATE</th>
                                                    <th>ANALYSIS MODULE</th>
                                                    <th>ATTENDANCE MODULE</th>
                                                    <th>SMS MODULE</th>
                                                    <th>CANVASSING MODULE</th>
                                                    <th>MAP MODULE</th>
                                                    <th>OFFICE  STATUS</th>
                                                    <th>CREATED AT</th>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($offices))
                                                    @if(count($offices) > 0)
                                                        @foreach($offices as $office)
                                                            <tr id="{{$office->idoffice}}">
                                                                <td>{{strtoupper($office->office_name)}} </td>
                                                                <td>{{strtoupper($office->district->name_en)}} </td>
                                                                <td style="text-align: right;">{{number_format($office->discount,2)}}</td>
                                                                <td style="text-align: right;">{{number_format($office->monthly_payment,2)}}</td>
                                                                <td>{{$office->payment_date}}</td>
                                                                @if($office->officeModule()->where('idmodule',1)->first() != null && $office->officeModule()->where('idmodule',1)->first()->status == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ENABLED</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif
                                                                @if($office->officeModule()->where('idmodule',5)->first() != null && $office->officeModule()->where('idmodule',5)->first()->status == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ENABLED</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif
                                                                @if($office->officeModule()->where('idmodule',2)->first() != null && $office->officeModule()->where('idmodule',2)->first()->status == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ENABLED</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif
                                                                @if($office->officeModule()->where('idmodule',3)->first() != null && $office->officeModule()->where('idmodule',3)->first()->status == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ENABLED</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif
                                                                @if($office->officeModule()->where('idmodule',4)->first() != null && $office->officeModule()->where('idmodule',4)->first()->status == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ENABLED</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif
                                                                @if($office->status == 1)
                                                                    <td nowrap><p><em  class="mdi mdi-checkbox-blank-circle text-success "></em> LIVE</p></td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                                @endif

                                                                <td>{{$office->created_at}}</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                                type="button" id="dropdownMenuButton"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                            Option
                                                                        </button>

                                                                        <div class="dropdown-menu"
                                                                             aria-labelledby="dropdownMenuButton">
                                                                            <a href="#"
                                                                               onclick="showUpdateModal({{$office->idoffice}})"
                                                                               class="dropdown-item">Edit
                                                                            </a>
                                                                            @if($office->status == 1)
                                                                            <a href="#"
                                                                               onclick="disableOffice({{$office->idoffice}})"
                                                                               class="dropdown-item">Disable
                                                                            </a>
                                                                            @else
                                                                                <a href="#"
                                                                                   onclick="enableOffice({{$office->idoffice}})"
                                                                                   class="dropdown-item">Enable
                                                                                </a>
                                                                            @endif
                                                                        </div>


                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10"
                                                                style="text-align: center;font-weight: 500">Sorry No
                                                                Results Found.
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($offices))
                                {{$offices->links()}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="updateModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Update Office</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="updateForm" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlertUpdate"
                                     style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label for="districtU" class="control-label">{{ __('District') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                        </div>
                                        <select id="districtU" name="district" class="form-control"
                                                onchange="setCustomValidity('')"
                                                oninvalid="this.setCustomValidity('Please select district')"
                                                required>
                                            <option value="" disabled selected>Select District</option>
                                            @if($districts != null)
                                                @foreach($districts as $district)
                                                    <option value="{{$district->iddistrict}}">{{$district->name_en}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="officeName">{{ __('Office Name') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-account"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter office name')"
                                               placeholder="Office name" name="officeName" id="officeNameU">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6 class="text-secondary">Additional Features</h6>
                        <hr/>
                        <div class="row">
                            <div style="text-align: center;" class="form-group col-md-4">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Analysis Module') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="analysis" onchange="calTotal()" type="checkbox" id="analysisBtnU"
                                          class="features"  switch="none"/>
                                        <label for="analysisBtnU" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-4">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Attendance Module') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="attendance" onchange="calTotal()" type="checkbox"
                                               class="features"        id="attendanceBtnU" switch="none"/>
                                        <label for="attendanceBtnU" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-4">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('SMS Module') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="sms" onchange="calTotal()" type="checkbox"
                                               class="features"     id="smsBtnU" switch="none"/>
                                        <label for="smsBtnU" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-4">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Canvassing Module') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="canvassing" onchange="calTotal()" type="checkbox"
                                               class="features"      id="canvassingBtnU" switch="none"/>
                                        <label for="canvassingBtnU" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-4">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Map Module') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="map" onchange="calTotal()" type="checkbox"
                                               class="features"     id="mapBtnU" switch="none"/>
                                        <label for="mapBtnU" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6 class="text-secondary">Payment Details</h6>
                        <hr/>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="paymentU">{{ __('Payment Amount') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control gtZero"
                                               oninput="setCustomValidity('');calTotal();"
                                               oninvalid="this.setCustomValidity('Please Enter payment amount')"
                                               placeholder="0.00" name="payment" id="paymentU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="discountU">{{ __('Discount') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control gtZero"
                                               oninput="setCustomValidity('');calTotal();"
                                               oninvalid="this.setCustomValidity('Please Enter discount')"
                                               placeholder="0.00" name="discount" id="discountU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="paymentDateU">{{ __('First Payment Date') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-calendar"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control datepicker-autoclose"
                                               required onchange="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter payment date')"
                                               placeholder="mm/dd/yyyy" name="paymentDate" id="paymentDateU">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="totalU">{{ __('Total Monthly Payment') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control" readonly
                                               placeholder="0.00" name="total" id="totalU">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr/>
                        <input type="hidden" class="noClear" name="updateId" id="updateId">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Update Office') }}</button>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" onclick="clearAll();event.preventDefault();$('#updateModal').modal('hide');"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                    </form> <!-- /form -->
                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function showUpdateModal(id) {
            $('.alert').html('').hide();
            $('#updateId').val(id);
            $.ajax({
                url: '{{route('getOfficeById')}}',
                data: {id: id},
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    $('#districtU').val(data.iddistrict).trigger('change');
                    $('#officeNameU').val(data.office_name);
                    let subTotal = data.sub_total;
                    $('.features').prop('checked',false);
                    $.each(data.office_module, function (key, value) {
                        if(value.idmodule == 1){
                            if(value.status == 1){
                                $('#analysisBtnU').prop('checked', true);
                            }
                            else{
                                $('#analysisBtnU').prop('checked', false);
                            }
                        }
                        else if(value.idmodule == 2){
                            if(value.status == 1){
                                $('#smsBtnU').prop('checked', true);
                            }
                            else{
                                $('#smsBtnU').prop('checked', false);
                            }
                        }
                        else if(value.idmodule == 3){
                            if(value.status == 1){
                                $('#canvassingBtnU').prop('checked', true);
                            }
                            else{
                                $('#canvassingBtnU').prop('checked', false);
                            }
                        }
                        else if(value.idmodule == 4){
                            if(value.status == 1){
                                $('#mapBtnU').prop('checked', true);
                            }
                            else{
                                $('#mapBtnU').prop('checked', false);
                            }
                        }
                        else if(value.idmodule == 5){
                            if(value.status == 1){
                                $('#attendanceBtnU').prop('checked', true);
                            }
                            else{
                                $('#attendanceBtnU').prop('checked', false);
                            }
                        }
                    });
                    $('#paymentU').val(subTotal);
                    $('#discountU').val(data.discount);
                    $('#paymentDateU').val(data.payment_date);
                    calTotal();
                    $('#updateModal').modal('show');
                }
            });
        }

        $("#updateForm").on("submit", function (event) {
            event.preventDefault();

            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('updateOffice')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlertUpdate').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlertUpdate').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'OFFICE UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Office details saved successfully.'
                            });
                            $('#updateModal').modal('hide');
                            location.reload();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html('Please provide all required fields.');
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });

        function calTotal() {
            let  attendance = $('#attendanceBtnU').prop('checked');
            let  analysis = $('#analysisBtnU').prop('checked');
            let  sms = $('#smsBtnU').prop('checked');
            let  canvassing = $('#canvassingBtnU').prop('checked');
            let  map = $('#mapBtnU').prop('checked');
            let  payment = $('#paymentU').val() ? parseFloat($('#paymentU').val()) < 0 ? 0 : parseFloat($('#paymentU').val()) : 0;
            let  discount = $('#discountU').val() ? parseFloat($('#discountU').val()) < 0 ? 0 : parseFloat($('#discountU').val()) : 0;

            total = payment;
            if (attendance) {
                total += parseFloat('{{\App\Module::where('name','Attendance')->first()->payment}}');
            }
            if (analysis) {
                total += parseFloat('{{\App\Module::where('name','Analysis')->first()->payment}}');
            }
            if (sms) {
                total += parseFloat('{{\App\Module::where('name','SMS')->first()->payment}}');
            }
            if (canvassing) {
                total += parseFloat('{{\App\Module::where('name','Canvassing')->first()->payment}}');
            }
            if (map) {
                total += parseFloat('{{\App\Module::where('name','Map')->first()->payment}}');
            }

            let netTotal = total - discount;
            if(netTotal < 0 ){
                $('#discountU').val(0);
                calTotal();
            }
            else{
                $('#totalU').val(netTotal);

            }

        }

        function disableOffice(id) {
            swal({
                title: 'Do you want to disable this office?',
                text:'All sub users will be disabled!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Disable!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('disableOffice')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'DISABLE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'OFFICE DISABLED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Office disabled successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

        function enableOffice(id) {
            swal({
                title: 'Do you want to enable this office?',
                text:'All disabled users will be enabled!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Enable!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('enableOffice')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'ENABLE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'OFFICE ENABLED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Office enabled successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }
    </script>
@endsection