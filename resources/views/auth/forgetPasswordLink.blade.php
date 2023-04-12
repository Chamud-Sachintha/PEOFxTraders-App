<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome To PeoFx | Dashboard</title>
    <meta name="description" content="Some description for the page">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="{{asset('login/css/style.css')}}" rel="stylesheet">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    @if (Session()->has('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ Session()->get('status') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger" role="alert">{{ $errors->first('email') }}</div>
                                    @endif
                                    <form action="{{ route('reset.password.post') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email Address</strong></label>
                                            <input type="text" class="form-control" name="email" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Confirm Password</strong></label>
                                            <input type="password" class="form-control"  name="password_confirmation" required autofocus>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Reset My Password</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Don't have an account? <a class="text-primary"
                                                href="/signup">Sign
                                                up</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('/login/js/global.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/loginjs/bootstrap-select.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/login/js/custom.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/login/js/deznav-init.js')}}" type="text/javascript"></script>

</body>

</html>