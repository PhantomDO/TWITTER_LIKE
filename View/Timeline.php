<!DOCTYPE HTML>
<p>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Timeline</title>
</p>
<h1>Timeline</h1>

<a href="/profile/' . <?php echo $_SESSION['ProfileGateway']['login']?> . '">Mon profile</a>
<a href="/search">Search</a><br><br>


<?php if($params['timeline']) :?>
    <?php
        foreach ($params['timeline'] as $timeline)
        {
            if ($timeline != null)
            {
                foreach ($timeline as $tweet)
                {
                    //var_dump($tweet);
                    $login = $params['services']->getService('profileFinder')->FindOneById($tweet->GetUserId());
                    echo '---------------------------------------------------------------------------- <br>';

                    echo '<a href="/profile/'.$login->GetLogin().'">'.$login->GetLogin().'</a>' . '<br>' . $tweet->GetTweetText() . '<br>' . $tweet->GetTweetDate() . '<br>';

                    echo '
                        <input type="hidden" name="tweet_id" value="' . $tweet->GetTweetId() . '">
                        <input type="hidden" name="tweet_user_id" value="' . $tweet->GetUserId() . '">
                        ';

                    echo '
                            <form action="/profile/' . mb_strtolower($login->GetLogin()) . '/tweet/update/rt" method="POST">
                                <input name="_method" type="hidden" value="PUT" />
                                <input type="hidden" name="tweet_rt" value="' . $tweet->GetTweetRt() . '">
                                <input type="hidden" name="tweet_id" value="' . $tweet->GetTweetId() . '">
                                <input type="hidden" name="tweet_user_id" value="' . $login->GetId() . '">
                                <button type="submit" name="rtBtn">RT</button> ' . $tweet->NumberOfRt($tweet->GetTweetId()) . '
                            </form>
                        ';

                    echo '
                            <form action="/profile/' . mb_strtolower($login->GetLogin()) . '/tweet/update/like" method="POST">
                                <input name="_method" type="hidden" value="PUT" />
                                <input type="hidden" name="tweet_like" value="' . $tweet->GetTweetLike() . '">
                                <input type="hidden" name="tweet_id" value="' . $tweet->GetTweetId() . '">
                                <input type="hidden" name="tweet_user_id" value="' . $login->GetId() . '">
                                <button type="submit" name="likeBtn"><3</button> ' . $tweet->NumberOfLike($tweet->GetTweetId()) . '
                            </form>
                        ';
                }
            }
        }
    ?>
<?php endif ?>
