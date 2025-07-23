document.addEventListener('DOMContentLoaded', () => {
    //Add click listeners to all interaction containers (hearted)
    document.querySelectorAll('.discussion-card-interaction-container .interaction').forEach(interactable => {
        interactable.addEventListener('click', (e) => {
            //Prevent the a tag navigation from occuring
            e.stopPropagation();
            e.preventDefault();

            const discussionId = interactable.getAttribute('data-discussion-id');

            //Send it to the interaction api to change the count
            const formData = new URLSearchParams();
            formData.append('discussionId', discussionId);
            fetch('../private/discussion_interaction_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                //If it succeeded, update the count
                if (data.success) {
                    //Get the container of the heart counter
                    const interactionContainer = interactable.parentElement;
                    
                    //Get the interaction containers and the counters inside
                    const interactables = interactionContainer.querySelectorAll('.interaction');
                    const counts = interactionContainer.querySelectorAll('.count');

                    //Update the counter
                    counts[0].textContent = data.heartCount;
                    interactables[0].classList.toggle('selected');
                } else {
                    console.error('Server error:', data.message);
                }
            }).catch(error => {
                console.error(error);
            });
        });
    });
});