import "bootstrap";

function editComment(id) {
    const commentCard = document.querySelector(`[data-id="${id}"]`);
    const text = commentCard.querySelector(".card-text").textContent;
    const dialog = new bootstrap.Modal("#commentUpdateDialog");
}
