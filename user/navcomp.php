<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../res/Icons/LMSicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
        $('#datatable').DataTable();
        });
    </script>
    <link rel="stylesheet" href="../res/assets/styles.css">
    
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
    <div class="navigation">
        <div id="toggler" class="webIcon">
            <a href="../index.php"><img src="../res/Icons/LMSIcon.png" alt="LibraryMS"></a>
            <a href="../index.php">LibraryMS</a>
        </div>
        <ul class="navigationLinks">
            <li><a href="frontpage.php">Book Collection</a></li>
            <li><a href="issues.php">My Issues</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><button onclick="location.href='logout.php'">Sign out</button></li>
        </ul>

    </div>
    <script src="../res/assets/global.js"></script>
</body>
</html>