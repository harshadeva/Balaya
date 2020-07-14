@extends('layouts.main')
@section('psStyle')
    <style>


    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" id="form1" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">
                                </div>
                            </div>
                        </div>
                        <h6 class="text-secondary">Office Details</h6>
                        <hr/>
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label for="district" class="control-label">{{ __('District') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                        </div>
                                        <select id="district" name="district" class="form-control"
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

                            <div class="form-group col-md-6">
                                <label for="officeName">{{ __('Office Name') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-account"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter office name')"
                                               placeholder="Office name" name="officeName" id="officeName">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6 class="text-secondary">Additional Features</h6>
                        <hr/>
                        <div class="row">
                            <div style="text-align: center;" class="form-group col-md-2">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Analysis Feature') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="analysis" onchange="calTotal()" type="checkbox" id="analysisBtn"
                                               switch="none"/>
                                        <label for="analysisBtn" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-2">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('Attendance Feature') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="attendance" onchange="calTotal()" type="checkbox"
                                               id="attendanceBtn" switch="none"/>
                                        <label for="attendanceBtn" data-on-label="On"
                                               data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center;" class="form-group col-md-2">
                                <label style="margin-left: 5px;"
                                       class="control-label ">{{ __('SMS Feature') }}</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="sms" onchange="calTotal()" type="checkbox"
                                               id="smsBtn" switch="none"/>
                                        <label for="smsBtn" data-on-label="On"
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
                                <label for="payment">{{ __('Payment Amount') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control gtZero"
                                               oninput="setCustomValidity('');calTotal();"
                                               oninvalid="this.setCustomValidity('Please Enter payment amount')"
                                               placeholder="0.00" name="payment" id="payment">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="discount">{{ __('Discount') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control gtZero"
                                               oninput="setCustomValidity('');calTotal();"
                                               oninvalid="this.setCustomValidity('Please Enter discount')"
                                               placeholder="0.00" name="discount" id="discount">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="paymentDate">{{ __('First Payment Date') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-calendar"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control datepicker-autoclose"
                                               required onchange="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter payment date')"
                                               placeholder="mm/dd/yyyy" name="paymentDate" id="paymentDate">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="total">{{ __('Total Monthly Payment') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="on" type="number" class="form-control" readonly
                                               placeholder="0.00" name="total" id="total">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr/>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Add Office') }}</button>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                    </form> <!-- /form -->
                </div><!-- /card body -->
            </div><!-- /card -->

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
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

        function clearAll() {
            $('input').not(':checkbox').not('.noClear').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
        }

        function initializeDate() {
            jQuery('.datepicker-autoclose').datepicker({
                autoclose: true,
                todayHighlight: true
            });

        }

        $("#form1").on("submit", function (event) {
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
                    url: '{{route('saveOffice')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'NEW OFFICE ADDED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Office details saved successfully.'
                            });
                            clearAll();
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
            attendance = $('#attendanceBtn').prop('checked');
            analysis = $('#analysisBtn').prop('checked');
            sms = $('#smsBtn').prop('checked');
            payment = $('#payment').val() ? parseFloat($('#payment').val()) < 0 ? 0 : parseFloat($('#payment').val()) : 0;
            discount = $('#discount').val() ? parseFloat($('#discount').val()) < 0 ? 0 : parseFloat($('#discount').val()) : 0;

            total = payment;
            if (attendance) {
                total += 5000;
            }
            if (analysis) {
                total += 5000;
            }
            if (sms) {
                total += 5000;
            }

            let netTotal = total - discount;
            if(netTotal < 0 ){
                $('#discount').val(0);
                calTotal();
            }
            else{
                $('#total').val(netTotal);

            }

        }

    </script>
@endsection