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

    <div class="modal fade" id="exampleModalCenter" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View KYC Documents & Checking</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/updateKYCDocStatus" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>KYC Type</strong></label>
                                    <input type="text" id="kycType" class="form-control" placeholder="Eg. " name="kyc_type" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="mb-1"><strong>Front Image</strong></label>
                                <a href="" id="setFrontImage" class="btn btn-primary btn-sm" style="width: 100%" onclick="showImageInNewTab(this.href)">View Image</a>
                            </div>
                            <div class="col-4">
                                <label class="mb-1"><strong>Back Image</strong></label>
                                <a href="" id="setBackImage" class="btn btn-primary btn-sm" style="width: 100%" onclick="showImageInNewTab(this.href)">View Image</a>
                            </div>
                            <div class="col-4">
                                <label class="mb-1"><strong>Selfi Image</strong></label>
                                <a href="" id="setSelfiImage" class="btn btn-primary btn-sm" style="width: 100%" onclick="showImageInNewTab(this.href)">View Image</a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Status</strong></label>
                                    <select id="inputState" class="form-control" tabindex="-98" name="status"  onchange="changeStatus()">
                                        <option value="0" selected>Choose One ...</option>
                                        <option value="R">Rejected</option>
                                        <option value="A">Approved</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="reasonRow" style="display: none">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1"><strong>Reason</strong></label>
                                    <select id="inputState" class="form-control" tabindex="-98" name="reason"  onchange="changeStatus()">
                                        <option value="0">-- Select Option --</option>
                                        <option value="1">Document Incomplete</option>
                                        <option value="2">Document Unreadable</option>
                                        <option value="3">Document Do Not Match User Data</option>
                                        <option value="4">Document not accepted</option>
                                        <option value="5">Document expired</option>
                                        <option value="6">Document missing</option>
                                        <option value="7">Document falsified</option>
                                        <option value="8">Underage user</option>
                                        <option value="9">Out of date</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="user_id" value="" id="setUserId">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save changes">
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
                                <h5 class="card-title">Submited KYC Informations</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table" id="kycInfoTable">
                                            <thead>
                                                <th>ID</th>
                                                <th>KYC Type</th>
                                                <th>Customer</th>
                                                <th>Front Image</th>
                                                <th>Back Image</th>
                                                <th>Selfi Image</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody>
                                                @foreach($kyc_details as $document)
                                                    <tr>
                                                        <td>{{ $document->user_id }}</td>
                                                        <td>{{ $document->kyc_type }}</td>
                                                        <td>{{ $document->fname . " " . $document->mname . " " . $document->lname }}</td>
                                                        <td>{{ $document->f_image != null ? "Submited" : "No" }}</td>
                                                        <td>{{ $document->b_image != null ? "Submited" : "No" }}</td>
                                                        <td>{{ $document->selfi_image != null ? "Submited" : "No" }}</td>
                                                        <td>
                                                            @if ($document->status == "P")
                                                                Pending
                                                            @elseif ($document->status == "A")
                                                                Approved
                                                            @else
                                                                Rejected
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <a href="" class="btn btn-primary btn-sm view" data-toggle="modal" data-target="#exampleModalCenter">View</a>
                                                                </div>
                                                                <div class="col-6">
                                                                    <a href="" class="btn btn-danger btn-sm">Delete</a>
                                                                </div>
                                                            </div>
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
            var table = $('#kycInfoTable').DataTable({});

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

        function changeStatus() {
            const getStatus = document.getElementById("inputState").value;

            if (getStatus === "R") {
                document.getElementById("reasonRow").style.display = "";
            } else {
                document.getElementById("reasonRow").style.display = "none";
            }
        }
    </script>
</body>

</html>