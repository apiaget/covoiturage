
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
            console.log(parameters);

            // Put your initialization code here
            var weeks = base.columnsAndRowsNumbers(parameters['ride']);
            console.log(weeks);

        };

        base.columnsAndRowsNumbers = function(ride){
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

            var firstWeekFirstDay = base.dayNumber(startDate.getDay()) === 0 ? startDate : new Date(startDate.getTime() - base.dayNumber(startDate.getDay())*86400000);
            var lastWeeklastDay = base.dayNumber(endDate.getDay()) === 0 ? endDate : new Date(endDate.getTime() + (6 - base.dayNumber(endDate.getDay()))*86400000);

            var nbDaysBetweenStartAndEndDates = Math.round((lastWeeklastDay.getTime() - firstWeekFirstDay.getTime())/(24*60*60*1000));
            var nbWeeks = Math.ceil(nbDaysBetweenStartAndEndDates/7);

            var nbDays = ride[5].reduce(function(pv, cv) { return pv + cv; }, 0);


            //console.log(firstWeekFirstDay);
            //console.log(lastWeeklastDay);

            //console.log(nbDaysBetweenStartAndEndDates);
            //console.log(nbWeeks);
            //console.log(nbDays);

            var text = '';
            text += '{"weeks" :[';
            for (var i = 0 ; i < nbWeeks; i++) {
                text +='[';
                text +='"'+firstWeekFirstDay.getFullYear()+'-'+(firstWeekFirstDay.getMonth()+1)+'-'+firstWeekFirstDay.getDate()+'"';
                var weekDay = firstWeekFirstDay;
                firstWeekFirstDay.setDate(firstWeekFirstDay.getDate()+6);
                text +=',"'+firstWeekFirstDay.getFullYear()+'-'+(firstWeekFirstDay.getMonth()+1)+'-'+firstWeekFirstDay.getDate()+'"';
                
                text+=',[';
                var dayCount = nbDays;
                for(var j = 0 ; j < 6; j++){
                    if(ride[5][j]===1){
                        text+='[';
                        text+='1,';
                        text+='"'+weekDay.getFullYear()+'-'+(weekDay.getMonth()+1)+'-'+weekDay.getDate()+'"';
                        text+=']';
                        if(dayCount-1>0) {
                            text+=',';
                            dayCount--;
                        }
                    }
                    weekDay.setDate(weekDay.getDate()+1);
                }
                text+=']';


                text+=']';
                if(i+1<nbWeeks){text+=',';}
            }
            text += ']}';
            console.log(text);
            console.log(JSON.parse(text));
            //return JSON.parse('{"weeks" :["2014-11-03",'+nbWeeks+'],"days":'+sum+'}');
            return JSON.parse('{"weeks" :[    ["2014-12-01","2014-12-07",   [   [0,"2014-12-01",3],[1,"2014-12-03",4]   ]   ]   ,   ["2014-12-08","2014-12-14",[[0,"2014-12-08",0],[1,"2014-12-10",2]]]   ]}');
            //semaines définie par la date du lundi et du vendredi et des jours actifs avec le nombre de personnes qui s'y trouvent
            //var text = '{';
            //text += 'weeks : [{}]';


        };

        //base.

        base.dayNumber = function(oldDayNumber){
            return (oldDayNumber + 6)%7;
        };

        base.init();
    };

    $.Registration.defaultOptions = {
        registred: "",
        register: "",
        unregister: ""
    };

    $.fn.registration = function(options){
        return this.each(function(){
            (new $.Registration(this, options));
        });
    };

})(jQuery);