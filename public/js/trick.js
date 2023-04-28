document.getElementById('add-image').addEventListener('click', function() {
        let imageFieldsList = document.querySelector('#trick_images');
        let newImageField = document.createElement('div');

        let newIndex = imageFieldsList.querySelectorAll('div').length;

        newImageField.innerHTML = imageFieldsList.getAttribute('data-prototype')
            .replace(/__name__/g, newIndex);

        imageFieldsList.appendChild(newImageField);
    });

const addVideoButton = document.querySelector('#add-video');
const videoCollectionHolder = document.querySelector('.video-collection');

if (addVideoButton && videoCollectionHolder) {
    addVideoButton.addEventListener('click', () => {
    addVideoForm(videoCollectionHolder);
});

// Add a delete button for each existing video form
videoCollectionHolder.querySelectorAll('.video-form').forEach(videoForm => {
    addVideoFormDeleteButton(videoForm);
});
}

function addVideoForm(videoCollectionHolder) {
    const videoFormPrototype = videoCollectionHolder.getAttribute('data-prototype');
    const newIndex = videoCollectionHolder.querySelectorAll('.video-form').length;
    const newForm = videoFormPrototype.replace(/__name__/g, newIndex);

    const videoForm = document.createElement('div');
    videoForm.classList.add('video-form');
    videoForm.innerHTML = newForm;
    videoCollectionHolder.appendChild(videoForm);

    addVideoFormDeleteButton(videoForm);
}

function addVideoFormDeleteButton(videoForm) {
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.classList.add('btn', 'btn-danger');
    deleteButton.textContent = 'Delete video';

    deleteButton.addEventListener('click', () => {
    videoForm.remove();
    });

    videoForm.appendChild(deleteButton);
}