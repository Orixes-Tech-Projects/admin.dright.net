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

        <button type="submit" class="btn btn-primary btn-block" style="color: white;">
            Login
        </button>

    </form>
    <div class="text-center mt-5">
        <div class="ajaxResponse" id="ajaxResponse"></div>
    </div>
    <!-- ./ form -->
</div>

<script>
    // Handle form submission
    document.getElementById('UserForm').addEventListener('submit', function (event) {
        event.preventDefault();
        handleLogin();
    });

    // Handle button click (though the form submit will handle this automatically)
    document.querySelector('#UserForm button[type="submit"]').addEventListener('click', function (event) {
        event.preventDefault();
        handleLogin();
    });

    function handleLogin() {
        var Email = document.getElementById('UserName').value;
        var Password = document.getElementById('Password').value;

        if (!Email) {
            document.getElementById('UserName').focus();
            return;
        }

        if (!Password) {
            document.getElementById('Password').focus();
            return;
        }

        var formdata = new FormData(document.getElementById('UserForm'));

        // Assuming AjaxUploadResponse is defined in your custom.js
        var response = AjaxUploadResponse("use-login-submit", formdata);

        if (response.status === 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                window.location.href = '<?= $path ?>';
            }, 500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }

    // Optional: Add keypress listener for Enter key in input fields
    document.getElementById('UserName').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleLogin();
        }
    });

    document.getElementById('Password').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleLogin();
        }
    });
</script>

<!-- App scripts -->
<script src="<?= $template ?>assets/js/app.min.js"></script>
</body>
</html>