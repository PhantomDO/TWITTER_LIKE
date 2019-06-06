<!-- ~/php/tp1/view/city.php -->
<!DOCTYPE HTML>
<p>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
</head>
<title>Profile</title>
</p>
    <h1>Profile <?= $params['profile']->GetLogin() ?></h1>

    <p>
        <a href="profile/<?= $params['profile']->GetLogin(); ?>"></a>
        Name of the guy: <?= $params['profile']->GetLogin(); ?>

        <?php if ($_SESSION['ProfileGateway']['login'] != $params['profile']->GetLogin()):?>

            <?php if(!$params['follow']):?>
                <p>
                    <form action="/twitter/profile/<?php if(isset($params['profile'])) echo $params['profile']->GetLogin();?>/follow" method="POST">
                        <input name="_method" type="hidden" value="PUT" />
                        <input type="hidden" name="login" value="<?php if(isset($params['profile'])) echo $params['profile']->GetLogin(); ?>">
                        <input type="hidden" name="user_id" value="<?php if(isset($_SESSION['ProfileGateway'])) echo $_SESSION['ProfileGateway']['id']; ?>">
                        <input type="hidden" name="id" value="<?php if(isset($params['profile'])) echo $params['profile']->GetId(); ?>">
                        <button type="submit">Follow</button>
                    </form>
                </p>
            <?php endif ?>

            <?php if ($params['follow']):?>
                <p>
                    <form action="/twitter/profile/<?php if(isset($params['profile'])) echo $params['profile']->GetLogin();?>/unfollow" method="POST">
                        <input name="_method" type="hidden" value="DELETE" />
                        <input type="hidden" name="login" value="<?php if(isset($params['profile'])) echo $params['profile']->GetLogin(); ?>">
                        <input type="hidden" name="user_id" value="<?php if(isset($_SESSION['ProfileGateway'])) echo $_SESSION['ProfileGateway']['id']; ?>">
                        <input type="hidden" name="id" value="<?php if(isset($params['profile'])) echo $params['profile']->GetId(); ?>">
                        <button type="submit">Unfollow</button>
                    </form>
                </p>
            <?php endif ?>

        <?php endif ?>

        <?php if($_SESSION['ProfileGateway']['login'] === $params['profile']->GetLogin()):?>
            <?php if ($params['settings'] === false):?>
                <p>
                    <a href='/twitter/profile/<?php if(isset($_SESSION['ProfileGateway']['login'])) echo $_SESSION['ProfileGateway']['login'];?>/settings'>
                        <button type="submit">Espace Personnel</button>
                    </a>
                </p>
            <?php endif ?>

            <?php if ($params['settings'] === true):?>
                <p>
                    Password: <?= $params['profile']->GetPassword(); ?>
                    Adress: <?= $params['profile']->GetAdress(); ?>
                    <form action="/twitter/profile/<?php if(isset($_SESSION['ProfileGateway']['login'])) echo $_SESSION['ProfileGateway']['login'];?>/settings/save" method="POST">
                        <input name="_method" type="hidden" value="PUT" />
                        <p>
                            <label>Login</label>
                            <input type="text" name="login" value="<?php if(isset($params['profile'])) echo $params['profile']->GetLogin(); ?>">
                        </p>
                        <p>
                            <label>Password</label>
                            <input type="password" name="password" value="<?php if(isset($params['profile'])) echo $params['profile']->GetPassword(); ?>">
                        </p>
                        <p>
                            <label>Adress</label>
                            <input type="text" name="adress" value="<?php if(isset($params['profile'])) echo $params['profile']->GetAdress(); ?>">
                        </p>
                        <p>
                            <input type="hidden" name="id" value="<?php if(isset($params['profile'])) echo $params['profile']->GetId(); ?>">
                        </p>
                        <p>
                            <a href='/twitter/profile/<?php if(isset($_SESSION['ProfileGateway']['login'])) echo $_SESSION['ProfileGateway']['login'];?>/settings/save'>
                                <button type="submit">Enregistrer</button>
                            </a>
                        </p>
                    </form>
                </p>
            <?php endif ?>
        <?php endif ?>
    </p>




</body>
</html>