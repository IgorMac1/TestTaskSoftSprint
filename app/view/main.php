<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Users table</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link href="./css/styles.css" rel="stylesheet">
<div class="container">
    <div class="row flex-lg-nowrap">
        <div class="col">
            <div class="row flex-lg-nowrap">
                <div class="col mb-3">
                    <div class="e-panel card">
                        <div class="card-body">
                            <div class="card-title">
                                <h6 class="mr-2 text-center"><span>Users</span></h6>
                            </div>

                            <div class="row cols-row-lg-3 text-center">
                                <div class="col ">
                                    <form>
                                        <button type="button" class="btn btn-primary addUser"
                                                data-target="#user-form-modal"
                                                data-toggle="modal">Add
                                        </button>
                                    </form>
                                </div>

                                <div class=" col ">

                                    <select name="select" class="custom-select select select-action top">
                                        <option value="" disabled selected hidden>Please Select</option>
                                        <option value="active">Set active</option>
                                        <option value="inactive">Set not active</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                </div>
                                <div class="col ">

                                    <button type="submit" class="btn btn-success ok top">OK</button>

                                </div>


                            </div>
                            <div class="e-table">
                                <div class="table-responsive table-lg mt-3">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="align-top">
                                                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                                                    <input type="checkbox" class="custom-control-input " id="all-items">
                                                    <label class="custom-control-label" for="all-items"></label>
                                                </div>
                                            </th>
                                            <th class="max-width">Name</th>
                                            <th class="sortable">Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>

                                        <tbody id="table-users">

                                        <?php foreach ($users as $users): ?>

                                            <tr class="user" id="<?= $users['id'] ?>">
                                                <td dataField="name" dataValue="<?= $users['name'] ?>" class="align-middle">
                                                    <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                        <input type="checkbox" class="custom-control-input checkbox id td-checkbox"
                                                               name="id" value="<?= $users['id'] ?>"
                                                               id="item-<?= $users['id'] ?>">
                                                        <label class="custom-control-label"
                                                               for="item-<?= $users['id'] ?>"></label>
                                                    </div>
                                                </td>
                                                <td dataField="surname" dataValue="<?= $users['surname'] ?>" class="text-nowrap align-middle"><?= $users['full_name'] ?></td>
                                                <td dataField="role_id" dataValue="<?= $users['role_id'] ?>" class="text-nowrap align-middle">
                                                    <span><?= $users['role'] ?></span></td>
                                                <td dataField="status" dataValue="<?= $users['status'] ?>" class="user-status text-center align-middle">
                                                    <i class="fa fa-circle <?php echo $users['status'] ?>-circle"></i>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-group align-top">
                                                        <button class="btn btn-sm btn-outline-secondary badge editUser"
                                                                type="submit"  data-target="#user-form-modal"
                                                                data-toggle="modal" id="<?= $users['id'] ?>" >Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary badge deleteUser "
                                                                data-target="#user-delete-modal"
                                                                data-toggle="modal"
                                                                id="<?= $users['id'] ?>"
                                                                type="submit"><i class="fa fa-trash" ></i></button>

                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="row cols-row-lg-3 text-center">
                                <div class="col">
                                    <form>
                                        <button type="button" class="btn btn-primary" data-target="#user-form-modal"
                                                data-toggle="modal">Add
                                        </button>
                                    </form>
                                </div>
                                <div class=" col">
                                    <select name="select" form="" class="custom-select select-action bottom">
                                        <option value="" disabled selected hidden>Please Select</option>
                                        <option value="active">Set active</option>
                                        <option value="inactive">Set not active</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <form>
                                        <button type="button" class="btn btn-success ok bottom">OK</button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- User Form Modal -->
            <form action="" id="ajax_form" method="post">
                <div class="modal fade" id="user-form-modal" tabindex="-1" aria-labelledby="user-form-modal"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="UserModalLabel">Add user</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="first-name" class="col-form-label ">First Name:</label>
                                    <input required type="text" class="form-control" id="first-name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="last-name" class="col-form-label">Last Name:</label>
                                    <input required type="text" class="form-control" id="last-name" name="surname">
                                </div>
                                <div class="custom-control custom-switch my-5">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                           name="status">
                                    <label class="custom-control-label" for="customSwitch1">Active</label>
                                </div>
                                <select class="custom-select" id="role" name="role_id">
                                    <option value="" disabled selected hidden>Role</option>
                                    <option value="1">User</option>
                                    <option value="2">Admin</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-button" >Save</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deleteModal" id="warningModal"
            hidden>
    </button>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body warning" id="warning">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmDelete" hidden>Confirm</button>
                </div>
            </div>
        </div>
    </div>



    <script src="./js/addUser.js"></script>

</body>
</html>