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

            dataObject.data.forEach(function (ligne){
                let divPastille = $("#pastille_"+ligne.idLignePanier);
                divPastille.removeClass();
                divPastille.addClass(ligne.couleur);
            });

        }).fail(function(error) {
            let dataObject = JSON.parse(error.responseJSON);

            alert(dataObject.erreur);
        });
// FIXME: le rafraichissement des pastilles se fait actuellement toutes les 30 secondes
    }, 30000);
//On appel la méthode "rappel" toutes les 30 minutes
    window.setInterval(function(){
        rappel()
    }, 30*60*1000);
//Si la date de fin de cookie - 12 heures est supérieure à maintenant, alors on affiche le message
    var rappel = function(){
        cookieStore.get('cle').then(function(cookie){
            let now = new Date();
            let endDate = new Date(cookie.expires);
            let warningDate = new Date(cookie.expires - 12*60*60*1000);

            if(warningDate > now){
                alert("Pensez à cloturer votre enchere avant le " + endDate.toLocaleDateString('fr-FR') + ' à ' + endDate.toLocaleTimeString('fr-FR'));
            }
        });
    }
    rappel()
});

