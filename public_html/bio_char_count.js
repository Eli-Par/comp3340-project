document.addEventListener('DOMContentLoaded', () => {
    const bio = document.getElementById('bio');

    const setCount = () => {
        const text = bio.value;

        const length = text.length;

        const bioMaxLabel = document.getElementById('bio-max');

        bioMaxLabel.textContent = `(${length} / 500 characters)`;
    };

    setCount();
    bio.addEventListener('input', () => {
        setCount();
    });
});