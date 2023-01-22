<?php

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}



$error_message = '';
$success_message = '';

if(isset($_POST['edit_account']))
{
    $data = array();
    if(empty($_POST['admin_email']))
    {
        $error_message .= '<li>E-mail address field must be filled.</li>';
    }
    else
    {
        if(!filter_var($_POST['admin_email'], FILTER_VALIDATE_EMAIL))
        {
            $error_message .= '<li> E-mail address is invalid.</li>';
        }
        else
        {
            $data['admin_email']=$_POST['admin_email'];
        }
    }
    if(empty($_POST['admin_password']))
    {
        $error_message .= '<li>Password field must be filled.</li>';
    }
    else
    {
        if(empty($_POST['confirm_password']))
        {
            $error_message .= '<li>Please confirm your new password.</li>';
        }
        else
        {
            if($_POST['confirm_password']!=$_POST['admin_password'])
            {
                $error_message .= '<li>The password fields dont\'t match.</li>';
            }
            else
            {
                $data['admin_password']=$_POST['admin_password'];
            }
        }
    }

    if($error_message == '')
    {
        $admin_id = $_SESSION['admin_id'];
        $query_data = array(
            ':admin_email' => $data['admin_email'],
            ':admin_password' => $data['admin_password'],
            ':admin_id' => $admin_id
        );

        $update_query = "UPDATE admin_accounts
                            SET admin_email = :admin_email, admin_password = :admin_password
                            WHERE admin_id = :admin_id";

        $stat = $connect->prepare($update_query);
        $stat->execute($query_data);
        $success_message = '<li>Account information has been successfully edited. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
    }
}

$query = "SELECT * FROM admin_accounts
          WHERE admin_id = '".$_SESSION['admin_id']."'";
$result = $connect->query($query);

include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account . LibraryMS</title>
</head>

<body>
    
<div class="content">
        <div class="head_title">
            <img src="../res/Icons/account.png" alt="Settings" style="height: 4rem ;"> | Account
        </div>
    <?php
        foreach($result as $account)
        {
    ?>

    <form class="card" method="POST" >
        <div>
            <label for="admin_email">
                <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Email address :
            </label>
            <input type="text" name="admin_email" id="admin_email" autocomplete="off" value="<?php echo $account['admin_email']; ?>"/>
        </div>
        <div>
            <label for="admin_password">
                <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit password :
            </label>
            <input class="password" type="password" name="admin_password" id="admin_password" value="<?php echo $account['admin_password']; ?>"/>
        </div>
        <div>
            <label for="confirm_password">
            <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Confirm password :
            </label>
            <input class="confirm_password" type="password" name="confirm_password" id="confirm_password" onkeyup="pwdmatching()"/>
            <p id="symbol" style="font-size:12px; margin-left:auto; margin-top:auto;"></p>
        </div>
            <input type="submit" name="edit_account" value="Edit Login" style="width: fit-content;">
    </form>

    <?php
        }
    ?>

    <div class="messageBox">
        Edit your administrator account's information.
    </div>

    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
        if($success_message != '')
        {
            echo '<div id="succ" class="success messageBox"><ul>'.$success_message.'</ul></div>';
        }
    ?>

</div>

</body>
</html>