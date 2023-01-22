<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'res/assets/globalfunctions.php';

$error_message = '';

if(isset($_POST['signup']))
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
            $error_message .= '<li>E-mail address is invalid.</li>';
        }
        else
        {
            $data['user_email'] = $_POST['user_email'];
        }
    }
    if(empty($_POST['user_name']))
    {
        $error_message .= '<li>Username field must be filled.</li>';
    }
    else
    {
        $data['user_name'] = $_POST['user_name'];
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
            if(empty($_POST['confirm_user_password']))
            {
                $error_message .= '<li>Password confirmation field must be filled.</li>';
            }
            else
            {
                if($_POST['confirm_user_password'] != $_POST['user_password'])
                {   
                    $error_message .= "<li>Password fields don't match.</li>";
                }
                else
                {
                    $data['user_password'] = $_POST['user_password'];
                }
            }
        }
    }
    if($error_message == '')
    {
        $email_exists = $connect->query("SELECT * FROM user_accounts WHERE user_email = '".$data['user_email']."'");
        if($email_exists->rowCount()>0)
        {
            $error_message .= '<li>E-mail already registered.</li>';
        }
        else
        {
            $user_id = uniqid();
            $confirmation_code = md5($user_id);
            $querydata = array(
                ':user_id'  => $user_id,
                ':user_name'    => $data['user_name'],
                ':user_email'   => $data['user_email'],
                ':user_password'    =>$data['user_password'],
                ':confirmation_status'  => 'N',
                ':confirmation_code'     => $confirmation_code,
                ':created_on'   => getdateandtime(),
                ':updated_on'   => getdateandtime(),
                ':month_start'  =>getdateandtime()
            );
            $query = "INSERT INTO user_accounts
                        (user_id, user_email, user_password, user_name, confirmation_status, created_on, updated_on, confirmation_code, month_start)
                        VALUES(:user_id, :user_email, :user_password, :user_name, :confirmation_status, :created_on, :updated_on, :confirmation_code, :month_start)";
            $connect->prepare($query)->execute($querydata);

            require 'vendor/autoload.php';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'oloka2018@gmail.com';
            $mail->Password = 'eboxjjoxvvlrktjk'; //for google accounts API codes are used for security purposes
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->setFrom('SPierroLib@lib.com', 'LibraryMS');
            $mail->addAddress($data['user_email'], $data['user_name']);
            $mail->addEmbeddedImage('./res/Images/welcome.png', 'welcome');
            $mail->addEmbeddedImage('./res/Icons/LMSicon.png', 'icon');
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
            

            header('location:signup.php?msg=success');
        }
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up . LibraryMS</title>
    <link rel="stylesheet" href="res/assets/styles.css">
    <link rel="icon" href="res/Icons/LMSicon.png">
</head>
<body>
<script>
    history.scrollRestoration = 'manual';
    let low_res = document.createElement('div');
    low_res.id = "low_res";
    low_res.classList.add('low_res');

    let low_res_text = document.createElement('h2');
    low_res_text.innerText = 'Please Use a Device with a Resolution width higher than 1600px for a pleasant experience!\nTry zooming out in your browser or putting it to full screen.';
    low_res_text.style.textShadow = '0 0 20px black';

    low_res.appendChild(low_res_text);
    document.body.append(low_res);
    if(window.innerWidth< 1600)
    { 
        low_res.style.visibility = 'visible';
        document.body.classList.remove('flex');

    }
    window.addEventListener("resize", function(){
        if(window.innerWidth < 1600)
        {
            low_res.style.visibility = 'visible';
            document.body.classList.remove('flex');
        }
        else
        {
            low_res.style.visibility = 'hidden';
            document.body.classList.add('flex');
        }
    })
</script>
<div class="content">
    <div class="icon">
        <a href="index.php"><img src="res/Icons/LMSicon.png" alt="LibraryMS"></a>
        <h2>Sign up to the LibraryMS</h2>
    </div>
    <form class="card" method="POST" style="width:30% ;">
        <div>
            <label for="user_email">E-mail address :</label>
            <input type="text" spellcheck="false" name="user_email" id="user_email" autocomplete="on" >
        </div>
        <div>
            <label for="user_name">Username :</label>
            <input type="text" name="user_name" autocomplete="off" id="user_name">
        </div>
        <div>
            <label for="user_password">Password :</label>
            <input class="password" type="password" name="user_password" autocomplete="off" id="user_password">
        </div>
            <p style="color:grey; font-size:12px;">(at least 8 characters, one uppercase, one lowercase, one digit and one special character)</p>
        <div>
            <label for="confirm_user_password">Confirm Password :</label>
            <input class="confirm_password" type="password" name="confirm_user_password" autocomplete="off" id="confirm_user_password" onkeyup="pwdmatching()">
            <p id="symbol" style="font-size:12px; margin-left:auto; margin-top:auto;"></p>
        </div>
        <input type="submit" name="signup" value="Sign up" style="width:fit-content ">
    </form>
    <p class="messageBox">
        This is the members sign up page. If you're already signed up, <a href="user_login.php">Log in here.</a>
    </p>
    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
        if(isset($_GET['msg']))
        {
            if($_GET['msg'] == 'success')
            {
                $success_message = '<li>Your account has been created. Please confirm your email before logging in.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        }
    ?>
</div>
    <script src="res/assets/global.js"></script>

    
    
</body>
</html>