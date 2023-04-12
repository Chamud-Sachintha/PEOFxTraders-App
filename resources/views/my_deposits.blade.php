<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chrev Crypto | Dashboard</title>

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
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="/app" class="brand-logo">
                <img class="" src="{{asset('web/images/logo.png')}}" id="logo" alt>
                <img class="logo-compact" src="images/logo-text.png" alt>
                <img class="brand-title" src="images/logo-text.png" alt>

            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

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
                                <h5 class="card-title">Wallets Types</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12 d-flex justify-content-center">
                                        <div class="card" style="width: 250px; height: 250px;">
                                            <div class="card-body">
                                                <h5 class="card-title text-center">BEP-20</h5>
                                                <img src="{{asset('Wallets/BEP20/bep.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 d-flex justify-content-center">
                                        <div class="card" style="width: 250px; height: 250px;">
                                            <div class="card-body">
                                                <h5 class="card-title text-center">TRC-20</h5>
                                                <img src="{{asset('Wallets/TRC20/trc.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <input type="text" value="0xe4ef84d8f85c90C77542cfDB2cE6478eDf93D3b6" style="text-align: center" onclick="this.select" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <input type="text" value="TV6LVAm6icaZNE3fXGmcqzZLLXgzUsBVLd" style="text-align: center" onclick="this.select" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Deposit Amount</h5>
                                <hr>
                                @if ($errors->any())
                                    @foreach($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif
                                @if (Session()->has('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session()->get('status') }}
                                    </div>
                                @endif
                                <form action="/createNewDeposit" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Wallet Type</strong></label>
                                                <select id="inputState" class="form-control" tabindex="-98" name="wtype">
                                                    <option value="0" selected>Choose One ...</option>
                                                    <option value="trc20" selected>BEP-20</option>
                                                    <option value="bep20">TRC-20</option>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Wallet Address</strong></label>
                                                <input type="text" class="form-control" placeholder="Eg. " name="user_wid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Amount</strong></label>
                                                <input type="text" class="form-control" placeholder="Eg. " name="deposit_amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <input type="hidden" name="user_id" value="{{ Session()->get('member')['id'] }}">
                                            <input type="hidden" id="packageId" name="package_id" value="{{ $package_id }}">
                                            <input type="submit" class="btn btn-primary btn-sm" value="Deposit Amount">
                                        </div>
                                    </div>
                                </form>

                                <h5 class="card-title mt-3">My All Deposits</h5>
                                <hr>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <table class="table" id="depositRecordsTable">
                                            <thead>
                                                <th>Wallet Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody>
                                                @foreach($deposit_details as $each_deposit)
                                                    <tr>
                                                        <td>{{ $each_deposit['wallet_type'] }}</td>
                                                        <td>{{ $each_deposit['deposit_amount'] }}</td>
                                                        <td>
                                                            @if ($each_deposit['status'] == "P")
                                                                Pending
                                                            @elseif ($each_deposit['status'] == "A")
                                                                Approved
                                                            @else
                                                                Rejected
                                                            @endif
                                                        </td>
                                                        <td>{{ $each_deposit['created_at'] }}</td>
                                                        <td>
                                                            <a href="" class="btn btn-primary btn-sm">View</a>
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
            $('#depositRecordsTable').DataTable();
            console.log(document.getElementById("packageId").value);
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