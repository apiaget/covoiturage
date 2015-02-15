
/*{
    "startdate":"2015-03-02 00:00:00",
    "enddate":"2015-03-30 00:00:00",
    "seats":"3",
    "isrecurrence":true,
    "recurrence":[true,true,true,true,true,false,false],
    "registrations": [[
    {"id": "220","user_fk": "32","ride_fk": "2","date": "2015-03-02"},
    {"id": "351","user_fk": "71","ride_fk": "2","date": "2015-03-04"},
    {"id": "501","user_fk": "103","ride_fk": "2","date": "2015-03-10"}
]],
    "userId":"2"
}*/
(function($){
    $.Registration = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        var recurrence;
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("Registration", base);

        base.init = function(){
            base.options = $.extend({},$.Registration.defaultOptions, options);

            var parameters = $.parseJSON($(el).html());
            $(el).html('');
            //troooollololol
            base.recurrence(parameters); //Converti les jours récurrents en valeurs numériques (plus facile de travailler avec par la suite)
            base.createCalendar(parameters); //Crée le calendrier
            base.eventsSetter(); //ajoute le comportement au cases
        };


        base.recurrence = function(parameters){
            recurrence = [parameters.recurrence['monday']?1:0,
                parameters.recurrence['tuesday']?1:0,
                parameters.recurrence['wednesday']?1:0,
                parameters.recurrence['thursday']?1:0,
                parameters.recurrence['friday']?1:0,
                parameters.recurrence['saturday']?1:0,
                parameters.recurrence['sunday']?1:0
            ];
        };

        base.createCalendar = function(parameters){
            var table = $('<table class="tableau-calendrier"></table>');

            var startdate = new Date(parameters.startdate);
            var enddate = new Date(parameters.enddate);
            startdate = startdate < new Date() ? new Date() : startdate;
            var startweek = base.getMonday(startdate); //stocke le premier jour du calendrier à afficher
            var endweek = base.getSunday(enddate);  //stocke le dernier jour du calendrier à afficher

            //entête
            var entete = '<tr><td></td>';
            if(recurrence[0]==1)
                entete+='<td><div class="carre-noir">Lun<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[1]==1)
                entete+='<td><div class="carre-noir">Mar<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[2]==1)
                entete+='<td><div class="carre-noir">Mer<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[3]==1)
                entete+='<td><div class="carre-noir">Jeu<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[4]==1)
                entete+='<td><div class="carre-noir">Ven<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[5]==1)
                entete+='<td><div class="carre-noir">Sam<br/><img src="img/arrowDownBlack.png"/></div></td>';
            if(recurrence[6]==1)
                entete+='<td><div class="carre-noir">Sun<br/><img src="img/arrowDownBlack.png"/></div></td>';

            entete+='</tr>';
            $(entete).appendTo(table);

            //nombre de semaines
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var diffDays = Math.ceil(Math.abs((startweek.getTime() - endweek.getTime())/(7*oneDay)));

            //Create weeks rows
            for(var week = 0 ; week < diffDays ; week++){

                //Date on the first column
                var row = '<tr><td class="semaine">' +
                    ("0" + startweek.getDate()).slice(-2)+'.'+("0" + (startweek.getMonth() + 1)).slice(-2)+'.'+startweek.getFullYear()
                    + '</td>';

                for(var day = 0 ; day < 7 ; day++){
                    if(recurrence[day]){
                        var count = base.countRegistrationsPerDate(parameters.registrations[0], startweek, parameters.userId);
                        row+='<td>';
                        if(1 == count[1]){
                            row+='<div class="carre-vert"><p>';
                        }else{
                            row+='<div class="carre-blanc"><p>';
                        }
                        row+=count[0]+'/'+parameters.seats+'</p></div>';
                        row+='</td>';
                    }
                    startweek = new Date(startweek.setDate(startweek.getDate() + 1));
                }
                row += '</tr>';
                $(row).appendTo(table);
            }


            table.appendTo(el);
        };

        base.getMonday = function(dateToConvert){
            var day = dateToConvert.getDay(),
                date = new Date(dateToConvert),
                diff = date.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
            return new Date(date.setDate(diff));
        };

        base.getSunday = function(dateToConvert){
            var day = dateToConvert.getDay(),
                date = new Date(dateToConvert),
                diff = date.getDate() + (day==0 ? 0 : -day+7);
            return new Date(date.setDate(diff));
        };

        base.countRegistrationsPerDate = function(registrations, date, userId){
            var registrationsPerDate = 0;
            var isRegistredThatDate = 0;
            for(var i = 0 ; i < registrations.length ; i ++){
                if(new Date(registrations[i].date) == date){
                    registrationsPerDate++;
                }
                var regdate = new Date(registrations[i].date);

                if(regdate.getFullYear() == date.getFullYear() && regdate.getMonth() == date.getMonth() && regdate.getDate() == date.getDate()){
                    registrationsPerDate++;
                    if(registrations[i].user_fk == userId){
                        isRegistredThatDate++;
                    }
                }

            }
            return [registrationsPerDate, isRegistredThatDate];
        };

        base.eventsSetter = function(){
            //Clic sur un carré de la grille (sélectionne juste la case)
            $("div.carre-blanc, div.carre-vert").click(function(e){
                $(this).toggleClass("carre-vert").toggleClass("carre-blanc");
            });
            //Clic sur le triangle du haut (sélectionne la colonne complète)
            $("div.carre-noir").click(function(e){
                var index = $(this).parent().index() + 1;
                $('.tableau-calendrier td:nth-child('+index+')').slice(1).find('div').attr("class", "carre-vert");
            });
            //Clic sur la column des 1er lundi des semaines (sélectionne la ligne complète)
            $("td.semaine").click(function(e){
                $(this).parent().children("td").slice(1).children("div").attr("class", "carre-vert");
            });
        };


        base.createSendTextbox = function(){
            $('<br><input id="newRegistrationDates" type="textbox"/>').appendTo(el);
        };


        base.init();
    };

    $.Registration.defaultOptions = {
    };

    $.fn.registration = function(options){
        return this.each(function(){
            (new $.Registration(this, options));
        });
    };

})(jQuery);