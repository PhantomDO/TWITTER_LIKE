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
                    This account doesn't exist.
                    Do you want to create one ?
                   </p>
                ";
            echo "
            <a href='/register'><button type=\"submit\">Yes</button></a>
            <a href='/'><button type=\"submit\">No</button></a>
            ";
        }
        ?>

        <form action="/handleLogin" method="POST"
            <?php if (isset($params['error'])) echo "<p style='display: none'>"; ?>>
            <p>
                <label>Login</label>
                <input type="text" name="login" value=
                "<?php if(isset($params['profile']))echo $params['profile']->GetLogin();?>">
            </p>
            <p>
                <label>Password</label>
                <input type="password" name="password" value=
                "<?php if(isset($params['profile']))echo $params['profile']->GetPassword();?>">
            </p>
            <button type="submit">Submit</button>
        </form>

    </body>
</html>