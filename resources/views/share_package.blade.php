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
                    <h5 class="modal-title">Share Package For User</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/createSharePackageForUser" method="POST">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Package Name</strong></label>
                                    <input type="text" class="form-control" placeholder="Eg. " name="package_name" value="" id="packageName">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Package Amount</strong></label>
                                    <input type="text" class="form-control" placeholder="Eg. " name="package_name" value="" id="packageAmount">
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Type Username</strong></label>
                                    <input type="text" id="setUsername" class="form-control" placeholder="Eg. " name="transfer_to_id" value="">
                                </div>
                                {{-- <div class="form-group">
                                    <label class="mb-1"><strong>Select Username</strong></label>
                                    <select id="inputState" class="form-control" tabindex="-98" name="username">
                                        @foreach ($users_list as $each_user)
                                            <option value="{{ $each_user['id'] }}">{{ $each_user['username'] }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="package_id" value="" id="packageId">
                                <input type="submit" class="btn btn-primary btn-sm" value="Share Package">
                            </div>
                        </div>
                    </form>
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
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif
                                <h5 class="card-title">Available Packages</h5>
                                <hr>
                                <div class="row">
                                    @if ($total_earning < 50)
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p>There are No Packages Available Right Now.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @foreach($packages as $each_package)
                                        <div class="col-md-4 col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $each_package->package_name }}</h5>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p>{{ $each_package->package_amount }} USDT</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p>PEO Value - {{ $each_package->peo_value }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter" onclick="getPackageAmountById({{$each_package->id}})">Share</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="card-title">Transaction History</h5>
                                        <hr>
                                        <table class="table table-responsive" id="packageShareHistoryTable">
                                            <thead>
                                                <th>Transaction ID</th>
                                                <th>Username</th>
                                                <th>Package Name</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($share_history as $each_share)
                                                    <tr>
                                                        <td>{{ $each_share->id }}</td>
                                                        <td>{{ $each_share->username }}</td>
                                                        <td>{{ $each_share->package_name }}</td>
                                                        <td>{{ $each_share->package_amount }}</td>
                                                        <td>{{ $each_share->created_at }}</td>
                                                        <td>
                                                            <a href="" class="btn btn-danger btn-sm">Delete</a>
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

        function getPackageAmountById(id) {
            document.getElementById("packageId").value = id;
        }

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
            $('#packageShareHistoryTable').DataTable();
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