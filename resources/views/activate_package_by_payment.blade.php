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
                                <h5 class="card-title">Available Packages</h5>
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
                                <div class="row">
                                    <div class="col-mg-6 col-lg-6 col-sm-12">
                                        <form action="/activePackageAndDeposit" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Wallet Type</strong></label>
                                                        <select id="inputState" class="form-control" tabindex="-98" name="wtype" onchange="changeWalletTypes()">
                                                            <option value="0" selected>Choose One ...</option>
                                                            <option value="bep20" selected>BEP-20</option>
                                                            <option value="trc20">TRC-20</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Wallet Address</strong></label>
                                                        <input type="text" class="form-control" placeholder="Eg. " name="user_wid">
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Amount (USDT)</strong></label>
                                                        <input type="text" class="form-control" placeholder="Eg. " name="deposit_amount" id="setAmount" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Transaction ID</strong></label>
                                                        <input type="text" class="form-control" placeholder="Eg. TXN001UI89" name="txn_number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="hidden" name="user_id" value="{{ Session()->get('member')['id'] }}">
                                                    <input type="hidden" name="package_id" value="{{ $package_id }}" id="packageId">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Deposit Amount">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 col-log-6 col-sm-12" style="display: none" id="bep20">
                                        <div class="row justify-content-center">
                                            <div class="card" style="width: 250px; height: 250px;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center">BEP-20</h5>
                                                    <img src="{{asset('Wallets/BEP20/bep.png')}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="form-group">
                                                <input type="text" value="0xe4ef84d8f85c90C77542cfDB2cE6478eDf93D3b6" style="text-align: center; width: 400px" onclick="this.select" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-log-6 col-sm-12" style="display: none" id="trc20">
                                        <div class="row justify-content-center">
                                            <div class="card" style="width: 250px; height: 250px;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center">TRC-20</h5>
                                                    <img src="{{asset('Wallets/TRC20/trc.png')}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="form-group">
                                                <input type="text" value="TV6LVAm6icaZNE3fXGmcqzZLLXgzUsBVLd" style="text-align: center; width: 400px" onclick="this.select" class="form-control">
                                            </div>
                                        </div>
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
        document.getElementById("bep20").style.display = "";

        $(document).ready(function () {
            $('#activePackageTable').DataTable();

            $.ajax({
                type: 'GET',
                url: "{{ route('getPackageAmountByPackageId') }}",
                data: { packageId:  document.getElementById("packageId").value },
                success: function (data) {
                    document.getElementById("setAmount").value = data.data;
                }
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

        function changeWalletTypes() {
            const selectedType = document.getElementById("inputState").value;

            if (selectedType === "trc20") {
                document.getElementById("trc20").style.display = "";
                document.getElementById("bep20").style.display = "none";
            } else if (selectedType === "bep20") {
                document.getElementById("bep20").style.display = "";
                document.getElementById("trc20").style.display = "none";
            } else {
                console.log("Invalid Type.");
            }
        }
    </script>
</body>

</html>