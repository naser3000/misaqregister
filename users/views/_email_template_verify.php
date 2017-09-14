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
  <body>
    <p>مبارک باشد <?=$fname;?>,</p>
    <p>Thanks for signing up Please click the link below to verify your email address.</p>
    <p>از ثبت نام شما سپاس گزاریم. لطفاً جهت تأیید آدرس ایمیل خود، بر روی لینک زیر کلیک کنید.</p>
    <p><a href="<?=$results->verify_url?>users/verify.php?email=<?=$email;?>&vericode=<?=$vericode;?>">Verify Your Email</a></p>
    <p>Once you verify your email address you will be ready to login!</p>
    <p>به زودی شما را خواهیم دید!</p>
  </body>
</html>
