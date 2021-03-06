<?php
    include_once("functions/functions.php");
    include_once("functions/api_calls.php");
    
    $title = filter_input(INPUT_GET, "query", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $min = filter_input(INPUT_GET, "min_price", FILTER_SANITIZE_NUMBER_FLOAT);
    $max = filter_input(INPUT_GET, "max_price", FILTER_SANITIZE_NUMBER_FLOAT);
    
    if (empty($min)){$min = 0;}
    if (empty($max)){$max = 1000;}
    
    $games = search($title, $min, $max);
    $count = sizeof($games);
    $games_found = $count > 0;
    $plural = ($count > 1);
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Search results for <?= $query ?></title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/search.css">
    </head>
    
    <body>
        <?php include "includes/header.php"; ?>
        
        <main>
            <?php if ($min > $max) : ?>
                <p>Did you mix up minimum/maximum prices?</p>;
            <?php endif; if (!$games_found) : ?>
                <p>No games found.</p>;
            <?php else : ?>
                <ul id="search_results">
                    <p><?=$count?> <?php echo ($plural ? $w["games found"] : $w["game found"]) ?>.</p>
                    
                    <?php if ($count > 20) : ?>
                        <p>Perhaps try using advanced search?</p>
                    <?php endif; ?>

                    <br/>
                
                    <?php foreach ($games as $game) : ?>
                        <li>
                            <a class="title" href="game.php?game_id=<?=$game->id?>">
                                <?=$game->title?><br>
                            </a>
                            
                            <?php
                                $steam = $game->steam;
                                $gog = $game->gog;
                                
                                if (!is_null($steam)) : ?>
                                    <a href="https://store.steampowered.com/app/<?php echo $steam->steam_id ?>">
                                        €<?=$steam->price?> <?=$w["on"]?> Steam
                                    </a>
                                    <br>
                                <?php endif; if (!is_null($gog)) : ?>
                                    <a href="https://www.gog.com/game/<?= $gog->slug ?>">
                                        €<?=$gog->price?> <?=$w["on"]?> GOG
                                    </a>
                                    <br>
                                <?php endif; ?>
                            <br/>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>
    </body>
</html>
