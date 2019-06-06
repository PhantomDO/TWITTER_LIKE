<!-- ~/php/tp1/view/city.php -->
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
</head>
    <title>Profile</title>
        <p>
            <h1>Home</h1>

            <?php if ($_SESSION === null):?>
                <a href='/twitter/register'><button type="submit">Register</button></a>
                <a href='/twitter/login'><button type="submit">Login</button></a>
            <?php endif ?>

            <?php if ($_SESSION != null):?>
                <a href='/twitter'><h2>Page d'acceuil</h2></a>
            <?php endif ?>

        </p>
    </body>
</html>