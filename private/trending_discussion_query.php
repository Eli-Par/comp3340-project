<?php
//Return sql query string for fetching trending discussions.
//A discussion is trending if it has any hearts in the last 7 days and is in the top [limit] discussions by hearts added in the last 7 days
function trending_discussion_query($limit=12) {
return 
    "SELECT 
        a.discussionId, 
        a.title, 
        a.content, 
        a.authorId,
        users.username,
        a.dateCreated,

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
    LIMIT $limit";
}
?>