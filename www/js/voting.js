/*jslint white: false, onevar: true, undef: true, nomen: true, eqeqeq: true, plusplus: true, bitwise: true, regexp: false, strict: true, newcap: true, immed: true */
/*global $, jQuery, window, alert */

var flyAnd = {};
(function(flyAnd){
    var init;

    init = function() {
        var radios;
        radios = $('input[type="radio"][name="film"]');
        // scroll to bottom after selecting a film
        radios.click(function(){
            $('html,body')
                .animate({scrollTop: $(document).height()}, 1000);
        });

        $('#voteBtn').click(function(e){
            e.preventDefault();
            var oneSelected = false;
            radios.each(function(){
                if ($(this).attr('checked') == 'checked') {
                    oneSelected = true;
                }
            });
            if (!oneSelected) {
                $('#selectError').show();
            } else {
                $('#selectError').hide();
                $('#votingForm').submit();
            }
        });
    };

    init();
}(flyAnd));
