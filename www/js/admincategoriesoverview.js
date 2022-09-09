/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
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
 * Set Click-Listener to the "Change Structure" Button
 * @var output : An Array having Categories, Features and SubFeatures
 *  {
 *      "Category-Name":{
 *                          "Feature-Name":{
 *                                              "SubFeature-Name",
 *                                              "SubFeature-Name",
 *                                              ...
 *                                         },
 *                          "Feature-Name":{...},
 *                          ...
 *                      },
 *      "Category-Name":{...},
 *      ...
 *  }
 */
$("#system-container").on("click", "button", function(){
    var output = {};

    $('.category').each(function(){
        var category = $(this).attr('id');
        output[category] = {};

        $(this).children('.feature').each(function(){
            var feature = $(this).attr('id');
            output[category][feature] = [];

            $(this).children('.subfeature').each(function(){
                var subfeature = $(this).attr('id');
                output[category][feature].push(subfeature);
            });
        });
    });

    var json = JSON.stringify(output);

    // Ajax Request to store the Changes - Reload the Page
    $.ajax({
        type: 'POST',
        url: '?AdminEditCategories',
        data: {'json': json}
    }).done(function(data){
        window.location.href = "?AdminCategoriesOverview";

    }).fail(function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
    });
});

/**
 * Set Click-Listeners to the Images to Add or Remove Categories, Features or SubFeatures
 */
$("#system-container").on("click", "img", function(e){
    if(/^remove-\w+$/.test($(this).attr('class'))) {
        $(this).closest('div').remove();
    }else
    if($(this).attr('class') == "add-sub"){
        var newSubName = $(this).closest('div').children('input').val();
        var parentDiv = $(this).closest('div').parent().closest('div');
        $(this).closest('div').remove();
        parentDiv.append(createSubfeature(newSubName));
        parentDiv.append(createAddSubfeature());
    }else
    if($(this).attr('class') == "add-feat"){
        var newFeatName = $(this).closest('div').children('input').val();
        var parentDiv = $(this).closest('div').parent().closest('div');
        $(this).closest('div').remove();
        parentDiv.append(createFeature(newFeatName));
        parentDiv.children('div :last-child').append(createAddSubfeature());
        parentDiv.append(createAddFeature());
    }else
    if($(this).attr('class') == "add-cat"){
        var newCatName = $(this).closest('div').children('input').val();
        var parentDiv = $(this).closest('div').parent();
        $(this).closest('div').remove();
        parentDiv.append(createCategory(newCatName));
        parentDiv.children('div :last-child').append(createAddFeature());
        parentDiv.children('div :last-child').children('div :last-child').append(createAddSubfeature());
        parentDiv.append(createAddCategory());
    }
});

/**
 * createSubFeatures(subfeature)
 * @param subfeature - The Name of a SubFeature
 * @returns {string} - HTML-Code for a Div of a SubFeature
 */
function createSubfeature(subfeature){
    var output = "";
    output += '<div style="margin-left:20px" class="subfeature" id="'+subfeature+'">';
    output += '<img src="img/dash.png" class="remove-sub" id="'+subfeature+'"> ';
    output += '<span id="'+subfeature+'">'+subfeature+'</span>';
    output += '</div>';
    return output;
}

/**
 * createFeature(feature)
 * @param feature - The Name of a Feature
 * @returns {string} - HTML-Code for a Div of a Feature
 */
function createFeature(feature){
    var output = "";
    output += '<div style="margin-left:20px" class="feature" id="'+feature+'">';
    output += '<img src="img/dash.png" class="remove-feat" id="'+feature+'"> ';
    output += '<span id="'+feature+'">'+feature+'</span>';
    return output;
}

/**
 * createCategory(category)
 * @param category - The Name of a Category
 * @returns {string} - HTML-Code for a Div of a Category
 */
function createCategory(category){
    var output = "";
    output += '<div class="category" id="'+category+'">';
    output += '<img src="img/dash.png" class="remove-cat" id="'+category+'"> ';
    output += '<span id="'+category+'">'+category+'</span>';
    return output;
}

/**
 * createAddSubfeature(feature)
 * @param feature - The Feature the AddSubFeature-Box should be related to
 * @returns {string} - HTML-Code for a Div of an Add SubFeature Text-Box
 */
function createAddSubfeature(feature){
    var output = "";
    output += '<div style="margin-left:20px" class="new_subfeature">';
    output += '<img src="img/cross.png" class="add-sub" id="'+feature+'">';
    output += '<input type="text" value="Add new Subfeature" id="'+feature+'">';
    output += '</div>';
    return output;
}

/**
 * createAddFeature(category)
 * @param category - The Category the AddFeature-Box should be related to
 * @returns {string} - HTML-Code for a Div of an Add Feature Text-Box
 */
function createAddFeature(category){
    var output = "";
    output += '<div style="margin-left:20px" class="new_feature">';
    output += '<img src="img/cross.png" class="add-feat" id="'+category+'">';
    output += '<input type="text" value="Add new Feature" id="'+category+'">';
    output += '</div>';
    return output;
}

/**
 * createAddCategory()
 * @returns {string} - HTML-Code for a Div of an Add Category Text-Box
 */
function createAddCategory() {
    var output = "";
    output += '<div class="new_category">';
    output += '<img src="img/cross.png" class="add-cat">';
    output += '<input type="text" value="Add new Category">';
    output += '</div>';
    return output;
}