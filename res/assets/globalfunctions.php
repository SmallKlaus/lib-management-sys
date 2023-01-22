<?php

include 'connect.php';

function isadminlogged()
{
    if(isset($_SESSION['admin_id']))
    {return true;}
    return false;
}

function isuserlogged()
{
    if(isset($_SESSION['user_id']))
    {
        return true;
    }
    return false;
}

function getdateandtime()
{
    return date("Y-m-d H:i:s", strtotime(date('h:i:sa')));
}

function isgenrechecked($id, $label)
{
    $echoed_bool = '';
    $checked_genres = $GLOBALS['connect']->query("SELECT genre_label FROM books_genres WHERE book_id = $id");
    if($checked_genres->rowCount()>0)
    {
        foreach($checked_genres->fetchAll() as $genre)
        {
            if($genre['genre_label']==$label)
            {
                $echoed_bool = 'checked';
                break;
            }
        }
        return $echoed_bool;
    }
}

function isauthorchecked($id, $label)
{
    $echoed_bool = '';
    $checked_authors = $GLOBALS['connect']->query("SELECT author_name FROM books_authors WHERE book_id = $id");
    if($checked_authors->rowCount()>0)
    {
        foreach($checked_authors->fetchAll() as $author)
        {
            if($author['author_name']==$label)
            {
                $echoed_bool = 'checked';
                break;
            }
        }
        return $echoed_bool;
    }
}

function validatepassword($password)
{
    $upper = preg_match('@[A-Z]@', $password);
    $lower = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $special = preg_match('@[^\w]@', $password);

    if(!$upper || !$lower || !$number || !$special || strlen($password) < 8)
        return false;
    else return true;
}

function base_path()
{
    return 'http://localhost/Library%20Management%20System/';
}

function weekly_billing()
{
    $connect = $GLOBALS['connect'];
    $stat = $connect->query("SELECT * FROM user_accounts");
    $weekly_fee = $connect->query("SELECT membership_fee FROM admin_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC)['membership_fee'];
    if($stat->rowCount()>0)
    {
        foreach($stat->fetchAll() as $user)
        {
           $difference = strtotime(getdateandtime())-strtotime($user['month_start']);
           settype($difference, 'integer');
           if($difference>=604800) //if a week has passed
           {
                $difference /= 604800;
                settype($difference, 'integer');
                $added_bill = $difference * $weekly_fee;
                $connect->query("UPDATE user_accounts SET user_bill = user_bill+$added_bill, month_start = DATE_ADD(month_start,INTERVAL $difference WEEK) WHERE user_id = '".$user['user_id']."'");
           }
        }
    }
}

function daily_fining()
{
    $connect = $GLOBALS['connect'];
    $stat = $connect->query("SELECT * FROM issues_table");
    $daily_fine = $connect->query("SELECT fine FROM admin_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC)['fine'];
    if($stat->rowCount()>0)
    {
        foreach($stat->fetchAll() as $issue)
        {
            $issue_duration = strtotime(getdateandtime()) - strtotime($issue['created_on']);
            $issue_duration /= 86400;
            settype($issue_duration, 'integer');
            $user_id =  $issue['user_id'];
            $book_isbn = $issue['book_isbn'];
            $loan_daylimit =  $connect->query("SELECT loan_daylimit FROM admin_settings LIMIT 1")->fetch()['loan_daylimit'];
            settype($loan_daylimit, 'integer');
            $unbilled_late_days = ((-$issue['billed_late_days']-$loan_daylimit+$issue_duration)>0 ? (-$issue['billed_late_days']-$loan_daylimit+$issue_duration) : 0);
            settype($unbilled_late_days, 'integer');
            $added_bill = $daily_fine * $unbilled_late_days;
            $connect->query("UPDATE user_accounts SET user_bill = user_bill + $added_bill WHERE user_id = '$user_id'");
            $connect->query("UPDATE issues_table SET billed_late_days = billed_late_days + $unbilled_late_days WHERE (user_id = '$user_id' AND book_isbn = '$book_isbn')");
        }
    }
}

?>