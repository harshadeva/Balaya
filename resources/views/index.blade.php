@extends('layouts.main')
@section('psStyle')
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">


@endsection
@section('psContent')


    <!-- ==================
                         PAGE CONTENT START
                         ================== -->

    <div class="page-content-wrapper">

        @if(isset($bars))
            <div class="header-bg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-4 pt-5">
                            <div id="barChart" class="dash-chart"></div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-outline-info ml-1 waves-effect waves-light">
                                    Year {{date('Y')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($responseChart))
            <div class="header-bg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-4 pt-5">
                            <div id="responseChart" class="dash-chart"></div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-outline-info ml-1 waves-effect waves-light">
                                    Year {{date('Y')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="container-fluid">
            <div class="row">
                @if(isset($officeCount))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 title="All active and pending agents count" class="text-info">{{$officeCount}}</h3>
                                Offices Count
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($usersCount))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 class="text-purple">{{$usersCount}}</h3>
                                Users Count
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($agentsCount))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 title="All active and pending agents count" class="text-info">{{$agentsCount}}</h3>
                                Agents Count
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($memberCount))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 class="text-purple">{{$memberCount}}</h3>
                                Members Count
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($postCount))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 class="text-primary">{{number_format($postCount)}}</h3>
                                Posts Count
                            </div>
                        </div>
                    </div>
                @endif
                    @if(isset($pendingPostCount))
                        <div class="col-md-6 col-xl-3">
                            <div class="card text-center m-b-30">
                                <div class="mb-2 card-body text-muted">
                                    <h3 class="text-primary">{{number_format($pendingPostCount)}}</h3>
                                    Pending Posts Count
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($pendingSmsCount))
                        <div class="col-md-6 col-xl-3">
                            <div class="card text-center m-b-30">
                                <div class="mb-2 card-body text-muted">
                                    <h3 class="text-success">{{number_format($pendingSmsCount)}}</h3>
                                    Pending SMS Count
                                </div>
                            </div>
                        </div>
                    @endif
                @if(isset($nextPayment))
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-center m-b-30">
                            <div class="mb-2 card-body text-muted">
                                <h3 class="text-danger">{{$nextPayment}}</h3>
                                Next Payment Date
                            </div>
                        </div>
                    </div>
                @endif
                {{--@if(isset($eventCount))--}}
                    {{--<div class="col-md-6 col-xl-3">--}}
                        {{--<div class="card text-center m-b-30">--}}
                            {{--<div class="mb-2 card-body text-muted">--}}
                                {{--<h3 class="text-danger">{{$eventCount}}</h3>--}}
                                {{--Events Count--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}

            </div>
            <!-- end row -->

            <div class="row">
                @if(isset($currentStorage))
                    <div class="col-xl-4">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h4 class="mt-0 header-title">Office Storage</h4>

                                        <div class="row text-center m-t-20">
                                            <div class="col-6">
                                                <h5 class="">{{round($currentStorage / 1000000,2)}} MB</h5>
                                                <p class="text-muted font-14">Used</p>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="">2 GB</h5>
                                                <p class="text-muted font-14">Total</p>
                                            </div>
                                        </div>
                                        <div id="storageChart" class="dash-chart" style="height: 250px;"></div>
                                        {{--<div id="morris-donut-example" class="dash-chart"></div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($totalStorage))
                    <div class="col-xl-4">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Total Storage</h4>

                                <div class="row text-center m-t-20">
                                    <div class="col-6">
                                        <h5 class="">{{round($totalStorage,2)}} MB</h5>
                                        <p class="text-muted font-14">Used</p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="">16 GB</h5>
                                        <p class="text-muted font-14">Total</p>
                                    </div>
                                </div>
                                <div id="totalStorageChart" class="dash-chart" style="height: 250px;"></div>
                                {{--<div id="morris-donut-example" class="dash-chart"></div>--}}
                            </div>
                        </div>
                    </div>
                @endif
                    <div class="col-xl-8">
                        <div class="row">
                        @if(isset($referralCode))
                            <div class="col-md-6 col-xl-6">
                                <div class="card text-center m-b-30">
                                    <div class="mb-2 card-body text-muted">
                                        <h3 title="Send this code to agents to join with your office."
                                            style="color: #1853ef">{{$referralCode}}</h3>
                                        Referral Code
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($smsLimit) && $smsLimit != null)
                            <div class="col-md-6 col-xl-6">
                                <div class="card text-center m-b-30">
                                    <div class="mb-2 card-body text-muted">
                                        <h3 style="color: rgb(9,118,36)">{{$smsLimit}}</h3>
                                        SMS Usage
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(isset($referralCode))
                            {{--<div class="col-md-6 col-xl-6">--}}
                                {{--<div class="card text-center m-b-30">--}}
                                    {{--<div class="mb-2 card-body text-muted">--}}
                                        {{--<h3 title="Send this code to agents to join with your office."--}}
                                            {{--style="color: #1853ef">{{$referralCode}}</h3>--}}
                                        {{--Referral Code--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        @endif
                    </div>
                </div>
                @if(isset($offices))
                    <div class="col-xl-8">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 m-b-30 header-title">Latest Offices</h4>

                                <div class="table-responsive">
                                    <table class="table m-t-20 mb-0 table-vertical">

                                        <tbody>
                                        @foreach($offices as $office)
                                            <tr>
                                                <td>
                                                    {{$office->office_name}}
                                                </td>
                                                @if($office->status == 1)
                                                    <td nowrap="">
                                                        <em style="color: #17c513" class="fa fa-circle"></em>

                                                        ACTIVATED
                                                    </td>
                                                @else
                                                    <td nowrap="">
                                                        <em style="color: #c51209" class="fa fa-circle"></em>

                                                        DEACTIVATED
                                                    </td>
                                                @endif
                                                <td colspan="3">
                                                    {{number_format($office->monthly_payment,2)}}
                                                </td>
                                                <td>
                                                    {{$office->created_at->format('Y-m-d')}}
                                                    <p class="m-0 text-muted font-14">Date</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- end row -->


            <div class="row">

                @if(isset($posts))
                    <div class="col-xl-8">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 m-b-30 header-title">Latest Posts</h4>

                                <div class="table-responsive">
                                    <table class="table m-t-20 mb-0 table-vertical">

                                        <tbody>
                                        @foreach($posts as $post)
                                            <tr>
                                                <td>
                                                    <em style="color: #b525c5" class="fa fa-circle"></em>

                                                    Post no : {{sprintf('%06d',$post->post_no)}}</td>
                                                <td colspan="3">
                                                    {{$post->title_en}}
                                                </td>
                                                <td>
                                                    {{$post->created_at->format('Y-m-d')}}
                                                    <p class="m-0 text-muted font-14">Date</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($comments))
                    <div class="col-xl-4">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 m-b-15 header-title">Recent Received Comments</h4>

                                <ol class="activity-feed mb-0">
                                    @foreach($comments as $comment)
                                        <li class="feed-item">
                                            <span class="date">{{$comment->created_at->format('M d')}}</span>
                                            <span class="activity-text">
                                             @if($comment->response_type == 1)
                                                    {{$comment->response}}
                                                @elseif($comment->response_type == 2)
                                                    Some image content
                                                @elseif($comment->response_type == 3)
                                                    Some video content
                                                @elseif($comment->response_type == 4)
                                                    Some audio content
                                                @endif
                                        </span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @endif


            </div>
            <!-- end row -->

        </div>


    </div> <!-- Page content Wrapper -->

@endsection
@section('psScript')

    <!--Morris Chart-->
    <script src="{{ URL::asset('assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/raphael/raphael-min.js')}}"></script>
    <script src="{{ URL::asset('assets/pages/dashborad.js')}}"></script>

    <script>
        $(document).ready(function () {
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if(isset($bars))
            Morris.Bar({
                element: 'barChart',
                data: [
                        @foreach($bars as $bar)
                    {
                        y: "{{$bar['y']}}", a: "{{$bar['a']}}"
                    },
                    @endforeach
                ],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Posts Count']
            });
            @endif
            @if(isset($responseChart))
            Morris.Bar({
                element: 'responseChart',
                data: [
                        @foreach($responseChart as $chart)
                    {
                        y: "{{$chart['y']}}",
                        a: "{{$chart['a']}}",
                        b: "{{$chart['b']}}",
                        c: "{{$chart['c']}}",
                        d: "{{$chart['d']}}"
                    },
                    @endforeach
                ],
                xkey: 'y',
                ykeys: ['a', 'b', 'c', 'd'],
                labels: ['Questions', 'Proposal', 'Requests', 'Response']
            });
            @endif
                    @if(isset($totalStorage))
                new Morris.Donut({
                // ID of the element in which to draw the chart.
                element: 'totalStorageChart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [{
                    label: 'Used',
                    value: (({{$totalStorage}}))
                },
                    {
                        label: 'Free',
                        value: ((16000 - {{$totalStorage}}))
                    }],
                labelColor: ["#9CC4E4"],
                colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
            });
            @endif

            getStorageCategories();

        });


        function getStorageCategories() {

            let categoriesChart = [];
            $.ajax({
                url: '{{route('dashboard-getStorage')}}',
                type: 'POST',
                success: function (data) {

                    $.each(data, function (key, value) {
                        if (key == 2) {
                            categoriesChart.push({
                                'value': value,
                                'label': 'IMAGES'
                            });
                        }
                        else if (key == 3) {
                            categoriesChart.push({
                                'value': value,
                                'label': 'VIDEOS'
                            });
                        }
                        else if (key == 4) {
                            categoriesChart.push({
                                'value': value,
                                'label': 'AUDIOS'
                            });
                        }
                        else {
                            categoriesChart.push({
                                'value': value,
                                'label': 'OTHER'
                            });
                        }


                    });
                    console.log(categoriesChart);

                    new Morris.Donut({
                        // ID of the element in which to draw the chart.
                        element: 'storageChart',
                        // Chart data records -- each entry in this array corresponds to a point on
                        // the chart.
                        data: categoriesChart,
                        labelColor: ["#9CC4E4"],
                        colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
                    });
                }
            });
        }

    </script>
@endsection