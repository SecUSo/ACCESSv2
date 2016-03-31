<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Mancosu, Christian Engelbert
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
?>

<?
/**
 * @desc
 * The following section contains the palcement of elements of the decision platform (features and subfeatures).
 * The order is:
 * (category (feature (subfeature)))
 * (category (feature (subfeature)))
 * (category (feature (subfeature)))
 *
 */
?>
<div id="decision_platform">
    <div class="container">
        <div class="row">
            <div class="content-block cowl-sm-12">
                <h2>Decision Making</h2>
                <hr>
                Mark what's important for you and order Containers in Descending Order by Importance!
                <hr>
                <div class="panel panel-default">
                    <div class ="panel-heading">Decision Support</div>
                    <div id="helpBox"></div>
                    <ul id="system-container">
                        <div class="category_conatiner_DP" id="category_container">

                            <? foreach($cat_feat_subfeat as $categoryName => $cArr) {?>
                                <div class="category_DP" id="<? echo $categoryName; ?>">
                                    <img class="fold_category" src="img/collapse.png">
                                    <span class="category_name" id="<? echo 'ID_'.$categoryName; ?>"><? echo $categoryName; ?></span>

                                    <? foreach($cArr as $featureName => $fArr) {if($featureName != NULL){?>
                                        <div style="margin-left:20px;" class="feature_DP selectability_<?echo current($fArr)?>" id="<? echo $featureName; ?>">
                                            <img class="fold_feature" src="img/collapse.png">
                                            <input type="checkbox" class="select-feat" id="<? echo "IN_F_".$featureName; ?>" value="<? echo "VAL_F_".$featureName; ?>">
                                            <span class="feature_name" id="<? echo $featureName.'name'; ?>"><? echo $featureName; ?>
                                                <span class="glyphicon glyphicon-question-sign info-help" id="<? echo $featureName; ?>" title="<? echo $data_featureDescriptions[$featureName];?>"></span>
                                            </span>

                                            <?
                                            if (current($fArr) == 0 || current($fArr) == 1) {
                                                ?><fieldset><?
                                            }
                                            ?>

                                            <? foreach($fArr as $subFeatureName => $statusCode) { if($subFeatureName != NULL || true){?>

                                                <div style="margin-left:20px;" class="subfeature_DP" id="<? echo $subFeatureName; ?>" >
                                                    <? switch ($statusCode) {

                                                        case 0:
                                                            ?><input type="radio"  name=<? echo $featureName;?> class="select-subfeat" id="<? echo "IN_SF_".$subFeatureName; ?>" value="<? echo "VAL_SF_".$subFeatureName; ?>"><?
                                                            break;
                                                        case 1:
                                                            ?><input type="radio"  name=<? echo $featureName;?> class="select-subfeat" id="<? echo "IN_SF_".$subFeatureName; ?>" value="<? echo "VAL_SF_".$subFeatureName; ?>"><?
                                                            break;
                                                        case 2:
                                                            ?><input type="checkbox"  class="select-subfeat" id="<? echo "IN_SF_".$subFeatureName; ?>" value="<? echo "VAL_SF_".$subFeatureName; ?>"><?
                                                            break;
                                                        case 3:
                                                            ?><input type="checkbox"  class="select-subfeat" id="<? echo "IN_SF_".$subFeatureName; ?>" value="<? echo "VAL_SF_".$subFeatureName; ?>"><?
                                                } ?>


                                                    <!-- <input type="checkbox"  class="remove-sub" id="<? echo $subFeatureName; ?>" value="<? echo $subFeatureName; ?>" draggable="true" ondragstart="drag(event)"> -->
                                                    <span class="subfeat_name" id="<? echo $subFeatureName.'_name'; ?>"><? echo $subFeatureName; ?>
                                                        <? if($subFeatureName != "0"){?>
                                                        <span class="glyphicon glyphicon-question-sign info-help" id="<? echo $subFeatureName; ?>" title="<? echo $data_subFeatureDescriptions[$subFeatureName];?>"></span>
                                                        <? } ?>
                                                    </span>
                                                </div>
                                            <? }} ?>

                                                <?
                                                if (current($fArr) == 0 || current($fArr) == 1) {
                                                ?></fieldset><?
                                                    }
                                                    ?>

                                        </div>
                                    <? }} ?>
                                </div>
                            <? } ?>

                            <button id="sendSelection">Start Algorithm</button>
                            </div>

                    </ul>
                    <div id="container">
                        <p>
                            <a href="#" id="close">close</a>
                        </p>
                        <p id="changeText">

                        </p>
                    </div>
                    <div id="overlay"></div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
</div>
<!-- PARSER-->
<script src="js/decisionmaking.js"></script>