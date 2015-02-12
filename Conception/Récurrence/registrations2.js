
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
            console.log(parameters);
            base.recurrence(parameters);
            base.createCalendar(parameters);
        };

        //tableau en fonction de
        //startdate et enddate
        //seats
        //jours récurrents
        //enregistrements par jours

        /*base.createCalendar = function(parameters) {
            var now = new Date();
            //console.log(now);
            //console.log(new Date(parameters.startdate));
            var dateMondayFirstWeek = base.getFirstWeek(now, parameters);
            //header


        };*/

        base.recurrence = function(parameters){
            recurrence = [parameters.recurrence['monday']?1:0,
                parameters.recurrence['tuesday']?1:0,
                parameters.recurrence['wednesday']?1:0,
                parameters.recurrence['thursday']?1:0,
                parameters.recurrence['friday']?1:0,
                parameters.recurrence['saturday']?1:0,
                parameters.recurrence['sunday']?1:0,
            ];
        };

        base.createCalendar = function(parameters){
            var table = $('<table id="registrationtable"></table>');

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


            //nombre de semaines
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var diffDays = Math.round(Math.abs((startweek.getTime() - endweek.getTime())/(oneDay)));
            console.log(startweek);
            console.log(endweek);
            console.log(diffDays/7);
            $(entete).appendTo(table);
            table.appendTo(el);
            /*
            var startDate = ride[0].split("-");
            startDate = new Date(startDate[0], (startDate[1] - 1), startDate[2]);
            startDate = startDate.getTime() < new Date().getTime() ? new Date() : startDate;

            var startDayDayNb = base.dayNumber(startDate.getDay());
            var recurringDaysAfter = 0;
            do{
            if(ride[5][startDayDayNb]===1){
            recurringDaysAfter=1;
            }
            startDayDayNb++;
            }while(recurringDaysAfter===0&&startDayDayNb<7);

            if(recurringDaysAfter===0){
            startDate = new Date(startDate.getTime() + 7 * 86400000);
            }
            var endDate = ride[1].split("-");
            endDate = new Date(endDate[0], (endDate[1] - 1), endDate[2]);

            //startDate : Premier jour où un ride peut être proposé. Si le ride est récurrent depuis mai et qu'on est en décembre, indiquera une date en décembre.
            //endDate : Dernier jour où un ride peut être proposé.

            //firstWeekFirstDay : Lundi de la première semaine où des rides sont affichés.
            //lastWeeklastDay : Dimanche de la dernière semaine où des rides sont affichés.

            //nbWeeks : Nombre de semaines où des rides seront affichés.

            //dayCount : Compte le nombre de jours ayant été affichés par semaine. Si on en est au premier, ne pas insérer de virgule avant.

            var firstWeekFirstDay = base.dayNumber(startDate.getDay()) === 0 ? startDate : new Date(startDate.getTime() - base.dayNumber(startDate.getDay())*86400000);
            var lastWeeklastDay = base.dayNumber(endDate.getDay()) === 0 ? endDate : new Date(endDate.getTime() + (6 - base.dayNumber(endDate.getDay()))*86400000);

            var nbDaysBetweenStartAndEndDates = Math.round((lastWeeklastDay.getTime() - firstWeekFirstDay.getTime())/(24*60*60*1000));
            var nbWeeks = Math.ceil(nbDaysBetweenStartAndEndDates/7);
            */
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

        base.init();
    };

    $.Registration.defaultOptions = {
        registred: "rgb(45, 229, 70)"
    };

    $.fn.registration = function(options){
        return this.each(function(){
            (new $.Registration(this, options));
        });
    };

})(jQuery);