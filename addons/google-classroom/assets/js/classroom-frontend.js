(()=>{function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}window.jQuery(document).ready(function(r){function a(t,e){t.prop("disabled",!e),e?t.find("img").remove():t.append('<img style="width: 13px; margin-left: 9px; vertical-align: middle; display:inline-block;" src="'+window._tutorobject.loading_icon_url+'"/>')}r(window).resize(function(){var t=r(".tutor-gc-class-shortcode .class-header");t.css("height",.5*t.eq(0).outerWidth()+"px")}).trigger("resize"),r("#tutor_gc_student_password_set button").click(function(){var t=r(this),e=t.parent().parent(),o=e.find('[name="password-1"]').val(),n=e.find('[name="password-2"]').val(),e=e.find('[name="token"]').val();o&&n&&o===n?(a(t,!1),r.ajax({url:window._tutorobject.ajaxurl,data:{action:"tutor_gc_student_set_password",token:e,password:o},type:"POST",success:function(t){window.location.replace(window._tutorobject.tutor_frontend_dashboard_url)},error:function(){a(t,!0),alert("Request Failed.")}})):alert("Invalid Password")}),r(".tutor-gc-copy-text").click(function(t){t.stopImmediatePropagation(),t.preventDefault();t=r("<input>");r("body").append(t),t.val(r(this).data("text")).select(),document.execCommand("copy"),t.remove()}),r(".tutor-gc-google-thumbnail").each(function(){var t=r(this),e=t.data("thumbnail_url"),o=new Image;o.onload=function(){t.css("background-image","url("+e+")")},o.src=e}),r("#tutor_gc_stream_loader a").click(function(t){t.preventDefault();var e=r(this),o=e.parent(),n=o.find("img"),a=o.data("next_token"),t=o.data("course_id");e.add(n).toggle(),r.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{next_token:a,action:"tutor_gc_load_more_stream",course_id:t},success:function(t){try{t=JSON.parse(t)}catch(t){}e.add(n).toggle(),"object"==i(t)&&(t.html&&0!=/\S+/.test(t.html)?(o.data("next_token",t.next_token),o.before(t.html),t.next_token||o.remove()):o.remove())},error:function(){e.add(n).toggle()}})})})})();