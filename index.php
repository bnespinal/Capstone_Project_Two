<?php
/**
 * Created by PhpStorm.
 * User: B.Espinal
 * Date: 9/20/2019
 * Time: 9:07 AM
 */
$pagename = "Welcome";
require_once "header.inc.php";
?>



<div class="row">
    <div class="side">
    </div>
    <div class="main">
        <h1>Welcome to Ben's Bank</h1>
        <h5>Taking care of peoples' banking needs since 2019!</h5>
        <div><img src="images/image4.jpg" width="1153" alt="Welcome_sign"></div>
        <p id="statement">This bank site is a capstone project for the course CSCI 495.</p>
        <br>
        <h2>Free Checking</h2>
        <p>Our most popular checking account. Get started with:</p>
        <ul>
            <li>No Monthly Fees</li>
            <li>No minimum balance requirements</li>
            <li>Initial deposit is $50</li>
        </ul>
        <h2>Your Savings</h2>
        <p>Make it simple for you to save a little, or a lot:</p>
        <ul>
            <li>Low interest rate</li>
            <li>No minimum balance requirements</li>
            <li>Initial deposit is $25</li>
        </ul>
    </div>
    <div class="side">
    </div>
</div>


<?php
require_once "footer.inc.php";
?>
