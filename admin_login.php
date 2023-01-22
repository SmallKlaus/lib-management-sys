<?php

include 'res\assets\globalfunctions.php';

if(isadminlogged())
{
    header('location:administrator/announcement.php');
}

$error_message = '';

if(isset($_POST['login']))
{
    $data = array();
    if(empty($_POST["admin_email"]))
    {
        $error_message .= '<li>E-mail address field must be filled.</li>';
    }
    else
    {
        if(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL))
        {
            $error_message .= '<li> E-mail address is invalid.</li>';
        }
        else
        {
            $data["admin_email"]=$_POST["admin_email"];
        }
    }
    if(empty($_POST["admin_password"]))
    {
        $error_message .= '<li>Password field must be filled.</li>';
    }
    else
    {
        $data["admin_password"]=$_POST["admin_password"];
    }

    if($error_message == '')
    {
        $query_data = array(
            ':admin_email' => $data['admin_email']
            );
        $query = " SELECT * FROM admin_accounts
                    WHERE admin_email = :admin_email";
        $stat = $connect->prepare($query);
        $stat->execute($query_data);
        if($stat->rowCount() > 0)
        {
            foreach($stat->fetchAll() as $result)
            {
                if($result["admin_password"] == $data["admin_password"])
                {
                    $_SESSION["admin_id"] = $result["admin_id"];
                    header('location:administrator/announcement.php');
                }
                else
                {
                    $error_message .= '<li>You have entered the wrong password.</li>';
                }
            }
        }
        else
        {
            $error_message.='<li>Non-registered E-mail address.</li>';
        }
    }

}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in as Admin . LibraryMS</title>
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
        <h2>Sign in to LibraryMS</h2>
    </div>
    <form class="card" method="POST">
        <div>
            <label for="admin_email">E-mail address :</label>
            <input type="text" spellcheck="false" name="admin_email" id="admin_email" autocomplete="on" >
        </div>
        <div>
            <label for="admin_password">Password :</label>
            <input type="password" name="admin_password" autocomplete="off" id="admin_password">
            <input type="checkbox" onclick="toggleVis()">
        </div>
        <input type="submit" name="login" value="Login">
    </form>
    <p class="messageBox">
        This is the admin login page. If you're a regular member, <a href="user_login.php">Sign in here.</a>
    </p>

    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
    ?>
</div>
    <script>
        function toggleVis()
        {
            let password = document.getElementById("admin_password");
            if(password.type === "password")
            {
                password.type = "text";
            }
            else
            {
                password.type = "password";
            }
        }
    </script>
    
    
</body>
</html>