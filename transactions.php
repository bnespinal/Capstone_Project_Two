<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 9/23/2019
 * Time: 4:30 PM
 */
$pagename = "About Us";
include_once "header.inc.php";
?>

<div class="row">
    <div class="side">
    </div>
    <div class="main">
        <h1>Handle Your Finances</h1>
        <br>
        <p id="statement"><a href='deposit.php'>Make a Deposit</a></p>
        <p id="statement"><a href='withdraw.php'>Make a Withdraw</a></p>
    </div>
    <div class="side">
    </div>
</div>

<?php
include_once "footer.inc.php";
?>
