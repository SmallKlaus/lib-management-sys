<?php

include '../res/assets/globalfunctions.php';

if(!isuserlogged())
{
    header('location:../user_login.php');
}

weekly_billing();
daily_fining();

$success_message = '';

if(isset($_POST['apply']))
{
    $data = array(
        ':user_name' => $_POST['user_name'],
        ':user_address' => $_POST['user_address'],
        ':contact_number' => $_POST['contact_number'],
        ':updated_on'   => getdateandtime(),
        ':user_id'  => $_SESSION['user_id']
    );

    $query = "UPDATE user_accounts
                SET user_name = :user_name,
                user_address = :user_address,
                contact_number = :contact_number,
                updated_on = :updated_on
                WHERE user_id = :user_id";
    
    $stat = $connect->prepare($query);
    $stat->execute($data);
    $success_message = '<li>Changes applied successfully.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';

}

$user_id= $_SESSION['user_id'];
$query = "SELECT * FROM user_accounts WHERE user_id = '$user_id' LIMIT 1";
$row = $connect->query($query);

include 'navcomp.php';
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings . LibraryMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/setting.png" alt="Settings" style="height: 4rem ;">
            | Settings
            
        </div>
        <form method="POST" class="card" style="width: 70%;">
            <?php 
                foreach($row as $info)
                {
            ?>
            <div>
                <label for="user_name" style="margin-right: auto;">Username : </label>
                <input type="text" name="user_name" id="user_name" value="<?php echo $info['user_name']; ?>">
            </div>       
            <div>
                <label for="user_address" style="margin-right: auto;">User address : </label>
                <input type="text" name="user_address" id="user_address" value="<?php echo $info['user_address']; ?>">
            </div>    
            <div>
                <label for="contact_number" style="margin-right: auto;">Phone number : </label>
                <input type="text" name="contact_number" id="contact_number" value="<?php echo $info['contact_number']; ?>">
            </div>       
            <div>
                <label for="user_bill" style="margin-right: auto;">Billing : </label>
                <p id="user_bill"><?php echo $info['user_bill'].' $'; ?></p>
            </div>    
            <div>
                <label for="user_borrowed" style="margin-right: auto;">Number of currently borrowed books: </label>
                <p id="user_borrowed"><?php echo $info['user_borrowed'].' Books'; ?></p>
            </div>     
            <input type="submit" name="apply" value="Apply" style=" width: fit-content;">
            <?php
                }
            ?>   
        </form>
        <?php
            if($success_message != '')
            {
                echo '<div id= "succ" class="messageBox success" style=" margin-top: 20px;"><ul>'.$success_message.'</ul></div>';
            }
            ?>
    </div>
</body>
</html>