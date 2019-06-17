<!DOCTYPE HTML>
<p>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Search</title>
</p>

<p>
    <form action="/search" method="POST">
        <p>
            <input type="text" name="login">
        </p>

        <?php
        if(isset($_POST['login']))
        {
            $login= $_POST['login'];
            echo $login;
        }
        ?>

        <p>
            <a href='/search'>
                <button type="submit">Search</button>
            </a>
        </p>
    </form>
</p>

<?php if($params['search']) :?>
    <?php
        foreach ($params['search'] as $profil)
        {
            if ($profil != null)
            {
                echo '---------------------------------------------------------------------------- <br>';
                echo '<a href="/profile/'.$profil->GetLogin().'">'.$profil->GetLogin().'</a>' . '<br>';
            }
        }
    ?>
<?php endif ?>
