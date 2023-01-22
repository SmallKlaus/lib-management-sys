<?php
    include 'res/assets/connect.php';

    $fetch_stat = $connect->query("SELECT * FROM admin_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library MS</title>
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
<div class="navigation" >
    <div class="webIcon">
        <a href="index.php"><img src="res/Icons/LMSicon.png" alt="LibraryMS"></a>
        <a href="index.php"><?php echo  'LibraryMS'.' &#183; '.$fetch_stat['library_name']?></a>
    </div>
    <ul class="navigationLinks">
        <li><a href="announcements.php">Announcements</a></li>
        <li><a href="mailto:<?php echo $fetch_stat['email_contact']; ?>">Contact</a></li>
        <li><a href="about_us.php">About</a></li>
    </ul>
    <div>
        <button onclick="window.location.href='admin_login.php'">Admin Login</button>
        <button onclick="window.location.href='user_login.php'">Member Login</button>
    </div>
</div>

<div class="container">
    <div class="welcomeText">
        <h1>Access <?php echo '&#183;'.$fetch_stat['library_name'].'&#183;'?> library's collection</h1>
        <h2>Query for books, manage your account.</h2>
        <h2>It's free, don't miss out on our service.</h2>
    </div>
    <div class="signup">
        <button onclick="window.location.href='signup.php'">Sign up</button>
    </div>
</div>
    <script src="res/assets/global.js"></script>
</body>
</html>