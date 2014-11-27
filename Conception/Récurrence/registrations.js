
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


        };

        // Sample Function, Uncomment to use
        // base.functionName = function(paramaters){
        //
        // };

        // Run initializer
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