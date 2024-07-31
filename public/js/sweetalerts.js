document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('deleteHousingBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior

        Swal.fire({
            title: 'Delete Housing',
            text: 'Are you sure you want to delete this housing?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.target.href; // Proceed with the deletion
            }
        });
    });
});
