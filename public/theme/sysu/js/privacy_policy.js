    $(document).ready(function() {
      cookiesPolicyBar();
    });
 

    function cookiesPolicyBar(){
        var privacypolicycookie = getCookie("privacypolicy_cookie");
        if (privacypolicycookie != "active"){
            $('#privacy-policy').show(); 
        }
        
        //Assign cookie on click
        $('#cookieAcceptBarConfirm').on('click',function(){            
            setCookie("privacypolicy_cookie","active",1);
            $('#privacy-policy').fadeOut();
        });
    }


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    // function checkCookie() {
    //     var user = getCookie("username");
    //     if (user != "") {
    //         alert("Welcome again " + user);
    //     } else {
    //         user = prompt("Please enter your name:", "");
    //         if (user != "" && user != null) {
    //             setCookie("username", user, 365);
    //         }
    //     }
    // }
