<?php
/**
 * Created by PhpStorm.
 * User: bnespinal
 * Date: 9/20/2019
 * Time: 12:50 PM
 */

$pagename = "Member Registry";
include_once "header.inc.php";
?>


<?php
//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errfname = "";
$errlname = "";
$errusername = "";
$erremail = "";
$errrad = "";
$errdeposit = "";
$errcap = "";
$errpassword = "";
$errpassword2 = "";



if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * SANITIZE USER DATA
     * Here, we sanitize some of the data being entered. Certain items will be case sensitive, and others
     * need to be checked for items such as white spaces (Ex: unintended space at beginning).
     * ***********************************************************************
     */
    $formdata['fname'] = trim($_POST['fname']);
    $formdata['lname'] = trim($_POST['lname']);
    $formdata['username'] = trim($_POST['username']);
    $formdata['email'] = trim(strtolower($_POST['email']));
    if(isset($_POST['accounttype'])){$formdata['accounttype'] = $_POST['accounttype'];}else{$formdata['accounttype'] = "";}
    $formdata['password'] = $_POST['password'];
    $formdata['password2'] = $_POST['password2'];
    $formdata['deposit'] = $_POST['deposit'];


    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * This is where you check to make sure that required information is actually put in the required fields
     * ***********************************************************************
     */
    if (empty($formdata['fname'])) {$errfname = "Your first name is required."; $errmsg = 1; }
    if (empty($formdata['lname'])) {$errlname = "Your last name is required."; $errmsg = 1; }
    if (empty($formdata['username'])) {$errusername = "The username is required."; $errmsg = 1; }
    if (empty($formdata['email'])) {$erremail = "The email is required."; $errmsg = 1; }
    if (empty($formdata['deposit'])) {$errdeposit = "A deposit is required for registration."; $errmsg = 1;}
    if (empty($formdata['accounttype'])) {$errrad = "The Account Type field is required.";$errmsg = 1;}
    if (empty($formdata['password'])) {$errpassword = "The password is required."; $errmsg = 1; }
    if (empty($formdata['password2'])) {$errpassword2 = "The confirmation password is required."; $errmsg = 1; }
    if (empty($_POST['g-recaptcha-response'])) {$errcap = "The reCAPTCHA is required."; $errmsg = 1;}

    /* ***********************************************************************
     * CHECK MATCHING FIELDS
     * This is where you check some of the fields where information is being inputted, to see if important fields match
     * Usually used for passwords and sometimes emails.  We'll do passwords.
     * ***********************************************************************
     */

    if(strlen($formdata['password']) < 8 || strlen($formdata['password']) > 64 )
    {
        $errmsg = 1;
        $errpassword .= "The password does not meet length requirements. Must be greater than 8 characters or less than 64.";
    }
    if ($formdata['password'] != $formdata['password2'])
    {
        $errmsg = 1;
        $errpassword2 = "The passwords do not match.";
    }

    /* ***********************************************************************
     * CHECK EXISTING DATA
     * This is where you check for existing data to avoid duplicates
     * ***********************************************************************
     */
    //checking for exiting username
    $sql = "SELECT * FROM bankmember WHERE username = ?";
    $count = checkDup($pdo, $sql, $formdata['username']);
    if($count > 0)
    {
        $errmsg = 1;
        $errusername = "The chosen username is unavailable.";
    }


    //checking for duplicate email.
    $sql = "SELECT * FROM bankmember WHERE email = ?";
    $count = checkDup($pdo, $sql, $formdata['email']);
    if($count > 0)
    {
        $errmsg = 1;
        $erremail = "The email inputted has already been used.";
    }
    if(!filter_var($formdata['email'], FILTER_VALIDATE_EMAIL)){

        $errmsg = 1;
        $erremail = " This email is not valid";
    }

    /* ***********************************************************************
     * CONTROL STATEMENT TO HANDLE ERRORS
     * ***********************************************************************
     */
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{

        /* ***********************************************************************
         * HASH SENSITIVE DATA
         * Here is the code used for hashing passwords and other sensitive data
         * If checked for matching fields, do NOT hash and insert both to the DB
         * ***********************************************************************
         */
        $hashedpwd = password_hash($formdata['password'], PASSWORD_BCRYPT);


        /* ***********************************************************************
         * Adding in account numbers
         * Here is the code used for making the customers' account numbers. These numbers
         * can be from 10 to 12 numbers long.
         * ***********************************************************************
         */
        $accountnum = mt_rand(10000000001, 42949672958);
        $sql = "SELECT * FROM bankmember WHERE accountnum = ?";
        $count = checkDup($pdo, $sql, $accountnum);
        if($count > 0)
        {
            $accountnum = mt_rand(10000000001, 42949672958);
        }
        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * This is where we will be entering information into the database. Note: Not
         * all information will be coming from the form.
         * ***********************************************************************
         */

        try{
            $sql = "INSERT INTO bankmember (fname, lname, username, email, balance, accounttype, accountnum, password, inputdate)
                    VALUES (:fname,:lname, :username, :email, :balance, :accounttype, :accountnum, :password, :inputdate) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':fname', $formdata['fname']);
            $stmt->bindValue('lname', $formdata['lname']);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue('email', $formdata['email']);
            $stmt->bindValue('balance', $formdata['deposit']);
            $stmt->bindValue(':accounttype', $formdata['accounttype']);
            $stmt->bindValue( 'accountnum', $accountnum);
            $stmt->bindValue(':password', $hashedpwd);
            $stmt->bindValue(':inputdate', $rightnow);
            $stmt->execute();

            $showform =0; //hide the form
            echo "<p class='success'>Thanks for entering your information.</p>";

        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    }
}

if($showform == 1)
{
    ?>

    <div class="row">

    <div class="side">
    </div>
    <div class="main">
        <h1>Member Registry</h1>
    <form name="memberinsert" id="memberinsert" method="post" action="memberinsert.php">
        <table class = "center">
            <tr><th><label for="fname">First Name:</label><span class="error">*</span></th>
                <td><input name="fname" id="fname" type="text" size="20" placeholder="Required fname"
                           value="<?php if(isset($formdata['fname'])){echo $formdata['fname'];}?>"/>
                    <span class="error"><?php if(isset($errfname)){echo $errfname;}?></span></td>
            </tr>
            <tr><th><label for="lname">Last Name:</label><span class="error">*</span></th>
                <td><input name="lname" id="lname" type="text" size="20" placeholder="Required fname"
                           value="<?php if(isset($formdata['fname'])){echo $formdata['fname'];}?>"/>
                    <span class="error"><?php if(isset($errfname)){echo $errfname;}?></span></td>
            </tr>
            <tr><th><label for="username">Username:</label><span class="error">*</span></th>
                <td><input name="username" id="username" type="text" size="20" placeholder="Required username"
                           value="<?php if(isset($formdata['username'])){echo $formdata['username'];}?>"/>
                    <span class="error"><?php if(isset($errusername)){echo $errusername;}?></span></td>
            </tr>
            <tr><th><label for="email">Email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="50" placeholder="Required email"
                           value="<?php if(isset($formdata['email'])){echo $formdata['email'];}?>"/>
                    <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
            </tr>

            <tr><th>Account Type:<span class="error">*</span></th>
                <td><span class="error"><?php if(!empty($errrad)){echo $errrad . "<br />";}?></span>
                    <input type="radio" name="accounttype" id="accounttype-1" value="Checking"
                        <?php if(isset($formdata['accounttype']) && $formdata['accounttype']==1){echo " checked";}?>
                    />
                    <label for="accounttype-1">Checking</label>
                    <br/>
                    <input type="radio" name="accounttype" id="accounttype-2" value="Savings"
                        <?php if(isset($formdata['accounttype']) && $formdata['accounttype']==2){echo " checked";}?>
                    />
                    <label for="accounttype-2">Savings</label>
                </td>
            </tr>

            <tr><th><label for="deposit">Initial Deposit <br>(At least $25.00 for Checking. $50 for Savings):</label><span class="error">*</span></th>
                <td><input name="deposit" id="deposit" type="number" size="50" placeholder="Required deposit" min="1"
                           value="<?php if(isset($formdata['deposit'])){echo $formdata['deposit'];}?>"/>
                    <span class="error"><?php if(isset($errdeposit)){echo $errdeposit;}?></span></td>
            </tr>
            <tr><th><label for="password">Password:</label><span class="error">*</span></th>
                <td><input name="password" id="password" type="password" size="40" onkeyup="passwordStrength(this.value)" placeholder="Required password"  />
                    <br>
                    <div id="passwordDescription">Password not entered</div><div id="passwordStrength" class="strength0"></div>
                    <script>
                        function passwordStrength(password)
                        {
                            var desc = new Array();
                            desc[0] = "Bad";
                            desc[1] = "Needs Improvement";
                            desc[2] = "Better";
                            desc[3] = "Good";
                            desc[4] = "Strong";
                            var score   = 0;
                            if (password.length > 3) score++;
                            if (password.length > 6 ) score++;
                            if (password.length > 9 ) score++;
                            if (password.length > 12 ) score++;

                            document.getElementById("passwordDescription").innerHTML = desc[score];
                            document.getElementById("passwordStrength").className = "strength" + score;
                        }
                        var pass = document.getElementById('password');
                        pass.addEventListener('keyup', function(event) {
                            var value = this.value;
                            passwordStrength(value);
                        });
                    </script>
                    <span class="error"><?php if(isset($errpassword)){echo $errpassword;}?></span></td>
            </tr>
            <tr><th><label for="password2">Password Confirmation:</label><span class="error">*</span></th>
                <td><input name="password2" id="password2" type="password" size="40" placeholder="Required confirmation password" />
                    <span class="error"><?php if(isset($errpassword2)){echo $errpassword2;}?></span></td>
            </tr>

            <tr><th><label for="submit">Submit:</label></th>
                <td><span class="error"><?php if(isset($errcap)) {echo $errcap;}?></span>
                    <div class="g-recaptcha" data-sitekey="6LevcB0UAAAAAI_Y_dKMg-bT_USxicPojFxWTgp_"></div>
                    <input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
    </form>
        <br>
        <h3>A message from our CEO!</h3>
        <br>
        <div><img src="images/image5.jpg" class="centerImg" alt="CEO"></div>

    <p id="statement">Thank you for considering becoming a member of our friendly little community. Know that<br> we take your personal security very seriously, and the information you put in will
    never be<br> seen by unauthorized eyes.</p>

    </div>
        <div class="side">
        </div>
    </div>
    <?php
}//end showform
include_once "footer.inc.php";
?>