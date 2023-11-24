<?php
require_once 'lib/required_files.php';
require_once 'lib/poll.php';

$error404 = false;

$messages = [];
$errors = [];

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $poll = getPollById($pdo, $id);

    if ($poll) {
        $pageTitle = $poll['title'];

        if (isset($_SESSION['user']) && isset($_POST['voteSubmit'])) {
            if (empty($_POST['items'])) {
                $errors[] = "Vous devez sélectionner au moins une proposition";
            } else {
                removeVotesByPollIdAndUserId($pdo, $id, (int)$_SESSION['user']['id']);
                $resAddVote = addVote($pdo, (int)$_SESSION['user']['id'], $_POST['items']);
                if ($resAddVote) {
                    $messages[] =  "Votre vote a bien été pris en compte.";
                } else {
                    $errors[] = "Une erreur est survenue pendant l'ajout du vote.";
                }
            }


        }

        $results = getPollResultsByPollId($pdo, $id);
        $totalUsers = getPollTotalUsersByPollId($pdo, $id);
        $items = getPollItems($pdo, $id);
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
            <h1 class="display-5 fw-bold lh-1 mb-3"><?= $poll['title'] ?></h1>
            <p class="lead"><?= $poll['description'] ?></p>

        </div>
        <div class="col-10 col-sm-8 col-lg-6">
            <h2>Résultats</h2>
            <div class="results">
                <?php foreach ($results as $index => $result) {

                    if ($totalUsers) {
                        //Calcul
                        $resultPercent = $result['votes'] / $totalUsers * 100;
                    } else {
                        $resultPercent = 0;
                    }
                ?>
                    <h3><?= $result['name'] ?></h3>
                    <div class="progress" role="progressbar" aria-label="<?= $result['name'] ?>" aria-valuenow="<?= $resultPercent; ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped progress-color-<?= $index ?>" style="width: <?= $resultPercent; ?>%"><?= $result['name'] ?> <?= round($resultPercent, 2); ?>%</div>
                    </div>
                <?php } ?>

            </div>
            <div class="mt-5">
                <?php if (isset($_SESSION['user'])) { ?>
                    <div>
                        <form method="post">
                            <h2>Votez pour ce sondage :</h2>
                            <h3><?= $poll['title']; ?></h3>
                            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                <?php foreach ($items as $key => $item) { ?>
                                    <input type="checkbox" class="btn-check" id="btncheck<?= $item['id'] ?>" autocomplete="off" value="<?= $item['id'] ?>" name="items[]">
                                    <label class="btn btn-outline-primary" for="btncheck<?= $item['id'] ?>"><?= $item['name'] ?></label>
                                <?php } ?>
                            </div>
                            <?php if ($messages) { ?>
                                <?php foreach ($messages as $message) { ?>
                                    <div class="alert alert-success mt-2" role="alert">
                                        <?=$message?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($errors) { ?>
                                <?php foreach ($errors as $error) { ?>
                                    <div class="alert alert-danger mt-2" role="alert">
                                        <?=$error?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <div class="mt-2">
                                <input type="submit" name="voteSubmit" class="btn btn-primary" value="Voter">
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-warning">
                        Vous devez être connecté pour voter.
                    </div>
                <?php } ?>

            </div>
        </div>

    </div>
<?php } else { ?>
    <h1>Le sondage n'existe pas</h1>
<?php } ?>


<?php require_once 'templates/footer.php'; ?>