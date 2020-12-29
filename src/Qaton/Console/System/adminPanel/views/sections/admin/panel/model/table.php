<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Model Data</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            
            <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php
                        foreach ($schema as $column_name => $column_props) {
                            if ($column_props['type'] != 'text') {
                                echo "<th>{$column_name} <small>{$column_props['type']}</small></th>";
                            }
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <?php
                        foreach ($schema as $column_name => $column_props) {
                            if ($column_props['type'] != 'text') {
                                echo "<th>{$column_name} <small>{$column_props['type']}</small></th>";
                            }
                        }
                        ?>
                        <th>Actions

                        <a href="<?php $this->baseUrl() ?>admin/panel/table/create/<?php echo $model_slug ?>/0" class="btn btn-sm btn-success btn-icon-split float-right">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Create</span>
                        </a>
                        </th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($data as $index => $row) : ?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <?php
                        foreach ($schema as $column_name => $column_props) {
                            if ($column_props['type'] != 'text') {
                                if ($column_props['type'] == 'foreign') {
                                    echo "<td><a href='{$this->base_url}admin/panel/table/view/:"
                                            . ucfirst($column_name) . "/{$row[$column_name]}"
                                            . "'>{$column_props['foreign']}:{$column_props['key']}: {$row[$column_name]}</a></td>";
                                } else {
                                    echo "<td>{$row[$column_name]}</td>";
                                }
                            }
                        }
                        ?>
                        <td class="text-right">
                            <a href="<?php $this->baseUrl() ?>admin/panel/table/view/<?php echo $model_slug ?>/<?php echo $row['id'] ?>" class="btn btn-sm btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="text">View</span>
                            </a>
                            <a href="<?php $this->baseUrl() ?>admin/panel/table/edit/<?php echo $model_slug ?>/<?php echo $row['id'] ?>" class="btn btn-sm btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-pencil-alt"></i>
                                </span>
                                <span class="text">Edit</span>
                            </a>
                            <a onclick="javascript: return confirm('Are you sure you want to clone this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/clone/<?php echo $model_slug ?>/<?php echo $row['id'] ?>" class="btn btn-sm btn-secondary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-clone"></i>
                                </span>
                                <span class="text">Clone</span>
                            </a>
                            <a onclick="javascript: return confirm('Are you sure you want to trash this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/delete/<?php echo $model_slug ?>/<?php echo $row['id'] ?>" class="btn btn-sm btn-warning btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span class="text">Trash</span>
                            </a>
                            <a onclick="javascript: return confirm('Are you sure you want to permanently delete this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/purge/<?php echo $model_slug ?>/<?php echo $row['id'] ?>" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-ban"></i>
                                </span>
                                <span class="text">Purge</span>
                            </a>


                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>