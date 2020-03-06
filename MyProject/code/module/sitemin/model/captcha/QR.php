<?php

class sitemin_model_captcha_QR extends sitemin_model_captcha {
    static $on = false;
    //validate returns
    function validate($q) {
            return 1;
    }
        function check($q){
                $sid = xpAS::preg_get($q[2], '/[^\:]+$/ims');
                session_write_close ();
                session_id($sid);
                session_start();
                if($_SESSION['QR'] != $q[2]) return false;
                return true;

        }
    //generate html block
    function html() {
        $h =  $_SESSION['QR'] = "527b58ae2568566ba85c613404502578:".session_id();
        session_write_close ();
        $s = '
        <script>
        var isQRloginCheck=0;
        function qrcheck(){
                $.ajax({
                    url: "sitemin/login?isQRlogin=check",
                    success: function(data){
                            if(data =="ok"){
                                    window.location = "sitemin/dashboard";
                                    return 0;
                            }
                            if(isQRloginCheck++ > 900){
                                    alert("Login failed, refresh window and try again");
                                    return ;
                            }
                            setTimeout(function(){ qrcheck() ;}, 100) ;
                    }
                });
        }
        (function(){

                $("#save").attr({"type":"button"}).click(function(){
                        var imglink="https://api.qrserver.com/v1/create-qr-code/?data=";
                        var hash = "'.$h.'";
                        var u = $("#email").val();
                        var p = $("#password").val();
                        var callback= "'._X_URL._url().'?cmd=QRcheck&data=";
                        var m=JSON.stringify([u,p,hash]);
                        m = btoa(m);
                        callback += m;
                        imglink += encodeURIComponent(callback);
                        //alert(imglink);
                        var p = $("#save").parent();
                        $(this).parent().html("<img src=\""+imglink+"\"  >");
                        setTimeout(function(){ qrcheck() ;}, 500)
                        return false;
                });
        })()
        </script>
        ';
        return $s;
    }
    function test() {
        die(__FILE__);
    }
}
