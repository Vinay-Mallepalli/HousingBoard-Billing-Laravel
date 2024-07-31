document.addEventListener("DOMContentLoaded", function () {
    let selectAllCheckbox = document.getElementById("select-all-checkbox");
    let checkboxes = document.querySelectorAll(
        'input[name="selected_records[]"]'
    );

    selectAllCheckbox.addEventListener("change", function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                selectAllCheckbox.checked =
                    checkboxes.length ===
                    document.querySelectorAll(
                        'input[name="selected_records[]"]:checked'
                    ).length;
            }
        });
    });
});
