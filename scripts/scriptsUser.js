function functionEreaseForm() {
    document.getElementById('sin').style.display = "none";
    document.getElementById('cos').style.display = "none";
}
function functionDisplayForm(display) {
    if (display === 'y') {
        document.getElementById('sin').style.display = "block";
        document.getElementById('cos').style.display = "block";
    }
    else {
        document.getElementById('sin').style.display = "none";
        document.getElementById('cos').style.display = "block";
    }
}