<?php

session_start();

if (isset($_SESSION["uid"])) {
    //echo "UID is set <BR>";
    $uid = $_SESSION["uid"];
} else {
    header('Refresh:1   ; url=./login.php');
    echo 'Please Log In First';
    exit();
}

$user_mob = $_GET['user_mob_rmA'];
echo "mob numer is $user_mob";


include './database/config/config.php';
if ($connection == "local") {
    $t_message = "messageLog";
} else {
    $t_message = "$database.messageLog";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";
    
    //Fetch customer details and total bookings done by the customer
    $cmessages = $db->query("Select count(*) as tcount from $t_message where receiverMob =$user_mob")->fetch()['tcount'];

    if ($cmessages > 0) {
        $i = 1;
        foreach ($db->query("SELECT  msg_id,senderMob FROM $t_message WHERE receiverMob =$user_mob") as $rs1) {
            $myMessages[$i]['msg_id']    = $rs1['msg_id'];
            $myMessages[$i]['senderMob']    = $rs1['senderMob'];
            $myMessages[$i]['receiverMob']    = $user_mob;
            $i++;
        }
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br />";
    die();
}
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
                                <a class="nav-link" href="receiveMessage1.php">Generate Key</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="receiveMessageA.php">See Messages</a>
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
                <label class="font-weight-bold">My Message History:</label>
                <div class="row justify-content-center">
                    <div class="container">
                        <table id="myTable" class=" table order-list table-bordered table-sm">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Message_ID</th>
                                    <th>Sender Mobile Number</th>
                                    <th>Receiver Mobile Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 1; $i <= $cmessages; $i++) {
                                    echo "<tr><td><a href=./receiveMessageB.php?msglog_id=" . $myMessages[$i]['msg_id'] . ">" . $myMessages[$i]['msg_id'] . "</td>";
                                    echo "<td>" . $myMessages[$i]['senderMob'] . "</td>";
                                    echo "<td>" . $myMessages[$i]['receiverMob'] . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <BR>
            </div>
        </div>
    </div>

</body>

</html>