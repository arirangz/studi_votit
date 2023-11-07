<?php 
require_once 'lib/required_files.php';
require_once 'lib/poll.php';

$error404 = false;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $poll = getPollById($pdo, $id);

    if ($poll) {
        $pageTitle = $poll['title'];
        $results = getPollResultsByPollId($pdo, $id);
    } else {
        $error404 = true;
    }

} else {
    $error404 = true;
}

require_once 'templates/header.php';




if (!$error404) {
?>
<div class="row align-items-center g-5 py-5">
    <div class="col-lg-6">
        <h1 class="display-5 fw-bold lh-1 mb-3"><?=$poll['title'] ?></h1>
        <p class="lead"><?=$poll['description'] ?></p>

    </div>
    <div class="col-10 col-sm-8 col-lg-6">
        <h2>Résultats</h2>
        <div class="results">
            <?php foreach ($results as $index => $result) { ?>
                <h3><?=$result['name'] ?></h3>
                <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped progress-color-<?=$index?>" style="width: 25%"><?=$result['name'] ?> 25%</div>
                </div>  
            <?php } ?>

        </div>
    </div>

</div>
<?php } else { ?>
    <h1>Le sondage n'existe pas</h1>
<?php } ?>


<?php require_once 'templates/footer.php'; ?>