<?php
include '../common/adminHeader.php';

$query = "SELECT * from categories";
$result = mysqli_query($conn, $query);

$categories = [];

while ($category = mysqli_fetch_array($result)) {

    $categories[] = array(
        'id' => $category['id'],
        'name' => $category['name'],
        'status' => $category['status']
    );
}

?>

<button class="btn btn-success pull-right" id="AddCategory">
    <i class="fa fa-plus"></i> Add Category
</button>
<h3>
    Categories
</h3>
<br>
<br>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center"> Id </th>
            <th> Name </th>
            <th width="100px" class="text-center"> Status </th>
            <th class="text-center" width="150px"> Actions </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($categories as $cat) {
        ?>
            <tr>
                <td width="50px" class="text-center"> <?php echo $cat['id']; ?> </td>
                <td> <?php echo $cat['name']; ?> </td>
                <td class="text-center"> <?php echo $cat['status'] ? 'Active' : 'Inactive'; ?> </td>
                <td width="150px" class="text-right">
                    <button class="btn btn-secondary btn-sm edit-category" data-status="<?php echo $cat['status']; ?>" data-id="<?php echo $cat['id']; ?>" data-name="<?php echo $cat['name']; ?>"> Edit</button>
                    <button class="btn btn-danger btn-sm remove-modal" data-id="<?php echo $cat['id']; ?>"> Remove</button>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="modal fade" id="addCategoryMondal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Category Name" required>
                        <br>
                        <label for="cat_name" class="form-label">Category Status</label>
                        <select class="form-control" name="cat_status" id="cat_status">
                            <option value="0"> Active </option>
                            <option value="1"> Inactive </option>
                        </select>
                        <input type="hidden" name="create-category" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editCategoryMondal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Category Name" required>
                        <input type="hidden" name="category-id" id="category-id" value="">
                        <br>
                        <label for="cat_name" class="form-label">Category Status</label>
                        <select class="form-control" name="cat_status" id="cat_status_edit">
                            <option value="1"> Active </option>
                            <option value="0"> Inactive </option>
                        </select>
                        <input type="hidden" name="edit-category" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="removeCategoryMondal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove Category</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <h5 class="text-danger text-center"> Are you sure to remove this Category </h5>
                        <input type="hidden" name="category-id" id="category-id" value="">
                        <input type="hidden" name="remove-category" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger"> Remove </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Create New Category
        $("#AddCategory").click(function() {
            $("#addCategoryMondal").modal('show');
        });

        // Edit Category
        $('.edit-category').click(function() {
            var catId = $(this).attr('data-id');
            var catName = $(this).attr('data-name');
            var catStatus = $(this).attr('data-status');

            $("#editCategoryMondal #category-id").val(catId);
            $("#editCategoryMondal #cat_name").val(catName);
            $("#editCategoryMondal #cat_status_edit").val(catStatus);
            $("#editCategoryMondal").modal('show');
        });

        // Remove Categoty
        $('.remove-modal').click(function() {
            var catId = $(this).attr('data-id');

            $("#removeCategoryMondal #category-id").val(catId);
            $("#removeCategoryMondal").modal('show');
        });

    });
</script>

<?php include '../common/adminFooter.php'; ?>