function submitForm() {
    document.getElementById("search-button").click();
}

function submitSort(id) {
    document.getElementById("sort").value = id;
    document.getElementById("search-button").click();
}