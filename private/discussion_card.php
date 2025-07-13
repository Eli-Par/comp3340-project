<?php
function createDiscussionInteractionButtons($discussionId, $hearts, $isHearted, $pill = false)
{
    return '
    <div class="discussion-card-interaction-container ' . ($pill ? 'interaction-pill' : '') . '">
        <div class="interaction heart-button ' . ($isHearted ? 'selected' : '') . '" data-action="heart" data-discussion-id="' . intval($discussionId) . '">
            <span class="material-symbols-outlined">favorite</span>
            <span class="count">' . intval($hearts) . '</span>
        </div>
    </div>
    ';
}

function createDiscussionCard($discussionId, $title, $content, $username, $hearts, $isHearted)
{
    $safeContent = htmlentities($content);

    //Create the card with the information inserted
    return '
    <a href="discussion.php?discussionId=' . $discussionId . '" " style="text-decoration:none; color:inherit;">
        <section class="card discussion-card">
            <div class="discussion-card-top-row">
                <h2 class="discussion-card-title">' . htmlspecialchars($title) . '</h2>
                <div style="margin-right: 10px;">By ' . htmlspecialchars($username) . '</div>
                ' . createDiscussionInteractionButtons($discussionId, $hearts, $isHearted) . '
            </div>
            <p class="discussion-card-content">' . $safeContent . '</p>
        </section>
    </a>
    ';
}
?>