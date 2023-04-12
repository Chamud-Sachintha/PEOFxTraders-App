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
                    <h5 class="modal-title">Rejected Reson & Description</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p id="reasonDesc"></p>
                        </div>
                    </div>
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
                                    <div class="col-6">
                                        <h5 class="card-title">KYC Information</h5>
                                    </div>
                                    <div class="col-6">
                                        <a href="/app/profile" class="btn btn-secondary" style="float: right;">Back to The Profile</a>
                                    </div>
                                </div>
                                <hr>
                                <form action="/uploadKYCDocuments" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label for="">Choose KYC Type</label>
                                            <select id="inputState" class="form-control" tabindex="-98" name="kyc_type">
                                                <option selected="">Choose...</option>
                                                <option value="nic">NIC</option>
                                                <option value="dl">Driver Lision</option>
                                                <option value="p">Passport</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1">Front-Side Image</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="f_image">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1">Back-Side Image</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="b_image">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="mb-1">Upload Selfi Image</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="selfi_image">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="hidden" name="user_id" value="{{ Session()->get('member')['id'] }}" id="userId">
                                            <input type="submit" class="btn btn-primary" value="Submit KYC Informations">
                                        </div>
                                    </div>
                                </form>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div>
                                            <table class="table table-responsive" id="kycInfoTable">
                                                <thead>
                                                    <th>KYC Type</th>
                                                    <th>Front Image</th>
                                                    <th>Back Image</th>
                                                    <th>Selfi Image</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($documents as $document)
                                                        <tr>
                                                            <td>{{ $document['kyc_type'] }}</td>
                                                            <td>{{ $document['f_image'] != null ? "Submited" : "No" }}</td>
                                                            <td>{{ $document['b_image'] != null ? "Submited" : "No" }}</td>
                                                            <td>{{ $document['selfi_image'] != null ? "Submited" : "No" }}</td>
                                                            <td>
                                                                @if ($document['status'] == "P")
                                                                    Pending
                                                                @elseif ($document['status'] == "A")
                                                                    Approved
                                                                @else
                                                                    Rejected
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($document['reason'] != "0")
                                                                    <a href="" class="btn btn-primary btn-sm view" data-toggle="modal" data-target="#exampleModalCenter">View</a>
                                                                @endif
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

                $.ajax({
                    type: 'GET',
                    url: "{{ route('getDocRejectedReasonByUserId') }}",
                    data: { userId:  document.getElementById("userId").value },
                    success: function (data) {
                        console.log(data.reason);
                        switch (data.reason) {

                            case "1":
                                document.getElementById("reasonDesc").innerHTML = "Document Incomplete";
                                break;
                            case "2":
                                document.getElementById("reasonDesc").innerHTML = "Document Unreadable";
                                break;
                            case "3":
                                document.getElementById("reasonDesc").innerHTML = "Document Do Not Match User Data";
                                break;
                            case "4":
                                document.getElementById("reasonDesc").innerHTML = "Document not accepted";
                                break;
                            default:
                                document.getElementById("reasonDesc").innerHTML = "Document expired";
                                break;
                        }
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