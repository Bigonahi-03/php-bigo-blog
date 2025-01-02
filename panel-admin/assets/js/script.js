// Function to show toast
document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        toast.show(); // Show toast
    }
});

// JavaScript to manage opening and closing sections
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Close all edit sections
            const allCollapses = document.querySelectorAll('.collapse');
            allCollapses.forEach(collapse => {
                if (collapse.id !== this.getAttribute('data-bs-target').substring(1)) {
                    collapse.classList.remove('show'); // Close other sections
                }
            });
        });
    });

    // Open the collapse section based on URL id
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('id');
    if (categoryId) {
        const collapseElement = document.getElementById('collapseEdit' + categoryId);
        if (collapseElement) {
            collapseElement.classList.add('show'); // Open the collapse section
        }
    }

    // Remove URL parameters when collapse is closed
    const collapses = document.querySelectorAll('.collapse');
    collapses.forEach(collapse => {
        collapse.addEventListener('hidden.bs.collapse', function() {
            history.pushState(null, '', './index.php');
        });
    });
});

// Function to change URL
function changeUrl(categoryId) {
    if (categoryId) {
        history.pushState(null, '', './index.php?action=update&id=' + categoryId);
    } else {
        console.error('categoryId is not defined');
    }
}
