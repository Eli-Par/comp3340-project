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

function createDiscussionCard($discussionId, $title, $content, $authorId, $username, $hearts, $isHearted)
{
    $safeContent = htmlentities($content);

    $editActions = '';
    if($_SESSION['userId'] == $authorId || $_SESSION['isAdmin']) {
        $editActions = '
            <div class="discussion-edit-card">
                <span class="material-symbols-outlined edit-action" onclick="event.preventDefault(); event.stopPropagation(); window.location.href=\'edit_discussion.php?discussionId=' . $discussionId . '\';">edit</span>
                <span class="material-symbols-outlined delete-action" onclick="deleteItem(event, this)" data-discussion-id="' . $discussionId . '">delete</span>
            </div>';
    }

    //Create the card with the information inserted
    return '
    <a href="discussion.php?discussionId=' . $discussionId . '" " style="text-decoration:none; color:inherit;">
        <section class="card discussion-card">
            <div class="discussion-card-top-row">
                <h2 class="discussion-card-title">' . htmlspecialchars($title) . '</h2>
                <div style="margin-right: 10px;">By ' . htmlspecialchars($username) . '</div>
                ' . createDiscussionInteractionButtons($discussionId, $hearts, $isHearted) . '
            </div>
            <p class="discussion-card-content">' . $safeContent . '</p>'
            . $editActions .
        '</section>
    </a>
    ';
}
?>