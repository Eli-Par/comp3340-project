function deleteItem(event, elem) {
    console.log("Delete item...")
    event.stopPropagation();
    event.preventDefault();

    const discussionId = elem.getAttribute('data-discussion-id');

    fetch('../private/delete_discussion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'discussionId=' + encodeURIComponent(discussionId)
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            throw new Error('Failed to delete');
        }
    })
    .catch(error => {
        console.error(error);
    });
}