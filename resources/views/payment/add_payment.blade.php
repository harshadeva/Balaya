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
                                <label for="office" class="control-label">{{ __('Office') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                        </div>
                                        <select id="office" name="office" class="form-control"
                                                onchange="setCustomValidity('');officeChanged(this.value)"
                                                oninvalid="this.setCustomValidity('Please select office')"
                                                required>
                                            <option value="" disabled selected>Select Office</option>
                                            @if($offices != null)
                                                @foreach($offices as $office)
                                                    <option value="{{$office->idoffice}}">{{$office->office_name}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="monthlyPayment">{{ __('Monthly Payment') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" readonly
                                               placeholder="0.00" name="monthlyPayment" id="monthlyPayment">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="discount">{{ __('Add Discount') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="off" type="number" class="form-control gtZero"
                                           oninput="calTotal();"    placeholder="0.00" name="discount" id="discount">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="netTotal">{{ __('Net Total') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" readonly
                                               placeholder="0.00" name="netTotal" id="netTotal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6 class="text-secondary">Make Payment</h6>
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
                                <label for="month">{{ __('Payment for') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-calendar"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control monthPicker"
                                               required onchange="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter payment date')"
                                               placeholder="mm/yyyy" name="month" id="month">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Save Payment') }}</button>
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
            $('input').not(':checkbox').val('');
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
                    url: '{{route('savePayment')}}',
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
                                title: 'PAYMENT SAVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Payment details saved successfully.'
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
            monthly = $('#monthlyPayment').val() ? parseFloat($('#monthlyPayment').val()) < 0 ? 0 : parseFloat($('#monthlyPayment').val()) : 0;
            discount = $('#discount').val() ? parseFloat($('#discount').val()) < 0 ? 0 : parseFloat($('#discount').val()) : 0;
            payment = $('#payment').val() ? parseFloat($('#payment').val()) < 0 ? 0 : parseFloat($('#payment').val()) : 0;
            $('#monthlyPayment').val(monthly);
            $('#discount').val(discount);
            $('#payment').val(payment);

            let netTotal = monthly - discount;
            if(netTotal < 0 ){
                $('#discount').val(0);
                calTotal();
            }
            else{
                $('#netTotal').val(netTotal);

            }
            if(netTotal < payment ){
                $('#payment').val(netTotal);
                calTotal();
            }

        }

        function officeChanged(id) {
            if(id) {
                $('.notify').empty();
                $('.alert').hide();
                $('.alert').html("");
                $.ajax({
                    url: '{{route('getRentalByOffice')}}',
                    type: 'POST',
                    data: {id: id},
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
                            $('#monthlyPayment').val(data.success.rental);

                        }
                        calTotal();
                    }
                });
            }
        }

    </script>
@endsection