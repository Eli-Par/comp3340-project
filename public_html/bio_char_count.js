document.addEventListener('DOMContentLoaded', () => {
    const bio = document.getElementById('bio');

    //Set bio label to have the current char count and max
    const setCount = () => {
        const text = bio.value;

        const length = text.length;

        const bioMaxLabel = document.getElementById('bio-max');

        bioMaxLabel.textContent = `(${length} / 500 characters)`;
    };

    //Set and up date the char count as bio is changed
    setCount();
    bio.addEventListener('input', () => {
        setCount();
    });
});