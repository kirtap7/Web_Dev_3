<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
        <title>Payments</title>
        <link href="style.css" type="text/css" rel="stylesheet">
        <!-- importing Google Fonts from https://fonts.google.com !-->
        <link href="https://fonts.googleapis.com/css?family=Roboto|Poppins|Roboto+Slab|Patua+One" rel="stylesheet">
    </head>
<body id="background">

<!-- add navigation bar from php file -->
<nav>
    <?php include 'menu.php';?>
</nav>
    
<h2 class="title"> Classic Models Ltd - INTRANET - Payments</h2>

<h3 class="subtitle">Recent Payments Information</h3>

<div class="table_column">

    <!-- dropdown menu to select number of results to display -->
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <label id="dropdown" for='selected_results'>Select the number of results:</label><br>
        <select name="selected_results">
        <option value="20">20</option>
        <option value="40">40</option>
        <option value="60">60</option>
        </select><br>
        <input id="submit" type="submit" name="formSubmit" value="Submit" >
    </form>
    <!-- source http://form.guide/php-form/php-form-select.html -->
    
    <div id="payment_table">
        <!-- php for main table -->
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

        // get the selected amount of results to display
        if(isset($_POST['formSubmit'])){
            $display_result = $_POST['selected_results'];
        } 
        // if nothing is selected, as default, 20 most recent results will be displayed
        else{
            $display_result = 20;
        }
        // query to get most recent results with set limit
        $sql = "SELECT checkNumber, paymentDate, amount, customerNumber 
                FROM payments
                ORDER BY paymentDate DESC 
                LIMIT $display_result";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table id=\"payment_table\"><tr><th>check number</th><th>payment date</th><th>amount</th><th>customer number</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["checkNumber"]. "</td><td>" . $row["paymentDate"] . "</td><td>" . $row["amount"]. "</td><td>" . "<form action=\"payments.php\" method=\"post\"> <input type=\"submit\" class=\"more_info\" name=\"customers\" value='$row[customerNumber]'/>
                </form>" . "</td></tr>";
            }
            echo "</table>";
        } 
        else {
            echo "0 results";
        }
        ?>
    </div>

    <div id="extra">
        <!-- php for extra info table -->
        <?php

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['customers'])){
            // when click on the button the customer code value is assigned to the variable
            $customer_code = $_POST['customers'];
            // and the function to get customer info is run with the customer code
            func_customer($customer_code);
        }
        function func_customer($customer_code){
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
            
            // this query performs a join between 2 tables to extract information about customer
            $sql = "SELECT c.phone, c.salesRepEmployeeNumber, c.creditLimit, ROUND(SUM(p.amount), 2), c.customerNumber
                    FROM customers c, payments p
                    WHERE c.customerNumber = p.customerNumber AND c.customerNumber = $customer_code";

            $result = $conn->query($sql);
            
            // display a table next the main one
            if ($result->num_rows > 0) {
                echo "<h3>Customer $customer_code information</h3>";
                echo "<table id=\"extra\"><tr><th>Phone Number</th><th>Sales Rep</th><th>Credit Limit</th><th>Payments Total</th><th>Payment History</th></tr>";
                // output data of each row
                // as default it's displayed phone number, sales rep, credit limit and the total amount paid by the customer. To get the payment history use the specific button
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["phone"] . "</td><td>" . $row["salesRepEmployeeNumber"]. "</td><td>" . $row["creditLimit"]. "</td><td>" . $row["ROUND(SUM(p.amount), 2)"]. "</td><td>" . "<form action=\"payments.php\" method=\"post\"> <button type=\"submit\" class=\"more_info\" name=\"payments\" value='$row[customerNumber]'> More Info </button>
                </form>". "</td></tr>";
                }
                echo "</table>";
            } 
            else {
                echo "0 results";
            }
        }

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['payments'])){
            // when click on the button the customer code value is assigned to the variable
            $customer_code = $_POST['payments'];
            // and the function to get customer payments is run with the customer code
            func_payments($customer_code);
        }
        function func_payments($customer_code){
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
            
            // extract the payment history 
            $sql = "SELECT checkNumber, paymentDate, amount, customerNumber
                    FROM payments 
                    WHERE customerNumber = $customer_code";

            $customerNumber = $customer_code;
            $result = $conn->query($sql);
            
            // replace previous table with a new table containing payment history 
            if ($result->num_rows > 0) {
                echo "<h3>Payment History of customer $customerNumber</h3>";
                echo "<table id=\"extra\"><tr><th>check number</th><th>payment date</th><th>amount</th></tr>";
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["checkNumber"] . "</td><td>" . $row["paymentDate"]. "</td><td>" . $row["amount"]. "</td></tr>";
                }
                echo "</table>"
                    ;
            } 
            else {
                echo "0 results";
            }
        }

        $conn->close();
        ?> 
    </div>
</div>
<!-- add footer from php file -->
<footer>
    <?php include 'footer.php';?>
</footer> 
</body>
</html>