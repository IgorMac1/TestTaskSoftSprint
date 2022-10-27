$(function () {
    const warningText = document.getElementById('warning');

    let selectedIds;
    let selectedDeleteId;

    $('form#ajax_form').submit(function (event) {
        let formData = parseFormData($(this).serializeArray());
        event.preventDefault();
        if (!formData.role_id){
            $('h6#warning-role').attr('hidden', false);
            return false;
        }else if (formData.name.length < 2 || formData.name.length > 50){
            $('h6#warning-name').attr('hidden', false);
            return false;
        }else if (formData.surname.length < 2 || formData.surname.length > 50){
            $('h6#warning-surname').attr('hidden', false);
            return false;
        }
        let id = $('form#ajax_form .submit-button').attr('id');
        if (id) {
            editUser(id, formData);
        } else {
            addUser(formData);
        }

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
                let userRow = getUserRow(result.user);
                $('#table-users').append(userRow);
            });
    }

    function editUser(userId, userData) {
        sendRequest(
            '/api/users/' + userId,
            userData,
            function (result) {
                let userRow = getUserRow(result.user);
                $('tr#' + userId).replaceWith(userRow);
            }, function () {
                $('h6#warning-user-not-found').attr('hidden', false);
                $('.submit-button').attr('disabled',true)
            }, 'PUT');
    }

    $('button.ok').on('click',function (){
        let checkboxes = [];
        let userData = {};
        $('input:checkbox:checked').each(function(){
            if (this.value !== 'on'){
                checkboxes.push(this.value);
            }return true
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
                setAllCheckboxesOff();
                break;
            }
            case 'delete': {
                deleteUsers();
                setAllCheckboxesOff();
                $('.confirmDelete').attr('disabled',false);
                break;
            }
            case null: {
                modalWarning('Choose action');
                break;
            }
        }

        function deleteUsers() {
            warningText.innerText = 'Are you sure you want to delete these users ?' ;
            $('button.confirmDelete').attr('hidden', false);
            $('#warningModal').click();
            selectedIds = checkboxes;
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
                    modalWarning('User not found');
                    $('button.confirmDelete').attr('hidden', true);
                });
        }
    })

    $(document).on('click', 'button.confirmDelete', function () {
        $('h6#warning-user-not-found').attr('hidden', true);
        if (selectedDeleteId) {
            sendRequest(
                '/api/users/' + selectedDeleteId,
                {},
                function () {
                    $('tr#' + selectedDeleteId).remove();
                    selectedDeleteId = undefined;
                },
                function () {
                    warningText.innerText = 'User not found' ;
                    $('.confirmDelete').attr('disabled',true);
                },
                'DELETE');
        } else {
            let requestData = {ids: selectedIds};
            sendRequest(
                '/api/users/delete',
                requestData,
                function () {
                    for (let id in selectedIds) {
                        let userRow = $('tr#' + selectedIds[id]);
                        userRow.remove();
                    }
                    selectedIds = undefined;
                },
                function () {
                    warningText.innerText = 'User not found' ;
                    $('.confirmDelete').attr('disabled',true);
                    },
                'DELETE');
        }

    })
    function initializeButtonAction() {
        $(document).on('click', 'button.deleteUser', function () {
            $('.confirmDelete').attr('disabled',false)
            setAllCheckboxesOff()
            warningText.innerText = 'Are you sure you want to delete this user ?' ;
            $('button.confirmDelete').attr('hidden', false);
            $('#warningModal').click();
            selectedDeleteId = $(this).attr('id').split(['-'])[1];
        });
        $(document).on('click', 'button.edit', function () {
            $('.submit-button').attr('disabled',false);
            $('h6#warning-user-not-found').attr('hidden', true);
            $('h6#warning-name').attr('hidden', true);
            $('h6#warning-surname').attr('hidden', true);
            $('h6#warning-role').attr('hidden', true);
            setAllCheckboxesOff()
            let id = $(this).attr('id').split(['-'])[1];
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
        $(document).on('click', 'button.addUser', function () {
            $('.submit-button').attr('disabled',false);
            setAllCheckboxesOff()
            $('h6#warning-user-not-found').attr('hidden', true);
            $('h6#warning-name').attr('hidden', true);
            $('h6#warning-surname').attr('hidden', true);
            $('h6#warning-role').attr('hidden', true);
            $('form#ajax_form .submit-button').attr('id', '');
            $('h5.modal-title').text('Add User');
            $('form#ajax_form')[0].reset();
        });
        $(document).on('click', '#all-items', function() {
            if(this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                setAllCheckboxesOff()
            }
        });
        $(document).on('click', '.td-checkbox', function() {
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
                $('#user-form-modal').modal('hide')
                $('#deleteModal').modal('hide');
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
            <td dataField="name" dataValue="${userData.name}" class="align-middle">
                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                    <input type="checkbox" class="custom-control-input checkbox id"
                           name="id" value="${userData.id}"
                           id="item-${userData.id}">
                    <label class="custom-control-label"
                           for="item-${userData.id}"></label>
                </div>
            </td>
            <td dataField="surname" dataValue="${userData.surname}" class="text-nowrap align-middle">${userData.full_name}</td>
            <td dataField="role_id" dataValue="${userData.role_id}" class="text-nowrap align-middle">
                <span>${userData.role}</span></td>
            <td dataField="status" dataValue="${userData.status}" class="user-status text-center align-middle">
                <i class="fa fa-circle ${userData.status}-circle"></i>
            </td>
            <td class="text-center align-middle">
                <div class="btn-group align-top">
                    <button class="btn btn-sm btn-outline-secondary badge edit"
                            type="submit" data-toggle="modal"
                            data-target="#user-form-modal" id="edit-${userData.id}" >Edit
                    </button>
                    <button class="btn btn-sm btn-outline-secondary badge deleteUser "
                            data-target="#user-delete-modal"
                            data-toggle="modal"
                            id="delete-${userData.id}"
                            type="submit"><i class="fa fa-trash" ></i></button>
                </div>
            </td>
        </tr>`
    }

    function modalWarning(text = '',confirmButton = true){
        warningText.innerText = text;
        document.getElementsByClassName('confirmDelete').hidden = confirmButton ;
        document.getElementById('warningModal').click();
    }

    function setAllCheckboxesOff(){
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }
})








