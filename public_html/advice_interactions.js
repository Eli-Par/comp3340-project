document.addEventListener('DOMContentLoaded', () => {
    //Add click listeners to all interaction containers (like or dislike)
    document.querySelectorAll('.advice-card-interaction-container .interaction').forEach(interactable => {
        interactable.addEventListener('click', (e) => {
            //Prevent the a tag navigation from occuring
            e.stopPropagation();
            e.preventDefault();

            //Get the advice and action type of the interactable
            const adviceId = interactable.getAttribute('data-advice-id');
            const action = interactable.getAttribute('data-action');

            //Send it to the interaction api to change the count
            const formData = new URLSearchParams();
            formData.append('adviceId', adviceId);
            formData.append('action', action);
            fetch('../private/advice_interaction_api.php', {
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
                    //Get the container of the like and dislike buttons/counters
                    const interactionContainer = interactable.parentElement;
                    
                    //Get the interaction containers and the counters inside
                    const interactables = interactionContainer.querySelectorAll('.interaction');
                    const counts = interactionContainer.querySelectorAll('.count');

                    //Update the counters
                    counts[0].textContent = data.likeCount;
                    counts[1].textContent = data.dislikeCount;

                    //Toggle the selected style on the like and dislike to reflect the change
                    if (action == "like") {
                        interactables[0].classList.toggle('selected');
                        interactables[1].classList.remove('selected');
                    }
                    else {
                        interactables[0].classList.remove('selected');
                        interactables[1].classList.toggle('selected');
                    }
                } else {
                    console.error('Server error:', data.message);
                }
            }).catch(error => {
                console.error(error);
            });
        });
    });
});