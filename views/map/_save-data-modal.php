<div id="save-data-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Save GPS-data</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" id="default-form">
                    <label for="gps-data-title">Title for GPS-data</label>
                    <input type="text" class="form-control" id="gps-data-title">
                </div>
                <div id="save-success" class="alert alert-success" style="display: none">Data successfully saved</div>
                <div id="save-error" class="alert alert-danger" style="display: none">Incorrect input data</div>
                <div id="save-progress" class="alert alert-info" style="display: none">Data uploading. Please wait</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-button">Save</button>
            </div>
        </div>
    </div>
</div>