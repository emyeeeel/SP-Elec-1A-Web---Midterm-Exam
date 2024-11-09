<?php
session_start();

if (isset($_GET['play_again'])) {
    $_SESSION['player_score'] = 0;
    $_SESSION['computer_score'] = 0;
    $_SESSION['game_over'] = false; 
    header("Location: jackenpoy.php"); 
    exit(); 
}

if (!isset($_SESSION['player_score'])) {
    $_SESSION['player_score'] = 0;
    $_SESSION['computer_score'] = 0;
    $_SESSION['game_over'] = false;
}

$choices = ['rock', 'paper', 'scissors'];

$player_choice_image = '';
$computer_choice_image = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['choice']) && !isset($_POST['end_game'])) {
        $player_choice = $_POST['choice'];

        $player_choice_image = "jackenpoy/" . 
            (($player_choice === 'rock') ? 'rck' : (($player_choice === 'scissors') ? 'sciss' : $player_choice)) . 
            "-l.gif";

        $computer_choice = $choices[rand(0, 2)];

        $computer_choice_image = "jackenpoy/" . 
            (($computer_choice === 'rock') ? 'rck' : (($computer_choice === 'scissors') ? 'sciss' : $computer_choice)) . 
            "-r.gif";

        $result = determine_winner($player_choice, $computer_choice);
        if ($result === 'human') {
            $_SESSION['player_score']++;
        } elseif ($result === 'computer') {
            $_SESSION['computer_score']++;
        }
    }

    if (isset($_POST['end_game'])) {
        $_SESSION['game_over'] = true; 
    }
}

function determine_winner($human, $computer) {
    if ($human === $computer) {
        return 'draw';
    }

    switch ($human) {
        case 'rock':
            return ($computer === 'scissors') ? 'human' : 'computer';
        case 'paper':
            return ($computer === 'rock') ? 'human' : 'computer';
        case 'scissors':
            return ($computer === 'paper') ? 'human' : 'computer';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock, Paper, Scissors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            justify-content: center;
        }
        table {
            width: 50%;
            margin-top: 20px;
            text-align: center;
        }
        td, th {
            padding: 10px;
        }
        .choices label, .canvas img {
            margin-top: 10px;
            width: 100px;
        }
        .end-button {
            margin-top: 20px;
        }
        #left-align{
            text-align: left;
        }
        .game-over{
            display: none;
        }
        .game-over.show{
            display: block;
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['game_over']) || !$_SESSION['game_over']): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th colspan="4">Rock, Paper, Scissors</th>
            </tr>
            <tr>
                <td>
                    <?php if ($player_choice_image): ?>
                        <img src="<?php echo $player_choice_image; ?>" alt="Player's choice">
                    <?php else: ?>            
                        <img src="jackenpoy/filler.png" alt="Filler">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($computer_choice_image): ?>
                        <img src="<?php echo $computer_choice_image; ?>" alt="Computer's choice">
                    <?php else: ?>
                        <img src="jackenpoy/filler.png" alt="Filler">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Human Player</strong>
                </td>
                <td>
                    <strong>Computer Player</strong>
                </td>
            </tr>

            <tr>
                <td colspan="4" id="left-align">
                    <form method="POST" action="jackenpoy.php">
                        <div class="choices">
                            <label><input type="radio" name="choice" value="rock"> Rock</label>
                            <label><input type="radio" name="choice" value="paper"> Paper</label>
                            <label><input type="radio" name="choice" value="scissors"> Scissors</label>
                        </div>
                        Your score is: <?php echo $_SESSION['player_score']; ?><br>
                        PC Player score is: <?php echo $_SESSION['computer_score']; ?>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <button type="submit">Play</button>
                    <form method="POST" action="jackenpoy.php">
                        <button type="submit" name="end_game">End</button>
                    </form>
                </td>
            </tr>
        </table>
    <?php endif; ?>

    <div class="game-over <?php echo isset($_SESSION['game_over']) && $_SESSION['game_over'] ? 'show' : ''; ?>">
        Thank you very much for playing...<br>
        Your score is: <?php echo $_SESSION['player_score']?><br>
        Click here to <a href="jackenpoy.php?play_again=true" class='play-again'>play</a> again
    </div>
</body>
</html>
