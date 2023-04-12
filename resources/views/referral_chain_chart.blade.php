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

    <link rel="stylesheet" href="{{asset('gene_chart/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('gene_chart/css/jquery.orgchart.css')}}">
    <link rel="stylesheet" href="{{asset('gene_chart/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('gene_chart/css/style_1.css')}}">

    <style>
        .download {
          padding: 1.25rem;
          border: 0;
          border-radius: 3px;
          background-color: #4F46E5;
          color: #fff;
          cursor: pointer;
          text-decoration: none;
        }
    
        .download:hover {
          color: #fff
        }
    
        #carbonads {
          display: block;
          overflow: hidden;
          max-width: 728px;
          position: relative;
          font-size: 22px;
          box-sizing: content-box
        }
    
        #carbonads>span {
          display: block
        }
    
        #carbonads a {
          color: #4F46E5;
          text-decoration: none
        }
    
        #carbonads a:hover {
          color: #4F46E5
        }
    
        .carbon-wrap {
          display: flex;
          align-items: center
        }
    
        .carbon-img {
          display: block;
          margin: 0;
          line-height: 1
        }
    
        .carbon-img img {
          display: block;
          height: 90px;
          width: auto
        }
    
        .carbon-text {
          display: block;
          padding: 0 1em;
          line-height: 1.35;
          text-align: left
        }
    
        @media only screen and (min-width:320px) and (max-width:759px) {
          .carbon-text {
            font-size: 14px
          }
        }

        .orgchartFinal {
            display: inline-block;
            position: relative;
            top: 30px;
            min-height: 202px;
            min-width: 202px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-size: 10px 10px;
            border: 1px dashed rgba(0,0,0,0);
            transition: border .3s;
            padding: 20px;
        }
      </style>
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
        <div class="content-body" style="overflow-x: scroll">
            <!-- row -->
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="height: 600px;">
                                <h5 class="card-title">My Geneology</h5>
                                <hr>

                                <div id="chart-container"></div>
                                
                            
                                @if (empty($top_level))
                                    <?php
                                        $top_level = "N/A";
                                    ?>
                                @endif
                                @if (empty($pararal_agent))
                                    <?php
                                        $pararal_agent = "N/A";
                                    ?>
                                @endif
                                @if (empty($top_level))
                                    <?php
                                        $top_level = "N/A";
                                    ?>
                                @endif

                                

                                <input type="hidden" value="{{$top_level}}" id="topAgent">
                                <input type="hidden" value="{{ $pararal_agent }}" id="pararrelAgent">
                                <input type="hidden" value="{{ $user->username }}" id="me">
                                <input type="hidden" value="{{$first}}" id="levelOneFirst">
                                <input type="hidden" value="{{$second}}" id="levelOneSec">
                                <input type="hidden" value="" id="levelTwoFirst">
                                <input type="hidden" value="" id="levelTwoSec">
                                <input type="hidden" value="{{ $f_u_1 }}" id="f_u_1">
                                <input type="hidden" value="{{ $f_u_2 }}" id="f_u_2">
                                <input type="hidden" value="{{ $s_u_1 }}" id="s_u_1">
                                <input type="hidden" value="{{ $s_u_2 }}" id="s_u_2">
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
    <script src="js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="{{asset('gene_chart/js/html2canvas.js')}}"></script>
    <script type="text/javascript" src="{{asset('gene_chart/js/jquery.orgchart.js')}}"></script>
    <script type="text/javascript" src="{{asset('gene_chart/js/scripts.js')}}"></script>
    {{-- <script>
        var count = 1;
        var chart = new OrgChart(document.getElementById("tree"), {
            nodeBinding: {
                field_0: "name"
            },
            nodes: [
                { id: 1, name: document.getElementById("topAgent").value },
                { id: 2, pid: 1, name: document.getElementById("pararrelAgent").value },
                { id: 3, pid: 1, name: document.getElementById("me").value },

                {id: 4, pid: 3 ,name: document.getElementById("levelOneFirst").value},
                {id: 5, pid: 3 , name: document.getElementById("levelOneSec").value},

                {id: 6, pid: 2 , name: document.getElementById("levelTwoFirst").value},
                {id: 7, pid: 2 , name: document.getElementById("levelTwoSec").value}
            ]
        });
    </script>  --}}
    <script>
        $(document).ready(function () {
            $('#kycInfoTable').DataTable();
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