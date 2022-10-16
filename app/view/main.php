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
                                        <button type="button" class="btn btn-primary" data-target="#user-form-modal"
                                                data-toggle="modal">Add
                                        </button>
                                    </form>
                                </div>

                                <div class=" col ">
                                    <form>
                                        <select name="select" form="" class="custom-select">
                                            <option>Please Select</option>
                                            <option>Set active</option>
                                            <option>Set not active</option>
                                            <option>Delete</option>
                                        </select>
                                </div>
                                <div class="col ">

                                    <button type="button" class="btn btn-success">OK</button>
                                    </form>
                                </div>


                            </div>
                            <div class="e-table">
                                <div class="table-responsive table-lg mt-3">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="align-top">
                                                <div
                                                        class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                                                    <input type="checkbox" class="custom-control-input" id="all-items">
                                                    <label class="custom-control-label" for="all-items"></label>
                                                </div>
                                            </th>
                                            <th class="max-width">Name</th>
                                            <th class="sortable">Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($users as $users): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <div
                                                            class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="item-<?= $users['id'] ?>">
                                                        <label class="custom-control-label"
                                                               for="item-<?= $users['id'] ?>"></label>
                                                    </div>
                                                </td>
                                                <td class="text-nowrap align-middle"><?= ucfirst($users['name']) . ' ' . ucfirst($users['surname']) ?></td>
                                                <td class="text-nowrap align-middle">
                                                    <span><?php if ($users['is_admin'] == 2) {
                                                            echo "Admin";
                                                        } else echo "User" ?></span></td>
                                                <td class="text-center align-middle"><i
                                                            class="fa fa-circle active-circle"></i></td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-group align-top">
                                                        <button class="btn btn-sm btn-outline-secondary badge"
                                                                type="button" data-toggle="modal"
                                                                data-target="#user-form-modal">Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary badge"
                                                                data-target="#user-delete-modal"
                                                                data-toggle="modal"
                                                                id="deleteUser<?= $users->id ?>"
                                                                type="button"><i class="fa fa-trash"></i></button>

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
                                    <select name="select" form="" class="custom-select">
                                        <option>Please Select</option>
                                        <option>Set active</option>
                                        <option>Set not active</option>
                                        <option>Delete</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <form>
                                        <button type="button" class="btn btn-success">OK</button>
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
                                <div id="errorMess" class="alert alert-danger" role="alert"></div>
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
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="status">
                                    <label class="custom-control-label" for="customSwitch1" >Active</label>
                                </div>
                                <select class="custom-select" id="role" name="role">
                                    <option selected>Role</option>
                                    <option value="1">User</option>
                                    <option value="2">Admin</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button id="addUserButton" type="submit" class="btn btn-primary" id="btn">Save</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <div class="modal fade" id="user-delete-modal" tabindex="-1" aria-labelledby="user-form-modal"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">

                            <h2>Are you sure you want to delete this user?</h2>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="deleteUserButton" type="submit" class="btn btn-primary" id="btn">Delete</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>




    </script>
    <script src="./js/addUser.js"></script>

</body>
</html>