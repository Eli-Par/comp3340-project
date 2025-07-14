<?php
function trending_discussion_query($limit=12) {
return 
    "SELECT 
        discussionId, 
        title, 
        content, 
        username,
        dateCreated,

        -- Like and dislike count queries
        (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
        
        -- Booleans for current user
        EXISTS (
            SELECT 1 FROM discussion_interactions a4
            WHERE a4.discussionId = a.discussionId AND a4.userId = ?
        ) AS heartedByUser

    FROM discussion a JOIN users ON a.authorId = users.userId 
    WHERE (
        SELECT COUNT(*) 
        FROM discussion_interactions a1 
        WHERE a1.discussionId = a.discussionId 
        AND a1.dateInteracted >= NOW() - INTERVAL 7 DAY
    ) > 0
    ORDER BY (
        SELECT COUNT(*) 
        FROM discussion_interactions a1 
        WHERE a1.discussionId = a.discussionId 
        AND a1.dateInteracted >= NOW() - INTERVAL 7 DAY
    ) DESC, dateCreated DESC
    LIMIT 12";
}
?>