<script src="js/adminsuggestionoverview.js"></script>

<div class="container">
    <div class="loader"></div>
    <div class="row">
        <h1>Active Suggestions</h1>
        <hr>


        <div class="content-block col-sm-12">
            <h2>Scheme Suggestions</h2>
            <hr>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Meta</th>

                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data_scheme_suggestions as $suggestion) {
                    ?>
                    <tr>
                        <td><? echo $suggestion["id"]; ?></td>
                        <td><? echo $suggestion["name"]; ?></td>
                        <td><? echo substr($suggestion["description"], 0, 15) ?></td>
                        <td><? echo $suggestion["category"]; ?></td>
                        <td><? echo $suggestion["FirstName"] . " " . $suggestion["LastName"]; ?></td>
                        <td><? echo $suggestion["suggestion_date"]; ?></td>
                        <td>
                            <a class="accept" href="?AdminAcceptSchemeSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-success btn-primary btn-xs">Accept</button>
                            </a>
                            <a href="?AdminEditSchemeSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-primary btn-xs">Edit</button>
                            </a>
                            <a class="delete" href="?AdminDeleteSchemeSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-warning btn-primary btn-xs">Delete</button>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="content-block col-sm-12">
            <h2>Subfeature Suggestions</h2>
            <hr>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Scheme</th>
                    <th>Subfeature</th>
                    <th>Value</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Meta</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data_subfeature_suggestions as $suggestion) {
                    ?>
                    <tr>
                        <td><? echo $suggestion["id"]; ?></td>
                        <td><? echo $suggestion["scheme"]; ?></td>
                        <td><? echo $suggestion["subfeature"]; ?></td>
                        <td><? if($suggestion["value"] == "1") echo "Add"; else echo "Remove"; ?></td>
                        <td><? echo $suggestion["FirstName"] . " " . $suggestion["LastName"]; ?></td>
                        <td><? echo $suggestion["suggestion_date"]; ?></td>
                        <td>
                            <a class="accept"href="?AdminAcceptSubfeatureSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-success btn-primary btn-xs">Accept</button>
                            </a>
                            <a class="reject" href="?AdminRejectSubfeatureSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-danger btn-primary btn-xs">Reject</button>
                            </a>
                            <a href="?Content&id=<? echo $suggestion["auth_id"]."#discussion_id_".$suggestion["discussion_id"]; ?>">
                                <button type="button" class="btn btn-primary btn-xs">Discussion</button>
                            </a>
                            <a class="delete" href="?AdminDeleteSubfeatureSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-warning btn-primary btn-xs">Delete</button>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="content-block col-sm-12">
            <h2>Classification Suggestions</h2>
            <hr>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Scheme</th>
                    <th>Feature</th>
                    <th>Class</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Meta</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data_classification_suggestions as $suggestion) {
                    ?>
                    <tr>
                        <td><? echo $suggestion["id"]; ?></td>
                        <td><? echo $suggestion["scheme"]; ?></td>
                        <td><? echo $suggestion["feature"]; ?></td>
                        <td><? echo $suggestion["class"]; ?></td>
                        <td><? echo $suggestion["FirstName"] . " " . $suggestion["LastName"]; ?></td>
                        <td><? echo $suggestion["suggestion_date"]; ?></td>
                        <td>
                            <a class="accept" href="?AdminAcceptClassificationSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-success btn-primary btn-xs">Accept</button>
                            </a>
                            <a class="reject" href="?AdminRejectClassificationSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-danger btn-primary btn-xs">Reject</button>
                            </a>
                            <a href="?Content&id=<? echo $suggestion["auth_id"]."#discussion_id_".$suggestion["discussion_id"]; ?>">
                                <button type="button" class="btn btn-primary btn-xs">Discussion</button>
                            </a>
                            <a class="delete" href="?AdminDeleteClassificationSuggestion&id=<? echo $suggestion["id"] ?>">
                                <button type="button" class="btn btn-warning btn-primary btn-xs">Delete</button>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
                </tbody>
            </table>
        </div>


    </div>
</div>