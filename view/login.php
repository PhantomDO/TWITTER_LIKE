<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;
                charset=utf-8" />
    </head>

    <body>
        <?php
        if(isset($params['error'])) {
            echo "
                   <p style='color: red'>
                    An error has occured, please retry.
                   </p>
                ";
        } ?>

        <form action="/twitter/handleLogin" method="POST">
            <p>
                <label>Login</label>
                <input type="text" name="login" value=
                "<?php if(isset($params['profile']))echo $params['profile']->GetLogin();?>">
            </p>
            <p>
                <label>Password</label>
                <input type="text" name="password" value=
                "<?php if(isset($params['profile']))echo $params['profile']->GetPassword();?>">
            </p>
            <button type="submit">Submit</button>
        </form>

    </body>
</html>