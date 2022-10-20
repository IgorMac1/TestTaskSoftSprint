$(function () {
    const warningText = document.getElementById('warning');

    $('form#ajax_form').submit(function (event) {
        let formData = parseFormData($(this).serializeArray());
        event.preventDefault();
        if (!formData.role_id){
            modalWarning('Choose role');
            return false;
        }else if (formData.name.length < 3){
            modalWarning('Name must be at least 3 symbols');
            return false;
        }else if (formData.surname.length < 5){
            modalWarning('Surname must be at least 5 symbols');
            return false;
        }
        let id = $('form#ajax_form .submit-button').attr('id');
        if (id) {
            editUser(id, formData);
        } else {
            addUser(formData);
        }

        $('#user-form-modal').modal('hide')
    });

    function parseFormData(formData) {
        let userData = {};
        $.each(formData, function (i, field) {

            userData[field.name] = field.value;
        });
        if (!userData.status) {
            userData.status = 'off';
        }
        return userData;
    }

    function addUser(userData) {
        sendRequest(
            '/api/users',
            userData,
            function (result) {
                $userRow = getUserRow(result.user);
                $('#table-users').append($userRow);
                initializeButtonAction();

            });
    }

    function editUser(userId, userData) {
        sendRequest(
            '/api/users/' + userId,
            userData,
            function (result) {
                $userRow = getUserRow(result.user);
                $('tr#' + userId).replaceWith($userRow);
                initializeButtonAction();
            }, function () {

            }, 'PUT');
    }

    $('button.ok').on('click',function (){
        let checkboxes = [];
        let userData = {};

        $('input:checkbox:checked').each(function(){
            checkboxes.push(this.value);
        });
        let selectedAction = $('select.select-action.top').val();
        if ($(this).hasClass('bottom')) {
            selectedAction = $('select.select-action.bottom').val();
        }

        if (checkboxes.length === 0) {
            modalWarning('Choose users');
            return false;
        }
        switch (selectedAction) {
            case 'active':
            case 'inactive': {
                setUserStatus(selectedAction);
                break;
            }
            case 'delete': {
                deleteUsers();
                break;
            }
            case null: {
                modalWarning('choose action');
                break;
            }
        }

        function deleteUsers() {
            warningText.innerText = 'Are you sure you want to delete these users ?' ;
            $('#confirmDelete').attr('hidden', false);
            $('#warningModal').click();
            $('button#confirmDelete').on('click',function (){
                userData['ids'] = checkboxes;
                sendRequest(
                    '/api/users/delete',
                    userData,
                    function () {
                        for (let id in checkboxes) {
                            let userRow = $('tr#' + checkboxes[id]);
                            userRow.remove();
                        }
                    },
                    function () {
                    },
                    'DELETE');
                $('#deleteModal').modal('hide');
            });
        }
        function setUserStatus (status) {
            userData['status'] = status;
            userData['ids'] = checkboxes;
            sendRequest(
                '/api/users/change-status',
                userData,
                function () {
                    let userIconStatus = status === 'active' ? 'on' : 'off';
                    for (let id in checkboxes) {
                        let statusBlock = $('tr#' + checkboxes[id] + ' td.user-status');
                        statusBlock.empty().append('<i class="fa fa-circle ' + userIconStatus + '-circle"></i>');
                    }
                },
                function () {
            });
        }
    })

    function initializeButtonAction() {
        $('button.deleteUser').on('click',function () {
            warningText.innerText = 'Are you sure you want to delete this user ?' ;
            $('#confirmDelete').attr('hidden', false);
            $('#warningModal').click();
            let userId = $(this).attr('id');
            $('button#confirmDelete').on('click',function (){
                sendRequest(
                    '/api/users/' + userId,
                    {},
                    function () {
                        $('tr#' + userId).remove();
                    },
                    function () {
                    },
                    'DELETE');
                $('#deleteModal').modal('hide');
            })
        });
        $('button.edit').on('click',function () {
            let id = $(this).attr('id');
            $('form#ajax_form .submit-button').attr('id', id);
            $('h5.modal-title').text('Edit User');
            $('tr#' + id).find('td').each(function () {
                if ($(this).attr('dataField')) {
                    let fieldName = $(this).attr('dataField');
                    let fieldValue = $(this).attr('dataValue');
                    if (fieldName === 'status') {
                        $('form#ajax_form [name="' + fieldName + '"]').prop('checked', (fieldValue === 'on'));
                    } else {
                        $('form#ajax_form [name="' + fieldName + '"]').val(fieldValue);
                    }
                }
            });
        });
        $('button.addUser').on('click',function () {
            $('form#ajax_form .submit-button').attr('id', '');
            $('h5.modal-title').text('Add User');
            $('form#ajax_form')[0].reset();
        });
        $('#all-items').on('change', function() {
            if(this.checked) {
                $('.td-checkbox').each(function () {
                    $(this).prop('checked', 'checked');
                });
            } else {
                $('.td-checkbox').each(function () {
                    $(this).prop('checked', false);
                });
            }
        });
        $('.td-checkbox').on('change', function() {
            if(!this.checked) {
                $('#all-items').prop('checked', false);
            } else {
                let allChecked = true;
                $('.td-checkbox').each(function () {
                    if(!this.checked) {
                        allChecked = false;
                    }
                });
                if(allChecked) {
                    $('#all-items').prop('checked', 'checked');
                }
            }
        });
    }

    initializeButtonAction();

    function sendRequest(url, data, onSuccess, onError, method = 'POST') {
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function (result) {
                onSuccess(result);
            },
            error: function (result) {
                console.log('error');
                console.log(result);
                onError(result);
            }
        });
    }

    function getUserRow(userData){
        return `
        <tr class="user" id="${userData.id}">
            <td class="align-middle">
                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                    <input type="checkbox" class="custom-control-input checkbox id"
                           name="id" value="${userData.id}"
                           id="item-${userData.id}">
                    <label class="custom-control-label"
                           for="item-${userData.id}"></label>
                </div>
            </td>
            <td class="text-nowrap align-middle">${userData.name + ' ' + userData.surname}</td>
            <td class="text-nowrap align-middle">
                <span>${userData.role}</span></td>
            <td class="user-status text-center align-middle">
                <i class="fa fa-circle ${userData.status}-circle"></i>
            </td>
            <td class="text-center align-middle">
                <div class="btn-group align-top">
                    <button class="btn btn-sm btn-outline-secondary badge edit"
                            type="submit" data-toggle="modal"
                            data-target="#user-form-modal" id="${userData.id}" >Edit
                    </button>
                    <button class="btn btn-sm btn-outline-secondary badge deleteUser "
                            data-target="#user-delete-modal"
                            data-toggle="modal"
                            id="${userData.id}"
                            type="submit"><i class="fa fa-trash" ></i></button>
                </div>
            </td>
        </tr>`
    }
    function modalWarning(text = '',confirmButton = true){
        warningText.innerText = text;
        document.getElementById('confirmDelete').hidden = confirmButton ;
        document.getElementById('warningModal').click();
    }
})








