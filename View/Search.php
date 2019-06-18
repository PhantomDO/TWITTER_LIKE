<!DOCTYPE HTML>
<p>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Search</title>
</p>
<h1>Search</h1>
<p>
    <form action="/handleSearch" method="POST">
        <?php if (isset($params['error'])) echo "<p style='display: none'>"; ?>>
        <p>
            <input type="text" name="login">
        </p>
        <a href="/handleSearch" method="POST">
            <button type="submit">Submit</button>
        </a>
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
