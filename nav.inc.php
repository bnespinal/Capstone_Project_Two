<?php
/**
 * Created by PhpStorm.
 * User: bnespinal
 * Date: 9/22/2019
 * Time: 11:29 AM
 */

/* ***********************************************************************
         * LOGGING IN
         * Here we have our navigation file which creates our navigation bar on the site.
         * Certain pages require members to be logged in to be able to see and access.
         * ***********************************************************************
         */

?>
<ul id="nav">
    <?php
        echo ($currentfile == "index.php") ? "<li>Home</li>" : "<li><a href='index.php'>Home</a></li>";
        echo ($currentfile == "memberinsert.php") ? "<li>Register</li>" : "<li><a href='memberinsert.php'>Register</a></li>";
        if (isset($_SESSION['ID'])) {
            echo "<li><a href='memberpwd.php?ID=" . $_SESSION['ID'] ."'>Update My Password</a>";
            echo "<li><a href='transactions.php?ID=" . $_SESSION['ID'] ."'>Handle Finances</a>";
            echo "<li><a href='logout.php'>Log Out</a></li>";
            echo "Welcome, " . $_SESSION['username'];
        } else {
            echo "<li><a href='login.php'>Log In</a></li>";
        }
    ?>
</ul>

