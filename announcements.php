<?php
include 'res/assets/connect.php';

$fetch_announce = $connect->query("SELECT * FROM announcements_table ORDER BY created_on ASC");
$fetch_stat = $connect->query("SELECT * FROM admin_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements . LibraryMS</title>
    <link rel="icon" href="res/Icons/LMSicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="res/assets/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="res/assets/global.js"></script>
    <script>
        $(document).ready(function () {
        $('#datatable').DataTable();
        $('.clickable_row').click(function (){
            console.log('clickedd');
            window.location.href = $(this).data("href");
        })
        });
    </script>
</head>
<body class="flex">
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
<?php
    if(isset($_GET['action']))
    {
        if($_GET['action']=='show')
        {
?>
<div class="popup_msg" id="popup">
    <div class="card" style="opacity: 100% ; box-shadow:inset 0 0 5px grey; width: 50%; text-align: center; background-color: rgba(20,20,30,0.9);">
        <a style="margin-left: auto; font-size: 13px;" href="javascript:removeEle()">Close</a>
        <h2><?php echo $_GET['title']; ?></h2>
        <br>
        <?php echo $_GET['text']; ?>
        <p style="margin-right: auto; font-size: 13px;">
            <?php echo $_GET['date']; ?>
        </p>
    </div>
</div>
<?php
        }
    }
?>
<div class="alternative_content">
    <div class="navigation trans">
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
    <div class="head_title" style="background-color: rgba(10,10,20,0.5);">
        <img src="res/Icons/announcement.png" alt="" style="height: 4rem;"> | Announcements
    </div>
    <div class="card" style="background-color: rgba(10,10,20,0.5); width: 70%;">
    <table id="datatable" class="table">
            <thead>
                <tr>
                    <th class="column-gapping">Announcement Title</th>
                    <th class="column-gapping" style="width: 200px;">Announced On</th>
                </tr>
            </thead>
            <tbody>
<?php
                if($fetch_announce->rowCount()>0)
                {
                    foreach($fetch_announce->fetchAll() as $announce)
                    {
                        echo'
                        <tr class="clickable_row" data-href="#">
                            <td class="column-gapping clickable_row" data-href="announcements.php?action=show&title='.$announce['announce_title'].'&text='.$announce['announce_text'].'&date='.$announce['created_on'].'">'.$announce['announce_title'].'</td>
                            <td class="column-gapping clickable_row" data-href="announcements.php?action=show&text='.$announce['announce_text'].'&date='.$announce['created_on'].'">'.$announce['created_on'].'</td>
                        </tr>
                        ';
                    }
                }
?>
            </tbody>
        </table>
    </div>
</div>
    
</body>
</html>