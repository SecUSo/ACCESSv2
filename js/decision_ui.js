/**
 * #####################################################################################################################
 * Copyright (C) 2017   Philip Stumpf
 * #####################################################################################################################
 * This file is part of AccessV2.
 *
 * AccessV2 is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 * AccessV2 is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *     along with AccessV2.  If not, see <http://www.gnu.org/licenses/>.
 * #####################################################################################################################
 **/

/**
 * Javascript Base of User Interface for Decision Making Platform -> Controls Look and Feel of Platform
 */
$( document ).ready(function() {
    initialize();
});

// And Button in Step 2
$(".andButton").click(function(){
    var standardText = 'Choose Subfeature';
    $(this).toggleClass("active");
    $(this).parent('.btn-group').parent('span').parent('li').toggleClass("and");
    $(this).parent('.btn-group').parent('span').parent('li').removeClass("or");
    $(this).parent('div').find(".orButton").removeClass("active");
    $(this).parent('div').find(".dropdown-toggle").addClass("disabled");

    var selTextID = $(this).parent('.btn-group').parent('span').parent('li').attr("id");
    $(this).parent('div').find(".dropdown-toggle").each((function() {
        if ($(this).hasClass('triggered')) {
            var selText = $(this).text();
            $('#' + selText).find('.dropdown-toggle').each((function() {
                if ($(this).text() == selTextID) {
                    $(this).removeClass('triggered');
                    $(this).removeClass('disabled');
                    $(this).html(standardText + '<span class="caret"></span>');
                }
            }));
            $(this).html(standardText + '<span class="caret"></span>');
            $(this).addClass('disabled');
        }
    }));

    $(this).parent('div').find(".additional-or").each((function() {
        $(this).remove();
    }));

    $(this).parent('div').find(".dropdown-toggle").html(standardText + '<span class="caret"></span>');
    $(this).parent('div').find(".dropdown-toggle").removeClass("triggered");
});

// Or Button in Step 2
$(".orButton").click(function() {
    var standardText = 'Choose Subfeature';
    if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        $(this).parent('.btn-group').parent('span').parent('li').removeClass("or");
        $(this).parent('div').find(".orButton").removeClass("active");
        $(this).parent('div').find(".dropdown-toggle").addClass("disabled");

        var selTextID = $(this).parent('.btn-group').parent('span').parent('li').attr("id");
        $(this).parent('div').find(".dropdown-toggle").each((function() {
            if ($(this).hasClass('triggered')) {
                var selText = $(this).text();
                $('#' + selText).find('.dropdown-toggle').each((function() {
                    if ($(this).text() == selTextID) {
                        $(this).removeClass('triggered');
                        $(this).removeClass('disabled');
                        $(this).html(standardText + '<span class="caret"></span>');
                    }
                }));
                $(this).html(standardText + '<span class="caret"></span>');
                $(this).addClass('disabled');
            }
        }));

        $(this).parent('div').find(".additional-or").each((function() {
            $(this).remove();
        }));

        $(this).parent('div').find(".dropdown-toggle").html(standardText + '<span class="caret"></span>');
        $(this).parent('div').find(".dropdown-toggle").removeClass("triggered");

    } else {
        $(this).addClass("active");
        $(this).parent('.btn-group').parent('span').parent('li').addClass("or");
        $(this).parent('.btn-group').parent('span').parent('li').removeClass("and");
        $(this).parent('div').find(".andButton").removeClass("active");
        $(this).parent('div').find(".dropdown-toggle").removeClass("disabled");
    }

});

// Or Dropdown Button
$(".or-dropdown li").click(function(){
    var selText = $(this).text();
    var selTextID = $(this).parent('ul').parents('.btn-group').parent('span').parent('li').attr("id");
    $(this).parent('ul').parent('.btn-group').find('.dropdown-toggle').html(selText+'<span class="caret"></span>');
    $(this).parent('ul').parent('.btn-group').find('.dropdown-toggle').addClass("triggered");
    $('#' + selText).removeClass("and");
    $('#' + selText).find(".andButton").removeClass("active");
    $('#' + selText).addClass("or");
    $('#' + selText).find(".orButton").addClass("active");
    if ($('#' + selText).find('.dropdown-toggle').hasClass("triggered")) {
        var dropdown =  $('#' + selText).find(".or-dropdowngroup:first").clone(true);
        var buttongroup = $('#' + selText).find(".andorgroup");
        $(dropdown).wrap('<span class="clearfix additional-or"></span>').parent().appendTo(buttongroup);
        $('#' + selText).find('.dropdown-toggle:last').html(selTextID);
        $('#' + selText).find('.dropdown-toggle:last').addClass('disabled');
        $('#' + selText).find('.plus-button').click();
        $('#' + selText).find(".andorgroup").find(".or-dropdowngroup:last").find('button').removeClass('disabled');
    }
    else {
        $('#' + selText).find('.dropdown-toggle').html(selTextID);
        $('#' + selText).find('.dropdown-toggle').addClass("triggered");
        $('#' + selText).find('.dropdown-toggle').addClass("disabled");
    }

});


// Add Another and/or Option
$(".plus-button").click(function(){
    var standardText = 'Choose Subfeature';
    var dropdown = $(this).parent('span').find(".andorgroup").find(".or-dropdowngroup:first").clone(true);
    var buttongroup = $(this).parent('span').parent('li').find(".andorgroup");
    $(dropdown).wrap('<span class="clearfix additional-or"></span>').parent().appendTo(buttongroup);
    if($(this).parent('span').parent('li').hasClass("or")) {
        $(this).parent('span').find(".andorgroup").find(".or-dropdowngroup:last").find(".dropdown-toggle").removeClass('disabled');
    };
    $(this).parent('span').find(".andorgroup").find(".or-dropdowngroup:last").find(".dropdown-toggle").removeClass('triggered');
    $(this).parent('span').find(".andorgroup").find(".or-dropdowngroup:last").find(".dropdown-toggle").html(standardText + '<span class="caret"></span>');
});


//Navigation of Platform -> goToStep 1
$(".showFeatures").click(function(){
    $("#subFeature-selection").hide();
    $("#decisionResult").hide();
    $("#counter").hide();
    $("#feature-selection").show();
});

//Navigation of Platform -> goToStep 2
$(".showSubFeatures").click(function(){
    $("#feature-selection").hide();
    $("#decisionResult").hide();
    $("#counter").show();
    $("#subFeature-selection").show();
});

//Navigation of Platform -> goToStep 3 --> Evaluate
$(".showResult").click(function(){
    $("#feature-selection").hide();
    $("#subFeature-selection").hide();
    $("#counter").hide();
    $("#decisionResult").show();
    // Fix Appending Problem of Second Table
    $("#changeTable2 .table-item").remove();
});


// Hover Effect
$(".btn").mouseup(function(){
    $(this).blur();
});

// Create new List Container
$("#addlist").click(function(){
    $('#toggleButtons').before('<ol class="list-group droptrue feature-list" style="padding: 20px; background-color: #ccc;"></ol>');
    initialize();
});

// Add all Remaining Features in Step 1
$("#addRemaining").click(function(){
    $('#toggleButtons').before('<ol id="lastItem" class="list-group droptrue feature-list" style="padding: 20px; background-color: #ccc;"></ol>');
    $('#baseList > .feature').appendTo('#lastItem');
    initialize();
});

// Toggle Information Overlay for Features
$(".info-button").click(function(){
    document.getElementById("info-overlay").style.height = "100%";
    var selTextID = $(this).parent('li').attr("id");
    var selTextVal = $(this).parent('li').attr("value");
    $( "#featoverlay-name" ).text(selTextID);
    $( "#featoverlay-desc" ).text(selTextVal);
});

// Information Overlay Close
function closeNav() {
    document.getElementById("info-overlay").style.height = "0%";
}

// Reinitialize Droppable Elements if new List Container is Created
function initialize() {
    $( "ol.droptrue" ).sortable({
        connectWith: "ol"
    });

    $( "ol.dropfalse" ).sortable({
        connectWith: "ol",
        dropOnEmpty: false
    });

    $( "#sortable1, #sortable2, #sortable3" ).disableSelection();
}


// Toggle all Table Items

$("#table-show-all").click(function(){
    $(".table-item-hidden").toggleClass('hidden');
});

// Toggle example in Step 1
$("#show_ex1").click(function(){
    $("#example_step1").toggleClass('hidden');
});

// Toggle example in Step 2
$("#show_ex2").click(function(){
    $("#example_step2").toggleClass('hidden');
});




window.onbeforeunload = function() { return "Your work will be lost. Please Use Platform Navigation"; };