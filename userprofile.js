// script.js
document.getElementById('edit-profile-picture').addEventListener('click', function() {
    document.getElementById('profile-picture-input').click();
});

document.getElementById('profile-picture-input').addEventListener('change', function(event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profile-image').src = e.target.result;
    };
    reader.readAsDataURL(file);
});