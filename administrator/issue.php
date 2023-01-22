<?php

$type = 'issue';
    include '../res/assets/globalfunctions.php';

    if(!isadminlogged())
    {
        header('location:../admin_login.php');
    }
    $fetch_stat = $connect->query("SELECT * FROM issues_table");

    include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issues . LibraryMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/issue.png" alt="Issues" style="Height: 4rem;"> | Issues
        </div>
        <?php
            if(isset($_GET['action']))
            {
                if($_GET['action']=='delete')
                {
                    $user_id = $_GET['user'];
                    $book_isbn = $_GET['code'];
                    $bill = $connect->query("SELECT user_bill FROM user_accounts WHERE user_id = '$user_id'")->fetch(PDO::FETCH_ASSOC)['user_bill'];
                    if($bill==0)
                    {
                        //updating stock
                        $connect->query("UPDATE books_table SET book_stock = book_stock+1 WHERE book_isbn = '$book_isbn'");
                        //updating borrowed books
                        $connect->query("UPDATE user_accounts SET user_borrowed = user_borrowed-1 WHERE user_id = '$user_id'");
                        //deleting the issue
                        $connect->query("DELETE FROM issues_table WHERE user_id = '$user_id' AND book_isbn = '$book_isbn'");
                        header("location:issue.php?msg=delete");
                    }
                    else
                    {
                        header("location:issue.php?msg=no_delete");
                    }
                }
            }
            else
            {
        ?>
                <div class="card" style="width: 70%;">
                    <table id="datatable" class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="column_gapping">User Issue ID</th>
                                <th class="column_gapping">User Email</th>
                                <th class="column_gapping">Book ISBN</th>
                                <th class="column_gapping">Issued On</th>
                                <th class="column_gapping">Issue Duration</th>
                                <th class="column_gapping">Late Days</th>
                                <th class="column_gapping">Operations</th>
                            </tr>
                        </thead>
                        <tbody>
        <?php
                            if($fetch_stat->rowCount()>0)
                            {
                                foreach($fetch_stat->fetchAll() as $issue)
                                {
                                    $user_id = $issue['user_id'];
                                    
                                    $issue_duration = strtotime(getdateandtime()) - strtotime($issue['created_on']);
                                    $issue_duration /= 86400;

                                    $user_email =  $connect->query("SELECT user_email FROM user_accounts WHERE user_id = '$user_id' LIMIT 1")->fetch()['user_email'];

                                    $loan_daylimit =  $connect->query("SELECT loan_daylimit FROM admin_settings LIMIT 1")->fetch()['loan_daylimit'];
                                    $late_days = ((-$loan_daylimit+$issue_duration)>0 ? (-$loan_daylimit+$issue_duration) : 0);
                                    settype($issue_duration, 'integer');
                                    settype($loan_daylimit, 'integer');
                                    settype($late_days, 'integer');
                                    echo'   
                                    <tr>
                                        <td class="column_gapping">'.$issue['user_id'].'</td>
                                        <td class="column_gapping"><a href="mailto:'.$user_email.'">'.$user_email.'</a></td>
                                        <td class="column_gapping" style="width: 200px;">'.$issue['book_isbn'].'</td>
                                        <td class="column_gapping" style="width: 200px;">'.$issue['created_on'].'</td>
                                        <td class="column_gapping" style="text-align: center;">'.$issue_duration.' days</td>
                                        <td class="column_gapping" style="text-align: center;">'.$late_days.' days</td>
                                        <td class="column_gapping">
                                            <button class="edit_a" onclick="deleteAlert(`'.$issue["book_isbn"].'`, `'.$type.'`, `'.$issue['user_id'].'`, `'.$late_days.'`, `'.$issue['billed_late_days'].'`)" >Return</button>
                                        </td>
                                    </tr>';
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
                if($_GET['msg'] == 'delete')
                {
                    $success_message = '<li>Issue has been cleared '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                    echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
                }
                if($_GET['msg']== 'no_delete')
                {
                    $error_message = '<li>Issue can only be cleared after late fines are paid. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
                    echo '<div id="succ" class="messageBox warning " ><ul>'.$error_message.'</ul></div>';
                }
            }
        ?>

    </div>
</body>
</html>