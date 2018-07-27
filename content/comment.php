<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Philip Stumpf
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

<? if ($data_validSession) { ?>
<div class="comment-section">
    <div class="comment">
        <h2>Discussion</h2>
        <? for($it = 0; $it < count($data_discussion); $it++) { ?>
            <? if ($data_discussion[$it]['post_type'] == 'suggestion' || $data_discussion[$it]['post_type'] == 'auto-suggestion'){ ?>
                <? if ($data_discussion[$it]['post_threadstatus'] == 'accepted'){ ?>
                <div class="panel panel-success">
                <? } elseif ($data_discussion[$it]['post_threadstatus'] == 'rejected'){ ?>
                <div class="panel panel-danger">
                <? } else { ?>
                <div class="panel panel-info">
                 <? }; ?>
            <? } else  { ?>
                <div class="panel panel-default">
            <?}; ?>
                <div id="discussion_id_<? echo $data_discussion[$it]['id'];?>" class="panel-heading">
                    <? echo $data_discussion[$it]['Title'] . "&nbsp" . $data_discussion[$it]['FirstName'] . "&nbsp" . $data_discussion[$it]['LastName'] . "  (" . $data_discussion[$it]['Organization'] . ")";?>
                    <span class="label label-default" style="margin-left: 2%"><? echo $data_discussion[$it]['post_date']; ?></span>
                    <span class="label label-primary" style="margin-left: 2%"><? echo $data_discussion[$it]['post_type']; ?></span>
                    <? if ($data_isAdmin) { ?>
                    <? if ($data_discussion[$it]['post_type'] == 'suggestion'){ ?>
                   <button type="button" class="close_btn btn btn-default btn-xs" style="float:right" value="<? echo $data_discussion[$it]['id']; ?>">Reject</button>
                   <button type="button" class="accept_btn btn btn-default btn-xs" style="float:right" value="<? echo $data_discussion[$it]['id']; ?>">Accept</button>
                    <? } ?>
                    <button type="button" class="del_btn btn btn-default btn-xs" style="float:right" value="<? echo $data_discussion[$it]['id']; ?>">Delete Comment</button>
                    <? } ?>
                </div>
                <div class="panel-body word-wrap">
                    <? echo $data_discussion[$it]['post_content']; ?>
                        <? if ($data_discussion[$it]['post_type'] == 'suggestion' || $data_discussion[$it]['post_type'] == 'auto-suggestion'){ ?>
                         <hr>
                         <? for($ity = 0; $ity < count($data_discussion_subthreads); $ity++) {
                          if ($data_discussion[$it]['id'] == $data_discussion_subthreads[$ity]['thread_id']) { ?>
                              <div class="panel panel-default">

                                <div class="panel-heading">
                                    <? echo $data_discussion[$it]['Title'] . "&nbsp" . $data_discussion_subthreads[$ity]['FirstName'] . "&nbsp" . $data_discussion_subthreads[$ity]['LastName'] . "  (" . $data_discussion_subthreads[$ity]['Organization'] . ")";?>
                                    <span class="label label-default" style="margin-left: 2%"><? echo $data_discussion_subthreads[$ity]['post_date']; ?></span>
                                    <? if ($data_isAdmin) { ?>
                                    <button type="button" class="del_btn_subthread btn btn-default btn-xs" style="float:right" value="<? echo $data_discussion_subthreads[$ity]['id']; ?>">Delete Comment</button>
                                    <? } ?>
                                </div>
                                <div class="panel-body word-wrap">
                                 <? echo $data_discussion_subthreads[$ity]['post_content']; ?>
                                 </div>
                             </div>
                          <? } ?>
                         <? }; ?>
                      <? }; ?>
                </div>
                <? if (($data_discussion[$it]['post_type'] == 'suggestion' || $data_discussion[$it]['post_type'] == 'auto-suggestion')&& $data_discussion[$it]['post_threadstatus'] == 'active'){ ?>
                <div class="panel-footer">

                <div class="post-comment">
                         <form class="form-panelfooter" role="form">
                          <div class="form-group">
                                <label for="enterCommentsubthread">Reply: &nbsp;</label>
                                <textarea class="form-control" rows="4" id="enterCommentsubthread"></textarea>
                            </div>
                          <div class="form-group">
                              <input type="hidden" id="contentTypesubthread" value="<? echo $data_contentType ?>">
                              <input type="hidden" id="contentIDsubthread" value="<? echo $data_contentId ?>">
                              <input type="hidden" id="ThreadIDsubthread" value="<? echo $data_discussion[$it]['id']?>">
                            </div>
                          <button type="submit" class="btn btn-default" >Submit</button>
                        </form>
                </div>

                </div>
                 <? }?>

            </div>
        <? }; ?>
    </div>
    <hr>
    <div class="post-comment">
        <h2>Suggestion</h2>
			<div class="form-group">
                <label for="sel1">Select Type:</label>
                <select class="form-control" id="selectType">
                    <option value="1">comment</option>
                    <!-- deleted value=2 for suggestion discussion type-->
                    <? if($data_comment_type == "full") { ?>
                    <option value="3">subfeature suggestion</option>
                    <option value="4">classification suggestion</option>
                    <? } ?>
                </select>
            </div>
        <form role="form" class="form-comment">
            <div id="suggestion_plaintext" class="form-group">
                <label for="enterComment">Text</label>
                <textarea class="form-control" rows="5" id="enterComment"></textarea>
            </div>
            <div id="suggestion_ec" class="form-group2 hidden">
                <div class="jumbotron">
                    <h2>Subfeature Suggestion</h2>
                    <p>Suggest a binary change of the current subfeature selection. After checking or unchecking a box, the current selection will be marked in red and the form will be blocked. If you wanna change your selection, click on the marked selection box and the form will be unlocked.</p>
                    <p>For selective features with included nullclass, selected subfeatures can either be deselected or the selection can be changed. For selective features with an excluded nullclass, only a change in the subfeature selection can be made.</p>
                    <p>Hover your mouse over features and sub-features to get detailed information about it.</p>
                </div>
                <label for="authsubfeature">Subfeatures of '<? echo $content_name; ?>'</label>
                <div id="authsubfeature" class="panel panel-default">
                        <div class="panel-body word-wrap" id="suggestion_ec_body">
                            <div class="text-center">
                                <span style="font-size: small;" class="label label-default">Additive Feature: |subfeatures| >= 0</span>
                                <span style="font-size: small;" class="label label-primary">Selective Feature excluding nullclass: |subfeatures| <= 1</span>
                                <span style="font-size: small;" class="label label-info">Selective Feature including nullclass: |subfeatures| == 1</span>
                            </div>
                            </br>
                            <div>
                            <? if(isset($data_content))
                                foreach($data_content as $categoryName => $cArr){?>
                                    <div class="category" id="<? echo $categoryName; ?>">
                                        <span><? echo $categoryName; ?></span>
                                    <? if(isset($cArr))
                                        foreach($cArr as $featureName => $fArr){?>
                                        <div class="feature" id="<? echo $featureName; ?>">
                                            <? if (in_array($featureName, $selectiveFeaturesWithNullclass))
                                                echo '<span class="label label-primary subfeature_info_box" data-content="' . $data_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';
                                            else if (in_array($featureName, $selectiveFeatures))
                                                echo '<span class="label label-info subfeature_info_box" data-content="' . $data_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';
                                            else if (in_array($featureName, $additiveFeatures))
                                                echo '<span class="label label-default subfeature_info_box" data-content="' . $data_feature_descriptions[$featureName] . '" title="Description" rel="popover"
                                                                                    data-placement="top"
                                                                                    data-trigger="hover">' . $featureName . '</span>';?>
                                                <ul style="list-style-type:disc; padding-left: 0px;">
                                                <? if (isset($fArr))
                                                    foreach ($fArr as $subFeatureName => $inAuth) {
                                                        ?>
                                                        <li style="list-style-type: none; margin-top: 5px;">
                                                            <div class="subfeature"
                                                                 id="<? echo $subFeatureName; ?>">
                                                                <? if ($inAuth) { ?>
                                                                    <img src="img/cross.png"
                                                                         class="in-auth">
                                                                <? } else { ?>
                                                                    <img src="img/dash.png"
                                                                         class="not-in-auth">
                                                                <? } ?>
                                                                <span class="subfeature_info_box"
                                                                    data-content="<? echo $data_subfeature_descriptions[$subFeatureName]; ?>"
                                                                    title="Description" rel="popover"
                                                                    data-placement="top"
                                                                    data-trigger="hover">
                                                                <? echo $subFeatureName; ?></span>
                                                            </div>
                                                        </li>
                                                    <? } ?>
                                            </ul>
                                        </div>
                                    <? } ?>
                                    </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <label for="subfeature_suggestion_references">References</label>
                <textarea class="form-control" rows="3" id="subfeature_suggestion_references"></textarea>
                </br>
                <label for="subfeature_suggestion_comment">Comment</label>
                <textarea class="form-control" rows="3" id="subfeature_suggestion_comment"></textarea>
            </div>
            <div id="suggestion_rate" class="form-group3 hidden">
                <div class="jumbotron">
                    <h2>Classification Suggestion</h2>
                    <p>Make a classification suggestion by rating the current scheme against all other schemes within a single class.</p>
                </div>
                <label for="selectSubClass">Select class:</label>
                <select class="form-control" id="selectSubClass">
                <? if(isset($data_feature_list))
                foreach($data_feature_list as $subclass){?>
                    <option feature="<? echo $subclass["feature"];?>" class="<? if($subclass["zeroclass"] == "1"){ echo "0"; } else { echo $subclass["class"];} ?>"><? echo $subclass["class"]; ?> (Feature: <? echo $subclass["feature"]; ?>)</option>
                <? } ?>
                </select>
                </br>
				<label for="authclassification">Pairwise comparisons for '<? echo $content_name; ?>' based on a.m. class</label>
                <div id="authclassification" class="panel panel-default">
                    <div id="siteloader" style="overflow: auto;"></div>​
                </div>
                <label for="classification_suggestion_references">References</label>
                <textarea class="form-control" rows="3" id="classification_suggestion_references"></textarea>
                </br>
                <label for="classification_suggestion_comment">Comment</label>
                <textarea class="form-control" rows="3" id="classification_suggestion_comment"></textarea>
            </div>
            <div class="form-group">
            <input type="hidden" id="contentType" value="<? echo $data_contentType ?>">
                <input type="hidden" id="contentID" value="<? echo $data_contentId ?>">
            </div>
            <hr>
            <p><button type="submit" class="btn btn-default">Send Suggestion</button></p>
        </form>
    </div>
</div>


<script src="js/comment.js"></script>

<? } else { ?>
    <div class="alert alert-info">
        <a href="?Login">  Login / Register to take part in Discussion!</a>
    </div>
    <hr>
<?};?>