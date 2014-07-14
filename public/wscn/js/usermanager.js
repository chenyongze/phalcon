(function () {
    'use strict';

    //Debug shortcut
    function p(){
        if(typeof console === 'undefined') {
            return false;
        }
        console.info.apply(console, arguments);
    }
        
    /************************************
        Constants
    ************************************/

    var usermanager = {}
        , VERSION = '1.0.0'
        , status = {
            checked : false,
            login : false
        }
        , debug = true
        , user = null
        , loginFunc = []
        , notLoginFunc = []
        , defaultOptions = {
            debug : true,
            dataType : 'json',
            userUrl : '/me.json',
            cookiekey : 'PHPSESSID'
        }
        , options = {}
        , hasModule = (typeof module !== 'undefined' && module.exports);


    /************************************
        Constructors
    ************************************/
    function UserManager(inputOptions) {
        options = $.extend({}, defaultOptions, inputOptions);
        debug = options.debug;
        initEvent(this);
    }

    function initEvent(root) {
        var events = $.extend(defautEvents, options.events),
            key = 0;
        for(key in events) {
            root.on(key, events[key]);
        }
    }

    function cookie (name, value, options) {
        if (typeof value != 'undefined') { // name and value given, set cookie
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            // CAUTION: Needed to parenthesize options.path and options.domain
            // in the following expressions, otherwise they evaluate to undefined
            // in the packed version for some reason...
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        } else { // only name given, get cookie
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    };



    /************************************
        Top Level Functions
    ************************************/
    usermanager = function (options) {
        return new UserManager(options);
    };

    // version number
    usermanager.version = VERSION;


    /************************************
        EvaFinanceChart Prototype
    ************************************/
    usermanager.fn = UserManager.prototype = {
        isLogin : function() {
            return status.login;
        }

        , start : function() {
            var root = this;
            if(null === cookie(options.cookiekey)) {
                root.trigger('notlogin');
            } else {
                $.ajax({
                    url : options.userUrl,
                    dataType : options.dataType,
                    error : function(response) {
                        root.trigger('notlogin');
                    },
                    success : function(response){
                        if(response) {
                            user = response;
                            status.login = true;
                            root.trigger('login', [user]);
                        } else {
                            root.trigger('notlogin');
                        }
                    
                    }
                });
            }
        }

        , getUser : function() {
            return user;
        }

        , onNotLogin : function(func) {
            if (typeof func !== 'function') {
                return false;
            } 

            if(true === status.checked) {
                //already checked, run func immediately
                if(false === status.login) {
                    func(this, null);
                }
            } else {
                notLoginFunc.push(func);
            }
            return this;
        }

        , onLogin : function(func) {
            if (typeof func !== 'function') {
                return false;
            } 

            if(true === status.checked) {
                //already checked, run func immediately
                if(true === status.login) {
                    func(this, user);
                }
            } else {
                loginFunc.push(func);
            }
            return this;
        }
        
        , trigger : function(eventName, params) {
            $(document).trigger(eventName, params);
            return this;
        }

        , on : function(eventName, callback) {
            $(document).on(eventName, $.proxy(callback, this));
            return this;
        }

        , off : function(eventName) {
            $(document).off(eventName);
            return this;
        }
    }


    var defautEvents = {
        'login' : function(event, user)  {
            status.checked = true;
            status.login = true;
            $(document).off('click', '.action-require-login');
            $(document).ready(function(){
                $('.action-show-after-login').removeClass('action-show-after-login');
                $('.action-hide-after-login').hide();
            });
            var i = 0;
            for(i in loginFunc) {
                loginFunc[i](this, user);
            }
        },

        'notlogin' : function(event) {
            status.checked = true;
            status.login = false;

            //TODO:Remove session cookie
            var i = 0;
            for(i in notLoginFunc) {
                notLoginFunc[i](this);
            }
        }
    }


    // CommonJS module is defined
    if (hasModule) {
        module.exports = usermanager;
    }

    /*global ender:false */
    if (typeof ender === 'undefined') {
        // here, `this` means `window` in the browser, or `global` on the server
        // add `efc` as a global object via a string identifier,
        // for Closure Compiler 'advanced' mode
        this['UserManager'] = usermanager;
    }

    /*global define:false */
    if (typeof define === 'function' && define.amd) {
        define([], function () {
            return usermanager;
        });
    }
}).call(this);
