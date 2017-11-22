<div class="container">
    <div class="row">
        <h1>Manage Invite Codes</h1>
        <hr>
        <div class="text-center">
            <button id="generate_invite_key" type="button" class="btn btn-primary btn-lg">Generate Invite Key</button>
        </div>
        </br>
        <div class="col-sm-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Invite Code</th>
                    <th>Expiry Date</th>
                    <th>Meta</th>
                </tr>
                </thead>
                <tbody>
                <? for ($it = 0; $it < count($data_active_invite_codes); $it++) { ?>
                    <tr>
                        <td>
                            <? echo $data_active_invite_codes[$it]['id'] ?>
                        </td>
                        <td>
                            <? echo $data_active_invite_codes[$it]['invite_code'] ?>
                        </td>
                        <td>
                            <? echo $data_active_invite_codes[$it]['expiry_date'] ?>
                        </td>
                        <td>
                            <button invite_index="<? echo $data_active_invite_codes[$it]['id'] ?>" type="button"
                                    class="btn btn-primary btn-xs delete_invite_code">Delete
                            </button>
                        </td>
                    </tr>
                <? }; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Javascript Files-->
<script src="js/admininviteoverview.js"></script>
