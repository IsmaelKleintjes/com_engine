var component = "engine";

jQuery(document).ready(function($){
    $(".imagePreview").fancybox();

    settings = {
        'auto'             : true,
        'buttonText'       : 'Selecteer bestanden',
        'buttonClass'      : 'uploadButton btn',
        'fileSizeLimit'    : sizeLimit,
        'fileType'         : fileType,
        'queueID'          : 'queue',
        'width'            : '150',
        'formData'         : {
            object         : object,
            object_id      : object_id
        },
        'uploadScript'     : 'index.php?option=com_'+component+'&task=media4U.save',
        'checkScript'      : 'index.php?option=com_'+component+'&task=media4U.exists',
        'onUploadComplete' : function(file, data){
            var data = $.parseJSON(data);
            array = [];
            for( var i in data ) {
                array[i] = data[i];
            }
            if(typeof(array["error"]) == "string"){
                if(array["error"] == "false"){
                    if(array["default"] == "1"){
                        var star = "star";
                    } else {
                        var star = "star-empty";
                    }
                    message = "<tr object_id='"+array["id"]+"'><td class='order hidden-phone'><span><i class='icon-menu icon-align-justify sortable-handler'></i></span></td><td class='phone-center'><div class='btn uploadDefault btn-small'><i class='icon-"+star+"'></i></div></td><td>"+array["name"]+"</td><td><input type='text' class='inputbox' value='" + array["alt_tag"] + "' name='media4U[" + array["id"] + "][alt_tag]'/></td><td><center><a class='imagePreview' rel='imagePreview' href='../"+array["preview"]+"'><img class='uploadImage' src='../"+array["url"]+"' /></a></center></td><td class='phone-center'><div class='btn btn-danger btn-mini uploadDelete'><i class='icon-remove icon-trash icon-white'></i></div></td></tr>";
                    i = $(".table tbody").children().length;

                    $(".table tbody").append(message);

                    if(array['remove_id'] > 0){

                        var $removeTr = $(".table tbody").find('tr[object_id="' + array['remove_id'] + '"]');

                        if($removeTr.find('.btn.uploadDefault > i').hasClass('icon-star')){
                            $('tr[object_id="' + array["id"] + '"]').find('.uploadDefault').click();
                        }

                        $removeTr.remove();
                    }
                    $("#uploadMessages").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>Succes!</h4>Bestand is opgeslagen</div>").show();
                } else {
                    $("#uploadErrors").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>Fout!</h4>"+array["error"]+"</div>").show();
                }
            }
        },
        'onQueueComplete'  : function(uploads){
            setTimeout(function(){
                $('#file_upload').uploadifive("clearQueue");
                $("#uploadMessages, #uploadErrors").fadeOut(500);
            }, 3000);
        },
        'onFallback': function(){
            $.ajax({
                url: 'administrator/components/com_'+component+'/assets/js/jquery.uploadify.js',
                dataType: "script",
                async: false,
                success: function () {
                    settings.uploader = settings.uploadScript;
                    settings.swf = "administrator/components/com_"+component+"/assets/uploadify.swf";
                    settings.onUploadSuccess = settings.onUploadComplete;
                    settings.onQueueComplete = function(uploads){
                        setTimeout(function(){
                            $('#file_upload').uploadify("cancel", "*");
                            $("#uploadMessages, #uploadErrors").delay(1000).fadeOut(500);
                        }, 3000);
                    }
                    settings.fileTypeExts = "*.jpg; *.png";
                    $('#file_upload').uploadify(settings);
                },
                error: function () {
                    console.log("Could not load script " + script);
                }
            });
        }
    };
    $('#file_upload').uploadifive(settings);
    $("body").delegate(".uploadDelete", "click", function(){
        if(confirm("Weet u zeker dat u dit bestand wil verwijderen?")){
            var id = $(this).parents("tr").attr("object_id");

            var updateDefault = false;

            if($(this).parents("tr").find('.btn.uploadDefault > i').hasClass('icon-star')){
                updateDefault = true;
            }

            index = $(".table tbody tr").index($(this).parents("tr"));
            $(this).parents("tr").remove();

            if(updateDefault){
                var trs = $(".table tbody").find('tr');

                $(trs[0]).find('.uploadDefault').click();
            }

            $.ajax({
                url: 'index.php?option=com_'+component+'&task=media4U.delete',
                type: 'POST',
                data: {
                    id: id
                }
            });
        }
    });
    $("body").delegate(".uploadDefault", "click", function(){
        var id = $(this).parents("tr").attr("object_id");
        $(".icon-star").addClass("icon-star-empty").removeClass("icon-star");
        $(this).children("i").addClass("icon-star").removeClass("icon-star-empty");
        $.ajax({
            url: 'index.php?option=com_'+component+'&task=media4U.setDefault',
            type: 'POST',
            data: {
                id: id,
                object_id: object_id
            }
        });
    });
    $(".uploadLabel").focusout(function(e){
        label = $(this).val();
        id = $(this).parents("tr").attr("object_id");
        $.ajax({
            url: 'index.php?option=com_'+component+'&task=media4U.saveImage',
            type: 'POST',
            data: {
                label: label,
                id: id
            }
        });
    });
    $(".sortable tbody").sortable({
        axis: 'y',
        handle: '.sortable-handler',
        cursor: 'n-resize',
        opacity: 0.5,
        helper:function (e, ui) {
            ui.children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        },
        stop: function(event, ui){
            sortRows();
        }
    });

    $(document).on('click', '.btn-edit-alt-tag', function(){
        var toggle = $(this).data('toggle');

        $('.' + toggle).toggle();
    });
});

function sortRows()
{
    var $ = jQuery.noConflict();

    i = 0;
    var items = Array();
    $(".sortable tbody tr").each(function(){
        items[i] = $(this).attr("object_id");
        i++;
    });

    $.ajax({
        url: 'index.php?option=com_'+component+'&task=media4U.saveOrder',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data: JSON.stringify(items, null, 2)
        }
    });
}

function saveVideoUrl()
{
    var url = $.trim($('#videoUrl').val());

    if(url.length) {

        var item = {
            object: object,
            object_id: object_id,
            url: url
        }

        $.ajax({
            url: 'index.php?option=com_'+component+'&task=media4U.saveVideo',
            type: 'POST',
            dataType: 'JSON',
            data: { data: JSON.stringify(item) },
            success: function (response) {
                var video = "<tr object_id='"+response.id+"'><td class='order hidden-phone'><span><i class='icon-menu icon-align-justify sortable-handler'></i></span></td><td class='phone-center'></td><td>"+response.name+"</td><td><center><a target='_blank' href='"+response.url+"'>Bekijk video</a></center></td><td class='phone-center'><div class='btn btn-danger btn-mini uploadDelete'><i class='icon-remove icon-trash icon-white'></i></div></td></tr>";
                $(".table tbody").append(video);

                $('#videoUrl').val('');
            }
        });

    }
}

