
/**
 * Touch Menu
 *
 * @version 1.0
 * @author Creative Pulse
 * @copyright Creative Pulse 2013
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


function Mod_TouchMenu(params) {
    var base_cid = ".mod_touchmenu_" + params.instance_id;

    this.timer = 0;
    this.interval = 1000;
    this.fade_time = 200;
    this.offset_left = typeof params.offset_left != "number" ? 0 : params.offset_left;
    this.offset_top = typeof params.offset_top != "number" ? 0 : params.offset_top;
    this.css_class_header_selected = typeof params.css_class_header_selected != "string" ? "" : params.css_class_header_selected;

    this.current_idx = -1;
    this.content_is_open = false;


    // initialize menu items

    var that = this;
    var html_body = document.getElementsByTagName("body")[0];
    this.items = [];
    for (var i = 0; ; i++) {
        var header_obj = jQuery(base_cid + "_header_" + i);
        if (header_obj.length == 0) {
            break;
        }

        header_obj
            .attr("data-idx", i)
            .mouseover(function () { that.h_mouseover(this.getAttribute("data-idx")); })
            .mouseout(function () { that.h_mouseout(this.getAttribute("data-idx")); })
            .click(function () { that.h_click(this.getAttribute("data-idx")); });

        var content_obj = jQuery(base_cid + "_content_" + i);
        html_body.appendChild(content_obj[0]);
        content_obj
            .css({position: "absolute", top: 0, left: 0})
            .attr("data-idx", i)
            .mouseover(function () { that.h_mouseover(this.getAttribute("data-idx")); })
            .mouseout(function () { that.h_mouseout(this.getAttribute("data-idx")); });

        this.items.push({header: header_obj, content: content_obj});
    }

    this.h_mouseover = function (idx) {
        if (this.timer > 0) {
            clearTimeout(this.timer);
            this.timer = 0;
        }

        if (idx == this.current_idx) {
            return;
        }

        if (idx != this.current_idx && this.current_idx > -1) {
            this.items[this.current_idx].header.removeClass(this.css_class_header_selected);
            if (this.content_is_open) {
                this.content_close(false);
            }
        }

        this.current_idx = idx;
        this.items[this.current_idx].header.addClass(this.css_class_header_selected);
        if (this.content_is_open) {
            this.content_open(false);
        }
    }

    this.h_mouseout = function (idx) {
        if (idx != this.current_idx) {
            return;
        }

        if (this.content_is_open) {
            this.timer = setTimeout(function () { that.do_close(); }, this.interval);
        }
        else {
            this.items[this.current_idx].header.removeClass(this.css_class_header_selected);
            this.current_idx = -1;
        }
    }

    this.do_close = function () {
        this.timer = 0;
        this.items[this.current_idx].header.removeClass(this.css_class_header_selected);
        this.content_close(true);
        this.content_is_open = false;
        this.current_idx = -1;
    }

    this.h_click = function (idx) {
        if (this.current_idx == -1) {
            // case: no menu was previously selected
            this.current_idx = idx;
            this.items[this.current_idx].header.addClass(this.css_class_header_selected);
            this.content_open(true);
            this.content_is_open = true;
        }
        else if (this.current_idx != idx) {
            // case: another item was previously open

            this.items[this.current_idx].header.removeClass(this.css_class_header_selected);
            this.content_close(false);

            this.current_idx = idx;
            this.items[this.current_idx].header.addClass(this.css_class_header_selected);
            this.content_open(false);
            this.content_is_open = true;
        }
        else if (this.content_is_open) {
            // case: the same item was previously selected with the content panel open
            this.items[this.current_idx].header.removeClass(this.css_class_header_selected);
            this.content_close(true);
            this.content_is_open = false;
            this.current_idx = -1;
        }
        else {
            // case: the same item was previously selected with the content panel closed
            this.content_open(true);
            this.content_is_open = true;
        }
    }

    this.content_open = function (animate) {
        var header = this.items[this.current_idx].header;
        var content = this.items[this.current_idx].content;
        var header_pos = header.offset();
        content.css({left: "" + (header_pos.left + this.offset_left) + "px", top: "" + (header_pos.top + header.height() + this.offset_top) + "px"});

        if (animate) {
            content.fadeIn(this.fade_time);
        }
        else {
            content.show();
        }
    }

    this.content_close = function (animate) {
        if (animate) {
            this.items[this.current_idx].content.fadeOut(this.fade_time);
        }
        else {
            this.items[this.current_idx].content.hide();
        }
    }
}

jQuery(document).ready(function () {
    
    // load menus

    for (var i = 0, len = document.mod_touchmenu_db.length; i < len; i++) {
        new Mod_TouchMenu(document.mod_touchmenu_db[i]);
    }

});
