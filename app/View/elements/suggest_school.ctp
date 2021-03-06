<script src="/js/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#suggestSchoolForm').ajaxForm({
            success:function (response) {
                if (response == "true") {
                    $('#btnsuggest').addClass("disabled");
                    $('#form-response-container').show();
                    $('#form-container').hide();
                    $('#suggest-response').html('Thank you!  Your suggestion has been submitted.  Please check back with us to see if your school has been added.');
                } else if (response == "false") {
                    $('#formResponse').html('Sorry! Your suggestion was not submitted. Please try again later.');
                }
            },
            beforeSubmit:function (formData, jqForm, options) { // pre-submit callback
                if ($('#name').val() == "") {
                    $('#name-error-msg').show();
                    $('#form-container').addClass('error');
                    $('#name').focus();
                    return false;
                }
            }
        });

        $('#btnsuggest').click(function () {
            $('#suggestSchoolForm').submit();
        });
    });
</script>
<div class="modal hide fade" id="suggestComponent">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Suggest your school</h3>
    </div>

    <div class="modal-body">
        <?php echo $this->Form->create('School', array('action' => 'suggestion', 'id' => 'suggestSchoolForm')); ?>

        <div id="form-response-container" class="row" style="display: none;">
            <div class="success-img span1">&nbsp;</div>
            <div id="suggest-response"></div>
        </div>
        <div id="form-container" class="control-group">
            <div class="controls">
                <?php echo $this->Form->input('name', array('label' => false, 'id' => 'name', 'value' => '', 'class' => 'input-xxlarge ulink-input-bigfont', 'placeholder' => 'Enter your school name')); ?>
                <span id="name-error-msg" class="help-inline" style="display:none;">Please enter a school name.</span>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="modal-footer">
        <a id="btnsuggest" class="btn btn-primary btn-large">Submit</a>
    </div>
</div> <!-- /suggestComponent -->