$(function() {

    $('.btnEnchere').click(function(){
        let idLigne = $(this).get(0).id.substring(8);
        let prix = $('#lignePanier_'+idLigne).val();

        // alert('idLigne:' + idLigne + ' prix:' + prix);
        var jsonData = JSON.stringify({ 'idLigne': idLigne, 'prix': prix });

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/enchere/ajouter",
            contentType: "application/json",
            data: jsonData
        }).done(function(data) {
            alert('Enchere enregistrée');
        });

    });
});