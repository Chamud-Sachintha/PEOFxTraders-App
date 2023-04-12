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
    <link href="{{asset('/register/css/style.css')}}" rel="stylesheet">

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
                                    <h4 class="text-center mb-4">Sign up your account</h4>
                                    @if ($errors->any())
                                        @foreach($errors->all() as $error)
                                            <div class="alert alert-danger" role="alert">
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if (Session()->has('valid_username'))
                                        <div class="alert alert-success" role="alert">
                                            {{ Session()->get('valid_username') }}
                                        </div>
                                    @endif
                                    @if (Session()->has('status'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ Session()->get('status') }}
                                        </div>
                                    @endif
                                    <form action="/user/create" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>First name</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="fname">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Middle Name</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="mname">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Last name</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="lname">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Username</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Referal No</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="refNo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Country</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="country">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Mobile No</strong></label>
                                                    <input type="text" class="form-control" placeholder="" name="mobile">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" class="form-control" placeholder="" name="email">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Address</strong></label>
                                            <input type="text" class="form-control" placeholder="" name="address">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>State</strong></label>
                                                    <input type="text" class="form-control" placeholder="" name="state">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>ZIP Code</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="zipcode">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Gender</strong></label>
                                                    <select id="inputState" class="form-control" tabindex="-98" name="gender">
                                                        <option selected="">Choose...</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Birthday</strong></label>
                                                    <input type="date" class="form-control" name="bdate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Wallet Type</strong></label>
                                                    <select id="inputState" class="form-control" tabindex="-98" name="wtype">
                                                        <option selected="">Choose...</option>
                                                        <option value="trc20">TRC-20</option>
                                                        <option value="bep">BEP</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="mb-1"><strong>Wallet ID</strong></label>
                                                    <input type="text" class="form-control" placeholder=" " name="wid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Confirm Password</strong></label>
                                            <input type="password" class="form-control" name="conf_password">
                                        </div>
                                        <div class="text-center mt-4">
                                            <input type="hidden" value="data:image/png;base64,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" name="profile_image">
                                            <button type="submit" class="btn btn-primary btn-block">Sign me up</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Already have an account? <a class="text-primary"
                                                href="/signin">Sign in</a>
                                        </p>
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