<?php
/**
 * Created by PhpStorm.
 * User: bnespinal
 * Date: 9/23/2019
 * Time: 4:00 PM
 */

$pagename = "Deposit";
include_once "header.inc.php";

?>

<?php
checkLogin();
//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errdeposit = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * SANITIZE USER DATA
     * ***********************************************************************
     */
    $formdata['deposit'] = $_POST['deposit'];


    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * ***********************************************************************
     */

    if (empty($formdata['deposit'])) {$errdeposit = "A deposit is required to submit."; $errmsg = 1; }



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

    $newbalance = $result['balance'] += $formdata['deposit'];

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
    <form name="deposit" id="deposit" method="post" action="deposit.php">
        <table class = "center">
            <tr><th><label for="deposit">Deposit:</label><span class="error">*</span></th>
                <td><input name="deposit" id="deposit" type="number" size="50" placeholder="Required deposit" min="1"
                           value="<?php if(isset($formdata['deposit'])){echo $formdata['deposit'];}?>"/>
                    <span class="error"><?php if(isset($errdeposit)){echo $errdeposit;}?></span></td>
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








