
const warningText = document.getElementById('warning');

$(function () {

    $('form#ajax_form').submit(function (event) {
        let formData = $(this).serializeArray();
        event.preventDefault();
        addUser(formData);
        $('#user-form-modal').modal('hide')
    });

    function addUser(formData) {
        let userData = {};
        $.each(formData, function (i, field) {
            userData[field.name] = field.value;
        });
        console.log(userData);
        $.ajax({
            url: "/api/users",
            type: 'POST',
            data: userData,
            success: function (result) {
                console.log('success');
                console.log(result);
            },
            error: function (result) {
                console.log('error');
                console.log(result);
            }
        });
    }
})

$(document).ready(function (){
    $('button.ok').on('click',function (){
        let checkboxes = [];
        let userData = {};

        $('input:checkbox:checked').each(function(){
            checkboxes.push(this.value);


        });
        let selected = $('select.select').val();
        if (selected === 'Please Select'){
            warningText.innerText = 'choose action';
            document.getElementById('confirmDelete').hidden = true ;
            document.getElementById('warningModal').click();
            console.log(warningText)
            return false;
        }else if (selected !== 'Please Select' && checkboxes.length === 0 ){
            document.getElementById('confirmDelete').hidden = true ;
            document.getElementById('warningModal').click();
            warningText.innerText = 'choose users';
            return false;
        }
        else if (checkboxes.length !== 0 && selected === 'Delete' ){
            document.getElementById('deleteUser').click();
            $('button#confirmDelete').on('click',function (){
                getUserAction();
                // for (let key in checkboxes){
                //   let i = checkboxes[key];
                //
                //     document.getElementById(`${i}`).remove()
                // }
                $('#exampleModal').modal('hide');
            })

        }else getUserAction();
        function getUserAction() {


            userData["action"] = selected;
            userData["id"] = checkboxes;

            console.log(userData)

            $.ajax({
                url: "/api/setActive",
                type: 'POST',
                data: userData,
                success: function (result) {
                    console.log('success');
                    console.log(result);
                },
                error: function (result) {
                    console.log('error');
                    console.log(result);
                }
            });
        }
    })
})

$(document).ready(function (){
    $('button.deleteUser').on('click',function (){
        warningText.innerText = 'Are you sure you want to delete this user ?' ;
        document.getElementById('confirmDelete').hidden = false ;
        document.getElementById('warningModal').click();

        $('button#confirmDelete').on('click',function (){
            console.log($(this).id)
            $('#exampleModal').modal('hide');
        })
    })
})










