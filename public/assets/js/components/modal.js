$(document).ready(function () {
    $('#openPostModal').click(function () {
        $('#postModal').modal('show');
    });
    $('#postForm').submit(function (e) {
        e.preventDefault();
        alert('Post submitted!');
    });
});
