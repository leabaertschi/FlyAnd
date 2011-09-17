/*jslint white: false, onevar: true, undef: true, nomen: true, eqeqeq: true, plusplus: true, bitwise: true, regexp: false, strict: true, newcap: true, immed: true */
/*global $, jQuery, window, alert */

var flyAnd = {};
(function(flyAnd){
    var init, checkVoted, setVoted, showResults, getCookie, ec;

    init = function() {
        var radios;
        radios = $('input[type="radio"][name="film"]');
        // scroll to bottom after selecting a film
        radios.click(function(){
            $('html,body')
                .animate({scrollTop: $(document).height()}, 1000);
        });
        // send form if film selected
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
        ec = new evercookie();
        // add item to localstorage if just voted
        setVoted();
        // check if this user already voted
        checkVoted();
    };

    setVoted = function() {
        if ('Danke für deine Stimme' == $('.success').text()) {
            ec.set('fafv', "1");
        }
    };

    checkVoted = function() {
        ec.get('fafv', function(value){
            if (value === "1") {
                showResults();
            } else {
                showRadios();
            }
        });
    };

    showRadios = function() {
        $('.voteRadio').show();
        $('.voteResult').remove();
    };

    showResults = function() {
        $('.voteRadio').remove();
        $('.voteResult').show();
        $('.captcha').remove();
        $('.sendVote').remove();
    };

    /*
     * Source http://www.w3schools.com/js/js_cookies.asp
     */
    getCookie = function (c_name) {
        var i,x,y,ARRcookies=document.cookie.split(";");
        for (i=0;i<ARRcookies.length;i++)
        {
            x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
            y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
            x=x.replace(/^\s+|\s+$/g,"");
            if (x==c_name)
            {
                return unescape(y);
            }
        }
    };

    init();
}(flyAnd));
