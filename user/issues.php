<?php

include '../res/assets/globalfunctions.php';


if(!isuserlogged())
{
    header('locatin:../user_login.php');
}

$user_id = $_SESSION['user_id'];
$fetch_stat = $connect->query("SELECT * FROM issues_table WHERE user_id = '$user_id'");

include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issues . LibrayMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/issue.png" alt="Issues" style="height: 4rem;"> | My Issues
        </div>
        <div class="card" style="width: 70%;">
            <table id="datatable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="column-gapping">Book ISBN</th>
                        <th class="column-gapping">Book Name</th>
                        <th class="column-gapping">Issue Date</th>
                        <th class="column-gapping">Issue Duration</th>
                        <th class="column-gapping">Late Days</th>
                    </tr>
                </thead>
                <tbody>
<?php
                    if($fetch_stat->rowCount() > 0)
                    {
                        foreach($fetch_stat->fetchAll() as $issue)
                        {
                            $book_name = $connect->query("SELECT book_name FROM books_table WHERE book_isbn = '".$issue['book_isbn']."' LIMIT 1")->fetch(PDO::FETCH_ASSOC)['book_name'];
                            $issue_duration = strtotime(getdateandtime()) - strtotime($issue['created_on']);
                            $issue_duration /= 86400;
                            $loan_daylimit =  $connect->query("SELECT loan_daylimit FROM admin_settings LIMIT 1")->fetch()['loan_daylimit'];
                            $late_days = ((-$loan_daylimit+$issue_duration)>0 ? (-$loan_daylimit+$issue_duration) : 0);
                            settype($issue_duration, 'integer');
                            settype($loan_daylimit, 'integer');
                            settype($late_days, 'integer');
                            echo'   
                            <tr>
                                <td class="column_gapping">'.$issue['book_isbn'].'</td>
                                <td class="column_gapping">'.$book_name.'</td>
                                <td class="column_gapping">'.$issue['created_on'].'</td>
                                <td class="column_gapping" style="text-align: center;">'.$issue_duration.' days</td>
                                <td class="column_gapping" style="text-align: center;">'.$late_days.' days</td>
                            </tr>';
                        }
                    }
?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>