<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
        <title>Offices</title>
        <link href="style.css" type="text/css" rel="stylesheet">
        <!-- importing Google Fonts from https://fonts.google.com !-->
        <link href="https://fonts.googleapis.com/css?family=Roboto|Poppins|Roboto+Slab|Patua+One" rel="stylesheet">
    </head>
<body id="background">
    
<!-- add navigation bar from php file -->
<nav>
    <?php include 'menu.php';?>
</nav>

<h2 class="title"> Classic Models Ltd - INTRANET - Offices</h2>
    
<h3 class="subtitle">Offices Detail and Information</h3>

<div id="office_table">
    <!-- php for the table displayed when opening the page -->
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

    $sql = "SELECT city, addressLine1, addressLine2, phone, officeCode 
            FROM offices";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table id=\"office_table\"><tr><th>City</th><th>Address</th><th>Phone</th><th>Employees</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["city"]. "</td><td>" . $row["addressLine1"] . " - " . $row["addressLine2"]. "</td><td>" . $row["phone"]. "</td><td>" . "<form action=\"offices.php\" method=\"post\"> <button type=\"submit\" class=\"more_info\" name=\"employees\" value='$row[officeCode]'> More Info </button>
            </form>". "</td></tr>";
        }
        echo "</table>";
    } 
    else {
        echo "0 results";
    }

    ?> 
    <!-- php for the table displayed when user requests info about employees -->
    <?php
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and ($_POST['employees'])){
        // when click on the button the office code value is assigned to the variable
        $office_code = $_POST['employees'];
        // and the function is run with the office code
        func($office_code);
    }
    function func($office_code){
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

        $sql = "SELECT firstname, lastname, jobTitle, employeeNumber, email 
                FROM employees 
                WHERE officeCode = $office_code 
                ORDER BY jobTitle";

        $result = $conn->query($sql);
        // display a second table below the main one
        if ($result->num_rows > 0) {
            echo "<h3>Employees of Office $office_code</h3>";
            echo "<table id=\"office_table\"><tr><th>Full Name</th><th>Job Title</th><th>Employee Number</th><th>email</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["firstname"] . " " . $row["lastname"]. "</td><td>" . $row["jobTitle"]. "</td><td>" . $row["employeeNumber"]. "</td><td>" . $row["email"]. "</td></tr>";
            }
            echo "</table>";
        } 
        else {
            echo "0 results";
        }
    }
    $conn->close();
    ?> 
</div>
<!-- add footer from php file -->
<footer>
    <?php include 'footer.php';?>
</footer> 

</body>
</html>