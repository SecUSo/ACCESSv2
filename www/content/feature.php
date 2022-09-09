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

<?/* BASIC CONTENT PAGE FOR "WIKI LIKE VIEW", DISPLAYS INFO ABOUT SINGLE AUTHENTICATIONS*/?>

<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h1 class="auth_name"><?php echo $data_features[0]['name'] ?></h1>
            <hr>
            <h2>Description</h2>
            <span class="auth_desc"><?php echo $data_features[0]['description'] ?></span>
            <hr>
            <h2>AHP Matrix</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td>&nbsp;</td>
                    <? for($ith = 0; $ith < count($data_ahpMatrixCaptions); $ith++) { ?>
                        <td><? echo $data_ahpMatrixCaptions[$ith]['name']?></td>
                    <? }; ?>
                    </tr>
                    </thead>
                    <tbody>
                   <? $itz = 0; ?>
                    <? for($ity = 0; $ity < sqrt(count($data_ahpMatrix)); $ity++) { ?>
                        <tr>
                            <td><? echo $data_ahpMatrixCaptions[$ity]['name']?></td>
                         <? for($itx = 0; $itx < sqrt(count($data_ahpMatrix)); $itx++) { ?>
                            <td><? echo $data_ahpMatrix[$itz]['value']?></td>
                             <? $itz++; ?>
                        <? }; ?>

                        </tr>
                    <? }; ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <? include 'comment.php'; ?>
        </div><!-- /.content -->
    </div> <!-- row -->
</div> <!-- /container -->