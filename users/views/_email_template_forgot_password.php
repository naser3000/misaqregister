<?php
$db = DB::getInstance();
$query = $db->query("SELECT * FROM email");
$results = $query->first();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body style="direction: rtl; font-family: 'IRANSans';">
    <p>سلام <?=$fname;?>,</p>
    <p>شما جهت بازیابی رمز عبور این ایمیل را دریافت کرده اید. اگر اشتباهی رخ داده, این ایمیل را نادیده بگیرید.</p>
    <p>در صورت صحت, جهت بازیابی رمز عبور بر روی لینک زیر کلیک کنید.</p>
    <p><a href="<?php echo $results->verify_url."users/forgot_password_reset.php?email=".$email."&vericode=$vericode&reset=1"; ?>">بازیابی رمز عبور</a></p>
    <p>با آرزوی موفقیت,</p>
    <p>-گروه فرهنگی میثاق-</p>
  </body>
</html>
