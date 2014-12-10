
	//input :
    //'driver':[1]
	//'registration':['2014-12-01','2014-12-15', '08:00', '08:45',4,[0,1,0,1,0,0,0]]
    //'filling':[[['Axel PIAGET','Elliott CHIARADIA','Joël BUCHS'],['Axel PIAGET','Elliott CHIARADIA','Joël BUCHS'],['Axel PIAGET','Elliott CHIARADIA','Joël BUCHS']],[['Axel PIAGET'],['Axel PIAGET'],['Axel PIAGET']]]

(function($){
    $.Registration = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("Registration", base);

        base.init = function(){
            base.options = $.extend({},$.Registration.defaultOptions, options);

            var parameters = $.parseJSON($(el).html());
            $(el).html('');

            // Put your initialization code here
            var weeks = base.columnsAndRowsNumbers(parameters['ride'],parameters['filling']);
            base.createCalendar(weeks,parameters['ride'],parameters['filling'],parameters['registrations']);
            base.createSendTextbox();
            base.eventsSetter();
            //console.log(weeks);

        };

        base.columnsAndRowsNumbers = function(ride,filling){
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

            var text = '';
            text += '{"weeks" :[';
            for (var i = 0 ; i < nbWeeks; i++) {
                text +='[';
                text +='"'+firstWeekFirstDay.getFullYear()+'-'+("0" + (firstWeekFirstDay.getMonth()+1)).slice(-2)+'-'+("0" + firstWeekFirstDay.getDate()).slice(-2)+'"';
                var weekDay = new Date(firstWeekFirstDay.getTime());
                firstWeekFirstDay.setDate(firstWeekFirstDay.getDate()+6);
                text +=',"'+firstWeekFirstDay.getFullYear()+'-'+("0" + (firstWeekFirstDay.getMonth()+1)).slice(-2)+'-'+("0" + firstWeekFirstDay.getDate()).slice(-2)+'"';
                
                text+=',[';
                var dayCount = 0;
                for(var j = 0 ; j < 6; j++){
                    if(ride[5][j]===1 && weekDay>=new Date(new Date().toJSON().slice(0,10)) && weekDay<=endDate){
                        if(dayCount>0){
                            text+=',';
                        }

                        text+='[';
                        text+='1,'; //----------------------------------
                        text+='"'+weekDay.getFullYear()+'-'+(weekDay.getMonth()+1)+'-'+("0" + weekDay.getDate()).slice(-2)+'"';
                        //text+=','+filling[i][dayCount].length;
                        text+=',4';
                        text+=']';

                        dayCount++; 
                    }
                    weekDay.setDate(weekDay.getDate()+1);
                }
                text+=']';

                text+=']';
                if(i+1<nbWeeks){text+=',';}
                firstWeekFirstDay.setDate(firstWeekFirstDay.getDate()+1);
            }
            text += ']}';
            return(JSON.parse(text));
        };





        base.createCalendar = function(weeks, ride, filling, registrations){
            var table = $('<table id="registrationtable"></table>');
            //ligne du haut
            var daysRow = $('<tr></tr>');
            var tds = '<td class="dates"></td><td class="arrow"></td>';
            for(var i = 0 ; i < ride[5].length ; i++){
                if(ride[5][i]===1){
                    tds+='<td>'
                    switch(i){
                        case 0:
                            tds+='Lun';
                            break;
                        case 1:
                            tds+='Mar';
                            break;
                        case 2:
                            tds+='Mer';
                            break;
                        case 3:
                            tds+='Jeu';
                            break;
                        case 4:
                            tds+='Ven';
                            break;
                        case 5:
                            tds+='Sam';
                            break;
                        case 6:
                            tds+='Dim';
                            break;
                    }
                    tds+='<br/>▼</td>';
                }
            }
            daysRow.append($(tds)).appendTo(table);
            
            var personNumber = 0;

            for(var i = 0 ; i < weeks.weeks.length; i++){ //On boucle par semaine
                var weekRow = $('<tr></tr>');
                var tdWeekDays = '<td class="dates">'+weeks.weeks[i][0].split("-")[2]+'-'+weeks.weeks[i][0].split("-")[1]+'-'+weeks.weeks[i][0].split("-")[0].substring(2)+
                '<br/>'+weeks.weeks[i][1].split("-")[2]+'-'+weeks.weeks[i][1].split("-")[1]+'-'+weeks.weeks[i][1].split("-")[0].substring(2)+'</td>';

                var tdDays = '';
                
                weekRow.append($(tdWeekDays)).append($('<td class="arrow">►</td>'));

                for(var j = new Date(weeks.weeks[i][0]) ; j <= new Date(weeks.weeks[i][1]) ; j.setDate(j.getDate()+1) ){ //on boucle à travers les 7 jours de la semaine
                    var text = '';
                    if(ride[5][base.dayNumber(j.getDay())]===1){ //si le jour est bel est bien récurrent
                        text = '<td class="colorable"';
                        for(var l = 0 ; l < registrations.length ; l++){ //indique si l'utilisateur en cours est inscrit ou pas
                            if(j.getTime() == new Date(registrations[l]).getTime()){
                                text+=' style="background-color:'+base.options['registred']+';"';
                            }
                        }
                        text+=">";

                        for(var k = 0 ; k < filling.length ; k++){ //indique le remplissage de la voiture
                            if(new Date(filling[k][0]).getTime()==j.getTime() ){
                                text+='<span>'+filling[k][1].length+'/'+ride[6]+'</span>';
                            } 
                        }
                        text+="<p style='display:none;'>"+j.getFullYear()+'-'+("0" + (j.getMonth()+1)).slice(-2)+'-'+("0" + j.getDate()).slice(-2)+"</p></td>";
                    }
                
                    weekRow.append($(text));
                
                }
                personNumber=0;

                weekRow.appendTo(table);
            }
            
            $('<tr></tr>').appendTo(table);
            table.appendTo(el);
        }







        base.eventsSetter = function(){
            //clic sur un point de la grille
            //clic sur une date ou le triangle à gauche
            //clic sur un jour ou le triangle du haut            

            //console.log($(el).children('table'));
            
            $("td.colorable").click(function(e){     //function_td
                //console.log($(this).parent().children().index($(this)) + " ; " + $(this).parent().parent().children().index($(this).parent()));
                //console.log($(this).css('background-color')=='rgba(0, 0, 0, 0)'||$(this).css('background-color')=='rgb(255, 255, 255)');
                base.basculeCouleur($(this).parent().children().index($(this)),$(this).parent().parent().children().index($(this).parent()));
                //$(this).css("background-color",base.options['registred']);
                e.stopPropagation();
            });
        };

        base.basculeCouleur = function(line, row){
            //fait basculer un point de la grille en fonction de la ligne et de la colonne
            //calcule toutes les dates qui sont en vert
                var td = $($('#registrationtable').find('tr')[row]).find('td')[line];
                if($(td).css('background-color')=='rgba(0, 0, 0, 0)'||$(td).css('background-color')=='rgb(255, 255, 255)'){
                    if($(td).find('span').html()!=undefined){
                        
                        $(td).css("background-color",base.options['registred']);
                    }
                }else{
                    $(td).css("background-color",'#fff');
                }

                $('#newRegistrationDates').val($("#registrationtable").find("td[style='background-color: rgb(45, 229, 70);']").find('p').map(function() {
                    return $(this).text();
                }).get().join(' '));

        };








        base.createSendTextbox = function(){
            //$('<tr></tr>').appendTo(table);
            $('<br><input id="newRegistrationDates" type="textbox"/>').appendTo(el);
        };









        base.dayNumber = function(oldDayNumber){
            return (oldDayNumber + 6)%7;
        };

        base.init();
    };

    $.Registration.defaultOptions = {
        registred: "rgb(45, 229, 70)",
        register: "",
        unregister: ""
    };

    $.fn.registration = function(options){
        return this.each(function(){
            (new $.Registration(this, options));
        });
    };

})(jQuery);