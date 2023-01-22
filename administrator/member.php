<?php

$type = 'member';

include '../res/assets/globalfunctions.php';
if(!isadminlogged())
{
    header('location:../admin_login.php');
}

weekly_billing();
daily_fining();

$fetch_stat = $connect->query("SELECT * FROM user_accounts");

include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members . LibraryMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/account.png" alt="Users" style="height: 4rem;">
            | Members
        </div>
        <?php
        if(isset($_GET['action']))
        {
            //clears the bill of selected user
            if($_GET['action'] == 'bill')
            {
                $user_id = $_GET['code'];
                $connect->query("UPDATE user_accounts SET user_bill = 0 WHERE user_id = '$user_id'");
                header("location:member.php?msg=bill_clear&code='$user_id'");
            }
            //delete selected user
            if($_GET['action'] == 'delete')
            {
                $user_email = $_GET['code'];
                $connect->query("DELETE FROM user_accounts WHERE user_email = '$user_email'");
                header('location:member.php?msg=delete');
            }
            //delete all unverified accounts that have been created over 2 days ago
            if($_GET['action']== 'clear')
            {
                $stat = $connect->query("SELECT * FROM user_accounts WHERE confirmation_status = 'N'");
                if($stat->rowCount()>0)
                {
                    foreach($stat->fetchAll() as $user)
                    {
                        $difference = strtotime(getdateandtime()) - strtotime($user['created_on']);
                        if($difference>172800)
                        {
                            $user_id = $user['user_id'];
                            $connect->query("DELETE FROM user_accounts WHERE user_id = '$user_id'");
                        }
                    }
                }
                header('location:member.php?msg=clear');
            }
        }
        else
        {    
        ?>
        <div class="card" style=" width: 70%;">
            <table id="datatable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="column_gapping">Email Address</th>
                        <th class="column_gapping">Username</th>
                        <th class="column_gapping">Address</th>
                        <th class="column_gapping">Contact Number</th>
                        <th class="column_gapping">Due Bill</th>
                        <th class="column_gapping">Borrowed Books</th>
                        <th class="column_gapping">Created On</th>
                        <th class="column_gapping">Updated On</th>
                        <th class="column_gapping">Operations</th>
                    </tr>
                </thead>
                <tbody>   
        <?php
            if($fetch_stat->rowCount()>0)
            {
                foreach($fetch_stat->fetchAll() as $user)
                {
                    echo '<tr>
                    <td class="column_gapping">'.$user['user_email'].'</td>
                    <td class="column_gapping">'.$user['user_name'].'</td>
                    <td class="column_gapping">'.$user['user_address'].'</td>
                    <td class="column_gapping">'.$user['contact_number'].'</td>
                    <td class="column_gapping">'.$user['user_bill'].' $</td>
                    <td class="column_gapping">'.$user['user_borrowed'].'</td>
                    <td class="column_gapping">'.$user['created_on'].'</td>
                    <td class="column_gapping">'.$user['updated_on'].'</td>
                    <td class="column_gapping" style="display: flex; flex-direction: column; row-gap: 5px; align-items: center;">
                        <a  class="edit_a" style="width: fit-content;" href="member.php?action=bill&code='.$user['user_id'].'">Pay Bill</a>
                        <button class="delete_a" onclick="deleteAlert(`'.$user["user_email"].'`, `'.$type.'`)" >Delete</button>
                    </td>
                        </tr>';
                }    
            }
            else
            {
                echo '<tr>
                <td colspan="7">No available data.</td>
                    </tr>';
            }
        }
        ?>
                </tbody>
            </table>
            <button onclick="location.href='member.php?action=clear'">Filter out</button>
        </div>
        <div class="messageBox">Filtering out deletes any unverified accounts that lasted longer than 48 hours.</div>
        <?php
        if(isset($_GET['msg']))
        {
            if($_GET['msg']=='delete')
            {
                $success_message = '<li>User has been deleted '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
            }
            if($_GET['msg']=='clear')
            {
                $success_message = '<li>Unused accounts have been deleted '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
            }
            if($_GET['msg']=='bill_clear')
            {
                $success_message = '<li>The user *'.$_GET['code'].'* has paid his bills. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
            }
        }
        ?>
    </div>
</body>
</html>