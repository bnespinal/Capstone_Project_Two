<?php
/**
 * Created by PhpStorm.
 * User: bnespinal
 * Date: 9/23/2019
 * Time: 5:00 PM
 */

$pagename = "Withdraw";
include_once "header.inc.php";

?>

<?php
checkLogin();
//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errwithdraw = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * SANITIZE USER DATA
     * ***********************************************************************
     */
    $formdata['withdraw'] = $_POST['withdraw'];


    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * ***********************************************************************
     */

    if (empty($formdata['withdraw'])) {$errwithdraw = "A Withdraw is required to submit."; $errmsg = 1; }



    try{
        //query the data
        $sql = "SELECT balance FROM bankmember WHERE ID=".$_SESSION['ID'];
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();



    }
    catch (PDOException $e)
    {
        die( $e->getMessage() );
    }

    $newbalance = $result['balance'] -= $formdata['withdraw'];

    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{


        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * This is where we will be updating your password in the database! It binds the value of the
         * new password to the customer based on their unique ID number. Then you are automatically
         * logged out.
         * ***********************************************************************
         */

        try{
            $sql = "UPDATE bankmember 
                    SET balance = :balance
                    WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':balance', $newbalance);
            $stmt->bindValue(':ID', $_SESSION['ID']);
            $stmt->execute();

            $showform =0; //hide the form
            echo "<p class='success'>Thanks for entering your information.</p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    } // else errormsg
}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
    ?>

<div class="row">

    <div class="side">
    </div>
    <div class="main">
        <h1>Make a Deposit</h1>
    <form name="deposit" id="withdraw" method="post" action="withdraw.php">
        <table class = "center">
            <tr><th><label for="withdraw">Deposit:</label><span class="error">*</span></th>
                <td><input name="withdraw" id="withdraw" type="number" size="50" placeholder="Required withdraw" min="1"
                           value="<?php if(isset($formdata['withdraw'])){echo $formdata['withdraw'];}?>"/>
                    <span class="error"><?php if(isset($errwithdraw)){echo $errwithdraw;}?></span></td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
    </form>


    </div>
    <div class="side">
    </div>
</div>
    <?php
}//end showform
include_once "footer.inc.php";
?>








