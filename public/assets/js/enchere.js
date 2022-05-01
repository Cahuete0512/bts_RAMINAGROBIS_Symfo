$(function() {

    $('.btnEnchere').click(function(){
        let idLigne = $(this).get(0).id.substring(8);
        let prix = $('#lignePanier_'+idLigne).val();

        let jsonData = JSON.stringify({'idLigne': idLigne, 'prix': prix});

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/enchere/ajouter",
            contentType: "application/json",
            data: jsonData
        }).done(function(data) {
            let dataObject = JSON.parse(data);
            let divPastille = $("#pastille_"+dataObject.idLignePanier);

            divPastille.removeClass();
            divPastille.addClass(dataObject.couleur);
        }).fail(function(error) {
            let dataObject = JSON.parse(error.responseJSON);

            alert(dataObject.erreur);
        });
    });


    window.setInterval(function(){
        console.log("refresh");


        $.ajax({
            method: "GET",
            url: "http://127.0.0.1:8000/enchere/rafraichir/",
            contentType: "application/json"
        }).done(function(data) {
            let dataObject = JSON.parse(data);

            let reponseFactice =
            { "data" :
                [
                    {
                        "idLignePanier": 5387,
                        "couleur": "cercle_orange"
                    },
                    {
                        "idLignePanier" : 5329,
                        "couleur": "cercle_rouge"
                    }
                ]
            };

            dataObject.data.forEach(function (ligne){
                let divPastille = $("#pastille_"+ligne.idLignePanier);
                divPastille.removeClass();
                divPastille.addClass(ligne.couleur);
            });

        }).fail(function(error) {
            let dataObject = JSON.parse(error.responseJSON);

            alert(dataObject.erreur);
        });


// FIXME: le rafraichissement se fait actuellement toutes les 30 secondes
    }, 30000);



    $('#cloreEncheres').click(function(){

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/enchere/clore",
            contentType: "application/json"
        }).done(function(data) {
            alert("Encheres closes");
        }).fail(function(error) {
            alert(error);
        });
    });
});