<!DOCTYPE HTML>
<p>
    <head>
        <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    </head>
    <title>Timeline</title>
</p>
<h1>Timeline</h1>
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

                    echo $login->GetLogin() . '<br>' . $tweet->GetTweetText() . '<br>' . $tweet->GetTweetDate() . '<br>';

                    echo '<input type="hidden" name="tweet_id" value="' . $tweet->GetTweetId() . '">';

                    echo '
                            <form action="/twitter/profile/' . $login->GetLogin() . '/tweet/update/rt" method="POST">
                                <input name="_method" type="hidden" value="PUT" />
                                <input type="hidden" name="tweet_rt" value="' . $tweet->GetTweetRt() . '">
                                <button type="submit" name="rtBtn">RT</button>
                            </form>
                        ';

                    echo '
                            <form action="/twitter/profile/' . $login->GetLogin() . '/tweet/update/like" method="POST">
                                <input name="_method" type="hidden" value="PUT" />
                                <input type="hidden" name="tweet_like" value="' . $tweet->GetTweetLike() . '">
                                <button type="submit" name="likeBtn"><3</button>
                            </form>
                        ';
                }
            }
        }
    ?>
<?php endif ?>
