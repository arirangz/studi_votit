<?php require_once 'templates/header.php';

require_once 'lib/poll.php';

$polls = getPolls($pdo);


?>

<h1>Les sondages</h1>

<div class="row text-center">
    <?php foreach($polls as $poll) { 
        require 'templates/poll_part.php';
    } ?>
</div>


<?php require_once 'templates/footer.php'; ?>