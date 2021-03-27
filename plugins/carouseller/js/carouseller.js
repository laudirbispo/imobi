var carousel = function(i, t) {
    "undefined" == typeof t && (t = 0),
    	this.obj = $(i), 
    	this.autoscrolldelay = t, 
    	$(this.obj).find(".carousel-button-left").on("click", this.left.bind(this)), 
    	$(this.obj).find(".carousel-button-right").on("click", this.right.bind(this)),
    	$(this.obj).on("mouseenter", this.mousein.bind(this)), 
    	$(this.obj).on("mouseleave", this.mouseout.bind(this)), t && (this.interval = window.setInterval(this.right.bind(this), t))
};
carousel.prototype = {
    obj: null,
    interval: null,
    scrollSpeed: 200,
    stop: !1,
    mousein: function() {
        window.clearTimeout(this.interval)
    },
    mouseout: function() {
        this.autoscrolldelay && (this.interval = window.setInterval(this.right.bind(this), this.autoscrolldelay))
    },
    left: function() {
        if (!this.stop) {
            this.stop = !0;
            var i = $(this.obj).find(".carousel-block").outerWidth();
            $(this.obj).find(".carousel-items .carousel-block").eq(-1).prependTo($(this.obj).find(".carousel-items")), $(this.obj).find(".carousel-items").css({
                left: "-" + i + "px"
            }), $(this.obj).find(".carousel-items").animate({
                left: "0px"
            }, this.scrollSpeed, function() {
                this.stop = !1
            }.bind(this))
        }
    },
    right: function() {
        if (!this.stop) {
            this.stop = !0;
            var i = $(this.obj).find(".carousel-block").outerWidth();
            $(this.obj).find(".carousel-items").animate({
                left: "-" + i + "px"
            }, this.scrollSpeed, function() {
                $(this.obj).find(".carousel-items .carousel-block").eq(0).css("margin-left", "0.25641%"), $(this.obj).find(".carousel-items .carousel-block").eq(0).appendTo($(this.obj).find(".carousel-items")), $(this.obj).find(".carousel-items .carousel-block").eq(0).css("margin-left", "0"), $(this.obj).find(".carousel-items").css({
                    left: "0px"
                }), this.stop = !1
            }.bind(this))
        }
    }
};