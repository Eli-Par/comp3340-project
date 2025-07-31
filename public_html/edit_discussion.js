function deleteItem(event, elem) {
    event.stopPropagation();
    event.preventDefault();

    if (!confirm("Are you sure you want to delete this discussion post?")) return;

    const discussionId = elem.getAttribute('data-discussion-id');

    //Send api request to delete discussion
    fetch('../private/delete_discussion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'discussionId=' + encodeURIComponent(discussionId)
    })
    .then(response => {
        if (response.ok) {
            //If discussion deleted succeeded reload page
            location.reload();
        } else {
            throw new Error('Failed to delete');
        }
    })
    .catch(error => {
        console.error(error);
    });
}