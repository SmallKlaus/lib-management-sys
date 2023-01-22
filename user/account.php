<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include '../res/assets/globalfunctions.php';

if(!isuserlogged())
{
    header('location:../user_login.php');
}

$error_message = '';
$success_message = '';

if(isset($_POST['edit_account']))
{
    $data = array();
    if(empty($_POST['user_email']))
    {
        $error_message .= '<li>E-mail address field must be filled.</li>';
    }
    else
    {
        if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL))
        {
            $error_message .= '<li> E-mail address is invalid.</li>';
        }
        else
        {   
            $exist_data = array(
                ':user_email' => $_POST['user_email'],
                ':user_id' => $_SESSION['user_id']
            );
            $query = "SELECT * FROM user_accounts WHERE user_email = :user_email AND user_id != :user_id";
            $exists = $connect->prepare($query);
            $exists->execute($exist_data);
            if($exists->rowCount()>0)
            {
                $error_message .= '<li> E-mail address already registered</li>';
            }
            else
            {
                $data['user_email']=$_POST['user_email'];
            }
        }
    }
    if(empty($_POST['user_password']))
    {
        $error_message .= '<li>Password field must be filled.</li>';
    }
    else
    {
        if(!validatepassword($_POST['user_password']))
        {
            $error_message .= '<li>Password must match the validation requirements.</li>';
        }
        else
        {
            if(empty($_POST['confirm_password']))
            {
                $error_message .= '<li>Please confirm your new password.</li>';
            }
            else
            {
                if($_POST['confirm_password']!=$_POST['user_password'])
                {
                    $error_message .= '<li>The password fields dont\'t match.</li>';
                }
                else
                {
                    $data['user_password']=$_POST['user_password'];
                }
            }
        }
    }

    if($error_message == '')
    {
        $user_id = $_SESSION['user_id'];
        $query_data = array(
            ':user_email' => $data['user_email'],
            ':user_password' => $data['user_password'],
            ':user_id' => $user_id,
            ':updated_on' =>getdateandtime()
        );

        $update_query = "UPDATE user_accounts
                            SET user_email = :user_email, user_password = :user_password, updated_on = :updated_on
                            WHERE user_id = :user_id";

        $stat = $connect->prepare($update_query);
        $stat->execute($query_data);

        if($_POST['old_user_email'] != $query_data[':user_email'])
        {
            $connect->query("UPDATE user_accounts SET confirmation_status = 'N' WHERE user_id = '$user_id'");
            require '../vendor/autoload.php';
            $stat = $connect->query("SELECT * FROM user_accounts WHERE user_id = '$user_id'");
            foreach($stat->fetchAll() as $data)
            {
                $confirmation_code = $data['confirmation_code'];
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'oloka2018@gmail.com';
                $mail->Password = 'eboxjjoxvvlrktjk';
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;
                $mail->setFrom('SPierroLib@lib.com', 'LibraryMS');
                $mail->addAddress($data['user_email'], $data['user_name']);
                $mail->addEmbeddedImage('../res/Images/welcome.png', 'welcome');
                $mail->addEmbeddedImage('../res/Icons/LMSicon.png', 'icon');
                $mail->isHTML(true);
                $mail->Subject='Registration Confirmation of LibraryMS';
                $mail->Body='
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td align="center">
                <div style=" height: fit-content; 
                    text-align: center;
                    background-image: linear-gradient(to top, rgba(109, 148, 172, 0.5), rgb(16, 13, 31));
                    border-radius: 10px; 
                    color:white;
                    padding: 30px 20px;
                    width: fit-content;
                    border: 1px solid rgb(16, 13, 31);
                    text-shadow: 0 0 20px rgb(15, 17, 36);
                ">
                <p><img src="cid:icon" alt="Icon" width="10%"></p>
                <p>Hey <b>'.$data['user_name'].'</b>. Thank you for registering to our Library service.</p>
                <p>Please click the link below to verify your email address and proceed with your log in.</p>
                <p><img src="cid:welcome" alt="Books" width="70%"></p>
                <p><a style= " color: black;
                        margin-bot: auto;" href="'.base_path().'confirm.php?code='.$confirmation_code.'">Click here to Verify</a></p>
                </div>
                </td>
                </tr>
                </table>';
                $mail->send();
                $mail->smtpClose();
            }
            $success_message = '<li>Account information has been successfully edited. Make sure to verify your new email address before your next login.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
        }

        else $success_message = '<li>Account information has been successfully edited.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
        
    }
}

$query = "SELECT * FROM user_accounts
          WHERE user_id = '".$_SESSION['user_id']."'";
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

    <form class="card" method="POST">
        <div>
            <label for="user_email">
                <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Email address :
            </label>
            <input type="text" name="user_email" id="user_email" autocomplete="off" value="<?php echo $account['user_email']; ?>"/>
        </div>
        <div>
            <label for="user_password">
                <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit password :
            </label>
            <input class="password" type="password" name="user_password" id="user_password" value="<?php echo $account['user_password']; ?>"/>
        </div>
        <p style="color:grey; font-size:12px;">(at least 8 characters, one uppercase, one lowercase, one digit and one special character)</p>
        <div>
            <label for="confirm_password">
            <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Confirm password :
            </label>
            <input class="confirm_password" type="password" name="confirm_password" id="confirm_password" onkeyup="pwdmatching()"/>
            <p id="symbol" style="font-size:12px; margin-left:auto; margin-top:auto;"></p>
        </div>
            <input type="hidden" name="old_user_email" value="<?php echo $account['user_email']; ?>">
            <input type="submit" name="edit_account" value="Edit Login" style="width: fit-content;">
    </form>

    <?php
        }
    ?>

    <div class="messageBox">
        Edit your user account's information.
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