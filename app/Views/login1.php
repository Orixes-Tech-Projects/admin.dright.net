<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>D-Right aPanel</title>
    <link rel="shortcut icon" href="<?= $template ?>assets/media/image/favicon.png"/>
    <link rel="stylesheet" href="<?= $template ?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?= $template ?>assets/css/app.min.css" type="text/css">
    <script src="<?= $template ?>vendors/bundle.js"></script>
    <script type="text/javascript" charset="utf-8">
        localStorage.setItem('path', '<?= $path ?>');
        localStorage.setItem('template', '<?= $template ?>');
    </script>
    <script type="text/javascript" src="<?= $template ?>custom.js"></script>
</head>

<body class="form-membership"
      style="background: url(<?= $template ?>login-bg.png);background-repeat: no-repeat;background-position: top left;background-size: cover;">

<div class="form-wrapper">

    <!-- logo -->
    <div id="logo">
        <!-- <img class="logo" src="<?= $template ?>logo.png" style="width: 100%;" alt="logo"> -->
        D-Right Admin Panel
    </div>
    <!-- ./ logo -->


    <h4>Master Control Panel </h4>

    <!-- form -->
    <form class="user validate" method="post" id="UserForm" name="UserForm">
        <div class="form-group">
            <input type="email" class="form-control form-control-user"
                   id="UserName" name="UserName" aria-describedby="emailHelp"
                   placeholder="Enter Email...">
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-user"
                   id="Password" name="Password" placeholder="Password">
        </div>

        <hr>

        <a href="javascript:void(0);" type="submit"

           class="btn btn-primary btn-block" style="color: white;">

            Login

        </a>

    </form>
    <div class="text-center mt-5">
        <div class="ajaxResponse" id="ajaxResponse">

        </div>
    </div>
    <!-- ./ form -->
</div>
<script>

    document.getElementById('UserForm').addEventListener('submit', function (event) {
        event.preventDefault();
        LoginSubmit();
    });

    // Detect Enter key inside input fields and trigger form submission
    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent default form submission
            LoginSubmit(); // Call your submit function
        }
    });

    function LoginSubmit() {
        var Email = document.getElementById('UserName').value; // Use 'inputEmail' here
        var Password = document.getElementById('Password').value; // Use 'inputPassword' here

        if (Email == '') {
            document.getElementById('UserName').focus();
        } else if (Password == '') {
            document.getElementById('Password').focus();
        } else {

            var formdata = new window.FormData($("form#UserForm")[0]);
            response = AjaxUploadResponse("use-login-submit", formdata);

            if (response.status === 'success') {
                $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
                setTimeout(function () {
                    // location.reload();
                    location.href = '<?= $path ?>';
                }, 500);
            } else {
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            }

        }
    }

    function SubmitLogin() {

        var formdata = new window.FormData($("form#UserForm")[0]);

        var response = AjaxUploadResponse("use-login-submit", formdata);

        if (response.status == 'success') {

            setTimeout(function () {

                $("#Status").html('<div class="text-center alert alert-success mb-4 mt-4" role="alert">  <strong>' + response.message + '</strong> </div>');

                window.location.href = '<?= $path ?>';

            }, 1000);

        } else {

            $("#Status").html('<div class="text-center alert alert-danger mb-4 mt-4" role="alert">  <strong>' + response.message + '</strong> </div>');

        }

    }

</script>
<!-- App scripts -->
<script src="<?= $template ?>assets/js/app.min.js"></script>
</body>

</html>