<?php

$type = 'announcement';

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}
$error_message = '';
if(isset($_POST['add']))
{
    $data = array(
        ':created_on'   =>getdateandtime()
    );
    if(empty($_POST['announce_title']))
    {
        $error_message .= '<li>Tile field must be filled.</li>';
    }
    else
    {
        $data[':announce_title'] = $_POST['announce_title'];
    }
    if(empty($_POST['announce_text']))
    {
        $error_message .= '<li>Announcement field must be filled.</li>';
    }
    else
    {
        $data[':announce_text'] = $_POST['announce_text'];
    }
    if($error_message == '')
    {
        $connect->prepare("INSERT INTO announcements_table (announce_title, announce_text, created_on) VALUES(:announce_title, :announce_text, :created_on)")->execute($data);
        header('location:announcement.php?msg=add');
    }
}

$fetch_stat = $connect->query("SELECT * FROM announcements_table");

include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin . LibraryMS</title>
</head>
<body class="flex">
<?php
    if(isset($_GET['action']))
    {
        if($_GET['action']=='show')
        {
?>
<div class="popup_msg" id="popup">
    <div class="card" style="opacity: 100% ; box-shadow: 0 0 10px grey; width: 50%; text-align: center;">
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
        else if($_GET['action']=='delete')
        {
            $id = $_GET['user'];
            $connect->query("DELETE FROM announcements_table WHERE announce_id = $id");
            header('location:announcement.php');
        }
    }
?>
<div class="content">
    <div class="head_title">
        <img src="../res/Icons/announcement.png" alt="" style="height: 4rem;"> | Announcements
    </div>
<?php
        if(isset($_GET['add']))
        {
?>
        <form spellcheck="false" method="POST" class="card" style="width: 50%;">
            <div>
                <div>
                    <label for="announce_title"> <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Announcement Title:</label>
                    <input type="text" name="announce_title" id="announce_title" autocomplete="off">
                </div>
                <div>
                    <label for="announce_text"> <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Announcement:</label>
                    <textarea type="text" name="announce_text" id="announce_text" autocomplete="off" rows="4"></textarea>
                </div>
                <div style="width:100% ; display: flex; align-items:center;">
                    <input type="submit" value="Announce" name="add" style="width: fit-content;">
                </div>
            </div>
        </form>
        <div class="messageBox">Add an announcement for members and website visitors to tune in.</div>
<?php
        if($error_message != '')
        {
            echo '<div class="messageBox warning">'.$error_message.'</div>';
        }
        }
        else
        {
?>
    <div class="card" style="width: 70%;">
        <button style="border-radius:5px; margin-left:auto;" onclick="window.location.href='announcement.php?add=add'">
            <img src="../res/Icons/add.png" alt="add" style="height: 1rem;">
        </button>
        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th class="column-gapping">Announcement Title</th>
                    <th class="column-gapping" style="width: 200px;">Announced On</th>
                    <th class="column-gapping" style="width: 100px;">Operation</th>
                </tr>
            </thead>
            <tbody>
<?php
                if($fetch_stat->rowCount()>0)
                {
                    foreach($fetch_stat->fetchAll() as $announce)
                    {
                        echo'
                        <tr class="clickable_row" data-href="#">
                            <td class="column-gapping clickable_row" data-href="announcement.php?action=show&title='.$announce['announce_title'].'&text='.$announce['announce_text'].'&date='.$announce['created_on'].'">'.$announce['announce_title'].'</td>
                            <td class="column-gapping clickable_row" data-href="announcement.php?action=show&text='.$announce['announce_text'].'&date='.$announce['created_on'].'">'.$announce['created_on'].'</td>
                            <td class="column-gapping">
                                <button class="delete_a" onclick="deleteAlert(`'.$announce["announce_title"].'`, `'.$type.'`, `'.$announce['announce_id'].'`)" >Delete</button>
                            </td>
                        </tr>
                        ';
                    }
                }
?>
            </tbody>
        </table>
    </div>
<?php
        }
        if(isset($_GET['msg']))
        {
            if($_GET['msg']=='add')
            {
                $success_message = '<li>Announcement is live.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
            }
        }
?>
</div>

</body>
</html>