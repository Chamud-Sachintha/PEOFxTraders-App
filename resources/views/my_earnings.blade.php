<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome To PeoFx | Dashboard</title>

    <meta name="description" content="Some description for the page">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <link href="{{asset('admin/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/jqvmap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/chartist.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/LineIcons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/owl.carousel.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">

    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="modal fade" id="exampleModalCenter" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Earning</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

       

        <!--**********************************
            Header start
        ***********************************-->
        {{ View::make('top_header') }}

        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        {{ View::make('admin_panel_side_nav') }}



        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <!-- row -->
            <div class="container-fluid"> 
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Earinings</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Your Investment</h5>
                                                <p>{{ $totalApprovedDeposits[0]->total_deposits }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Direct Referal Earnings</h5>
                                                <p>{{ $totalDirectRefComission }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">PEO Values Daily</h5>
                                                <p>{{ $finalPEOValueUSDT }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">P2P Earnings</h5>
                                                <p>{{ $total_p2p_earnings }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Total Earnings</h5>
                                                <p>{{ $totalUserEarnings }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h5 class="card-title">Earnings By Duration</h5>
                                        <hr>
                                        <table class="table table-responsive" id="earningsTable">
                                            <thead>
                                                <th>Daily Earning</th>
                                                <th>P2P Earnings</th>
                                                <th>Referal Earnings</th>
                                                <th>Today Total Earnings</th>
                                                <th>Total Earnings</th>
                                                <th>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <th>Actions</th> 
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyEarningList as $each_detail)
                                                    <tr>
                                                        <td>{{ $each_detail->daily_earning }}</td>
                                                        <td>{{ $each_detail->p2p_earning_daily }}</td>
                                                        <td>
                                                            {{ $each_detail->ref_earning_daily }}
                                                        </td>
                                                        <td>
                                                            {{ $each_detail->daily_total_earning }}
                                                        </td>
                                                        <td>
                                                            {{ $each_detail->total_earning }}
                                                        </td>
                                                        <td>{{ date('d-m-y', strtotime($each_detail->created_at)) }}</td>
                                                        <td>
                                                            <a href="" class="btn btn-primary btn-sm view" data-toggle="modal" data-target="#exampleModalCenter">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->

        <div class="footer">
            <div class="copyright">
                <p>© Copyright 2023 - All Rights Recieved By PeoFx.com <br> Design & Developed By <a href="https://www.builtonus.com/">BuiltOnUs.com</a></p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{asset('admin/js/global.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/Chart.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/owl.carousel.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/jquery.peity.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/dashboard-1.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/custom.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminjs/deznav-init.js')}}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#earningsTable').DataTable({});

            table.on('click', '.view', function () {
                $tr = $(this).closest('tr');

                if($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }

                var data = table.row($tr).data();
                
                // $('#setUserId').val(data[0]);
                const userId = data[0];

                $.ajax({
                    type: 'GET',
                    url: "{{ route('getUserActivatedPackages') }}",
                    data: { userId: userId },
                    success: function (data) {
                        console.log(data);
                    }
                });

                $('#exampleModal').modal('show');
            });
        });
        (function ($) {

            var direction = getUrlParams('dir');
            if (direction != 'rtl') { direction = 'ltr'; }

            var dezSettingsOptions = {
                typography: "poppins",
                version: "light",
                layout: "Vertical",
                headerBg: "color_1",
                navheaderBg: "color_1",
                sidebarBg: "color_1",
                sidebarStyle: "full",
                sidebarPosition: "fixed",
                headerPosition: "fixed",
                containerLayout: "full",
                direction: direction
            };

            new dezSettings(dezSettingsOptions);

            jQuery(window).on('resize', function () {
                new dezSettings(dezSettingsOptions);
            });

        })(jQuery);

        const getEl = document.getElementById("main-wrapper").classList;
        const getHam = document.getElementsByClassName("nav-control")[0];

        getHam.addEventListener('click', function (e) {
            
            if (getEl.length > 1) {
                document.getElementById("logo").classList.add("logo-compact");
            } else {
                document.getElementById("logo").classList.remove("logo-compact");
            }
        });
    </script>
</body>

</html>