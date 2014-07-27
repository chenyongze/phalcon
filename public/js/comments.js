(function(window, $, easyXDM){
    "use strict";
    var WS_COMMENT = {
        /**
         * Shorcut post method.
         *
         * @param string url The url of the page to post.
         * @param object data The data to be posted.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        post: function(url, data, success, error, complete) {
            // Wrap the error callback to match return data between jQuery and easyXDM
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.responseText, response.status);
                }
            };
            var wrappedCompleteCallback = function(response){
                if('undefined' !== typeof complete) {
                    complete(response.responseText, response.status);
                }
            };
            $.post(url, data, success).error(wrappedErrorCallback).complete(wrappedCompleteCallback);
        },

        /**
         * Shorcut get method.
         *
         * @param string url The url of the page to get.
         * @param object data The query data.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        get: function(url, data, success, error) {
            // Wrap the error callback to match return data between jQuery and easyXDM
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.responseText, response.status);
                }
            };
            $.get(url, data, success).error(wrappedErrorCallback);
        },

        /**
         * Gets the comments of a thread and places them in the thread holder.
         *
         * @param string identifier Unique identifier url for the thread comments.
         * @param string url Optional url for the thread. Defaults to current location.
         */
        getThreadComments: function(identifier,params,permalink) {
            var event = jQuery.Event('ws_comment_before_load_thread');

            event.identifier = identifier;

            event.params = params || {};
            event.params.permalink = encodeURIComponent(permalink || window.location.href);

            WS_COMMENT.thread_container.trigger(event);
            WS_COMMENT.get(
                WS_COMMENT.full_url,
                event.params,
                // success
                function(data) {
                    WS_COMMENT.thread_container.html(data);
                    WS_COMMENT.thread_container.attr('data-thread', event.identifier);
                    WS_COMMENT.thread_container.trigger('ws_comment_load_thread', event.identifier);
                }
            );
        },

        /**
         * Initialize the event listeners.
         */
        initializeListeners: function() {
            WS_COMMENT.thread_container.on('submit',
                'form.ws_comment_comment_new_form',
                function(e) {
                    var that = $(this);
                    var serializedData = WS_COMMENT.serializeObject(this);

                    e.preventDefault();

                    var event = $.Event('ws_comment_submitting_form');
                    that.trigger(event);

                    if (event.isDefaultPrevented()) {
                        return;
                    }

                    WS_COMMENT.post(
                        this.action,
                        serializedData,
                        // success
                        function(data, statusCode) {
                            WS_COMMENT.appendComment(data, that);
                            that.trigger('ws_comment_new_comment', data);
                        },
                        // error
                        function(data, statusCode) {
                            var parent = that.parent();
                            parent.after(data);
                            parent.remove();
                        },
                        // complete
                        function(data, statusCode) {
                            that.trigger('ws_comment_submitted_form', statusCode);
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws-comment-reply',
                function(e) {
                    var form_data = $(this).data();
                    var that = $(this);

                    if(that.hasClass('ds_comment_replying')) {
                        var current = $('#ws-comment-'+form_data.id+' .ws-comment-body .ws-reply-box');
                        if(current.is(':hidden')){
                            $('.ws-comment-body .ws-reply-box').hide();
                            current.show();
                        }else{
                            //动画
                            current.hide()
                        }
                        return that;
                    }else{
                        $('.ws-comment-body .ws-reply-box').hide()
                    }

                    WS_COMMENT.get(
                        form_data.url,
                        {parentId: form_data.id},
                        function(data) {
                            that.addClass('ds_comment_replying');
                            $('#ws-comment-'+form_data.id+' .ws-comment-body').append(data);
//                            that.trigger('ws_comment_show_form', data);
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_comment_reply_cancel',
                function(e) {
                    var form_holder = $(this).closest('.ws_comment_comment_form_holder');

                    var event = $.Event('ws_comment_cancel_form');
                    form_holder.trigger(event);

                    if (event.isDefaultPrevented()) {
                        return;
                    }

                    form_holder.closest('.ws_comment_comment_reply').removeClass('ws_comment_replying');
                    form_holder.remove();
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_comment_edit_show_form',
                function(e) {
                    var form_data = $(this).data();
                    var that = $(this);

                    WS_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            var commentBody = $(form_data.container);

                            // save the old comment for the cancel function
                            commentBody.data('original', commentBody.html());

                            // show the edit form
                            commentBody.html(data);

                            that.trigger('ws_comment_show_edit_form', data);
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('submit',
                'form.ws_comment_comment_edit_form',
                function(e) {
                    var that = $(this);

                    WS_COMMENT.post(
                        this.action,
                        WS_COMMENT.serializeObject(this),
                        // success
                        function(data) {
                            WS_COMMENT.editComment(data);
                            that.trigger('ws_comment_edit_comment', data);
                        },

                        // error
                        function(data, statusCode) {
                            var parent = that.parent();
                            parent.after(data);
                            parent.remove();
                        }
                    );

                    e.preventDefault();
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_comment_edit_cancel',
                function(e) {
                    WS_COMMENT.cancelEditComment($(this).parents('.ws_comment_comment_body'));
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_comment_vote',
                function(e) {
                    var that = $(this);
                    var form_data = that.data();

                    // Get the form
                    WS_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $($.trim(data)).children('form')[0];
                            var form_data = $(form).data();

                            WS_COMMENT.post(
                                form.action,
                                WS_COMMENT.serializeObject(form),
                                function(data) {
                                    $('#' + form_data.scoreHolder).html(data);
                                    that.trigger('ws_comment_vote_comment', data, form);
                                }
                            );
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_comment_remove',
                function(e) {
                    var form_data = $(this).data();

                    var event = $.Event('ws_comment_removing_comment');
                    $(this).trigger(event);

                    if (event.isDefaultPrevented()) {
                        return
                    }

                    // Get the form
                    WS_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $($.trim(data)).children('form')[0];

                            WS_COMMENT.post(
                                form.action,
                                WS_COMMENT.serializeObject(form),
                                function(data) {
                                    var commentHtml = $($.trim(data));

                                    var originalComment = $('#' + commentHtml.attr('id'));

                                    originalComment.replaceWith(commentHtml);
                                }
                            );
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws_comment_thread_commentable_action',
                function(e) {
                    var form_data = $(this).data();

                    // Get the form
                    WS_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $($.trim(data)).children('form')[0];

                            WS_COMMENT.post(
                                form.action,
                                WS_COMMENT.serializeObject(form),
                                function(data) {
                                    var form = $($.trim(data)).children('form')[0];
                                    var threadId = $(form).data().wsCommentThreadId;

                                    // reload the intire thread
                                    WS_COMMENT.getThreadComments(threadId);
                                }
                            );
                        }
                    );
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws-paginator a',
                function(e) {
                    var data = $(this).data();

                    var identifier = window.ws_comment_thread_id;

                    var params = {
                        page: data.page||1,
                        sorter: $('.ws-sort .ws-current').data().sort
                    };

                    WS_COMMENT.getThreadComments(identifier,params);
                }
            );

            WS_COMMENT.thread_container.on('click',
                '.ws-sort a',
                function(e) {
                    var data = $(this).data();

                    var identifier = window.ws_comment_thread_id;

                    var params = {
                        sorter: data.sort
                    };

                    WS_COMMENT.getThreadComments(identifier,params);
                }
            );
        },

        appendComment: function(commentHtml, form) {
            var form_data = form.data();

            var parent = form_data.parent;
            if(form_data.parent) {
//                form.next().prepend(commentHtml);
                $('#ws-comment-'+form_data.parent).after(commentHtml);
//                var form_parent = form.closest('.ws_comment_comment_form_holder');
//
//                // reply button holder
//                var reply_button_holder = form.closest('.ws_comment_comment_reply');
//
//                var comment_element = form.closest('.ws_comment_comment_show')
//                    .children('.ws_comment_comment_replies');
//
//                reply_button_holder.removeClass('ws_comment_replying');
//
//                comment_element.prepend(commentHtml);
//                comment_element.trigger('ws_comment_add_comment', commentHtml);
//
//                // Remove the form
//                form_parent.remove();
            } else {
                // Insert the comment
//                form.after(commentHtml);
                var comment_element = $('#ws_comment_list');
                comment_element.prepend(commentHtml);

                //todo
                $('.ws-title').show();
            }
            form.trigger('ws_comment_add_comment', commentHtml);

            // "reset" the form
            form = $(form[0]);
            form[0].reset();
            form.children('.ws_comment_form_errors').remove();
        },

        editComment: function(commentHtml) {
            var commentHtml = $($.trim(commentHtml));
            var originalCommentBody = $('#' + commentHtml.attr('id')).children('.ws_comment_comment_body');

            originalCommentBody.html(commentHtml.children('.ws_comment_comment_body').html());
        },

        cancelEditComment: function(commentBody) {
            commentBody.html(commentBody.data('original'));
        },

        /**
         * easyXdm doesn't seem to pick up 'normal' serialized forms yet in the
         * data property, so use this for now.
         * http://stackoverflow.com/questions/1184624/serialize-form-to-json-with-jquery#1186309
         */
        serializeObject: function(obj)
        {
            var o = {};
            var a = $(obj).serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        },

        loadCommentCounts: function()
        {
            var threadIds = [];
            var commentCountElements = $('span.ws-comment-count');

            commentCountElements.each(function(i, elem){
                var threadId = $(elem).data('wsCommentThreadId');
                if(threadId) {
                    threadIds.push(threadId);
                }
            });

            WS_COMMENT.get(
                WS_COMMENT.base_url + '.json',
                {ids: threadIds},
                function(data) {
                    // easyXdm doesn't always serialize
                    if (typeof data != "object") {
                        data = jQuery.parseJSON(data);
                    }

                    var threadData = {};

                    for (var i in data.threads) {
                        threadData[data.threads[i].id] = data.threads[i];
                    }

                    $.each(commentCountElements, function(){
                        var threadId = $(this).data('wsCommentThreadId');
                        if(threadId) {
                            WS_COMMENT.setCommentCount(this, threadData[threadId]);
                        }
                    });
                }
            );

        },

        setCommentCount: function(elem, threadObject) {
            if (threadObject == undefined) {
                elem.innerHTML = '0';

                return;
            }

            elem.innerHTML = threadObject.num_comments;
        }
    };

    // Check if a thread container was configured. If not, use default.
    WS_COMMENT.thread_container = window.ws_comment_thread_container || $('#ws_comment_thread');

    // AJAX via easyXDM if this is configured
    if(typeof window.ws_comment_remote_cors_url != "undefined") {
        /**
         * easyXDM instance to use
         */
        WS_COMMENT.easyXDM = easyXDM.noConflict('WS_COMMENT');

        /**
         * Shorcut request method.
         *
         * @param string method The request method to use.
         * @param string url The url of the page to request.
         * @param object data The data parameters.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        WS_COMMENT.request = function(method, url, data, success, error) {
            // wrap the callbacks to match the callback parameters of jQuery
            var wrappedSuccessCallback = function(response){
                if('undefined' !== typeof success) {
                    success(response.data, response.status);
                }
            };
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.data.data, response.data.status);
                }
            };

            // todo: is there a better way to do this?
            WS_COMMENT.xhr.request({
                    url: url,
                    method: method,
                    data: data
            }, wrappedSuccessCallback, wrappedErrorCallback);
        };

        WS_COMMENT.post = function(url, data, success, error) {
            this.request('POST', url, data, success, error);
        };

        WS_COMMENT.get= function(url, data, success, error) {
            // make data serialization equals to that of jquery
            var params = jQuery.param(data);
            url += '' != params ? '?' + params : '';

            this.request('GET', url, undefined, success, error);
        };

        /* Initialize xhr object to do cross-domain requests. */
        WS_COMMENT.xhr = new WS_COMMENT.easyXDM.Rpc({
                remote: window.ws_comment_remote_cors_url
        }, {
            remote: {
                request: {} // request is exposed by /cors/
            }
        });
    }

    // set the appropriate base url
    WS_COMMENT.base_url = window.ws_comment_thread_api_base_url;



    // Load the comment if there is a thread id defined.
    if(typeof window.ws_comment_thread_id != "undefined") {
        // get the thread comments and init listeners
        var identifier = window.ws_comment_thread_id;

        WS_COMMENT.full_url = WS_COMMENT.base_url  + '/' + encodeURIComponent(identifier) + '/comments';
        WS_COMMENT.getThreadComments(identifier);
    }

    if(typeof window.ws_comment_thread_comment_count_callback != "undefined") {
        WS_COMMENT.setCommentCount = window.ws_comment_thread_comment_count_callback;
    }

    if($('span.ws-comment-count').length > 0) {
        WS_COMMENT.loadCommentCounts();
    }

    WS_COMMENT.initializeListeners();

    window.ws = window.ws || {};
    window.ws.Comment = WS_COMMENT;

    /**
     * 用户评论
     */
//    function hiedUsernameModal() {
//        var $modal = $('#comment-username-modal');
//        $modal.find('[name=username]').val('');
//        $modal.unwrap();
//    }
//    $(document).on('click', '.user-comments form.ws_comment_comment_new_form > [type=submit]', function(e){
//
//        var $form = $(this).parent();
//        var formId = $form[0].id;
//        var comment = $form.find('textarea').val();
//        if (comment) {
//            var $modal = $('#comment-username-modal');
//            $modal.attr('data-form-id', formId);
//            $modal.wrap('<div class="modal-wrap"></div>');
//        }
//        e.preventDefault();
//    });
//    $(document).on('click', '#comment-username-modal [data-action=close]', function(e){
//        hiedUsernameModal();
//    });
//    $(document).on('click', '#comment-username-modal [type=submit]', function(e){
//        var $modal = $('#comment-username-modal');
//        var username = $modal.find('[name=username]').val();
//        if (username) {
//            var formId = $modal.attr('data-form-id');
//            var $form = $('#' + formId);
//            $form.find('[name=username]').val(username);
//            $form.trigger('submit');
//            hiedUsernameModal();
//        }
//        e.preventDefault();
//    });

})(window, window.jQuery, window.easyXDM);
