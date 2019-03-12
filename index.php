<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
        <title>Home Page</title>
        <link href="style.css" type="text/css" rel="stylesheet">
        <!-- importing Google Fonts from https://fonts.google.com !-->
        <link href="https://fonts.googleapis.com/css?family=Roboto|Poppins|Roboto+Slab|Patua+One" rel="stylesheet">
    </head>
    
<body id="background">
<!-- add navigation bar from php file -->
<nav>
<?php include 'menu.php';?>
</nav>
    
<h2 class="title"> Classic Models Ltd - INTRANET - home page</h2>
    
<h3 class="subtitle"> Major product lines available from the <i>Classic Models</i> company</h3>

<div id="home_table">
    <?php
    //error handler function
    function customError($errno, $errstr) {
    // print a custom message for the user 
        echo "<b>Error!</b> <br> <br> Error type: $errstr <br> <br> Error number: $errno<br>";
    // when an error occurs the die() function prevents the script from running (to avoid error repetition and possible more errors)
        die();
    }
    //set error handler
    set_error_handler("customError");

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "classicmodels";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Error handling, gives an error if the connection fails
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT productLine, textDescription 
            FROM productlines";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>Product</th><th>Description</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["productLine"]. "</td><td>" . $row["textDescription"] . "</td></tr>";
        }
        echo "</table>";
    } 
    else {
        // if the query gives no result we print an Error
        echo "Error: 0 results found.";
    }
    ?>   
</div>  
<!-- add footer from php file -->
<footer>
    <?php include 'footer.php';?>
</footer>    
</body>
</html>