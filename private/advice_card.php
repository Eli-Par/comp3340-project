<?php
//Create interaction section for the advice specified
function createAdviceInteractionButtons($adviceId, $likes, $dislikes, $isLiked, $isDisliked, $pill=false)
{
    return '
    <div class="advice-card-interaction-container ' . ($pill ? 'interaction-pill' : '') . '">
        <div class="interaction like-button ' . ($isLiked ? 'selected' : '') . '" data-action="like" data-advice-id="' . intval($adviceId) . '">
            <span class="material-symbols-outlined">thumb_up</span>
            <span class="count">' . intval($likes) . '</span>
        </div>
        <div class="interaction dislike-button ' . ($isDisliked ? 'selected' : '') . '" data-action="dislike" data-advice-id="' . intval($adviceId) . '">
            <span class="material-symbols-outlined">thumb_down</span>
            <span class="count">' . intval($dislikes) . '</span>
        </div>
    </div>
    ';
}

function createAdviceCard($adviceId, $title, $content, $username, $likes, $dislikes, $isLiked, $isDisliked)
{
    // Truncate content after 150 characters
    $truncated = mb_substr($content, 0, 150);
    if (mb_strlen($content) > 150) {
        $truncated .= '...';
    }

    //Make newlines visible
    $safeContent = nl2br(htmlentities($truncated));

    //Create the card with the information inserted
    return '
    <a href="advice.php?adviceId=' . $adviceId . '" " style="text-decoration:none; color:inherit;">
        <section class="card advice-card">
            <h2>' . htmlentities($title) . '</h2>
            <div style="text-align: center">By ' . htmlentities($username) . '</div>
            <p>' . $safeContent . '</p>
            ' . createAdviceInteractionButtons($adviceId, $likes, $dislikes, $isLiked, $isDisliked) . '
        </section>
    </a>
    ';
}
?>
