(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define("UserLogin", function (require, exports, module) {
            var $ = require("jquery"),
                UserManager = require("UserManager"),
                LoginUI = require("login-ui");
            root.UserLogin = factory(root, exports, $, UserManager, LoginUI);
            return root.UserLogin;
        });
    } else {
        root.UserLogin = factory(root, {}, root.jQuery, root.UserManager, root.LoginUI);
    }

}(this, function(root, UserLogin, $, UserManager, LoginUI) {
    "use strict";
    var instance = null,
        defaultOptions = {
        },
        user,
        oauthUser,
        oauthSuccess,
        oauthError;
    var userMessages = {
            "ERR_USER_PASSWORD_WRONG" : "密码不匹配，请重试",
            "ERR_USER_PASSWORD_WRONG_MAX_TIMES" : "密码输入错误次数太多，用户已经被锁定，请稍后重试",
            "ERR_USER_NOT_EXIST" : "用户不存在",
            "ERR_USER_NOT_ACTIVED" : "用户未激活<span class='inactive-handle'></span>",
            "ERR_USER_RESET_CODE_NOT_MATCH" : "验证码不匹配",
            "ERR_USER_RESET_CODE_EXPIRED" : "验证码已过期",
            "ERR_USER_USERNAME_ALREADY_TAKEN" : "用户名已存在",
            "ERR_USER_EMAIL_ALREADY_TAKEN" : "用户邮箱已存在",
            "ERR_USER_CREATE_FAILED" : "创建用户失败",
            "ERR_USER_BE_BANNED" : "用户已被屏蔽",
            "ERR_OAUTH_AUTHORIZATION_FAILED" : "连接服务器失败，请稍后重试",
            "SUCCESS_USER_ACTIVE_MAIL_SENT" : "激活邮件已发送",
            "SUCCESS_USER_REGISTERED_ACTIVE_MAIL_SENT" : "一封验证邮件已发到注册邮箱，请验证后登陆",
            "SUCCESS_USER_RESET_MAIL_SENT" : "一封包含重置密码信息的邮件已经发送到注册邮箱",
            "SUCCESS_USER_PASSWORD_RESET" : "新密码已设置，请重新登陆",
            "SUCCESS_OAUTH_USER_REGISTERED" : "注册成功",
            "SUCCESS_OAUTH_USER_CONNECTED" : "绑定第三方帐号成功",
            "SUCCESS_OAUTH_AUTO_CONNECT_EXIST_EMAIL" : "登录成功"
    };
    var usrManager = UserManager.getInstance();
    var loginUI = new LoginUI();

    //Debug shortcut
    function p(){
        if(typeof console === "undefined") {
            return false;
        }
        console.info.apply(console, arguments);
    }

    /************************************
      Constructors
     ************************************/
    UserLogin = function(options){
        this.options = {};
        this.initialize(options);
    };

    /************************************
      Constants
     ************************************/
    var defaultEvents = {
    
    }

    var defaultErrorHandle = function(error){
        var messages = error.responseJSON.errors;
        //loginUI.showMessage("连接服务器失败，请稍候重试", "ERR_UNKNOW", "error")
        var i = 0;
        if(!messages || messages.length < 1) {
            loginUI.showMessage("ERR_UNKNOW", "连接服务器失败，请稍候重试");
        }
        for(i in messages) {
            var message = messages[i];
            var messageCode = message.message;
            var errorMsg = userMessages[messageCode] || messageCode;
            loginUI.showMessage(messageCode, errorMsg);
        }
    };

    var register = function (url, data) {
        $.ajax({
            url : url,
            dataType : "json",
            data : data,
            type : "POST"
        }).then(function(response){
            console.log(response);
            loginUI.hideMessage();
        }).fail(defaultErrorHandle);
    };

    var resetPassword = function(url, data) {
        $.ajax({
            url : url,
            dataType : "json",
            data : data,
            type : "POST"
        }).then(function(response){
            console.log(response);
            loginUI.hideMessage();
        }).fail(defaultErrorHandle);
    }

    var loginByOAuth = function() {
    
    };

    var registerByOAuth = function(url, data) {
    
    };

    var loginByPassword = function (url, data) {
        /*
        var inactiveHandler = function(form){
            var username = form.find("input[name=identify]").val();
            form.find(".inactive-handle").html("，如未收到激活邮件，请<a href='/login/reactive?username=" + username + "'>点击重发</a>");
            form.find(".inactive-handle").on("click", "a", function(){
                var inactiveLink = $(this);
                $.ajax({
                    url : inactiveLink.attr("href"),
                    type : "GET",
                    success : function(response){
                        inactiveLink.closest(".alert")
                        .removeClass("alert-danger")
                        .addClass("alert-success")
                        .html("激活邮件已发送，请登录邮箱查收");
                    },
                    error : function(error) {
                        inactiveLink.closest(".alert")
                        .html("激活邮件发送失败，请<a href='/login/reactive?username=" + username + "'>重试</a>");
                    }
                });
                return false;
            });
        };
        */
        var loginDeferred = $.ajax({
            url : url,
            dataType : "json",
            data : data,
            type : "POST",
        }).then(function(response) {
            loginUI.hideMessage();
            loginUI.hideModal();
            usrManager.setUser(response);
            usrManager.trigger('login');
        }).fail(defaultErrorHandle);
        return loginDeferred;
    };

    var notLoginFunctions = {
        initForm : function () {
            $("#user-modal-register").on("submit", "form", function() {
                var form = $(this);
                register(form.attr('action'), form.serialize());
                return false;    
            });
            $("#user-modal-reset").on("submit", "form", function() {
                var form = $(this);
                resetPassword(form.attr('action'), form.serialize());
                return false;    
            });
            $("#user-modal-connect-register").on("submit", "form", function() {
                var form = $(this);
                registerByOAuth(form.attr('action'), form.serialize());
                return false;    
            });
            $("#user-modal-login").on("submit", "form", function(){
                var form = $(this);
                loginByPassword(form.attr('action'), form.serialize());
                return false;
            });
        },
        initModal : function () {
            $(document).on("click", ".user-not-login [data-action=login]", function(e){
                loginUI.showModal();
                return false;
            });      
        }
    };

    var loginFunctions = {
        replaceViews : function() {
            var user = usrManager.getUser();
            $("#leftbar .avatar").attr('src', user.avatar);
            $("#leftbar [data-action=login]").attr('href', '/mine/dashboard');
            $("#leftbar [data-action=login]:not(:has(img))").html("个人中心");
            $('.user-control').addClass(('login'));
            $(".user-control img").attr('src', user.avatar);
        }
    };

    var initSocialBtn = function(){
        $(document).on("click", ".login-social-btn", function(){
            window.open($(this).attr("href"), "_blank");
            return false;
        });
    };


    /************************************
      Public Methods
     ************************************/
    UserLogin.prototype = {
        getOptions : function(){
            return this.options;
        }

        , setOAuthResponse : function(token, user, success, error) {
            if(token) {
                loginUI.showUser(token.remoteUserName, token.remoteImageUrl, token.adapter);
                loginUI.showModal('register-connect');
            }
            console.log(token, user, success, error);
        }

        , loginByPassword : loginByPassword

        , resetPassword : resetPassword

        , register : register

        , initialize: function(opts){
            this.options = $.extend({}, defaultOptions, opts);
            initSocialBtn();
            var i;
            for(i in notLoginFunctions) {
                usrManager.onNotLogin(notLoginFunctions[i]);
            }
            for(i in loginFunctions) {
                usrManager.onLogin(loginFunctions[i]);
            }
        }
    };

    UserLogin.getInstance  = function(options){
        if(instance === null){
            instance = new UserLogin(options);
        }
        return instance;
    };
    return UserLogin;
}));
