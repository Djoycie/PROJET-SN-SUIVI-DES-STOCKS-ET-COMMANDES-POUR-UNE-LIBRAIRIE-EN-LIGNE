function filterBooks() {
    const category = document.getElementById("category").value;
    const rows = document.querySelectorAll("#book-list tr");

    rows.forEach(row => {
        if (category === "tous" || row.getAttribute("data-category") === category) {
            row.style.display = ""; // Afficher
        } else {
            row.style.display = "none"; // Masquer
        }
    });
}