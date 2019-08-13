var Login = function() {

    var handleLogin = function() {

        jQuery.validator.addMethod( "ismobile" ,  function (value, element)  {
            var length = value.length;
            var mobile = /^1[3|4|5|7|8|][0-9]{9}$/;
            return   this .optional(element)  ||  (length  ==   11   &&  mobile.test(value));
        } ,  "请正确填写您的手机号码" );

        // login
        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            rules: {
                mobile: {
                    required : true,
                    number: true,
                    ismobile: true,
                    minlength : 11,
                    maxlength : 11
                },
                password: {
                    required : true,
                    minlength : 8,
                    maxlength : 20
                },
                remember: {
                    required: false
                }
            },

            messages: {
                mobile: {
                    required : '请输入正确的手机号码',
                    number : '手机号码不能有字母字符',
                    minlength :  '手机号不能少于11位',
                    maxlength :  '手机号不能多于11位'
                },
                password: {
                    required : '请输入密码',  
                    minlength :  '密码长度不能少于8位',
                    maxlength :  '密码长度不能超过20位'
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   
                //$('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function(form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('.login-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var handleForgetPassword = function() {
        $('.forget-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "",
            rules: {
                username: {
                    required : true,
                    minlength : 2,
                    maxlength : 80
                },
                mobile: {
                    required : true,
                    number: true,
                    ismobile: true,
                    minlength : 11,
                    maxlength : 11
                }
            },

            messages: {
                username: {
                    required :  '请输入正确的账号',
                    minlength :  '账号最少长度为2位',
                    maxlength :  '账号最少长度为80位'
                },
                mobile: {
                    required : '请输入正确的手机号码',
                    number : '手机号码不能有字母字符',
                    minlength :  '手机号不能少于11位',
                    maxlength :  '手机号不能多于11位'
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   
                //$('.alert-danger', $('.forget-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

        $('.forget-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.forget-form').validate().form()) {
                    $('.forget-form').submit();
                }
                return false;
            }
        });

        /*jQuery('#forget-password').click(function() {
            jQuery('.login-form').hide();
            jQuery('.forget-form').show();
        });

        jQuery('#back-btn').click(function() {
            jQuery('.login-form').show();
            jQuery('.forget-form').hide();
        });*/

    }

    var handleReset = function () {

         jQuery.validator.addMethod( "issecurity" ,  function (value, element)  {
                var length = value.length;
                var lv = -1;
                if (value.match(/[a-z]/ig)){ lv++; }
                if (value.match(/[0-9]/ig)){ lv++; }
                if (value.match(/(.[^a-z0-9])/ig)){ lv++; }
                if (length < 6 && lv > 0){ lv--; }
                if(lv<1) {
                    return false;
                }
                else {
                    return true;
                }
            } ,  "密码过于简单！");

            function issecurity2(value) {
                var length = value.length;
                var lv = -1;
                if (value.match(/[a-z]/ig)){ lv++; }
                if (value.match(/[0-9]/ig)){ lv++; }
                if (value.match(/(.[^a-z0-9])/ig)){ lv++; }
                if (length < 6 && lv > 0){ lv--; }
                if(lv<1) {
                    return false;
                }
                else {
                    return true;
                }
         }

         $('.resetpassword-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",
                rules: {
                    mobile: {
                        required : true,
                        number: true,
                        ismobile: true,
                        minlength : 11,
                        maxlength : 11
                    },
                    verifycode: {
                        required : true,
                        number : true,
                        minlength : 4,
                        maxlength : 4
                    },
                    new_password: {
                        issecurity : true,
                        required : true,
                        minlength : 8,
                        maxlength : 20
                    },
                    confirm_password: {
                        required : true,
                        minlength : 8,
                        maxlength : 20,
                        equalTo: "#adminpassword"
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    mobile: {
                        required : '请输入正确的手机号码',
                        number : '手机号码不能有字母字符',
                        minlength :  '手机号不能少于11位',
                        maxlength :  '手机号不能多于11位'
                    },
                    verifycode: {
                        required : '请输入验证码',
                        number : '请输入正确验证码',
                        minlength : '请输入正确验证码',
                        maxlength : '请输入正确验证码'
                    },
                    new_password: {
                        required : '请输入密码',  
                        minlength :  '密码长度不能少于8位',
                        maxlength :  '密码长度不能超过20位'
                    },
                    confirm_password: {
                        required : '请输入密码',
                        minlength : '密码长度不能少于8位',
                        maxlength : '密码长度不能超过20位',
                        equalTo : '两次输入密码不一致'
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    //$('.alert-danger', $('.resetpassword-form')).show();
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },

                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('.input-icon'));
                },

                submitHandler: function (form) {
                    form.submit();
                }
            });

            $('.resetpassword-form input').keypress(function (e) {
                if (e.which == 13) {
                    if ($('.resetpassword-form').validate().form()) {
                        $('.resetpassword-form').submit();
                    }
                    return false;
                }
            });

            /*jQuery('#reset-btn').click(function () {
                jQuery('.forget-form').hide();
                jQuery('.reset-form').show();
            });*/

    }

    return {
        //main function to initiate the module
        init: function() {

            handleLogin();
            handleForgetPassword();
            handleReset();

        }

    };

}();