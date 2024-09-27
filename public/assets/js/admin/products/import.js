$(function() {
    let excelPath = null;

    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
    );    
      
    const pond = FilePond.create(document.querySelector('.file-upload-single'), {
        acceptedFileTypes: [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
        ],
        server: {
            process: {
                url: SERVER_PROPS.uploadUrl,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                onload: uploadSuccess,
                onerror: uploadError,
            },
            revert: null,
        },
    });

    function uploadSuccess(resText) {
        const res = JSON.parse(resText);

        if ( 
            !res 
            || !res.data 
            || !res.data.path 
            || !res.data.headers 
            || !res.data.columns 
        ){
            popError();
            return;
        }
        res.data.headers = res.data.headers.filter((s) => typeof s === 'string' || s instanceof String);

        excelPath = res.data.path;

        generateMapper(res.data);
    }

    function uploadError(json) {
        popError((json && json.error) ? json.error : 'Something went wrong');
    }

    $(document).on('click', '#import-btn', function () {
        let btn = document.querySelector('#import-btn');
        let hasEmptyCols = false;
        let prods = {};

        $('#mapper [data-map-name]').removeClass('border-danger');

        $('#mapper [data-map-name]').each((i, ele) => {
            if (ele.value == "") {
                hasEmptyCols = true;
                $(ele).addClass('border-danger');
                return;
            }

            prods[ele.dataset.mapName] = ele.value;
        });

        if (hasEmptyCols) {
            popError("All Fields are required to be selected");
            return;
        }

        $.ajax({
            method: "POST",
            url: SERVER_PROPS.saveUrl,
            data: {
                path: excelPath,
                data: prods
            },
            beforeSend: function () {
                btn.setAttribute('disabled', true);
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing';
            },
            success: function (res) {
                if (res.status_code == 422) {
                    popError(res.message);
                    return;
                }

                Swal.fire({
                    title: 'Success !!!',
                    text: "Products has been imported successfully",
                    icon: 'success',
                });

                cleanUpMapper();
            },
            error: function(xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.error)
                    ? xhr.responseJSON.error
                    : "Something went wrong";

                popError(msg);
            }, 
            complete: function() {
                btn.removeAttribute('disabled');
                btn.innerHTML = 'Import';
            },
        });
    });
});

function popError(msg = 'Something went wrong') {
    Swal.fire({
        title: 'Error !!!',
        text: msg,
        icon: 'error',
        timer: 5000,
    });
}

function generateMapper(data) {
    const normalTemplate = document.querySelector('#col-normal');
    const container = document.querySelector('#map-container');
    const mapper = document.querySelector('#mapper');
    const grpNode = document.createDocumentFragment();
    const options = data.headers.reduce((acc, cur) => {
        return cur.startsWith('Attribute') 
            ? acc 
            : acc + `<option value="${cur}">${cur}</option>`;
    }, '<option value="">Select</option>');

    mapper.innerHTML = "";

    Object.entries(data.columns).forEach(([k, l]) => {
        if (k.includes(".")) {
            return;
        }

        let clone = normalTemplate.content.cloneNode(true);
        clone.querySelector('[data-map-label]').textContent = l;
        clone.querySelector('[data-map-name]').innerHTML = options;
        clone.querySelector('[data-map-name]').dataset.mapName = k;
        grpNode.append(clone);
    })

    mapper.append(grpNode);
    $(container).removeClass('d-none');
}

function cleanUpMapper() {
    $('#map-container').addClass('d-none');
    $('#mapper').html("");
}