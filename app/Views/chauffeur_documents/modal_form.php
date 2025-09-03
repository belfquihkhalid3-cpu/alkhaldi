<?php echo form_open_multipart(get_uri("chauffeur_documents/upload_file"), ["id" => "document-upload-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="chauffeur_id" value="<?php echo $chauffeur_id; ?>" />

    <div class="form-group">
        <label for="type_document" class=" col-md-3">Type de document</label>
        <div class="col-md-9">
            <?php
            $type_options = [
                "cnie" => "CNIE",
                "permis" => "Permis de conduire",
                "contrat" => "Contrat de travail",
                "cv" => "CV",
                "autre" => "Autre"
            ];
            echo form_dropdown("type_document", $type_options, "", "class='select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="document_file" class=" col-md-3">Fichier</label>
        <div class="col-md-9">
            <input type="file" name="file" id="document_file" class="form-control" data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" />
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Annuler</button>
    <button type="submit" class="btn btn-primary"><span data-feather="upload-cloud" class="icon-16"></span> Uploader</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#document-upload-form").appForm({
            onSuccess: function (result) {
                // Met à jour la table des documents après l'upload
                $("#chauffeur-documents-table").appTable({newData: result.data});
            }
        });

        $('.select2').select2();
    });
</script>