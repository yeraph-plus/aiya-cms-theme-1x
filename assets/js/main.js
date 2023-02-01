// 变黑函数
function setDark() {
  localStorage.setItem("isDarkMode", "1");
  document.documentElement.classList.add("dark");
}
// 变白函数
function removeDark() {
  localStorage.setItem("isDarkMode", "0");
  document.documentElement.classList.remove("dark");
}
// switch按钮
function switchDarkMode() {
  let isDark = localStorage.getItem("isDarkMode");
  if (isDark == '1') {
    removeDark();
  } else {
    setDark();
  }
}


jQuery(document).ready(function($){

//table预设calss
$('.wznrys table').addClass("table");

});


$(document).ready(function(){
    //子菜单点击展开
    $('.menu-zk .menu-item-has-children').prepend('<span class="czxjcdbs"></span>');
    $('.menu-zk li.menu-item-has-children .czxjcdbs').click(function(){
    $(this).toggleClass("kai");
    $(this).nextAll('.sub-menu').slideToggle("slow");
    });
});


//列表ajax加载
jQuery(document).ready(function($) {
$('div.post-read-more a').click( function() {
    $this = $(this);
    $this.addClass('loading');
    var href = $this.attr("href");
    if (href != undefined) {
        $.ajax( {
            url: href,
            type: "get",
        error: function(request) {
        },
        success: function(data) {
            $this.removeClass('loading');
            var $res = $(data).find(".post_loop");
            $('.post_box').append($res);
            var newhref = $(data).find(".post-read-more a").attr("href");
            if( newhref != undefined ){
                $(".post-read-more a").attr("href",newhref);
            }else{
                $(".post-read-more").hide();
            }
        }
        });
    }
    return false;
});
});


//导航菜单
function mainmenu(ulclass){
    $(document).ready(function(){
        $(ulclass+' li').hover(function(){
            $(this).children("ul").show();
        },function(){
            $(this).children("ul").hide();
        });
    });
}
mainmenu('.header-menu-ul');



//赞
$.fn.postLike = function() {
    if ($(this).hasClass('done')) {
        alert('勿重复操作');
        return false;
    } else {
        $(this).addClass('done');
        var id = $(this).data("id"),
        action = $(this).data('action'),
        rateHolder = $(this).children('.count');
        var ajax_data = {
            action: "specs_zan",
            um_id: id,
            um_action: action
        };
        $.post("/wp-admin/admin-ajax.php", ajax_data,
        function(data) {
            $(rateHolder).html(data);
        });
        return false;
    }
};
$(document).on("click", ".specsZan", function() {$(this).postLike();});


//深色模式
const isDark= localStorage.getItem("isDarkMode");
if(isDark==="1"){
  document.documentElement.classList.add('dark');
}else{
  document.documentElement.classList.remove('dark');
}


//返回顶部
const scrollToTopBtn = document.querySelector(".scrollToTopBtn")
const rootElement = document.documentElement
function handleScroll() {
  const scrollTotal = rootElement.scrollHeight - rootElement.clientHeight
  if ((rootElement.scrollTop / scrollTotal ) > 0.80 ) {
    scrollToTopBtn.classList.add("showBtn")
  } else {
    scrollToTopBtn.classList.remove("showBtn")
  }
}
function scrollToTop() {
  rootElement.scrollTo({
    top: 0,
    behavior: "smooth"
  })
}
scrollToTopBtn.addEventListener("click", scrollToTop)
document.addEventListener("scroll", handleScroll)


//ajax提交评论
jQuery(document).ready(function(jQuery) {
	var __cancel = jQuery('#cancel-comment-reply-link'),
		__cancel_text = __cancel.text(),
		__list = 'comment-list';//your comment wrapprer
	jQuery(document).on("submit", "#commentform", function() {
		jQuery.ajax({
			url: ajaxcomment.ajax_url,
			data: jQuery(this).serialize() + "&action=ajax_comment",
			type: jQuery(this).attr('method'),
			beforeSend: faAjax.createButterbar("提交中...."),
			error: function(request) {
				var t = faAjax;
				t.createButterbar(request.responseText);
			},
			success: function(data) {
				jQuery('textarea').each(function() {
					this.value = ''
				});
				var t = faAjax,
					cancel = t.I('cancel-comment-reply-link'),
					temp = t.I('wp-temp-form-div'),
					respond = t.I(t.respondId),
					post = t.I('comment_post_ID').value,
					parent = t.I('comment_parent').value;
				if (parent != '0') {
					jQuery('#respond').before('<ol class="children">' + data + '</ol>');
				} else if (!jQuery('.' + __list ).length) {
					if (ajaxcomment.formpostion == 'bottom') {
						jQuery('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
					} else {
						jQuery('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
					}

				} else {
					if (ajaxcomment.order == 'asc') {
						jQuery('.' + __list ).append(data); // your comments wrapper
					} else {
						jQuery('.' + __list ).prepend(data); // your comments wrapper
					}
				}
				t.createButterbar("提交成功");
				cancel.style.display = 'none';
				cancel.onclick = null;
				t.I('comment_parent').value = '0';
				if (temp && respond) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp)
				}
			}
		});
		return false;
	});
	faAjax = {
		I: function(e) {
			return document.getElementById(e);
		},
		clearButterbar: function(e) {
			if (jQuery(".butterBar").length > 0) {
				jQuery(".butterBar").remove();
			}
		},
		createButterbar: function(message) {
			var t = this;
			t.clearButterbar();
			jQuery("body").append('<div class="butterBar"><p class="butterBar-message">' + message + '</p></div>');
			setTimeout("jQuery('.butterBar').remove()", 3000);
		}
	};
});

