
<?php
    
include 'res/assets/globalfunctions.php';

    if(isset($_GET['msg']))
    {
        if($_GET['msg'] == 'success')
        {
            $success_message = '<li>Your Email has been successfully verified.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
    }

if(isuserlogged())
{
    header('location:user/frontpage.php');
}


$error_message = '';

if(isset($_POST['login']))
{
    $data = array();
    if(empty($_POST["user_email"]))
    {
        $error_message .= '<li>E-mail address field must be filled.</li>';
    }
    else
    {
        if(!filter_var($_POST["user_email"], FILTER_VALIDATE_EMAIL))
        {
            $error_message .= '<li> E-mail address is invalid.</li>';
        }
        else
        {
            $data["user_email"]=$_POST["user_email"];
        }
    }
    if(empty($_POST["user_password"]))
    {
        $error_message .= '<li>Password field must be filled.</li>';
    }
    else
    {
        $data["user_password"]=$_POST["user_password"];
    }

    if($error_message == '')
    {
        $query_data = array(
            ':user_email' => $data['user_email']
            );
        $query = " SELECT user_id, user_password, confirmation_status FROM user_accounts
                    WHERE user_email = :user_email";
        $stat = $connect->prepare($query);
        $stat->execute($query_data);
        if($stat->rowCount() > 0)
        {
            foreach($stat->fetchAll() as $result)
            {
                if($result["confirmation_status"] == 'N')
                {
                    $error_message .= "<li>Your email hasn't been verified yet.</li>";
                }
                else
                {
                    if($result["user_password"] == $data["user_password"])
                    {
                        $_SESSION["user_id"] = $result["user_id"];
                        header('location:user/frontpage.php');
                    }
                    else
                    {
                        $error_message .= '<li>You have entered the wrong password.</li>';
                    }
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
    <title>Log in as member . LibraryMS</title>
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
            <label for="user_email">E-mail address :</label>
            <input type="text" spellcheck="false" name="user_email" id="user_email" autocomplete="on" >
        </div>
        <div>
            <label for="user_password">Password :</label>
            <input type="password" name="user_password" autocomplete="off" id="user_password">
            <input type="checkbox" onclick="toggleVis()">
        </div>
        <input type="submit" name="login" value="Login">
    </form>
    <p class="messageBox">
        This is the members login page. If you're an administrator, <a href="admin_login.php">Sign in here.</a>
    </p>

    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
    ?>
</div>
    <script src="res/assets/global.js"></script>    
</body>
</html>