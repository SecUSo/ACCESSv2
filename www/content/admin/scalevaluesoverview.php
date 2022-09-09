<div class="container">
    <div class="row">
        <div class="content-block col-sm-12">
            <h2>Change Scale Values in Features</h2>
            <hr>
            <div id="authscalevalues" class="panel panel-default">
                <div class="panel-heading">ACCESS</div>
                <div id="system-container" class="panel-body">
                    <? if(isset($data_content)){
                        foreach($data_content as $catName => $cArr){?>
                    <div class="category" id="<? echo $catName; ?>">
                        <span><? echo $catName; ?></span>
                        <? if(isset($cArr)){
                            foreach($cArr as $featureName){ ?>
                        <div class="feature" name="<? echo $featureName; ?>">
                            <span><? echo $featureName; ?></span>
                        </div>
                        <? }} ?>
                    </div>
                    <? }}?>
                </div>
            </div>
        </div><!-- /.content -->
    </div> <!-- row -->
</div>

<script src="js/adminscalevaluesoverview.js"></script>