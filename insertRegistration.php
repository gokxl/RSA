<?php

include './database/config/config.php';

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}


$cname = $_POST["cname"];
$cphone = $_POST["cphone"];
$cuser = $_POST["cuser"];
$cpwd = $_POST["cpwd"];
$cpwd1 = $_POST["cpwd1"];



if ($connection == "local") {
    $t_user = "user";
} else {
    $t_user = "$database.user";
}

try {
    $db = new PDO("mysql:host=$host", $user, $password, $options);
    //echo "Database connected successfully <BR>";

    $sql_insert = "INSERT INTO $t_user (user_names,user_mobileNumber,user_username,user_password)  
        VALUES ('$cname','$cphone','$cuser' ,'$cpwd')";
    echo "$sql_insert <br>";
    echo "query insertion begins here<br>";
    //echo "SQL Statement $sql_insert";
    $stmt = $db->prepare($sql_insert);
    $rows = $stmt->execute();

    echo "query has been executed<br>";


    //echo "Rows  $rows <BR>";

    if ($rows > 0) {

        echo "query has been inserted<br>";
        //echo   $rows['username'];
        //echo '<script>alert("Login Successful")</script>';
        $_SESSION["valid"] = TRUE;
        $_SESSION["uid"] = $_POST["cuser"];
        $_SESSION["pwd"] = $_POST["cpwd"];

        if (isset($_SESSION["uid"])) {
            $uid = $_SESSION["uid"];
            echo "session uid is $uid<br>";
        }
        header("Refresh: 1; URL = login.php");
        exit();
    } else {
        echo '<script>alert("Insert Appropriate values")</script>';
    }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>
<html>

<body>

</body>

</html>