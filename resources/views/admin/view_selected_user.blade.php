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
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
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
                                <h5 class="card-title">Details About # {{ $user_details['fname'] . " " . $user_details['lname'] }}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>First name</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. Kamal" name="fname" value="{{ $user_details['fname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Middle Name</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. Nishantha" name="mname" value="{{ $user_details['mname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Last name</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. " name="lname" value="{{ $user_details['lname'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Country</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. Sri Lanka" name="country" value="{{ $user_details['country'] }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Mobile No</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 1234567890" name="mobile" value="{{ $user_details['mobile'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" class="form-control" placeholder="hello@example.com" name="email" value="{{ $user_details['email'] }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Address</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123/A Makola" name="address" value="{{ $user_details['address'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>State</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. Gampaha" name="state" value="{{ $user_details['state'] }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>ZIP Code</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_details['zipcode'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Gender</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_details['gender'] == "M" ? "Male" : "Female" }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Birth Date</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_details['bdate'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="card-title">Wallet Informations</h5>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Wallet Type</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_details['wallet_type'] }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Wallet ID</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_details['wallet_id'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>User Current Total</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $current_total['total_earnings'] }} USDT">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="card-title">KYC Informations</h5>
                                        <hr>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>KYC Type</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $user_kyc_docs['kyc_type'] }}">
                                        </div>
                                    </div>
                                    <?php
                                        $kyc_status = null;

                                        if ($user_kyc_docs['status'] == "A") {
                                            $kyc_status = "Approved";
                                        } else if ($user_kyc_docs == "P") {
                                            $kyc_status == "Pending";
                                        } else {
                                            $kyc_status = "Rejected";
                                        }
                                    ?>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Status</strong></label>
                                            <input type="text" class="form-control" placeholder="Eg. 123-456" name="zipcode" value="{{ $kyc_status }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="mb-1"><strong>Front Image</strong></label>
                                        <a href="{{ $user_kyc_docs['f_image'] }}" target="_blank" class="btn btn-primary btn-sm" onclick="showImageInNewTab(this.href)" style="width: 100%">View Image</a>
                                    </div>
                                    <div class="col-4">
                                        <label class="mb-1"><strong>Back Image</strong></label>
                                        <a href="{{ $user_kyc_docs['b_image'] }}" id="setBackImage" class="btn btn-primary btn-sm" style="width: 100%" onclick="showImageInNewTab(this.href)">View Image</a>
                                    </div>
                                    <div class="col-4">
                                        <label class="mb-1"><strong>Selfi Image</strong></label>
                                        <a href="{{ $user_kyc_docs['selfi_image'] }}" id="setSelfiImage" class="btn btn-primary btn-sm" style="width: 100%" onclick="showImageInNewTab(this.href)">View Image</a>
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
            var table = $('#kycInfoTable').DataTable({
                pageLength: 3
            });

            table.on('click', '.view', function () {
                $tr = $(this).closest('tr');

                if($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }

                var data = table.row($tr).data();
                
                $('#setUserId').val(data[0]);
                $('#kycType').val(data[1]);

                $.ajax({
                    type: 'GET',
                    url: "{{ route('getKYCImagesByUserId') }}",
                    data: { userId: data[0] },
                    success: function (data) {
                        const frontImageLink = document.getElementById("setFrontImage");
                        const backImageLink = document.getElementById("setBackImage");
                        const selfiImageLink = document.getElementById("setSelfiImage");

                        frontImageLink.href = data.success.f_image;
                        backImageLink.href = data.success.b_image;
                        selfiImageLink.href = data.success.selfi_image;
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

        function showImageInNewTab(_base64Url){
            var win = window.open();
            win.document.write("<img src=" +  _base64Url + " style='width: 100%; height: 100%'>");
        }

        const getEl = document.getElementById("main-wrapper").classList;
        const getHam = document.getElementsByClassName("nav-control")[0];

        getHam.addEventListener('click', function (e) {
            
            if (getEl.length > 1) {
                document.getElementById("logo").classList.add("logo-compact");
            } else {
                document.getElementById("logo").classList.remove("logo-compact");
            }
        });

        function showImageInNewTab(_base64Url){
            var win = window.open();
            win.document.write("<img src=" +  _base64Url + " style='width: 100%; height: 100%'>");
        }
    </script>
</body>

</html>