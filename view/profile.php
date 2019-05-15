<!-- ~/php/tp1/view/city.php -->
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
</head>
<title>Profile</title>
<p>
<h1>Profile <?= $params['profile']->GetName() ?></h1>
<p><a href="profile/<?= $params['profile']->GetName(); ?>"></a>
    Name of the guy: <?= $params['profile']->GetName(); ?>
</p>
</body>
</html>