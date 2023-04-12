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
            Nav header start
        ***********************************-->
        
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
                                <h5 class="card-title">My Wallet</h5> 
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
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <form action="/executeP2PTransfer" method="POST">
                                            @csrf
                                            <div class="row"> 
                                                <div class="col-6"> 
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Type Username</strong></label>
                                                        <input type="text" id="setUsername" class="form-control" placeholder="Eg. " name="transfer_to_id" value="">
                                                    </div>
                                                    {{-- <div class="form-group">
                                                        <label class="mb-1"><strong>Select Username</strong></label>
                                                        <select id="inputState" class="form-control" tabindex="-98" name="transfer_to_id">
                                                            @foreach ($users_list as $user)
                                                                <option value="{{ $user['id'] }}">{{ $user['username'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="mb-1"><strong>Amount (USDT)</strong></label>
                                                        <input type="text" class="form-control" name="amount" placeholder="Eg. 50">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="hidden" name="transfer_from_id" value="{{ Session()->get('member')['id'] }}">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Transfer Amount (P2P)" style="width: 100%">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-none d-md-block d-lg-block col-md-6 col-lg-6 col-sm-12">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Available Wallet Balance</h5>
                                                <p>{{ $totalUserEarnings }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 d-md-none d-lg-none mt-3">
                                        <div class="card" style="box-shadow: #0000002f 0px 6px 12px -2px, #0000002f 0px 3px 7px -3px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Available Wallet Balance</h5>
                                                <p>{{ $totalUserEarnings }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="card-title mt-3">Transaction History</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-responsive" id="transactionHistoryTable">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th> 
                                                    <th>Username</th>
                                                    <th>Amount (USDT)</th>
                                                    <th>Transfer Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transaction_list as $each_transaction)
                                                    <tr>
                                                        <td>{{ $each_transaction->id }}</td>
                                                        <td>{{ $each_transaction->username }}</td>
                                                        <td>{{ $each_transaction->amount }}</td>
                                                        <td>{{ $each_transaction->created_at }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js">
    </script>
    <script>

        var route = "{{ route('getUsernamesList') }}";
        $('#setUsername').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    console.log(data);
                    return process(data);
                });
            }
        });

        $(document).ready(function () {
            $('#transactionHistoryTable').DataTable();
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