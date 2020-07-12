@extends('layouts.main')
@section('psStyle')
    <style>

        .commentsContainer{
            border: solid 1px black;
            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
            /*background: rgba(181, 189, 192, 0.55);*/
          }

        .commentersContainer{
            border: solid 1px black;
            position:relative;

            background: rgb(27,66,190);
            background: linear-gradient(90deg, rgba(27,66,190,0.5533123230666257) 0%, rgba(0,212,255,0.6628628848643785) 100%);

            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
        }

        .commenterBox{
            background-color: #3A5060;
            border-radius: 10px;
        }
        .commenterBox.active{
            background-color: rgba(140, 164, 174, 0.61);
        }

        .receivedComment{
            border-radius: 10px;
            background-color: #82CCDD;

        }

        .ownComment{
            border-radius: 10px;
            background-color: #78E08F;
        }

        .writingSection{
            height: 30px;
            border: solid 1px black;
            border-radius: 10px;
        }

        .bottom{
            position:absolute;
            bottom:0;
            width: 100%}

        .mediaIcon{
            //
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 my-2 ">
                                    @if(isset($commenters))
                                        @if(count($commenters) > 0)
                                            @foreach($commenters as $key=>$item)
                                                <form action="{{route('viewUserComments')}}" id="form-{{$key}}" method="POST">
                                                    <input type="hidden" name="user" value="{{$key}}">
                                                    <input type="hidden" name="post_no" value="{{$item[0]->post->post_no}}">

                                                    <a href="#" onclick="$('#form-{{$key}}').submit()">

                                                    {{csrf_field()}}
                                                <div class="row  commenterBox m-2 p-2">

                                                    <div class="col-md-12">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                <h5 class="text-white col-md-7"><em class="fa fa fa-user-o"></em> {{$item[0]->user->fName}} </h5>
                                                                <p class="text-white ml-auto">{{$item[0]->created_at}}</p>
                                                                </div>
                                                                <div class="row">
                                                                @if($item[0]->response_type == 1)
                                                                <p class="text-white col-md-7">&nbsp;- Has commented some texts.</p>
                                                                    @elseif($item[0]->response_type == 2)
                                                                        <p class="text-white col-md-7">&nbsp;- Has commented some image content.</p>
                                                                @elseif($item[0]->response_type == 3)
                                                                    <p class="text-white col-md-7">&nbsp;- Has commented some video content.</p>
                                                                @elseif($item[0]->response_type == 4)
                                                                    <p class="text-white col-md-7">&nbsp;- Has commented some audio content.</p>
                                                                    @else
                                                                    <p class="text-white col-md-7">&nbsp;- Has commented some unknown content.</p>
                                                                @endif
                                                                    <p class="text-white ml-auto">{{ucwords(strtolower($item[0]->user->userRole->role))}}&nbsp;</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                </a>
                                                </form>

                                            @endforeach
                                        @else
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <br/>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {

        });
        function readURL(input, type) {
            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function (event) {
                        if (type == 1) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><img alt='image preview' class='attachmentPreview' src='" + event.target.result + "'></div>");

                        }
                        else if (type == 2) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa fa-file-movie-o (alias) fa-3x text-white'></em></div></div>");

                        }
                        else if (type == 3) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa  fa-file-audio-o fa-3x text-white'></em></div></div>");

                        }
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }
        }
    </script>
@endsection