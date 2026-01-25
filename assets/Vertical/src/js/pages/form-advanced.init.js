
/**
* Theme:  Velonic - Responsive Bootstrap 4 Admin & Dashboard
* Author: Coderthemes
* File:   form advanced
*/
!function($) {
    "use strict";

    var Components = function() {};


    //switch
    Components.prototype.initSwitchery = function() {
        $('[data-plugin="switchery"]').each(function (idx, obj) {
            new Switchery($(this)[0], $(this).data());
        });
    },

    //Select2
    Components.prototype.initSelect2 = function() {
        // Select2
        $('[data-toggle="select2"]').select2();
    },

    // Inputmask
    Components.prototype.initInputmask = function() {
        $('[data-toggle="input-mask"]').each(function (idx, obj) {
            var maskFormat = $(obj).data("maskFormat");
            var reverse = $(obj).data("reverse");
            if (reverse != null)
                $(obj).mask(maskFormat, {'reverse': reverse});
            else
                $(obj).mask(maskFormat);
        });
    },

    // touchspin
    Components.prototype.initTouchspin = function() {
        var defaultOptions = {
        };

        // touchspin
        $('[data-toggle="touchspin"]').each(function (idx, obj) {
            var objOptions = $.extend({}, defaultOptions, $(obj).data());
            $(obj).TouchSpin(objOptions);
        });

        $("input[name='demo3_21']").TouchSpin({
            initval: 40,
        });
        $("input[name='demo3_22']").TouchSpin({
            initval: 40,
        });
        
    },

    //Time Picker
    Components.prototype.initTimepicker = function() {
        // Time Picker
        $('#timepicker').timepicker({
            defaultTIme: false,
            icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
            }
        });
        $('#timepicker2').timepicker({
            showMeridian: false,
            icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
            }
        });
        $('#timepicker3').timepicker({
            minuteStep: 15,
            icons: {
                up: 'mdi mdi-chevron-up',
                down: 'mdi mdi-chevron-down'
            }
        });
    },

    //colorpicker
    Components.prototype.initColorpicker= function() {
        // colorpicker
        $('#default-colorpicker').colorpicker({
            format: 'hex'
        });
        $('#rgba-colorpicker').colorpicker();
        $('#component-colorpicker').colorpicker({
            format: null
        });
    },

    


    //initilizing
    Components.prototype.init = function() {
        var $this = this;
        this.initSwitchery(),
        this.initSelect2(),
        this.initInputmask(),
        this.initTouchspin(),
        this.initTimepicker(),
        this.initColorpicker()
    },

    $.Components = new Components, $.Components.Constructor = Components

}(window.jQuery),
    //initializing main application module
function($) {
    "use strict";
    $.Components.init();
}(window.jQuery);

