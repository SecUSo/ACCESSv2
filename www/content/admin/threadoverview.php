<div class="container">
    <div class="row">
        <h1>Active Threads</h1>
        <hr>
        <div class="col-sm-4">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2">Authentication</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? for($it = 0; $it < count($data_authThreads); $it++) { ?>
                            <tr>
                                <td><span style="font-weight: bold">Time:&nbsp;</span><? echo $data_authThreads[$it]['times']?></td>
                                <td><a target="_blank" href="?Content&id=<?echo $data_authThreads[$it]['foreignid']?>"<button type="button" class="btn btn-primary btn-xs">Go to thread</button></a></td>
                            </tr>
                        <? }; ?>
                        </tbody>
                    </table>
        </div>
        <div class="col-sm-4">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2">Feature</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? for($ity = 0; $ity < count($data_featureThreads); $ity++) { ?>
                            <tr>
                                <td><span style="font-weight: bold">Time:&nbsp;</span><? echo $data_featureThreads[$ity]['times']?></td>
                                <td><a target="_blank" href="?Feature&id=<?echo $data_featureThreads[$ity]['foreignid']?>"<button type="button" class="btn btn-primary btn-xs">Go to thread</button></a></td>
                            </tr>
                        <? }; ?>
                        </tbody>
                    </table>
        </div>
        <div class="col-sm-4">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2">Subfeature</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? for($it = 0; $it < count($data_subfeatureThreads); $it++) { ?>
                            <tr>
                                <td><span style="font-weight: bold">Time:&nbsp;</span><? echo $data_subfeatureThreads[$it]['times']?></td>
                                <td><a target="_blank" href="?Subfeature&id=<?echo $data_subfeatureThreads[$it]['foreignid']?>"<button type="button" class="btn btn-primary btn-xs">Go to thread</button></a></td>
                            </tr>
                        <? }; ?>
                        </tbody>
                    </table>
        </div>
            </div>
        </div>