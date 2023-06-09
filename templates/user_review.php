<?php

require_once("models/User.php");

$userModel = new User();

$fullName = $userModel->getFullName($review->users_id);
// Checar se o filme tem imagem

if ($review->users_id->image == "") {
    $review->users_id->image = "user.png";
}
?>


<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->users_id->image ?>');"></div>
        </div>
        <div class="md-9 author-details-container">
            <h4 class="author-name">
                <a href="#"><?= $fullName ?></a>
            </h4>
            <p> <i class="fas fa-star"></i><?= $review->rating ?></p>
        </div>
        <div class="col-md-12">
            <p class="coment-title">Comentário:</p>
            <p><?= $review->review ?></p>
        </div>
    </div>

</div>