<!-- ~/php/tp1/view/city.php -->
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
</head>
<title>Profile</title>
<p>
<h1>Profile <?= $params['profile']->GetLogin() ?></h1>
<p><a href="profile/<?= $params['profile']->GetLogin(); ?>"></a>
    Name of the guy: <?= $params['profile']->GetLogin(); ?>
    <?php var_dump($_SESSION); ?>
</p>
</body>
</html>