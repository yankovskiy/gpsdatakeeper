<div id="download-data-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Download GPS-data</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="download-file-format">Choose file format</label>
                    <select id="download-file-format" class="form-control">
                        <option selected>GPX</option>
                        <option>KML</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="download-file-name">File name</label>
                    <input type="text" id="download-file-name" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="download-button">Download</button>
            </div>
        </div>
    </div>
</div>