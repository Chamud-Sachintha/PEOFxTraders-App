<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="col d-flex justify-content-center">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Resend Verification Email</h5>
                    <hr>
                    <p>
                        In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available.
                    </p>
                    <div class="row">
                        <div class="col-12">
                            <form action="/resend_verification_link" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Request::get('user_id') }}">
                                <input type="submit" class="btn btn-success btn-sm" value="Resend Link">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>.
    </div>
</body>
</html>