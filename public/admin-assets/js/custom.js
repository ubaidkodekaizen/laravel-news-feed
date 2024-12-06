// script.js
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
  
    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      mainContent.classList.toggle('shifted');
    });
});
document.addEventListener('DOMContentLoaded', function () {
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 10,
        searching: true,
        language: {
            search: "Search Users:",
        },
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting for the Actions column
        ]
    });
    $('#companiesTable').DataTable({
        responsive: true,
        pageLength: 10,
        searching: true,
        language: {
            search: "Search Users:",
        },
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting for the Actions column
        ]
    });
});
  