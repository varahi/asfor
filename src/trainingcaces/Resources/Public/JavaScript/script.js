$(document).ready(function(){

    /****** DATEPICKER $ UI ******/
    $('.datepicker').datepicker({
        autoSize: false,
        dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
        dayNamesMin: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
        monthNames: [ "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre" ],
        monthNamesShort: [ "Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Jui", "Aoû", "Sep", "Oct", "Nov", "Dec" ],
        showOtherMonths: true,
        dateFormat: "dd/mm/yy",
        firstDay: 1
    });
    /****** DATEPICKER $ UI ******/

});
