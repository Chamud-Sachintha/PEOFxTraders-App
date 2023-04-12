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

    {{-- model starts here --}}

    <div class="modal fade" id="exampleModalCenter" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve or Reject Package Request</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/updatePackageByPackageId" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Package Name</strong></label>
                                    <input type="text" id="setPackageName" class="form-control" placeholder="Eg. " name="update_package_name" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Interest Rate (%)</strong></label>
                                    <input type="text" id="setPackageRate" class="form-control" placeholder="Eg. " name="update_package_rate" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Package Amount (USDT)</strong></label>
                                    <input type="text" id="setPackageAmount" class="form-control" placeholder="Eg. " name="update_package_amount" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>PEO Value</strong></label>
                                    <input type="text" id="setPEOValues" class="form-control" placeholder="Eg. " name="update_peo_value" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Status</strong></label>
                                    <select id="setPackageStatus" class="form-control" tabindex="-98" name="update_status">
                                        <option value="0" selected>Choose One ...</option>
                                        <option value="A" selected>Active</option>
                                        <option value="I">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <input type="hidden" name="package_id" id="setPackageId">
                                <input type="submit" class="btn btn-primary btn-sm" value="Update Packages">
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

    {{-- model ends here --}}

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

        {{ View::make('admin.admin_top_header') }}

        {{ View::make('admin.admin_panel_side_nav') }}



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
                                <h5 class="card-title mt-3">Create New Package</h5>
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
                                <form action="/createNewPackage" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Package Name</strong></label>
                                                <input type="text" id="packageName" class="form-control" placeholder="Eg. " name="package_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Peo Value</strong></label>
                                                <input type="text" id="packageName" class="form-control" placeholder="Eg. " name="peo_value">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Amount (USDT)</strong></label>
                                                <input type="text" id="packageAmount" class="form-control" placeholder="Eg. " name="package_amount">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Status</strong></label>
                                                <select id="inputState" class="form-control" tabindex="-98" name="status">
                                                    <option value="0" selected>Choose One ...</option>
                                                    <option value="A" selected>Active</option>
                                                    <option value="I">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1"><strong>Rate (%)</strong></label>
                                                <input type="text" id="packageRate" class="form-control" placeholder="Eg. 5" name="package_rate">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="submit" class="btn btn-primary btn-sm" value="Create Package">
                                        </div>
                                    </div>
                                </form>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h5 class="card-title">All Packages</h5>
                                        <hr>

                                        <table class="table" id="packagesTable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Package Name</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>PEO Values</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($packages as $package)
                                                    <tr>
                                                        <td>{{ $package['id'] }}</td>
                                                        <td>{{ $package['package_name'] }}</td>
                                                        <td>{{ $package['rate'] }}</td>
                                                        <td>{{ $package['package_amount'] }}</td>
                                                        <td>{{ $package['status'] }}</td>
                                                        <td>{{ $package['peo_value'] }}</td>
                                                        <td>
                                                            <a href="" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary btn-sm view">Update</a>
                                                            <a href="/deletePackageByPackageId/{{ $package['id'] }}" class="btn btn-danger btn-sm">Delete</a>
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
            var table = $('#packagesTable').DataTable({});

            table.on('click', '.view', function () {
                $tr = $(this).closest('tr');

                if($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }

                var data = table.row($tr).data();
                
                $('#setPackageId').val(data[0]);
                $('#setPackageName').val(data[1]);
                $('#setPackageRate').val(data[2]);
                $('#setPackageAmount').val(data[3]);
                $('#setPEOValues').val(data[5]);
                $('#setPackageStatus').val(data[4]);

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