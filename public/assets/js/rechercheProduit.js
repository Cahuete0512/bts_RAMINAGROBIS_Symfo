function rechercheProduit() {
    let input = document.getElementById('recherchebar').value
    input=input.toLowerCase();
    let x = document.getElementsByClassName('enchere_tr');

    for (i = 0; i < x.length; i++) {
        if (!x[i].children[0].innerText.toLowerCase().includes(input)) {
            x[i].style.display="none";
        }else{
            x[i].style.display="table-row";
        }
    }
}