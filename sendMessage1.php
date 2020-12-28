<?php

session_start();

if (isset($_SESSION["uid"])) {
    //echo "UID is set <BR>";
    $uid = $_SESSION["uid"];
} else {
    //echo "UID not set <BR>";
}
if (isset($_SESSION["isadmin"])) {
    //echo "isadmin is true <BR>";
    $isadmin = TRUE;
}

$user_mob = $_GET['user_mob_sm1'];
echo "mob numer is $user_mob";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Secured Chat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <style>
        .login-form {
            width: 340px;
            margin: 50px auto;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .input-group-addon .fa {
            font-size: 18px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }

        .bottom-action {
            font-size: 14px;
        }
    </style>
</head>


<body>


    <!-- Header section goes here -->
    <div class="container-fluid text-center bg-primary text-white pt-3">
        <h1>RSA CRYPTOGRAPHY AND STEGANOGRAPHY</h1>
        <h2>Cyber Security Academic Project - Fall Semester 2020</h2>
        <h4>Done by - K Gokul Raj, Rahul Sanjeev, Aradhya Bagrodia</h4>
        <br>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="index2.php">HOME</a>

            <!-- Rightside navbar Links: Set based on User signed-in or not-->
            <?php
            if (isset($_SESSION["uid"])) {

            ?>
                <!-- Set rightside navbar links if no user signed-in -->
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown text-info"><a class="dropdown-toggle" data-toggle="dropdown">
                            <?php if ($isadmin == 1) { ?> <i class="fa fa-usersecret"></i> <?php } ?> Welcome
                            <?php echo $uid; ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"> <i class="fa fa-user-plus"></i> My Profile</a></li>
                            <li><a href="./logout.php"> <i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>

            <?php } else { ?>
                <!-- Set rightside navbar links if user has signed-in -->
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php"><i class="fa fa-sign-in"></i> Login</a>
                    </li>
                </ul>
            <?php } ?>

        </div>
    </nav>

    <div class="row">

        <div class="col-sm-2">
            <div class="container ">
                <div class="row">

                    <!-- left side nvertical navigation bar starts here -->

                    <nav class="navbar bg-light">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="sendMessage1.php">Send Message</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="sendMessageA.php">About us</a>
                            </li>
                        </ul>
                    </nav>

                    <!-- left side nvertical navigation bar ends here -->
                </div>

            </div>
        </div>
        <div class="col-sm-1">
        </div>
        <div class="col-sm-8">


            <!--include specific code for your screen -->
            <div class="container" style=" width:80% ">
                <form action="./Bpython/RsaSender.py" method="post" class="was-validated" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col">

                            <div class="form-group">
                                <label class="font-weight-bold" for="rec_mob_smpy">Enter receiver mobile number:</label>
                                <input type="text" class="form-control" id="rec_mob_smpy" placeholder="mobile number" name="rec_mob_smpy" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="sender_msg">Enter message to be sent:</label>
                                <textarea rows="3" class="form-control-file" id="sender_msg" name="sender_msg" required></textarea>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                             <div class="form-group">
                                <label class="font-weight-bold" for="filename">Image for encrytpion:</label>
                                <input type="file" class="form-control-file" id="filename" accept="image/jpg, image/jpeg, image/png, image/gif" name="filename" required />
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                                <div class="ml-2">
                                    <img src="./tmp/80x80.png" id="preview" class="img-thumbnail">
                                </div>
                            </div> 
                            <input type="hidden" class="form-control" id="user_mob_smpy" name="user_mob_smpy" value=<?php echo $user_mob; ?> />
                            <input class="form-group bg-primary text-white" type="submit" name="EncryptStegano" value="Click to send message" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
        <script>
            // Preview selected image
            $(document).on("click", ".browse", function() {
                var file = $(this).parents().find(".file");
                file.trigger("click");
            });
            $('input[type="file"]').change(function(e) {
                var fileName = e.target.files[0].name;
                $("#file").val(fileName);

                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("preview").src = e.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);

            });
        </script>  
    </div>
</body>

</html>