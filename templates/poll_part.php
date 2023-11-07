<div class="col-md-4 my-2 d-flex">
    <div class="card w-100">
        <div class="card-header">
            <img width="40" src="<?=PATH_ASSETS_IMAGES?>icon-arrow.png" alt="icone flèche haut"> <?=$poll['category_name'] ?>
        </div>
        <div class="card-body d-flex flex-column">
            <h3 class="card-title"><?=$poll['title'] ?></h3>
            <div class="mt-auto">
                <a href="sondage.php?id=<?=$poll['id']; ?>" class="btn btn-primary">Voir le sondage</a>
            </div>
        </div>
    </div>
</div>