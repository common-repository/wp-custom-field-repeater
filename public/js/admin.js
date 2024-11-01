jQuery(document).ready(function ($) { //script to prevent duplicated field name entry from admin side
    "use strict";
    $("#publish").on("click", function (e) {
        var item = $("input[name='TitleItem[]']").map(function () {return $(this).val(); }).get();
        if ((new Set(item)).size !== item.length) {
            e.preventDefault(); // cancel default action
            alert("The Title field is required and must be unique");
        }
    });
});