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

function showDelBtn(id) {
    var btn1 = document.getElementById("btnxd1-" + id);
    var btn2 = document.getElementById("btnxd2-" + id);
    var btn3 = document.getElementById("btnxd3-" + id);
    var btn4 = document.getElementById("btnxd4-" + id);
    
    btn1.style.display = "none";
    btn2.style.display = "none";
    btn3.style.display = "inline";
    btn4.style.display = "inline";
}

function hideDelBtn(id) {
    var btn1 = document.getElementById("btnxd1-" + id);
    var btn2 = document.getElementById("btnxd2-" + id);
    var btn3 = document.getElementById("btnxd3-" + id);
    var btn4 = document.getElementById("btnxd4-" + id);
    
    btn1.style.display = "inline";
    btn2.style.display = "inline";
    btn3.style.display = "none";
    btn4.style.display = "none";
}
