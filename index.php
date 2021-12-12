<!doctype html>
<?php
include('config.php');

$login_button = '';


if (isset($_GET["code"])) {

    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);


    if (!isset($token['error'])) {

        $google_client->setAccessToken($token['access_token']);


        $_SESSION['access_token'] = $token['access_token'];


        $google_service = new Google_Service_Oauth2($google_client);


        $data = $google_service->userinfo->get();


        if (!empty($data['given_name'])) {
            $_SESSION['user_first_name'] = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if (!empty($data['email'])) {
            $_SESSION['user_email_address'] = $data['email'];
        }

        if (!empty($data['gender'])) {
            $_SESSION['user_gender'] = $data['gender'];
        }

        if (!empty($data['picture'])) {
            $_SESSION['user_image'] = $data['picture'];
        }
    }
}


if (!isset($_SESSION['access_token'])) {

    $login_button = '<a href="' . $google_client->createAuthUrl() . '"><div class="google text-center mr-3">
                                    <div class="fa fa-google-plus"></div>
                                </div></a>';
}
?>
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Quantumm Hunts Login</title>
        <link href="css/style.css" rel="stylesheet" media="all"/>
        <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
        <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '1562721637417127',
                    cookie: true,
                    xfbml: true,
                    version: 'v3.1'
                });
                FB.AppEvents.logPageView();
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            function fbLogin() {
                FB.login(function (response) {
                    if (response.authResponse) {
                        fbAfterLogin();
                    }
                });
            }

            function fbAfterLogin() {
                FB.getLoginStatus(function (response) {
                    if (response.status === 'connected') {   // Lo
                        FB.api('/me', function (response) {
                            jQuery.ajax({
                                url: 'check_login.php',
                                type: 'post',
                                data: 'name=' + response.name + '&id=' + response.id,
                                success: function (result) {
                                    window.location.href = 'index.php';
                                }
                            });
                        });
                    }
                });
            }
        </script>
    </head>

    <?php
    if ($login_button == '') {
        echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
        echo '<img src="' . $_SESSION["user_image"] . '" class="img-responsive img-circle img-thumbnail" />';
        echo '<h3><b>Name :</b> ' . $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'] . '</h3>';
        echo '<h3><b>Email :</b> ' . $_SESSION['user_email_address'] . '</h3>';
        echo '<h3><a href="logout.php">Logout</h3></div>';
    } else if (isset($_SESSION['FB_ID']) && $_SESSION['FB_ID'] != '') {
        echo 'Welcome ' . $_SESSION['FB_NAME'];
        echo "<br/>";
        ?>
        <a href="fblogout.php">Logout</a>
        <?php
    } else {
        ?>
        <body oncontextmenu='return false' class='snippet-body'>
            <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
                <div class="card card0 border-0">
                    <div class="row d-flex">
                        <div class="col-lg-6">
                            <div class="card1 pb-5">
                                <div class="row"> <img src="Images/QH.jfif" class="logo"> </div>
                                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img
                                        src="Images/cover.png" class="image"> </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card2 card border-0 px-4 py-5">
                                <div class="row mb-4 px-3">
                                    <h6 class="mb-0 mr-4 mt-2">Sign in with</h6>

                                    <a href="javascript:void(0)" onclick="fbLogin()">
                                        <div class="facebook text-center mr-3">
                                            <div class="fa fa-facebook"></div>
                                        </div>
                                    </a>
                                    <?php echo $login_button; ?>
                                </div>
                                <div class="row px-3 mb-4">
                                    <div class="line"></div> <small class="or text-center">Or</small>
                                    <div class="line"></div>
                                </div>
                                <div class="row px-3"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Email Address</h6>
                                    </label> <input class="mb-4" type="text" name="email"
                                                    placeholder="Enter a valid email address"> </div>
                                <div class="row px-3"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Password</h6>
                                    </label> <input type="password" name="password" placeholder="Enter password"> </div>
                                <div class="row px-3 mb-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"> <input id="chk1"
                                                                                                              type="checkbox" name="chk" class="custom-control-input"> <label for="chk1"
                                                                                                              class="custom-control-label text-sm">Remember me</label> </div> <a href="#"
                                                                                                       class="ml-auto mb-0 text-sm">Forgot Password?</a>
                                </div>
                                <div class="row mb-3 px-3"> <button type="submit"
                                                                    class="btn btn-blue text-center">Login</button> </div>
                                <div class="row mb-4 px-3"> <small class="font-weight-bold">Don't have an account? <a
                                            class="text-danger ">Register</a></small> </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue py-4">
                        <div class="row px-3"> <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2021. All rights
                                reserved.</small>
                            <div class="social-contact ml-4 ml-sm-auto"> <span class="fa fa-facebook mr-4 text-sm"></span> <span
                                    class="fa fa-google-plus mr-4 text-sm"></span> <span
                                    class="fa fa-linkedin mr-4 text-sm"></span> <span
                                    class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span> </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type='text/javascript'
            src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'></script>
        </body>
    </html>
    <?php
}
?>


