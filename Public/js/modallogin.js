/* #####################################################################
   #
   #   Project       : Modal Login with jQuery Effects
   #   Author        : Rodrigo Amarante (rodrigockamarante)
   #   Version       : 1.0
   #   Created       : 07/29/2015
   #   Last Change   : 08/04/2015
   #
   ##################################################################### */
   
$(function() {
    
    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegister = $('#register-form');
    var $divForms = $('#div-forms');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                //var $lg_username=$('#login_username').val();
                //var $lg_password=$('#login_password').val();
				var $lg_success = 1;				
				$.ajax({						
						type:"post",
						url:"doLogin",//"/myhs/Public/php/login.php",
						data: $('#login-form').serialize(),						
						success:function(res)
						{
							//alert(res.info);
							status = res.status;
							//alert('res:'+res);
							if (status == 0)
							{
								$lg_success = 0;
								//alert(res.msg);
								//window.location.replace("{:U('home/member/index')}");
							}
							else if (status == 2)
							{
								$lg_success = 2;
							}
							else if (status == 3) //error password
							{
								//alert('submit failed');
								$lg_success = 3;
							}
							else if (status == 1)
							{
								$lg_success = 1;
							}
							
							//alert('$lg_success:'+$lg_success);
			                if ($lg_success == 0) {
			                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "success", "glyphicon-ok", "Login OK");
			                    window.location.href = res.url;
			                } else if ($lg_success == 1) {
			                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "error", "glyphicon-remove", "Database error");
			                }
							 else if($lg_success == 2) {
			                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'),  "error", "glyphicon-remove", "Username doesn't exist");
			                }
							 else if($lg_success == 3) {
			                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'),  "error", "glyphicon-remove", "Error password");
			                }
			                return false;							

						}
				});
                break;
            case "lost-form":
                var $ls_email=$('#lost_email').val();
                if ($ls_email == "ERROR") {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "error", "glyphicon-remove", "Send error");
                } else {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "success", "glyphicon-ok", "Send OK");
                }
                return false;
                break;
            case "register-form":
                //var $rg_username=$('#register_username').val();
                //var $rg_email=$('#register_email').val();
                //var $rg_password=$('#register_password').val();
				var $rg_success = 1;
				$.ajax({
						url:"{:U('home/index/doJoin')}",
						type:"post",
						data:$('#register-form').serialize(),						
						success:function(res)
						{
							if (res.status == 1)
							{
								//alert(res.msg);
								//window.location.replace("{:U('home/member/index')}");
							}
							else
							{
								//alert('submit failed');
								$rg_success = 0;
							}
						}
				});
				
                if ($rg_success == 0) {
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "error", "glyphicon-remove", "Register error");
                } else {
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "success", "glyphicon-ok", "Register OK");
                }
                return false;
                break;
            default:
                return false;
        }
        return false;
    });
    
    $('#login_register_btn').click( function () { modalAnimate($formLogin, $formRegister) });
    $('#register_login_btn').click( function () { modalAnimate($formRegister, $formLogin); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#lost_register_btn').click( function () { modalAnimate($formLost, $formRegister); });
    $('#register_lost_btn').click( function () { modalAnimate($formRegister, $formLost); });
    
    function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
    
    function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }
    
    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText) {
        var $msgOld = $divTag.text();
        msgFade($textTag, $msgText);
        $divTag.addClass($divClass);
        $iconTag.removeClass("glyphicon-chevron-right");
        $iconTag.addClass($iconClass + " " + $divClass);
        setTimeout(function() {
            msgFade($textTag, $msgOld);
            $divTag.removeClass($divClass);
            $iconTag.addClass("glyphicon-chevron-right");
            $iconTag.removeClass($iconClass + " " + $divClass);
  		}, $msgShowTime);
    }
});