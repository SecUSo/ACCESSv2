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
            <? if ($data_discussion[$it]['post_type'] == 'suggestion'){ ?>
                <? if ($data_discussion[$it]['post_threadstatus'] == 'accepted'){ ?>
                <div class="panel panel-success">
                <? } elseif ($data_discussion[$it]['post_threadstatus'] == 'rejected'){ ?>
                <div class="panel panel-danger">
                <? } else { ?>
                <div class="panel panel-info">
                 <? }; ?>
            <? } else { ?>
            <div class="panel panel-default">
            <?}; ?>
                <div class="panel-heading">
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
                <div class="panel-body">
                    <? echo $data_discussion[$it]['post_content']; ?>
                        <? if ($data_discussion[$it]['post_type'] == 'suggestion'){ ?>
                         <br><br>
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
                                <div class="panel-body">
                                 <? echo $data_discussion_subthreads[$ity]['post_content']; ?>
                                 </div>
                             </div>
                          <? } ?>
                         <? }; ?>
                      <? }; ?>
                </div>
                <? if ($data_discussion[$it]['post_type'] == 'suggestion' && $data_discussion[$it]['post_threadstatus'] == 'active'){ ?>
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
    </div
    <hr>
    <div class="post-comment">
        <h2 class="auth_name">Post Comment</h2>
        <form role="form" class="form-comment">
            <div class="form-group">
                <label for="enterComment">Text</label>
                <textarea class="form-control" rows="5" id="enterComment"></textarea>
            </div>
            <div class="form-group">
                <label for="sel1">Select Type:</label>
                <select class="form-control" id="selectType">
                    <option>comment</option>
                    <option>suggestion</option>
                </select>
            </div>
            <div class="form-group">
            <input type="hidden" id="contentType" value="<? echo $data_contentType ?>">
                <input type="hidden" id="contentID" value="<? echo $data_contentId ?>">
            </div>
            <p><button type="submit" class="btn btn-default">Submit</button></p>
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