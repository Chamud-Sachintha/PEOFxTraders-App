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
                <div class="form-head d-flex mb-4 mb-md-5 align-items-start">
                    <div class="input-group search-area d-inline-flex">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="flaticon-381-search-2"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search here">
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary ml-auto">+ Add New Cyrpto</a>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-header border-0 pb-0">
                                <div class="mr-auto">
                                    <h2 class="text-black mb-2 font-w600">$65,123</h2>
                                    <p class="mb-1 fs-13">
                                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 13.5C1.91797 12.4157 4.89728 9.22772 6.5 7.5L12.5 10.5L19.5 1.5"
                                                stroke="#2BC155" stroke-width="2" />
                                            <path
                                                d="M6.5 7.5C4.89728 9.22772 1.91797 12.4157 1 13.5H19.5V1.5L12.5 10.5L6.5 7.5Z" />
                                            <defs>
                                                <lineargradient x1="10.25" y1="3" x2="11" y2="13.5"
                                                    gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#2BC155" offset="1" stop-opacity="0.73" />
                                                    <stop offset="1" stop-color="#2BC155" stop-opacity="0" />
                                                </lineargradient>
                                            </defs>
                                        </svg>
                                        4%(30 days)
                                    </p>
                                </div>
                                <img src="fonts/bitcoin-1.svg" alt>
                            </div>
                            <div class="card-body p-0">
                                <canvas id="widgetChart1" height="75"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-header border-0 pb-0">
                                <div class="mr-auto">
                                    <h2 class="text-black mb-2 font-w600">$2,551</h2>
                                    <p class="mb-1 fs-13">
                                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 13.5C1.91797 12.4157 4.89728 9.22772 6.5 7.5L12.5 10.5L19.5 1.5"
                                                stroke="#2BC155" stroke-width="2" />
                                            <path
                                                d="M6.5 7.5C4.89728 9.22772 1.91797 12.4157 1 13.5H19.5V1.5L12.5 10.5L6.5 7.5Z" />
                                            <defs>
                                                <lineargradient x1="10.25" y1="3" x2="11" y2="13.5"
                                                    gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#2BC155" offset="1" stop-opacity="0.73" />
                                                    <stop offset="1" stop-color="#2BC155" stop-opacity="0" />
                                                </lineargradient>
                                            </defs>
                                        </svg>
                                        4%(30 days)
                                    </p>
                                </div>
                                <img src="fonts/ethereum-1.svg" alt>
                            </div>
                            <div class="card-body p-0">
                                <canvas id="widgetChart2" height="75"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-header border-0 pb-0">
                                <div class="mr-auto">
                                    <h2 class="text-black mb-2 font-w600">$0,55</h2>
                                    <p class="mb-1 fs-13">
                                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 13.5C1.91797 12.4157 4.89728 9.22772 6.5 7.5L12.5 10.5L19.5 1.5"
                                                stroke="#2BC155" stroke-width="2" />
                                            <path
                                                d="M6.5 7.5C4.89728 9.22772 1.91797 12.4157 1 13.5H19.5V1.5L12.5 10.5L6.5 7.5Z" />
                                            <defs>
                                                <lineargradient x1="10.25" y1="3" x2="11" y2="13.5"
                                                    gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#2BC155" offset="1" stop-opacity="0.73" />
                                                    <stop offset="1" stop-color="#2BC155" stop-opacity="0" />
                                                </lineargradient>
                                            </defs>
                                        </svg>
                                        4%(30 days)
                                    </p>
                                </div>
                                <img src="fonts/ripple-1.svg" alt>
                            </div>
                            <div class="card-body p-0">
                                <canvas id="widgetChart3" height="75"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-header border-0 pb-0">
                                <div class="mr-auto">
                                    <h2 class="text-black mb-2 font-w600">$65,123</h2>
                                    <p class="mb-1 fs-13">
                                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 13.5C1.91797 12.4157 4.89728 9.22772 6.5 7.5L12.5 10.5L19.5 1.5"
                                                stroke="#2BC155" stroke-width="2" />
                                            <path
                                                d="M6.5 7.5C4.89728 9.22772 1.91797 12.4157 1 13.5H19.5V1.5L12.5 10.5L6.5 7.5Z" />
                                            <defs>
                                                <lineargradient x1="10.25" y1="3" x2="11" y2="13.5"
                                                    gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#2BC155" offset="1" stop-opacity="0.73" />
                                                    <stop offset="1" stop-color="#2BC155" stop-opacity="0" />
                                                </lineargradient>
                                            </defs>
                                        </svg>
                                        4%(30 days)
                                    </p>
                                </div>
                                <img src="fonts/litecoin-1.svg" alt>
                            </div>
                            <div class="card-body p-0">
                                <canvas id="widgetChart4" height="75"></canvas>
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
    <script>
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