function showEditCon(id) {
    var normCon = document.getElementById("normalCon-" + id);
    var editCon = document.getElementById("editCon-" + id);

    normCon.style.display = "none";
    editCon.style.display = "table-row";
}

function hideEditCon(id) {
    var normCon = document.getElementById("normalCon-" + id);
    var editCon = document.getElementById("editCon-" + id);

    normCon.style.display = "table-row";
    editCon.style.display = "none";
}
