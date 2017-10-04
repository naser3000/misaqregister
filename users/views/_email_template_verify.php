<?php
$db = DB::getInstance();
$query = $db->query("SELECT * FROM email");
$results = $query->first();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body style="direction: rtl;">
    <p>مبارک باشد <?=$fname;?>,</p>
    <p>از ثبت نام شما سپاس گزاریم. لطفاً جهت تأیید آدرس ایمیل خود، بر روی لینک زیر کلیک کنید.</p>
    <p><a href="<?=$results->verify_url?>users/verify.php?email=<?=$email;?>&vericode=<?=$vericode;?>">تأیید آدرس ایمیل</a></p>
    <p>با یک بار تأیید آدرس ایمیل شما می توانید وارد سایت شوید!</p>
    <p>به زودی شما را خواهیم دید!</p>
  </body>
</html>
