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

                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{$post->title_en}}</h4>
                        </div>
                        <div class="col-md-2"><a href="#" onclick="showPostContent({{$response->post->post_no}})"><p class="font-weight-bold">View Post</p></a></div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        @if($response->response_type == 1)
                            <div class="col-md-12">
                                <h4>{{$response->response}}</h4>
                            </div>
                        @elseif($response->response_type == 2)
                            <div class="col-md-12 text-center">
                                <img src="{{asset($response->full_path)}}" width="200">
                            </div>
                        @elseif($response->response_type == 3)
                            <div class="col-md-12 text-center">
                            <video width='100%' controls controlsList='nodownload'>
                                <source src='{{asset($response->full_path)}}'  type='video/mp4'>
                                <source src='{{asset($response->full_path)}}' type='video/ogg'>
                                Your browser does not support HTML video.
                            </video>
                            </div>
                        @elseif($response->response_type == 4)
                            <div class="col-md-12 text-center">
                                <audio width='100%' controls  controlsList='nofullscreen nodownload noremoteplayback'>
                                    <source src='{{asset($response->full_path)}}'  type='audio/ogg'>
                                    <source src='{{asset($response->full_path)}}' type='video/mpeg'>
                                    Your browser does not support HTML video.
                                </audio>
                            </div>
                        @endif
                            <form action="{{route('viewUserComments')}}" id="formComments" method="POST">
                                {{csrf_field()}}
                                <input type="hidden" name="user" value="{{$response->idUser}}">
                                <input type="hidden" name="post_no" value="{{$response->post->post_no}}">
                                <input type="hidden" name="responseId" value="{{$response->idpost_response}}">
                            </form>
                            <div class="col-md-3"><a href="#" onclick="$('#formComments').submit();"><p class="font-weight-bold">View All Comments</p></a></div>
                    </div>

                </div>
            </div>

            <br/>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal" id="form1" role="form" >

                        <div class="row">

                            <div class="form-group  col-md-2">
                                <button style="margin-top: 27px;" type="submit"
                                  onclick="reject()"      class="btn btn-info btn-block ">{{ __('Reject') }}</button>
                            </div>
                            <div class="form-group  col-md-2">
                                <button style="margin-top: 27px;" type="submit"
                                  onclick="publish()"      class="btn btn-info btn-block ">{{ __('Publish') }}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->

    <div class="modal fade" id="viewPost" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Post</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-secondary" id="modelTitle"></h4>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Text</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-secondary" id="modelEnglish"></h6>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Attachments</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12" id="modalAttachments"></div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });




        function publish(id) {
            swal({
                title: 'Publish this Post?',
                type:'warning',
                showCancelButton: true,
                confirmButtonText: 'Publish it',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {
                $.ajax({
                    url: '{{route('publishPost')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'PROCESS INVALID!',
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
                                title: 'POST PUBLISHED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Post published successfully.'
                            });
                            $('#form-'+id).remove();
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

        function reject(id) {
            swal({
                title: 'Reject this Post?',
                type:'warning',
                showCancelButton: true,
                confirmButtonText: 'Reject it',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {
                $.ajax({
                    url: '{{route('rejectPost')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'PROCESS INVALID!',
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
                                title: 'POST REJECTED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Post rejected successfully.'
                            });
                            $('#form-'+id).remove();
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