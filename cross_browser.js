$(document).ready(function() {

    // http://stackoverflow.com/questions/2400935/browser-detection-in-javascript
    var ua = navigator.userAgent.toLowerCase();
    var check = function(r) {
        return r.test(ua);
    };

    var isChrome = check(/chrome/);
    var isWebKit = check(/webkit/);
    var isOpera = check(/opera/);
    var isOpera = check(/firefox/i);

    var isiPad = check(/iPad/i);
    var isiOS = check(/iPad|iphone|ipod/i);
    var isiphone = check(/iphone/i);
    var isChromeIos = check(/CriOS/i);

    var isSafari = !isChrome && check(/safari/);
    var isSafari2 = isSafari && check(/applewebkit\/4/); 
    var isSafari3 = isSafari && check(/version\/3/);
    var isSafari4 = isSafari && check(/version\/4/);

    var isIE = !isOpera && check(/msie/);
    var isIE7 = isIE && check(/msie 7/);
    var isIE8 = isIE && check(/msie 8/);
    var isIE6 = isIE && !isIE7 && !isIE8;


    /**
     * if add critical classes to html to handle special UI cases
     */
    if(isSafari || isSafari2  || isSafari3 || isSafari4) {
        $('html').addClass('browser-safari');
    } 

    if(isiphone) {
        $('html').addClass('device-iphone');  
    }

    if(isChrome || chromeIos) {
        $('html').addClass('browser-chrome');
    } 


});